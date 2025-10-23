<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UltimaAcesso;

class MultiTenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            \Log::info('MultiTenantMiddleware - User authenticated', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'empresas_count' => $user->empresas()->count()
            ]);

            // Verificar se o usuário tem empresas vinculadas
            if ($user->empresas()->count() === 0) {
                \Log::warning('User has no companies, logging out', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                Auth::logout();
                return redirect()->route('login')->with('error', 'Usuário não possui empresas vinculadas.');
            }

            // Definir empresa atual na sessão se não estiver definida
            if (!session('empresa_atual_id')) {
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
                if ($empresaPrincipal) {
                    session(['empresa_atual_id' => $empresaPrincipal->id]);
                    session(['whitelabel_atual_id' => $empresaPrincipal->whitelabel_id]);
                }
            }

            // Registrar último acesso
            if (session('empresa_atual_id') && session('whitelabel_atual_id')) {
                UltimaAcesso::updateOrCreate(
                    [
                        'usuario_id' => $user->id,
                        'whitelabel_id' => session('whitelabel_atual_id'),
                        'empresa_id' => session('empresa_atual_id'),
                    ],
                    [
                        'usuario_id' => $user->id,
                        'whitelabel_id' => session('whitelabel_atual_id'),
                        'empresa_id' => session('empresa_atual_id'),
                    ]
                );
            }
        }

        return $next($request);
    }
}
