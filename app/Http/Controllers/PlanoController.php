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
     * Show configuration page after selecting period
     */
    public function configurar(Request $request, Plano $plano)
    {
        // Validar apenas se for POST
        if ($request->isMethod('post')) {
            $request->validate([
                'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            ]);
        } else {
            // Para GET, validar se período foi passado
            $request->validate([
                'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            ], [
                'periodo.required' => 'Período é obrigatório. Por favor, selecione um período primeiro.'
            ]);
        }

        $periodo = PlanoPeriodoEnum::from($request->periodo);
        $precoBase = $plano->getPrecoPorPeriodo($request->periodo);
        $usuariosExtras = (int) ($request->usuarios_extras ?? 0);
        $empresasExtras = (int) ($request->empresas_extras ?? 0);

        // Preços adicionais (pode ser configurável no futuro)
        $precoUsuarioAdicional = 10.00; // R$ 10,00 por usuário adicional
        $precoEmpresaAdicional = 50.00; // R$ 50,00 por empresa adicional

        return view('planos.configurar', compact(
            'plano',
            'periodo',
            'precoBase',
            'precoUsuarioAdicional',
            'precoEmpresaAdicional',
            'usuariosExtras',
            'empresasExtras'
        ));
    }

    /**
     * Show applications selection page
     */
    public function aplicativos(Request $request, Plano $plano)
    {
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'usuarios_extras' => 'nullable|integer|min:0',
            'empresas_extras' => 'nullable|integer|min:0',
            'aplicativos' => 'nullable|array',
            'aplicativos.*' => 'exists:aplicativos,id',
        ]);

        $periodo = PlanoPeriodoEnum::from($request->periodo);
        $precoBase = $plano->getPrecoPorPeriodo($request->periodo);
        $usuariosExtras = (int) ($request->usuarios_extras ?? 0);
        $empresasExtras = (int) ($request->empresas_extras ?? 0);

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = $usuariosExtras * $precoUsuarioAdicional;
        $valorEmpresasExtras = $empresasExtras * $precoEmpresaAdicional;
        $valorTotal = $precoBase + $valorUsuariosExtras + $valorEmpresasExtras;

        // Buscar aplicativos disponíveis
        $aplicativos = \App\Models\Aplicativo::ativos()->orderBy('nome')->get();
        $aplicativosSelecionados = $request->aplicativos ?? [];

        return view('planos.aplicativos', compact(
            'plano',
            'periodo',
            'precoBase',
            'usuariosExtras',
            'empresasExtras',
            'valorUsuariosExtras',
            'valorEmpresasExtras',
            'valorTotal',
            'aplicativos',
            'aplicativosSelecionados'
        ));
    }

    /**
     * Show payment page
     */
    public function pagamento(Request $request, Plano $plano)
    {
        // Validar período sempre
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
        ]);

        // Validar outros campos (tanto para GET quanto POST)
        $request->validate([
            'usuarios_extras' => 'nullable|integer|min:0',
            'empresas_extras' => 'nullable|integer|min:0',
            'aplicativos' => 'nullable|array',
            'aplicativos.*' => 'exists:aplicativos,id',
        ]);

        $periodo = PlanoPeriodoEnum::from($request->periodo);
        $precoBase = $plano->getPrecoPorPeriodo($request->periodo);
        $usuariosExtras = (int) ($request->usuarios_extras ?? 0);
        $empresasExtras = (int) ($request->empresas_extras ?? 0);
        $aplicativosIds = $request->aplicativos ?? [];

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = $usuariosExtras * $precoUsuarioAdicional;
        $valorEmpresasExtras = $empresasExtras * $precoEmpresaAdicional;

        // Calcular valor dos aplicativos selecionados
        $aplicativos = \App\Models\Aplicativo::whereIn('id', $aplicativosIds)->get();
        $valorAplicativos = 0;
        foreach ($aplicativos as $aplicativo) {
            $valorAplicativos += $aplicativo->getPrecoPorPeriodo($request->periodo);
        }

        $valorTotal = $precoBase + $valorUsuariosExtras + $valorEmpresasExtras + $valorAplicativos;

        return view('planos.pagamento', compact(
            'plano',
            'periodo',
            'precoBase',
            'usuariosExtras',
            'empresasExtras',
            'valorUsuariosExtras',
            'valorEmpresasExtras',
            'valorAplicativos',
            'aplicativos',
            'valorTotal'
        ));
    }

    /**
     * Activate a plan for the company
     */
    public function ativar(Request $request, Plano $plano)
    {
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'usuarios_extras' => 'nullable|integer|min:0',
            'empresas_extras' => 'nullable|integer|min:0',
            'aplicativos' => 'nullable|array',
            'aplicativos.*' => 'exists:aplicativos,id',
        ]);

        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->route('planos.index')
                ->with('error', 'Empresa principal não encontrada.');
        }

        $periodo = PlanoPeriodoEnum::from($request->periodo);
        $validacao = $this->planoService->podeAtivarPlano($empresaPrincipal, $plano);

        if (!$validacao['pode']) {
            // Redirecionar para a lista de planos com mensagem de erro
            // Evita loop de redirecionamento que ocorre com redirect()->back()
            return redirect()->route('planos.index')
                ->with('error', $validacao['mensagem']);
        }

        // Preparar dados para a view de sucesso
        $usuariosExtras = (int) ($request->usuarios_extras ?? 0);
        $empresasExtras = (int) ($request->empresas_extras ?? 0);
        $aplicativosIds = $request->aplicativos ?? [];

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = $usuariosExtras * $precoUsuarioAdicional;
        $valorEmpresasExtras = $empresasExtras * $precoEmpresaAdicional;

        // Calcular valor dos aplicativos selecionados
        $aplicativos = \App\Models\Aplicativo::whereIn('id', $aplicativosIds)->get();
        $valorAplicativos = 0;
        foreach ($aplicativos as $aplicativo) {
            $valorAplicativos += $aplicativo->getPrecoPorPeriodo($request->periodo);
        }

        $precoBase = $plano->getPrecoPorPeriodo($request->periodo);
        $valorTotal = $precoBase + $valorUsuariosExtras + $valorEmpresasExtras + $valorAplicativos;

        // Se é período de teste, ativar plano pago
        $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);
        if ($planoAtual && $planoAtual->isTeste()) {
            $novoPlano = $this->planoService->ativarPlano($empresaPrincipal, $plano, $periodo);

            // Recarregar a empresa e o plano para garantir que está disponível
            $empresaPrincipal->refresh();
            $novoPlano->refresh();

            // Atualizar sessão com novo plano
            $this->atualizarSessaoPlano($empresaPrincipal);

            // Limpar cache de relacionamentos
            $empresaPrincipal->unsetRelation('planos');

            // Redirecionar para página de sucesso
            return redirect()->route('planos.sucesso', $plano)
                ->with([
                    'periodo' => $periodo,
                    'usuariosExtras' => $usuariosExtras,
                    'empresasExtras' => $empresasExtras,
                    'aplicativos' => $aplicativos,
                    'valorTotal' => $valorTotal,
                ]);
        }

        // Ativar novo plano
        $novoPlano = $this->planoService->ativarPlano($empresaPrincipal, $plano, $periodo);

        // Recarregar a empresa e o plano para garantir que está disponível
        $empresaPrincipal->refresh();
        $novoPlano->refresh();

        // Limpar cache de relacionamentos
        $empresaPrincipal->unsetRelation('planos');

        // Atualizar sessão com novo plano
        $this->atualizarSessaoPlano($empresaPrincipal);

        // Redirecionar para página de sucesso
        return redirect()->route('planos.sucesso', $plano)
            ->with([
                'periodo' => $periodo,
                'usuariosExtras' => $usuariosExtras,
                'empresasExtras' => $empresasExtras,
                'aplicativos' => $aplicativos,
                'valorTotal' => $valorTotal,
            ]);
    }

    /**
     * Show success page after plan activation
     */
    public function sucesso(Plano $plano)
    {
        // Recuperar dados da sessão (flash data)
        $periodoData = session('periodo');
        $usuariosExtras = session('usuariosExtras', 0);
        $empresasExtras = session('empresasExtras', 0);
        $aplicativos = session('aplicativos', collect());
        $valorTotal = session('valorTotal', 0);

        // Converter período se for string ou enum
        if ($periodoData instanceof PlanoPeriodoEnum) {
            $periodo = $periodoData;
        } elseif (is_string($periodoData)) {
            $periodo = PlanoPeriodoEnum::from($periodoData);
        } elseif (request()->has('periodo')) {
            $periodo = PlanoPeriodoEnum::from(request('periodo'));
        } else {
            $periodo = PlanoPeriodoEnum::MENSAL;
        }

        // Garantir que aplicativos é uma Collection
        if (!($aplicativos instanceof \Illuminate\Support\Collection)) {
            $aplicativos = collect($aplicativos);
        }

        return view('planos.sucesso', compact(
            'plano',
            'periodo',
            'usuariosExtras',
            'empresasExtras',
            'aplicativos',
            'valorTotal'
        ));
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
