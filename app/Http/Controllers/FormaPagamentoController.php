<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\ContaEmpresa;
use App\Enums\FormaPagamentoModalidadeEnum;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FormaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'listar')) {
            abort(403, 'Você não tem permissão para listar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Filtros
        $filtroStatus = $request->get('status', 'todos');
        $filtroNome = $request->get('nome', '');

        $query = FormaPagamento::daEmpresa($empresaPrincipal->id)
            ->with(['contaEmpresa.banco'])
            ->orderBy('nome');

        // Aplicar filtro de status
        if ($filtroStatus === 'disponiveis') {
            $query->disponiveis();
        } elseif ($filtroStatus === 'indisponiveis') {
            $query->where('disponivel', false);
        }
        // Se for 'todos', não aplica filtro

        // Aplicar filtro por nome
        if (!empty($filtroNome)) {
            $query->where('nome', 'LIKE', '%' . $filtroNome . '%');
        }

        $formasPagamento = $query->get();

        return view('forma-pagamento.index', compact('formasPagamento', 'filtroStatus', 'filtroNome'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'criar')) {
            abort(403, 'Você não tem permissão para criar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $modalidades = FormaPagamentoModalidadeEnum::cases();
        $contasEmpresa = ContaEmpresa::daEmpresa($empresaPrincipal->id)->ativas()->orderBy('nome')->get();

        return view('forma-pagamento.create', compact('modalidades', 'contasEmpresa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'criar')) {
            abort(403, 'Você não tem permissão para criar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'numero_parcelas' => 'required|integer|min:1',
            'intercalo_parcelas' => 'required|integer|min:1',
            'primeira_parcela' => 'required|integer|min:0',
            'modalidade' => 'required|integer',
            'taxa_banco' => 'required|numeric|min:0',
            'taxa_operadora' => 'required|numeric|min:0',
            'juros_multa' => 'required|numeric|min:0',
            'juros_mora' => 'required|numeric|min:0',
            'conta_empresa_id' => 'nullable|exists:conta_empresa,id',
            'disponivel' => 'boolean',
            'confirmacao_automatica' => 'boolean',
            'gerar_boleto' => 'boolean',
            'permite_deletar' => 'boolean',
        ]);

        FormaPagamento::create([
            'id' => Str::uuid(),
            'empresa_id' => $empresaPrincipal->id,
            'conta_empresa_id' => $request->conta_empresa_id,
            'nome' => $request->nome,
            'numero_parcelas' => $request->numero_parcelas,
            'intercalo_parcelas' => $request->intercalo_parcelas,
            'primeira_parcela' => $request->primeira_parcela,
            'modalidade' => $request->modalidade,
            'taxa_banco' => $request->taxa_banco,
            'taxa_operadora' => $request->taxa_operadora,
            'juros_multa' => $request->juros_multa,
            'juros_mora' => $request->juros_mora,
            'disponivel' => $request->boolean('disponivel'),
            'confirmacao_automatica' => $request->boolean('confirmacao_automatica'),
            'gerar_boleto' => $request->boolean('gerar_boleto'),
            'permite_deletar' => $request->boolean('permite_deletar'),
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        return redirect()->route('forma-pagamento.index')->with('success', 'Forma de pagamento criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FormaPagamento $formaPagamento)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $formaPagamento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $formaPagamento->load(['contaEmpresa.banco']);

        return view('forma-pagamento.show', compact('formaPagamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormaPagamento $formaPagamento)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'editar')) {
            abort(403, 'Você não tem permissão para editar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $formaPagamento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $modalidades = FormaPagamentoModalidadeEnum::cases();
        $contasEmpresa = ContaEmpresa::daEmpresa($empresaPrincipal->id)->ativas()->orderBy('nome')->get();

        return view('forma-pagamento.edit', compact('formaPagamento', 'modalidades', 'contasEmpresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormaPagamento $formaPagamento)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'editar')) {
            abort(403, 'Você não tem permissão para editar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $formaPagamento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'numero_parcelas' => 'required|integer|min:1',
            'intercalo_parcelas' => 'required|integer|min:1',
            'primeira_parcela' => 'required|integer|min:0',
            'modalidade' => 'required|integer',
            'taxa_banco' => 'required|numeric|min:0',
            'taxa_operadora' => 'required|numeric|min:0',
            'juros_multa' => 'required|numeric|min:0',
            'juros_mora' => 'required|numeric|min:0',
            'conta_empresa_id' => 'nullable|exists:conta_empresa,id',
            'disponivel' => 'boolean',
            'confirmacao_automatica' => 'boolean',
            'gerar_boleto' => 'boolean',
            'permite_deletar' => 'boolean',
        ]);

        $formaPagamento->update([
            'conta_empresa_id' => $request->conta_empresa_id,
            'nome' => $request->nome,
            'numero_parcelas' => $request->numero_parcelas,
            'intercalo_parcelas' => $request->intercalo_parcelas,
            'primeira_parcela' => $request->primeira_parcela,
            'modalidade' => $request->modalidade,
            'taxa_banco' => $request->taxa_banco,
            'taxa_operadora' => $request->taxa_operadora,
            'juros_multa' => $request->juros_multa,
            'juros_mora' => $request->juros_mora,
            'disponivel' => $request->boolean('disponivel'),
            'confirmacao_automatica' => $request->boolean('confirmacao_automatica'),
            'gerar_boleto' => $request->boolean('gerar_boleto'),
            'permite_deletar' => $request->boolean('permite_deletar'),
            'atualizado_em' => now(),
        ]);

        return redirect()->route('forma-pagamento.index')->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormaPagamento $formaPagamento)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $formaPagamento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        if (!$formaPagamento->permite_deletar) {
            return redirect()->back()->with('error', 'Esta forma de pagamento não pode ser excluída.');
        }

        $formaPagamento->delete();

        return redirect()->route('forma-pagamento.index')->with('success', 'Forma de pagamento excluída com sucesso!');
    }

    /**
     * Toggle disponibilidade da forma de pagamento
     */
    public function toggleDisponibilidade(FormaPagamento $formaPagamento)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('formas-pagamento', 'editar')) {
            abort(403, 'Você não tem permissão para editar formas de pagamento.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $formaPagamento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $formaPagamento->update([
            'disponivel' => !$formaPagamento->disponivel,
            'atualizado_em' => now(),
        ]);

        $status = $formaPagamento->disponivel ? 'disponível' : 'indisponível';
        return redirect()->back()->with('success', "Forma de pagamento marcada como {$status}!");
    }
}
