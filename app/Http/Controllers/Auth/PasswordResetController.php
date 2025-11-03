<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;
use App\Services\PasswordResetService;
use App\Services\PHPMailerService;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    /**
     * Exibe o formulário de recuperação de senha
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Processa a solicitação de recuperação de senha
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $email = $request->input('email');
            $usuario = Usuario::where('email', $email)->first();

            if (!$usuario) {
                return redirect()->back()
                    ->with('error', 'Não encontramos um usuário com este email em nosso sistema. Verifique se o email está correto ou entre em contato com o suporte.')
                    ->withInput();
            }

            // Gerar URL de recuperação com token JWT
            $resetUrl = PasswordResetService::generateResetUrl($email);

            // Dados para o email
            $emailData = [
                'userName' => $usuario->nome,
                'userEmail' => $email,
                'expirationTime' => PasswordResetService::getExpirationTime() . ' minutos',
                'requestedAt' => now()->format('d/m/Y H:i'),
                'requestIp' => $request->ip() ?? 'Não informado'
            ];

            // Enviar email de recuperação
            $result = PHPMailerService::sendPasswordResetEmail($email, $resetUrl, $emailData);

            if ($result['success']) {
                Log::info('Email de recuperação de senha enviado para: ' . $email);

                return redirect()->back()
                    ->with('success', 'Instruções de recuperação de senha foram enviadas para o seu email. Verifique sua caixa de entrada e também a pasta de spam.');
            } else {
                Log::error('Erro ao enviar email de recuperação: ' . $result['message']);

                return redirect()->back()
                    ->with('error', 'Erro ao enviar email. Tente novamente em alguns minutos ou entre em contato com o suporte.')
                    ->withInput();
            }

        } catch (Exception $e) {
            Log::error('Erro no processo de recuperação de senha: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocorreu um erro interno. Tente novamente em alguns minutos.')
                ->withInput();
        }
    }

    /**
     * Exibe o formulário de redefinição de senha
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('forgot-password')
                ->with('error', 'Token de recuperação não fornecido.');
        }

        // Validar token
        $validation = PasswordResetService::validateToken($token);

        if (!$validation['valid']) {
            return redirect()->route('forgot-password')
                ->with('error', 'Token inválido ou expirado. Solicite uma nova recuperação de senha.');
        }

        // Calcular se está próximo da expiração (últimos 5 minutos)
        $expiresAt = \Carbon\Carbon::parse($validation['expires_at']);
        $isNearExpiration = $expiresAt->diffInMinutes(now()) <= 5;

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $validation['email'],
            'expires_at' => $expiresAt,
            'usuario' => $validation['usuario'],
            'is_near_expiration' => $isNearExpiration
        ]);
    }

    /**
     * Processa a redefinição de senha
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'token.required' => 'Token é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'email.email' => 'Email deve ter formato válido.',
            'password.required' => 'Nova senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $token = $request->input('token');
            $email = $request->input('email');
            $password = $request->input('password');

            // Validar token
            $validation = PasswordResetService::validateToken($token);

            if (!$validation['valid']) {
                return redirect()->route('forgot-password')
                    ->with('error', 'Token inválido ou expirado. Solicite uma nova recuperação de senha.');
            }

            // Verificar se o email do token confere com o enviado
            if ($validation['email'] !== $email) {
                return redirect()->back()
                    ->with('error', 'Email não confere com o token de recuperação.')
                    ->withInput();
            }

            // Atualizar senha do usuário
            $usuario = $validation['usuario'];
            $usuario->senha = bcrypt($password);
            $usuario->save();

            Log::info('Senha redefinida com sucesso para usuário: ' . $email);

            return redirect()->route('login')
                ->with('success', 'Senha redefinida com sucesso! Você já pode fazer login com sua nova senha.');

        } catch (Exception $e) {
            Log::error('Erro ao redefinir senha: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ocorreu um erro interno. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Verificar status do token (para AJAX)
     */
    public function checkTokenStatus(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['valid' => false, 'error' => 'Token não fornecido']);
        }

        $validation = PasswordResetService::validateToken($token);

        return response()->json($validation);
    }
}
