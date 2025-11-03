<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoteStatus;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Auth;

class LoteStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->temPermissao('lote-status', 'listar')) {
            abort(403, 'Você não tem permissão para listar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $status = LoteStatus::where('empresa_id', $empresaAtual->id)
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        return view('lote-status.index', compact('status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->temPermissao('lote-status', 'criar')) {
            abort(403, 'Você não tem permissão para criar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        return view('lote-status.create', compact('empresaAtual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('lote-status', 'criar')) {
            abort(403, 'Você não tem permissão para criar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:1,2',
            'cor' => 'required|string|max:7',
        ]);

        $validated['empresa_id'] = $empresaAtual->id;

        LoteStatus::create($validated);

        return redirect()->route('lote-status.index')
            ->with('success', 'Status criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoteStatus $loteStatus)
    {
        if (!Auth::user()->temPermissao('lote-status', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $loteStatus->empresa_id !== $empresaAtual->id) {
            abort(403, 'Status não encontrado.');
        }

        return view('lote-status.show', compact('loteStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoteStatus $loteStatus)
    {
        if (!Auth::user()->temPermissao('lote-status', 'editar')) {
            abort(403, 'Você não tem permissão para editar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $loteStatus->empresa_id !== $empresaAtual->id) {
            abort(403, 'Status não encontrado.');
        }

        return view('lote-status.edit', compact('loteStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoteStatus $loteStatus)
    {
        if (!Auth::user()->temPermissao('lote-status', 'editar')) {
            abort(403, 'Você não tem permissão para editar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $loteStatus->empresa_id !== $empresaAtual->id) {
            abort(403, 'Status não encontrado.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:1,2',
            'cor' => 'required|string|max:7',
        ]);

        $loteStatus->update($validated);

        return redirect()->route('lote-status.index')
            ->with('success', 'Status atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoteStatus $loteStatus)
    {
        if (!Auth::user()->temPermissao('lote-status', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar status de lotes.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $loteStatus->empresa_id !== $empresaAtual->id) {
            abort(403, 'Status não encontrado.');
        }

        // Verificar se há lotes usando este status
        if ($loteStatus->lotes()->count() > 0) {
            return redirect()->route('lote-status.index')
                ->with('error', 'Não é possível deletar este status pois existem lotes utilizando-o.');
        }

        $loteStatus->delete();

        return redirect()->route('lote-status.index')
            ->with('success', 'Status deletado com sucesso.');
    }
}
