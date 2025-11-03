<?php

namespace App\Http\Controllers;

use App\Models\Dre;
use App\Models\Empresa;
use App\Enums\DreTipoEnum;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $dres = Dre::daEmpresa($empresaPrincipal->id)
            ->ativos()
            ->raiz()
            ->with('children')
            ->orderBy('nome')
            ->get();

        return view('dre.index', compact('dres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $tipos = DreTipoEnum::cases();
        $dresPai = Dre::daEmpresa($empresaPrincipal->id)
            ->ativos()
            ->orderBy('nome')
            ->get();

        return view('dre.create', compact('tipos', 'dresPai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|integer|in:1,2,3',
            'dre_id' => 'nullable|uuid|exists:dre,id',
            'status' => 'boolean',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'Tipo inválido.',
            'dre_id.exists' => 'DRE pai não encontrada.',
        ]);

        Dre::create([
            'empresa_id' => $empresaPrincipal->id,
            'dre_id' => $request->dre_id,
            'nome' => $request->nome,
            'tipo' => DreTipoEnum::from($request->tipo),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('dre.index')
            ->with('success', 'DRE criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dre $dre)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $dre->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $dre->load(['children', 'parent']);

        return view('dre.show', compact('dre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dre $dre)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $dre->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $tipos = DreTipoEnum::cases();
        $dresPai = Dre::daEmpresa($empresaPrincipal->id)
            ->ativos()
            ->where('id', '!=', $dre->id)
            ->orderBy('nome')
            ->get();

        return view('dre.edit', compact('dre', 'tipos', 'dresPai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dre $dre)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $dre->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|integer|in:1,2,3',
            'dre_id' => 'nullable|uuid|exists:dre,id',
            'status' => 'boolean',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'Tipo inválido.',
            'dre_id.exists' => 'DRE pai não encontrada.',
        ]);

        $dre->update([
            'dre_id' => $request->dre_id,
            'nome' => $request->nome,
            'tipo' => DreTipoEnum::from($request->tipo),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('dre.index')
            ->with('success', 'DRE atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dre $dre)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $dre->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        // Verificar se tem filhos
        if ($dre->hasChildren()) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir uma DRE que possui subitens.');
        }

        $dre->delete();

        return redirect()->route('dre.index')
            ->with('success', 'DRE excluída com sucesso!');
    }

    /**
     * Toggle status of the DRE
     */
    public function toggleStatus(Dre $dre)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal || $dre->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Acesso negado.');
        }

        $dre->update(['status' => !$dre->status]);

        $status = $dre->status ? 'ativada' : 'desativada';

        return redirect()->back()
            ->with('success', "DRE {$status} com sucesso!");
    }
}
