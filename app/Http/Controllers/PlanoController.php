<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\Empresa;
use App\Services\PlanoService;
use App\Services\PagBankService;
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
    public function index(Request $request)
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
        // Verificar se a feature flag de aplicativos está ativa e se existem aplicativos ativos
        $temAplicativosAtivos = \App\Helpers\FeatureFlagHelper::estaAtiva('aplicativos') 
            && \App\Models\Aplicativo::ativos()->exists();
        
        if (!$temAplicativosAtivos) {
            // Se não estiver ativa ou não houver aplicativos, redirecionar direto para pagamento
            return redirect()->route('planos.pagamento', $plano)
                ->withInput($request->all());
        }

        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'usuarios_extras' => 'nullable|integer|min:0',
            'empresas_extras' => 'nullable|integer|min:0',
            'aplicativos' => 'nullable|array',
            'aplicativos.*' => 'exists:aplicativos,id',
        ]);

        $periodo = PlanoPeriodoEnum::from($request->periodo);

        // Validar período do aplicativo baseado no plano atual
        $empresaPrincipal = $this->getEmpresaPrincipal();
        if ($empresaPrincipal) {
            $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);
            if ($planoAtual && !$planoAtual->isTeste() && $planoAtual->periodo) {
                // Se não for teste, o período do aplicativo deve ser o mesmo do plano
                if ($periodo->value !== $planoAtual->periodo->value) {
                    return redirect()->back()
                        ->with('error', "Você só pode contratar aplicativos no período {$planoAtual->periodo->getLabel()} (mesmo período do seu plano atual).");
                }
            }
        }

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
     * Show payment page (GET)
     */
    public function pagamento(Request $request, Plano $plano)
    {
        // Para GET, período é opcional (pode vir da query string)
        $periodoValue = $request->get('periodo');
        
        if (!$periodoValue) {
            return redirect()->route('planos.show', $plano)
                ->with('error', 'Por favor, selecione um período primeiro.');
        }

        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
        ]);

        $periodo = PlanoPeriodoEnum::from($periodoValue);
        $precoBase = $plano->getPrecoPorPeriodo($periodoValue);
        $usuariosExtras = 0;
        $empresasExtras = 0;
        
        // Verificar se a feature flag está ativa e se existem aplicativos ativos
        $temAplicativosAtivos = \App\Helpers\FeatureFlagHelper::estaAtiva('aplicativos') 
            && \App\Models\Aplicativo::ativos()->exists();
        
        $aplicativosIds = [];
        $aplicativos = collect();
        $valorAplicativos = 0;

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = 0;
        $valorEmpresasExtras = 0;
        $valorTotal = $precoBase;

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
     * Process payment page data (POST)
     */
    public function pagamentoPost(Request $request, Plano $plano)
    {
        // Validar período sempre
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
        ]);

        // Validar outros campos
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
        
        // Verificar se a feature flag está ativa e se existem aplicativos ativos
        $temAplicativosAtivos = \App\Helpers\FeatureFlagHelper::estaAtiva('aplicativos') 
            && \App\Models\Aplicativo::ativos()->exists();
        
        // Se não houver aplicativos ativos, ignorar aplicativos
        $aplicativosIds = $temAplicativosAtivos 
            ? ($request->aplicativos ?? []) 
            : [];

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = $usuariosExtras * $precoUsuarioAdicional;
        $valorEmpresasExtras = $empresasExtras * $precoEmpresaAdicional;

        // Calcular valor dos aplicativos selecionados (apenas se houver aplicativos ativos)
        $aplicativos = collect();
        $valorAplicativos = 0;
        if ($temAplicativosAtivos && !empty($aplicativosIds)) {
            $aplicativos = \App\Models\Aplicativo::whereIn('id', $aplicativosIds)->get();
            foreach ($aplicativos as $aplicativo) {
                $valorAplicativos += $aplicativo->getPrecoPorPeriodo($request->periodo);
            }
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
            'metodo_pagamento' => 'required|in:cartao,boleto,pix',
            'cpf_cnpj' => 'required_if:metodo_pagamento,pix|nullable|string',
            'telefone' => 'required_if:metodo_pagamento,pix|nullable|string',
        ], [
            'metodo_pagamento.required' => 'Por favor, selecione um método de pagamento.',
            'cpf_cnpj.required_if' => 'CPF/CNPJ é obrigatório para pagamento via PIX.',
            'telefone.required_if' => 'Telefone é obrigatório para pagamento via PIX.',
        ]);

        $empresaPrincipal = $this->getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->route('planos.index')
                ->with('error', 'Empresa principal não encontrada.');
        }

        $periodo = PlanoPeriodoEnum::from($request->periodo);

        // Verificar se a feature flag está ativa e se existem aplicativos ativos
        $temAplicativosAtivos = \App\Helpers\FeatureFlagHelper::estaAtiva('aplicativos') 
            && \App\Models\Aplicativo::ativos()->exists();

        // Validar período do aplicativo baseado no plano atual (apenas se houver aplicativos ativos)
        if ($temAplicativosAtivos) {
            $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);
            if ($planoAtual && !$planoAtual->isTeste() && $planoAtual->periodo) {
                // Se não for teste, o período do aplicativo deve ser o mesmo do plano
                if ($periodo->value !== $planoAtual->periodo->value) {
                    return redirect()->back()
                        ->with('error', "Você só pode contratar aplicativos no período {$planoAtual->periodo->getLabel()} (mesmo período do seu plano atual).");
                }
            }
        }

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
        
        // Verificar se a feature flag está ativa e se existem aplicativos ativos
        $temAplicativosAtivos = \App\Helpers\FeatureFlagHelper::estaAtiva('aplicativos') 
            && \App\Models\Aplicativo::ativos()->exists();
        
        // Se não houver aplicativos ativos, ignorar aplicativos
        $aplicativosIds = $temAplicativosAtivos 
            ? ($request->aplicativos ?? []) 
            : [];

        // Preços adicionais
        $precoUsuarioAdicional = 10.00;
        $precoEmpresaAdicional = 50.00;

        $valorUsuariosExtras = $usuariosExtras * $precoUsuarioAdicional;
        $valorEmpresasExtras = $empresasExtras * $precoEmpresaAdicional;

        // Calcular valor dos aplicativos selecionados (apenas se houver aplicativos ativos)
        $aplicativos = collect();
        $valorAplicativos = 0;
        if ($temAplicativosAtivos && !empty($aplicativosIds)) {
            $aplicativos = \App\Models\Aplicativo::whereIn('id', $aplicativosIds)->get();
            foreach ($aplicativos as $aplicativo) {
                $valorAplicativos += $aplicativo->getPrecoPorPeriodo($request->periodo);
            }
        }

        $precoBase = $plano->getPrecoPorPeriodo($request->periodo);
        $valorTotal = $precoBase + $valorUsuariosExtras + $valorEmpresasExtras + $valorAplicativos;

        // Processar pagamento PIX
        if ($request->metodo_pagamento === 'pix') {
            $pagBankService = new PagBankService();
            $usuario = Auth::user();
            
            $dadosCliente = [
                'nome' => $usuario->nome ?? $usuario->name ?? 'Cliente',
                'email' => $usuario->email,
                'cpf' => $request->cpf_cnpj,
                'telefone' => $request->telefone,
            ];

            $dadosProduto = [
                'id' => $plano->id,
                'nome' => $plano->nome . ' - ' . $periodo->getLabel(),
                'quantidade' => 1,
                'valor' => $valorTotal,
            ];

            // Gerar reference_id com máximo de 64 caracteres (requisito do PagBank)
            $referenceId = 'plano_' . substr(str_replace('-', '', $plano->id), 0, 8) . '_' . substr(str_replace('-', '', $empresaPrincipal->id), 0, 8) . '_' . time();
            $referenceId = substr($referenceId, 0, 64); // Garantir que não exceda 64 caracteres
            
            $resultado = $pagBankService->criarPagamentoPix($dadosCliente, $dadosProduto, $referenceId);

            if (!$resultado['success']) {
                \Log::error('Erro ao criar pagamento PIX', [
                    'error' => $resultado['error'] ?? 'Erro desconhecido',
                    'status' => $resultado['status'] ?? null,
                    'dados_cliente' => $dadosCliente,
                    'dados_produto' => $dadosProduto
                ]);
                
                return redirect()->route('planos.pagamento', $plano)
                    ->with('error', 'Erro ao processar pagamento PIX: ' . ($resultado['error'] ?? 'Erro desconhecido'))
                    ->withInput();
            }

            $pagamentoData = $resultado['data'];
            
            // Verificar se os dados necessários estão presentes
            if (!isset($pagamentoData['qr_codes']) || empty($pagamentoData['qr_codes'])) {
                \Log::error('QR Code não encontrado na resposta do PagBank', [
                    'pagamento_data' => $pagamentoData
                ]);
                
                return redirect()->route('planos.pagamento', $plano)
                    ->with('error', 'Erro ao gerar QR Code PIX. Por favor, tente novamente.')
                    ->withInput();
            }
            
            // Salvar dados do pagamento na sessão para exibir QR Code
            session([
                'pagamento_pix' => [
                    'order_id' => $pagamentoData['id'] ?? null,
                    'reference_id' => $referenceId,
                    'qr_code_url' => $pagamentoData['qr_codes'][0]['links'][0]['href'] ?? null,
                    'qr_code_text' => $pagamentoData['qr_codes'][0]['text'] ?? null,
                    'plano_id' => $plano->id,
                    'periodo' => $periodo->value,
                    'usuarios_extras' => $usuariosExtras,
                    'empresas_extras' => $empresasExtras,
                    'aplicativos_ids' => $aplicativosIds,
                    'valor_total' => $valorTotal,
                ]
            ]);
            
            // Forçar salvamento da sessão antes do redirecionamento
            session()->save();

            // Redirecionar para página de aguardo de pagamento PIX
            return redirect()->route('planos.pix.aguardar', $plano);
        }

        // Se é período de teste, ativar plano pago
        $planoAtual = $this->planoService->obterPlanoAtual($empresaPrincipal);
        if ($planoAtual && $planoAtual->isTeste()) {
            $novoPlano = $this->planoService->ativarPlano($empresaPrincipal, $plano, $periodo);

            // Salvar aplicativos selecionados (apenas se houver aplicativos ativos)
            if ($temAplicativosAtivos) {
                if (!empty($aplicativosIds)) {
                    $empresaPrincipal->aplicativos()->sync($aplicativosIds);
                } else {
                    // Se não selecionou nenhum aplicativo, remover todos
                    $empresaPrincipal->aplicativos()->detach();
                }
            }

            // Recarregar a empresa e o plano para garantir que está disponível
            $empresaPrincipal->refresh();
            $novoPlano->refresh();

            // Limpar cache de relacionamentos
            $empresaPrincipal->unsetRelation('planos');
            $empresaPrincipal->unsetRelation('aplicativos');

            // Atualizar sessão com novo plano e aplicativos
            $this->atualizarSessaoPlano($empresaPrincipal);
            $this->atualizarSessaoAplicativos($empresaPrincipal);

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

        // Salvar aplicativos selecionados
        if (!empty($aplicativosIds)) {
            $empresaPrincipal->aplicativos()->sync($aplicativosIds);
        } else {
            // Se não selecionou nenhum aplicativo, remover todos
            $empresaPrincipal->aplicativos()->detach();
        }

        // Recarregar a empresa e o plano para garantir que está disponível
        $empresaPrincipal->refresh();
        $novoPlano->refresh();

        // Limpar cache de relacionamentos
        $empresaPrincipal->unsetRelation('planos');
        $empresaPrincipal->unsetRelation('aplicativos');

        // Atualizar sessão com novo plano e aplicativos
        $this->atualizarSessaoPlano($empresaPrincipal);
        $this->atualizarSessaoAplicativos($empresaPrincipal);

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
     * Show page waiting for PIX payment
     */
    public function aguardarPix(Plano $plano)
    {
        $pagamentoPix = session('pagamento_pix');

        if (!$pagamentoPix) {
            \Log::warning('Sessão de pagamento PIX não encontrada', [
                'plano_id' => $plano->id,
                'user_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);
            
            return redirect()->route('planos.pagamento', $plano)
                ->with('error', 'Sessão de pagamento não encontrada. Por favor, tente novamente.');
        }

        // Verificar se os dados necessários estão presentes
        if (empty($pagamentoPix['qr_code_url']) && empty($pagamentoPix['qr_code_text'])) {
            \Log::warning('QR Code PIX não encontrado na sessão', [
                'plano_id' => $plano->id,
                'pagamento_pix' => $pagamentoPix
            ]);
            
            return redirect()->route('planos.pagamento', $plano)
                ->with('error', 'Dados do QR Code não encontrados. Por favor, tente novamente.');
        }

        return view('planos.pix-aguardar', compact('plano', 'pagamentoPix'));
    }

    /**
     * Webhook para receber notificações do PagBank
     */
    public function webhookPix(Request $request)
    {
        try {
            $data = $request->all();
            
            \Log::info('Webhook PagBank recebido', $data);

            // Verificar se o pagamento foi aprovado
            if (isset($data['charges']) && is_array($data['charges'])) {
                foreach ($data['charges'] as $charge) {
                    if (isset($charge['status']) && $charge['status'] === 'PAID') {
                        // Buscar dados do pagamento na sessão ou banco de dados
                        $referenceId = $data['reference_id'] ?? null;
                        
                        if ($referenceId && strpos($referenceId, 'plano_') === 0) {
                            // Extrair informações do reference_id
                            $parts = explode('_', $referenceId);
                            if (count($parts) >= 3) {
                                $planoId = $parts[1];
                                $empresaId = $parts[2];
                                
                                // Buscar empresa e plano
                                $empresa = Empresa::find($empresaId);
                                $plano = Plano::find($planoId);
                                
                                if ($empresa && $plano) {
                                    // Recuperar dados da sessão ou recriar
                                    $periodoValue = $data['metadata']['periodo'] ?? 'mensal';
                                    $periodo = PlanoPeriodoEnum::from($periodoValue);
                                    
                                    // Ativar plano
                                    $novoPlano = $this->planoService->ativarPlano($empresa, $plano, $periodo);
                                    
                                    // Atualizar sessão
                                    $this->atualizarSessaoPlano($empresa);
                                    
                                    \Log::info('Plano ativado via webhook PIX', [
                                        'empresa_id' => $empresaId,
                                        'plano_id' => $planoId,
                                        'order_id' => $data['id'] ?? null
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao processar webhook PagBank', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['error' => 'Erro ao processar webhook'], 500);
        }
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

    private function atualizarSessaoAplicativos(Empresa $empresa): void
    {
        // Limpar cache de relacionamentos para garantir dados atualizados
        $empresa->unsetRelation('aplicativos');
        $aplicativos = $empresa->aplicativos()->get();
        session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
        session()->save(); // Forçar salvamento da sessão
    }
}
