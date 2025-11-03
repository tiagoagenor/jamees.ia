<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PlanoService;
use App\Enums\PlanoStatusEnum;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanoAtivo
{
    protected $planoService;

    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof \App\Models\Usuario) {
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

                if ($empresaPrincipal) {
                    $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);

                    // Rotas permitidas para usuários sem plano
                    $rotasPermitidas = [
                        'planos.*',
                        'login',
                        'logout',
                        'register',
                        'password.*',
                        'verification.*',
                        'profile.edit',
                        'profile.update',
                        'profile.destroy',
                        'switch.company'
                    ];

                    // Se não tem plano ativo, bloquear acesso a todas as outras rotas
                    if (!$planoAtual) {
                        $rotaPermitida = false;
                        foreach ($rotasPermitidas as $rota) {
                            if ($request->routeIs($rota)) {
                                $rotaPermitida = true;
                                break;
                            }
                        }

                        if (!$rotaPermitida) {
                            return redirect()->route('planos.index')
                                ->with('error', 'Você precisa ativar um plano para acessar esta funcionalidade.');
                        }
                    }

                    // Se está em período de teste, permitir acesso total
                    if ($planoAtual && $planoAtual->isTeste()) {
                        // Durante o teste, permitir acesso a todas as funcionalidades
                        // Não bloquear nenhuma rota
                    }

                    // Se o plano está expirado, bloquear acesso a todas as outras rotas
                    if ($planoAtual && $planoAtual->isExpirado()) {
                        $rotaPermitida = false;
                        foreach ($rotasPermitidas as $rota) {
                            if ($request->routeIs($rota)) {
                                $rotaPermitida = true;
                                break;
                            }
                        }

                        if (!$rotaPermitida) {
                            return redirect()->route('planos.index')
                                ->with('error', 'Seu plano expirou. Por favor, renove ou escolha um novo plano.');
                        }
                    }

                    // Adicionar informações do plano à sessão para uso nas views
                    if ($planoAtual) {
                        session([
                            'plano_atual' => [
                                'nome' => $planoAtual->plano->nome,
                                'tipo' => $planoAtual->plano->tipo->value,
                                'status' => $planoAtual->status->value,
                                'dias_restantes' => $planoAtual->diasRestantes(),
                                'is_teste' => $planoAtual->isTeste(),
                                'is_proximo_vencimento' => $planoAtual->isProximoDoVencimento(),
                                'data_fim' => $planoAtual->data_fim->format('d/m/Y'),
                            ]
                        ]);
                    }
                }
            }
        }

        return $next($request);
    }
}
