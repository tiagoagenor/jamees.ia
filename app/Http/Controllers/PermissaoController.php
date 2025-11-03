<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permissao;

class PermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissoes = Permissao::daEmpresaPrincipal()->ativas()->get()->groupBy('modulo');
        return view('permissoes.index', compact('permissoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissoes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'modulo' => 'required|string|max:255',
            'acao' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Permissao::create([
            'modulo' => $request->modulo,
            'acao' => $request->acao,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ativo' => true,
        ]);

        return redirect()->route('permissoes.index')
            ->with('success', 'Permissão criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permissao $permissao)
    {
        $permissao->load('grupos');
        return view('permissoes.show', compact('permissao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permissao $permissao)
    {
        return view('permissoes.edit', compact('permissao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permissao $permissao)
    {
        $request->validate([
            'modulo' => 'required|string|max:255',
            'acao' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $permissao->update([
            'modulo' => $request->modulo,
            'acao' => $request->acao,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('permissoes.index')
            ->with('success', 'Permissão atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permissao $permissao)
    {
        $permissao->delete();

        return redirect()->route('permissoes.index')
            ->with('success', 'Permissão deletada com sucesso!');
    }
}
