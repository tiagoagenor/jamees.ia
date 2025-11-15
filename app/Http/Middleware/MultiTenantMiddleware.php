<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UltimaAcesso;
use App\Models\Empresa;

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

            Log::info('MultiTenantMiddleware - User authenticated', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'empresas_count' => $user->empresas()->count()
            ]);

            // Verificar se o usuário tem empresas vinculadas
            if ($user->empresas()->count() === 0) {
                Log::warning('User has no companies, logging out', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                Auth::logout();
                return redirect()->route('login')->with('error', 'Usuário não possui empresas vinculadas.');
            }

            // Validar e definir empresa atual na sessão
            $empresaAtualId = session('empresa_atual_id');
            $empresaAtual = null;

            // Verificar se a empresa da sessão ainda existe e o usuário tem acesso
            if ($empresaAtualId) {
                $empresaAtual = $user->empresas()->where('empresa.id', $empresaAtualId)->first();
            }

            // Se não tem empresa válida na sessão, definir uma
            if (!$empresaAtual) {
                // Primeiro, tentar encontrar empresa principal
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

                if ($empresaPrincipal) {
                    // Se existe empresa principal, usar ela
                    $empresaAtual = $empresaPrincipal;
                    session(['empresa_atual_id' => $empresaPrincipal->id]);
                    session(['whitelabel_atual_id' => $empresaPrincipal->whitelabel_id]);
                    Log::info('Empresa principal selecionada automaticamente', [
                        'empresa_id' => $empresaPrincipal->id,
                        'empresa_nome' => $empresaPrincipal->nome_fantasia
                    ]);
                } else {
                    // Se não existe empresa principal, pegar a primeira empresa da lista
                    $primeiraEmpresa = $user->empresas()->first();
                    if ($primeiraEmpresa) {
                        $empresaAtual = $primeiraEmpresa;
                        session(['empresa_atual_id' => $primeiraEmpresa->id]);
                        session(['whitelabel_atual_id' => $primeiraEmpresa->whitelabel_id]);
                        Log::info('Primeira empresa selecionada automaticamente (sem empresa principal)', [
                            'empresa_id' => $primeiraEmpresa->id,
                            'empresa_nome' => $primeiraEmpresa->nome_fantasia
                        ]);
                    }
                }
            } else {
                // Empresa da sessão é válida, garantir que whitelabel também está na sessão
                if (!session('whitelabel_atual_id')) {
                    session(['whitelabel_atual_id' => $empresaAtual->whitelabel_id]);
                }
                Log::info('Empresa da sessão validada', [
                    'empresa_id' => $empresaAtual->id,
                    'empresa_nome' => $empresaAtual->nome_fantasia
                ]);
            }

            // Salvar aplicativos da empresa principal na sessão
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            if ($empresaPrincipal) {
                $aplicativos = $empresaPrincipal->aplicativos()->get();
                session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
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
