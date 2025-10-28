<?php

namespace App\Http\Controllers;

use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Helpers\PermissionHelper;
use App\Models\CentroCusto;
use App\Models\ContaEmpresa;
use App\Models\Entidade;
use App\Models\FormaPagamento;
use App\Models\Movimentacao;
use App\Models\PlanoConta;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MovimentacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'listar')) {
            abort(403, 'Você não tem permissão para listar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Validar tipo
        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        $filtroCard = $request->get('filtro', 'todos');
        $filtroSituacao = $request->get('situacao', 'todos');
        $filtroDescricao = $request->get('descricao', '');
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');

        $query = Movimentacao::daEmpresa($empresaAtual->id)
            ->porTipo($tipoEnum)
            ->with(['planoConta', 'centroCusto', 'formaPagamento', 'contaEmpresa', 'entidade'])
            ->orderBy('vencimento', 'asc');

        // Aplicar filtro do card clicável
        $this->aplicarFiltroCard($query, $filtroCard);

        // Aplicar filtros avançados
        if ($filtroSituacao !== 'todos') {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->porSituacao($situacaoEnum);
            }
        }

        if (!empty($filtroDescricao)) {
            $query->where('descricao', 'LIKE', '%' . $filtroDescricao . '%');
        }

        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }

        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        $movimentacoes = $query->paginate(15);

        // Calcular resumos para os cards
        $resumo = $this->calcularResumos($empresaAtual->id, $tipoEnum);

        $titulo = $tipoEnum->getLabel();
        $tipoCor = $tipoEnum->getColor();

        // Buscar dados para o modal
        $formasPagamento = FormaPagamento::daEmpresa($empresaAtual->id)->disponiveis()->orderBy('nome')->get();
        $contasBancarias = ContaEmpresa::daEmpresa($empresaAtual->id)->ativas()->orderBy('nome')->get();

        return view('movimentacao.index', compact(
            'movimentacoes',
            'titulo',
            'tipoCor',
            'tipo',
            'resumo',
            'filtroCard',
            'filtroSituacao',
            'filtroDescricao',
            'filtroVencimentoInicio',
            'filtroVencimentoFim',
            'formasPagamento',
            'contasBancarias'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'criar')) {
            abort(403, 'Você não tem permissão para criar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Validar tipo
        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        // Buscar dados para os selects
        $planoContas = PlanoConta::daEmpresa($empresaAtual->id)->orderBy('nome')->get();
        $centroCustos = CentroCusto::daEmpresa($empresaAtual->id)->ativos()->orderBy('nome')->get();
        $formasPagamento = FormaPagamento::daEmpresa($empresaAtual->id)->disponiveis()->orderBy('nome')->get();
        $contasEmpresa = ContaEmpresa::daEmpresa($empresaAtual->id)->ativas()->orderBy('nome')->get();
        $entidades = Entidade::daEmpresa($empresaAtual->id)->ativos()->orderBy('nome')->get();

        $titulo = 'Nova ' . $tipoEnum->getLabel();
        $tipoCor = $tipoEnum->getColor();

        return view('movimentacao.create', compact(
            'planoContas',
            'centroCustos',
            'formasPagamento',
            'contasEmpresa',
            'entidades',
            'titulo',
            'tipoCor',
            'tipo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'criar')) {
            abort(403, 'Você não tem permissão para criar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Validar tipo
        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        $request->validate([
            'plano_conta_id' => 'required|exists:plano_conta,id',
            'centro_custo_id' => 'nullable|exists:centro_custo,id',
            'forma_pagamento_id' => 'required|exists:forma_pagamento,id',
            'conta_empresa_id' => 'required|exists:conta_empresa,id',
            'entidade_id' => 'nullable|exists:entidade,id',
            'descricao' => 'required|string|max:255',
            'vencimento' => 'required|date',
            'observacao' => 'nullable|string',
            'informacao_complementar' => 'nullable|string',
            'valor' => 'required|numeric|min:0.01',
            'juros' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $valorTotal = $request->valor;
            if ($request->juros) {
                $valorTotal += $request->juros;
            }
            if ($request->desconto) {
                $valorTotal -= $request->desconto;
            }

            $movimentacao = Movimentacao::create([
                'id' => Str::uuid(),
                'empresa_id' => $empresaAtual->id,
                'plano_conta_id' => $request->plano_conta_id,
                'centro_custo_id' => $request->centro_custo_id,
                'forma_pagamento_id' => $request->forma_pagamento_id,
                'conta_empresa_id' => $request->conta_empresa_id,
                'situacao' => MovimentacaoSituacaoEnum::PENDENTE,
                'tipo' => $tipoEnum,
                'entidade_id' => $request->entidade_id,
                'descricao' => $request->descricao,
                'vencimento' => $request->vencimento,
                'observacao' => $request->observacao,
                'informacao_complementar' => $request->informacao_complementar,
                'valor' => $request->valor,
                'juros' => $request->juros,
                'desconto' => $request->desconto,
                'valor_total' => $valorTotal,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            DB::commit();

            // Registrar no audit log
            AuditService::logCreate($movimentacao, "Criada {$tipoEnum->getLabel()}: {$movimentacao->descricao}");

            return redirect()
                ->route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index')
                ->with('success', 'Movimentação criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se a movimentação pertence à empresa atual
        if ($movimentacao->empresa_id !== $empresaAtual->id) {
            abort(403, 'Movimentação não encontrada.');
        }

        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        $titulo = 'Detalhes da ' . $tipoEnum->getLabel();
        $tipoCor = $tipoEnum->getColor();

        return view('movimentacao.show', compact('movimentacao', 'titulo', 'tipoCor', 'tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            abort(403, 'Você não tem permissão para editar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se a movimentacao pertence à empresa
        if ($movimentacao->empresa_id !== $empresaAtual->id) {
            abort(403, 'Movimentação não encontrada.');
        }

        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        // Buscar dados para os selects
        $planoContas = PlanoConta::daEmpresa($empresaAtual->id)->orderBy('nome')->get();
        $centroCustos = CentroCusto::daEmpresa($empresaAtual->id)->ativos()->orderBy('nome')->get();
        $formasPagamento = FormaPagamento::daEmpresa($empresaAtual->id)->disponiveis()->orderBy('nome')->get();
        $contasEmpresa = ContaEmpresa::daEmpresa($empresaAtual->id)->ativas()->orderBy('nome')->get();
        $entidades = Entidade::daEmpresa($empresaAtual->id)->ativos()->orderBy('nome')->get();

        $titulo = 'Editar ' . $tipoEnum->getLabel();
        $tipoCor = $tipoEnum->getColor();

        return view('movimentacao.edit', compact(
            'movimentacao',
            'planoContas',
            'centroCustos',
            'formasPagamento',
            'contasEmpresa',
            'entidades',
            'titulo',
            'tipoCor',
            'tipo'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            abort(403, 'Você não tem permissão para editar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se a movimentacao pertence à empresa
        if ($movimentacao->empresa_id !== $empresaAtual->id) {
            abort(403, 'Movimentação não encontrada.');
        }

        $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
        if (!$tipoEnum) {
            abort(404, 'Tipo de movimentação inválido.');
        }

        $request->validate([
            'plano_conta_id' => 'required|exists:plano_conta,id',
            'centro_custo_id' => 'nullable|exists:centro_custo,id',
            'forma_pagamento_id' => 'required|exists:forma_pagamento,id',
            'conta_empresa_id' => 'required|exists:conta_empresa,id',
            'entidade_id' => 'nullable|exists:entidade,id',
            'descricao' => 'required|string|max:255',
            'vencimento' => 'required|date',
            'observacao' => 'nullable|string',
            'informacao_complementar' => 'nullable|string',
            'valor' => 'required|numeric|min:0.01',
            'juros' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
            'situacao' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // Salvar valores antigos para o audit log
            $oldValues = $movimentacao->getAttributes();

            $valorTotal = $request->valor;
            if ($request->juros) {
                $valorTotal += $request->juros;
            }
            if ($request->desconto) {
                $valorTotal -= $request->desconto;
            }

            $movimentacao->update([
                'plano_conta_id' => $request->plano_conta_id,
                'centro_custo_id' => $request->centro_custo_id,
                'forma_pagamento_id' => $request->forma_pagamento_id,
                'conta_empresa_id' => $request->conta_empresa_id,
                'situacao' => MovimentacaoSituacaoEnum::from($request->situacao),
                'entidade_id' => $request->entidade_id,
                'descricao' => $request->descricao,
                'vencimento' => $request->vencimento,
                'observacao' => $request->observacao,
                'informacao_complementar' => $request->informacao_complementar,
                'valor' => $request->valor,
                'juros' => $request->juros,
                'desconto' => $request->desconto,
                'valor_total' => $valorTotal,
                'atualizado_em' => now(),
            ]);

            DB::commit();

            // Registrar no audit log
            AuditService::logUpdate($movimentacao, $oldValues, "Atualizada {$tipoEnum->getLabel()}: {$movimentacao->descricao}");

            return redirect()
                ->route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index')
                ->with('success', 'Movimentação atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se a movimentacao pertence à empresa
        if ($movimentacao->empresa_id !== $empresaAtual->id) {
            abort(403, 'Movimentação não encontrada.');
        }

        try {
            // Registrar exclusão no audit log antes de deletar
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
            AuditService::logDelete($movimentacao, "Excluída {$tipoEnum->getLabel()}: {$movimentacao->descricao}");

            $movimentacao->delete();

            return redirect()
                ->route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index')
                ->with('success', 'Movimentação excluída com sucesso!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status da movimentação
     */
    public function toggleStatus(Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            abort(403, 'Você não tem permissão para alterar status de movimentações.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se a movimentacao pertence à empresa
        if ($movimentacao->empresa_id !== $empresaAtual->id) {
            abort(403, 'Movimentação não encontrada.');
        }

        try {
            $novaSituacao = $movimentacao->isPendente()
                ? MovimentacaoSituacaoEnum::PAGA
                : MovimentacaoSituacaoEnum::PENDENTE;

            // Salvar valores antigos para o audit log
            $oldValues = $movimentacao->getAttributes();

            $movimentacao->update([
                'situacao' => $novaSituacao,
                'data_compensacao' => $novaSituacao === MovimentacaoSituacaoEnum::PAGA ? now()->toDateString() : null,
                'atualizado_em' => now(),
            ]);

            $status = $novaSituacao === MovimentacaoSituacaoEnum::PAGA ? 'paga' : 'pendente';

            // Registrar mudança de status no audit log
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
            AuditService::logUpdate($movimentacao, $oldValues, "Status alterado para {$status}: {$movimentacao->descricao}");

            return redirect()
                ->route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index')
                ->with('success', "Movimentação marcada como {$status}!");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Aplicar filtro do card clicável
     */
    private function aplicarFiltroCard($query, $filtroCard)
    {
        $hoje = now()->toDateString();

        switch ($filtroCard) {
            case 'vencidos':
                $query->where('vencimento', '<', $hoje)
                      ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA);
                break;
            case 'vence_hoje':
                $query->where('vencimento', $hoje)
                      ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA);
                break;
            case 'a_vencer':
                $query->where('vencimento', '>', $hoje)
                      ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA);
                break;
            case 'pagos':
                $query->where('situacao', MovimentacaoSituacaoEnum::PAGA);
                break;
            case 'todos':
            default:
                // Não aplica filtro adicional
                break;
        }
    }

    /**
     * Calcular resumos para os cards
     */
    private function calcularResumos($empresaId, $tipoEnum)
    {
        $hoje = now()->toDateString();

        // Vencidos (vencimento < hoje e situação != PAGA)
        $vencidos = Movimentacao::daEmpresa($empresaId)
            ->porTipo($tipoEnum)
            ->where('vencimento', '<', $hoje)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA)
            ->sum('valor_total');

        // Vence hoje (vencimento = hoje e situação != PAGA)
        $venceHoje = Movimentacao::daEmpresa($empresaId)
            ->porTipo($tipoEnum)
            ->where('vencimento', $hoje)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA)
            ->sum('valor_total');

        // A vencer (vencimento > hoje e situação != PAGA)
        $aVencer = Movimentacao::daEmpresa($empresaId)
            ->porTipo($tipoEnum)
            ->where('vencimento', '>', $hoje)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA)
            ->sum('valor_total');

        // Pagos (situação = PAGA)
        $pagos = Movimentacao::daEmpresa($empresaId)
            ->porTipo($tipoEnum)
            ->where('situacao', MovimentacaoSituacaoEnum::PAGA)
            ->sum('valor_total');

        // Total geral
        $total = Movimentacao::daEmpresa($empresaId)
            ->porTipo($tipoEnum)
            ->sum('valor_total');

        return [
            'vencidos' => $vencidos,
            'vence_hoje' => $venceHoje,
            'a_vencer' => $aVencer,
            'pagos' => $pagos,
            'total' => $total,
        ];
    }

    /**
     * Gerar JWT temporário para confirmação de pagamento
     */
    public function gerarJwtConfirmacao(Request $request, Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar movimentações.'
            ], 403);
        }

        try {
            // Criar payload do JWT
            $payload = [
                'movimentacao_id' => $movimentacao->id,
                'user_id' => Auth::id(),
                'empresa_id' => Auth::user()->empresa_atual_id,
                'tipo' => $tipo,
                'action' => 'confirmar_pagamento',
                'exp' => now()->addMinutes(15)->timestamp, // Expira em 15 minutos
                'iat' => now()->timestamp,
                'jti' => Str::uuid()->toString() // JWT ID único
            ];

            // Gerar JWT usando a chave secreta da aplicação
            $jwt = JWT::encode($payload, config('app.key'), 'HS256');

            return response()->json([
                'success' => true,
                'jwt_token' => $jwt,
                'expires_at' => now()->addMinutes(15)->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar token de confirmação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmar pagamento de uma movimentação
     */
    public function confirmarPagamento(Request $request, Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar movimentações.'
            ], 403);
        }

        // Validar JWT temporário
        $jwtToken = $request->header('X-Confirmation-Token');
        if (!$jwtToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token de confirmação não fornecido.'
            ], 400);
        }

        try {
            // Decodificar e validar JWT
            $payload = JWT::decode($jwtToken, new Key(config('app.key'), 'HS256'));

            // Verificar se o JWT é válido para esta operação
            if ($payload->movimentacao_id !== $movimentacao->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido para esta movimentação.'
                ], 400);
            }

            if ($payload->action !== 'confirmar_pagamento') {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido para esta operação.'
                ], 400);
            }

            if ($payload->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido para este usuário.'
                ], 400);
            }

            // Verificar se o token não expirou
            if ($payload->exp < now()->timestamp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de confirmação expirado. Solicite um novo token.'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token de confirmação inválido: ' . $e->getMessage()
            ], 400);
        }

        $request->validate([
            'data_compensacao' => 'required|date',
            'forma_pagamento_id' => 'required|exists:forma_pagamento,id',
            'conta_empresa_id' => 'required|exists:conta_empresa,id',
            'valor_bruto' => 'required|numeric|min:0',
            'juros' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
            'valor_total' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Salvar valores antigos para o audit log
            $oldValues = $movimentacao->getAttributes();

            $movimentacao->update([
                'situacao' => MovimentacaoSituacaoEnum::PAGA,
                'data_pagamento' => $request->data_compensacao,
                'forma_pagamento_id' => $request->forma_pagamento_id,
                'conta_empresa_id' => $request->conta_empresa_id,
                'valor_bruto' => $request->valor_bruto,
                'juros' => $request->juros ?? 0,
                'desconto' => $request->desconto ?? 0,
                'valor_total' => $request->valor_total,
                'observacoes_pagamento' => $request->observacoes,
                'atualizado_em' => now()
            ]);

            DB::commit();

            // Registrar confirmação de pagamento no audit log
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
            AuditService::logUpdate($movimentacao, $oldValues, "Pagamento confirmado: {$movimentacao->descricao}");

            return response()->json([
                'success' => true,
                'message' => 'Pagamento confirmado com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar movimentação como pendente
     */
    public function marcarPendente(Request $request, Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar movimentações.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Salvar valores antigos para o audit log
            $oldValues = $movimentacao->getAttributes();

            $movimentacao->update([
                'situacao' => MovimentacaoSituacaoEnum::PENDENTE,
                'data_pagamento' => null,
                'observacoes_pagamento' => $request->observacoes ?? null,
                'atualizado_em' => now()
            ]);

            DB::commit();

            // Registrar marcação como pendente no audit log
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
            AuditService::logUpdate($movimentacao, $oldValues, "Marcada como pendente: {$movimentacao->descricao}");

            return response()->json([
                'success' => true,
                'message' => 'Movimentação marcada como pendente!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar como pendente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reativar movimentação cancelada
     */
    public function reativar(Request $request, Movimentacao $movimentacao, $tipo)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('movimentacao', 'editar')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para editar movimentações.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Salvar valores antigos para o audit log
            $oldValues = $movimentacao->getAttributes();

            $movimentacao->update([
                'situacao' => MovimentacaoSituacaoEnum::PENDENTE,
                'observacoes_pagamento' => $request->observacoes ?? null,
                'atualizado_em' => now()
            ]);

            DB::commit();

            // Registrar reativação no audit log
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($tipo);
            AuditService::logUpdate($movimentacao, $oldValues, "Reativada: {$movimentacao->descricao}");

            return response()->json([
                'success' => true,
                'message' => 'Movimentação reativada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao reativar movimentação: ' . $e->getMessage()
            ], 500);
        }
    }
}
