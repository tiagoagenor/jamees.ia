<?php

namespace App\Http\Controllers;

use App\Models\PlanoConta;
use App\Models\Dre;
use App\Enums\PlanoContaMovimentacaoEnum;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlanoContaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'listar')) {
            abort(403, 'Você não tem permissão para listar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Filtros
        $filtroMovimentacao = $request->get('movimentacao', 'todos');
        $filtroNome = $request->get('nome', '');

        $query = PlanoConta::daEmpresa($empresaPrincipal->id)
            ->with(['dre', 'planoContaPai', 'planoContasFilhos']);

        // Aplicar filtro de movimentação
        if ($filtroMovimentacao === 'debito') {
            $query->porMovimentacao(PlanoContaMovimentacaoEnum::DEBITO);
        } elseif ($filtroMovimentacao === 'credito') {
            $query->porMovimentacao(PlanoContaMovimentacaoEnum::CREDITO);
        }

        // Aplicar filtro por nome
        if (!empty($filtroNome)) {
            $query->where('nome', 'LIKE', '%' . $filtroNome . '%');
        }

        // Ordenação tri-state
        $sortBy = $request->get('sort_by');
        $sortDirection = strtolower($request->get('sort_direction')) === 'desc' ? 'desc' : (strtolower($request->get('sort_direction')) === 'asc' ? 'asc' : null);
        $sortable = [
            'codigo' => ['ordem_pai', 'ordem_filho'],
            'nome' => 'nome',
            'movimentacao' => 'movimentacao',
            'dre' => 'dre_id',
            'tipo' => 'plano_conta_id',
        ];
        if ($sortBy && isset($sortable[$sortBy]) && $sortDirection) {
            $column = $sortable[$sortBy];
            if (is_array($column)) {
                foreach ($column as $col) { $query->orderBy($col, $sortDirection); }
            } else {
                $query->orderBy($column, $sortDirection);
            }
        } else {
            // Ordem padrão hierárquica quando sem sort
            $query->orderBy('ordem_pai')->orderBy('ordem_filho');
        }

        $planoContas = $query->paginate(15);

        return view('plano-conta.index', compact('planoContas', 'filtroMovimentacao', 'filtroNome', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'criar')) {
            abort(403, 'Você não tem permissão para criar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $movimentacoes = PlanoContaMovimentacaoEnum::cases();
        $dres = Dre::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $planoContasPai = PlanoConta::daEmpresa($empresaPrincipal->id)->raiz()->orderBy('ordem_pai')->get();

        return view('plano-conta.create', compact('movimentacoes', 'dres', 'planoContasPai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'criar')) {
            abort(403, 'Você não tem permissão para criar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'movimentacao' => 'required|integer|in:' . implode(',', array_column(PlanoContaMovimentacaoEnum::cases(), 'value')),
            'ordem_pai' => 'required|integer|min:1',
            'ordem_filho' => 'nullable|integer|min:1',
            'dre_id' => 'nullable|uuid|exists:dre,id,empresa_id,' . $empresaPrincipal->id,
            'plano_conta_id' => 'nullable|uuid|exists:plano_conta,id,empresa_id,' . $empresaPrincipal->id,
        ]);

        $planoConta = PlanoConta::create([
            'id' => Str::uuid(),
            'empresa_id' => $empresaPrincipal->id,
            'plano_conta_id' => $request->plano_conta_id,
            'dre_id' => $request->dre_id,
            'nome' => $request->nome,
            'movimentacao' => $request->movimentacao,
            'ordem_pai' => $request->ordem_pai,
            'ordem_filho' => $request->ordem_filho,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log
        AuditService::logCreate($planoConta, "Criou plano de conta: {$planoConta->nome}");

        return redirect()->route('plano-conta.index')->with('success', 'Plano de conta criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanoConta $planoConta)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $planoConta->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $planoConta->load(['dre', 'planoContaPai', 'planoContasFilhos']);

        return view('plano-conta.show', compact('planoConta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanoConta $planoConta)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'editar')) {
            abort(403, 'Você não tem permissão para editar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $planoConta->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $movimentacoes = PlanoContaMovimentacaoEnum::cases();
        $dres = Dre::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $planoContasPai = PlanoConta::daEmpresa($empresaPrincipal->id)->raiz()->orderBy('ordem_pai')->get();

        return view('plano-conta.edit', compact('planoConta', 'movimentacoes', 'dres', 'planoContasPai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlanoConta $planoConta)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'editar')) {
            abort(403, 'Você não tem permissão para editar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $planoConta->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'movimentacao' => 'required|integer|in:' . implode(',', array_column(PlanoContaMovimentacaoEnum::cases(), 'value')),
            'ordem_pai' => 'required|integer|min:1',
            'ordem_filho' => 'nullable|integer|min:1',
            'dre_id' => 'nullable|uuid|exists:dre,id,empresa_id,' . $empresaPrincipal->id,
            'plano_conta_id' => 'nullable|uuid|exists:plano_conta,id,empresa_id,' . $empresaPrincipal->id,
        ]);

        // Capturar valores antigos antes da atualização
        $oldValues = $planoConta->getAttributes();

        $planoConta->update([
            'plano_conta_id' => $request->plano_conta_id,
            'dre_id' => $request->dre_id,
            'nome' => $request->nome,
            'movimentacao' => $request->movimentacao,
            'ordem_pai' => $request->ordem_pai,
            'ordem_filho' => $request->ordem_filho,
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log apenas se houve mudanças
        if ($planoConta->wasChanged()) {
            AuditService::logUpdate($planoConta, $oldValues, "Atualizou plano de conta: {$planoConta->nome}");
        }

        return redirect()->route('plano-conta.index')->with('success', 'Plano de conta atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanoConta $planoConta)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('plano-conta', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar plano de contas.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $planoConta->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        // Verificar se tem filhos
        if ($planoConta->planoContasFilhos()->count() > 0) {
            return redirect()->back()->with('error', 'Não é possível excluir um plano de conta que possui subcontas.');
        }

        // Registrar no audit log antes da exclusão
        AuditService::logDelete($planoConta, "Excluiu plano de conta: {$planoConta->nome}");

        $planoConta->delete();

        return redirect()->route('plano-conta.index')->with('success', 'Plano de conta excluído com sucesso!');
    }
}
