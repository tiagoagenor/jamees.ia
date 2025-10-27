<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\Empresa;
use App\Services\PlanoService;
use App\Enums\PlanoPeriodoEnum;
use App\Enums\PlanoStatusEnum;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    protected $planoService;

    public function __construct(PlanoService $planoService)
    {
        $this->planoService = $planoService;
    }

    /**
     * Display a listing of available plans
     */
    public function index()
    {
        $planos = Plano::visiveis()->ordenados()->get();
        $empresaPrincipal = $this->getEmpresaPrincipal();
        $planoAtual = $empresaPrincipal ? $this->planoService->obterPlanoAtual($empresaPrincipal) : null;

        return view('planos.index', compact('planos', 'planoAtual'));
    }

    /**
     * Show the form for activating a plan
     */
    public function show(Plano $plano)
    {
        $empresaPrincipal = $this->getEmpresaPrincipal();
        $planoAtual = $empresaPrincipal ? $this->planoService->obterPlanoAtual($empresaPrincipal) : null;
        $periodos = PlanoPeriodoEnum::cases();

        return view('planos.show', compact('plano', 'planoAtual', 'periodos'));
    }

    /**
     * Activate a plan for the company
     */
    public function ativar(Request $request, Plano $plano)
    {
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
        ]);

        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->back()
                ->with('error', 'Empresa principal não encontrada.');
        }

        $periodo = PlanoPeriodoEnum::from($request->periodo);
        $validacao = $this->planoService->podeAtivarPlano($empresaPrincipal, $plano);

        if (!$validacao['pode']) {
            return redirect()->back()
                ->with('error', $validacao['mensagem']);
        }

        // Se é período de teste, ativar plano pago
        $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);
        if ($planoAtual && $planoAtual->isTeste()) {
            $this->planoService->ativarPlano($empresaPrincipal, $plano, $periodo);

            // Atualizar sessão com novo plano
            $this->atualizarSessaoPlano($empresaPrincipal);

            return redirect()->route('planos.index')
                ->with('success', "Plano {$plano->nome} ativado com sucesso! Seu período de teste foi convertido para o plano escolhido.");
        }

        // Ativar novo plano
        $this->planoService->ativarPlano($empresaPrincipal, $plano, $periodo);

        // Atualizar sessão com novo plano
        $this->atualizarSessaoPlano($empresaPrincipal);

        return redirect()->route('planos.index')
            ->with('success', "Plano {$plano->nome} ativado com sucesso!");
    }

    /**
     * Cancel current plan
     */
    public function cancelar()
    {
        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->back()
                ->with('error', 'Empresa principal não encontrada.');
        }

        $this->planoService->desativarPlanoAtual($empresaPrincipal);

        // Atualizar sessão após cancelamento
        $this->atualizarSessaoPlano($empresaPrincipal);

        return redirect()->route('planos.index')
            ->with('success', 'Plano cancelado com sucesso!');
    }

    /**
     * Show plan history
     */
    public function historico()
    {
        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->back()
                ->with('error', 'Empresa principal não encontrada.');
        }

        $historico = $this->planoService->obterHistoricoPlanos($empresaPrincipal);

        return view('planos.historico', compact('historico'));
    }

    /**
     * Get company's current plan info
     */
    public function info()
    {
        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return response()->json(['error' => 'Empresa principal não encontrada.'], 404);
        }

        $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);

        if (!$planoAtual) {
            return response()->json(['error' => 'Nenhum plano ativo encontrado.'], 404);
        }

        return response()->json([
            'plano' => [
                'nome' => $planoAtual->plano->nome,
                'status' => $planoAtual->status->getLabel(),
                'dias_restantes' => $planoAtual->diasRestantes(),
                'is_teste' => $planoAtual->isTeste(),
                'is_proximo_vencimento' => $planoAtual->isProximoDoVencimento(),
                'data_fim' => $planoAtual->data_fim->format('d/m/Y'),
                'periodo' => $planoAtual->periodo->getLabel(),
            ]
        ]);
    }

    /**
     * Get empresa principal do usuário logado
     */
    private function getEmpresaPrincipal(): ?Empresa
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\Usuario) {
            return null;
        }

        return $user->empresas()->wherePivot('principal', 1)->first();
    }

    /**
     * Atualizar informações do plano na sessão
     */
    private function atualizarSessaoPlano(Empresa $empresa): void
    {
        $planoAtual = $this->planoService->obterPlanoAtual($empresa);

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
        } else {
            // Se não tem plano, remover da sessão
            session()->forget('plano_atual');
        }
    }
}
