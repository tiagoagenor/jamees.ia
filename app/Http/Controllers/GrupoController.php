<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Grupo;
use App\Models\Permissao;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grupo::daEmpresaPrincipal()->with('permissoes');

        // Filtro por nome
        $filtroNome = $request->get('nome');
        if ($filtroNome) {
            $query->where('nome', 'like', "%{$filtroNome}%");
        }

        // Filtro por tipo
        $filtroTipo = $request->get('tipo', 'todos');
        if ($filtroTipo !== 'todos') {
            $query->where('administrativo', $filtroTipo === 'administrativo');
        }

        // Filtro por status
        $filtroStatus = $request->get('status', 'todos');
        if ($filtroStatus !== 'todos') {
            $query->where('ativo', $filtroStatus === 'ativos');
        }

        $grupos = $query->paginate(15);

        return view('grupos.index', compact('grupos', 'filtroNome', 'filtroTipo', 'filtroStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissoes = Permissao::daEmpresaPrincipal()->ativas()->get()->groupBy('modulo');
        return view('grupos.create', compact('permissoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'permissoes' => 'array',
            'permissoes.*' => 'exists:permissoes,id',
        ]);

        // Buscar empresa principal do usuário logado
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->back()
                ->with('error', 'Usuário não possui empresa principal configurada.');
        }

        $grupo = Grupo::create([
            'empresa_id' => $empresaPrincipal->id,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'administrativo' => false,
            'ativo' => true,
        ]);

        if ($request->permissoes) {
            $grupo->permissoes()->sync(
                collect($request->permissoes)->mapWithKeys(function ($permissaoId) {
                    return [$permissaoId => ['concedida' => true]];
                })
            );
        }

        // Registrar no audit log
        AuditService::logCreate($grupo, "Criou grupo: {$grupo->nome}");

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        $grupo->load('permissoes');
        return view('grupos.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        // Verificar se o grupo pertence à empresa principal do usuário
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $grupo->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $permissoes = Permissao::daEmpresaPrincipal()->ativas()->get()->groupBy('modulo');
        $grupo->load('permissoes');
        return view('grupos.edit', compact('grupo', 'permissoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        // Verificar se o grupo pertence à empresa principal do usuário
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $grupo->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'permissoes' => 'array',
            'permissoes.*' => 'exists:permissoes,id',
        ]);

        // Capturar valores antigos antes da atualização
        $oldValues = $grupo->getAttributes();

        $grupo->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        if ($request->permissoes) {
            $grupo->permissoes()->sync(
                collect($request->permissoes)->mapWithKeys(function ($permissaoId) {
                    return [$permissaoId => ['concedida' => true]];
                })
            );
        } else {
            $grupo->permissoes()->detach();
        }

        // Registrar no audit log apenas se houve mudanças
        if ($grupo->wasChanged()) {
            AuditService::logUpdate($grupo, $oldValues, "Atualizou grupo: {$grupo->nome}");
        }

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        // Verificar se o grupo pertence à empresa principal do usuário
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $grupo->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        if ($grupo->administrativo) {
            return redirect()->back()
                ->with('error', 'Não é possível deletar o grupo Administrativo.');
        }

        // Registrar no audit log antes da exclusão
        AuditService::logDelete($grupo, "Excluiu grupo: {$grupo->nome}");

        $grupo->delete();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo deletado com sucesso!');
    }
}
