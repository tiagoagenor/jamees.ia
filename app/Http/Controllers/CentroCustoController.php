<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\AuditService;

class CentroCustoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'listar')) {
            abort(403, 'Você não tem permissão para listar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Filtros
        $filtroStatus = $request->get('status', 'todos');
        $filtroNome = $request->get('nome', '');

        $query = CentroCusto::daEmpresa($empresaPrincipal->id);

        // Aplicar filtro de status
        if ($filtroStatus === 'ativos') {
            $query->ativos();
        } elseif ($filtroStatus === 'inativos') {
            $query->inativos();
        }

        // Aplicar filtro por nome
        if (!empty($filtroNome)) {
            $query->where('nome', 'LIKE', '%' . $filtroNome . '%');
        }

        // Ordenação tri-state
        $sortBy = $request->get('sort_by');
        $sortDirection = strtolower($request->get('sort_direction')) === 'desc' ? 'desc' : (strtolower($request->get('sort_direction')) === 'asc' ? 'asc' : null);
        $sortable = [
            'nome' => 'nome',
            'status' => 'status',
            'criado_em' => 'criado_em',
        ];
        if ($sortBy && isset($sortable[$sortBy]) && $sortDirection) {
            $query->orderBy($sortable[$sortBy], $sortDirection);
        }

        $centroCustos = $query->paginate(15);

        return view('centro-custo.index', compact('centroCustos', 'filtroStatus', 'filtroNome', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'criar')) {
            abort(403, 'Você não tem permissão para criar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        return view('centro-custo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'criar')) {
            abort(403, 'Você não tem permissão para criar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        $centroCusto = CentroCusto::create([
            'id' => Str::uuid(),
            'empresa_id' => $empresaPrincipal->id,
            'nome' => $request->nome,
            'status' => $request->status,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log
        AuditService::logCreate($centroCusto, "Criou centro de custo: {$centroCusto->nome}");

        return redirect()->route('centro-custo.index')->with('success', 'Centro de custo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroCusto $centroCusto)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $centroCusto->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        return view('centro-custo.show', compact('centroCusto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroCusto $centroCusto)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'editar')) {
            abort(403, 'Você não tem permissão para editar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $centroCusto->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        return view('centro-custo.edit', compact('centroCusto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroCusto $centroCusto)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'editar')) {
            abort(403, 'Você não tem permissão para editar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $centroCusto->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        // Capturar valores antigos antes da atualização
        $oldValues = $centroCusto->getAttributes();

        $centroCusto->update([
            'nome' => $request->nome,
            'status' => $request->status,
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log apenas se houve mudanças
        if ($centroCusto->wasChanged()) {
            AuditService::logUpdate($centroCusto, $oldValues, "Atualizou centro de custo: {$centroCusto->nome}");
        }

        return redirect()->route('centro-custo.index')->with('success', 'Centro de custo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroCusto $centroCusto)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $centroCusto->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        // Registrar no audit log antes da exclusão
        AuditService::logDelete($centroCusto, "Excluiu centro de custo: {$centroCusto->nome}");

        $centroCusto->delete();

        return redirect()->route('centro-custo.index')->with('success', 'Centro de custo excluído com sucesso!');
    }

    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(CentroCusto $centroCusto)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('central-custo', 'editar')) {
            abort(403, 'Você não tem permissão para alterar status de centros de custo.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $centroCusto->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        // Capturar valores antigos antes da atualização
        $oldValues = $centroCusto->getAttributes();

        $centroCusto->update([
            'status' => $centroCusto->status === 1 ? 0 : 1,
            'atualizado_em' => now(),
        ]);

        $status = $centroCusto->status === 1 ? 'ativado' : 'inativado';

        // Registrar no audit log
        AuditService::logUpdate($centroCusto, $oldValues, "Alterou status do centro de custo: {$centroCusto->nome} - {$status}");

        return redirect()->back()->with('success', "Centro de custo {$status} com sucesso!");
    }
}
