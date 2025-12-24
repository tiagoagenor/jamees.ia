<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Tentar autenticação personalizada
        $user = \App\Models\Usuario::with('horarioAcesso')->where('email', $this->input('email'))->first();

        if ($user && \Hash::check($this->input('password'), $user->senha)) {
            // Verificar horário de acesso antes de fazer login
            $horarioAcessoService = new \App\Services\UsuarioHorarioAcessoService();

            if (!$horarioAcessoService->validarAcesso($user)) {
                // Usuário não pode fazer login no momento
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => $horarioAcessoService->obterMensagemErro($user),
                ]);
            }

            Auth::login($user, $this->boolean('remember'));

            // Salvar aplicativos da empresa principal na sessão
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            if ($empresaPrincipal) {
                $aplicativos = $empresaPrincipal->aplicativos()->get();
                session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
            }

            // Carregar feature flags na sessão
            \App\Helpers\FeatureFlagHelper::carregarNaSessao();

            // Debug: Verificar se o login funcionou
            \Log::info('Login successful', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'session_id' => session()->getId()
            ]);
        } else {
            \Log::error('Login failed - invalid credentials', [
                'email' => $this->input('email'),
                'user_found' => $user ? 'yes' : 'no'
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
