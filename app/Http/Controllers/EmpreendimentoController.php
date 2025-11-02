<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empreendimento;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpreendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->temPermissao('empreendimento', 'listar')) {
            abort(403, 'Você não tem permissão para listar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $empreendimentos = Empreendimento::where('empresa_id', $empresaAtual->id)
            ->with(['lotes.status']) // Eager loading para evitar N+1
            ->orderBy('nome')
            ->get();

        return view('empreendimento.index', compact('empreendimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->temPermissao('empreendimento', 'criar')) {
            abort(403, 'Você não tem permissão para criar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        return view('empreendimento.create', compact('empresaAtual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'criar')) {
            abort(403, 'Você não tem permissão para criar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'nullable|image|max:10240',
            'imagem_mapa' => 'nullable|image|max:10240',
            'zoom_default' => 'nullable|integer|min:50|max:300',
            'valor_m2' => 'nullable|numeric|min:0',
            'maximo_parcelas' => 'nullable|integer|min:1',
            'sinal' => 'required|in:1,2',
            'sinal_tipo' => 'nullable|in:1,2',
            'sinal_valor' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'quadra_numeracao_tipo' => 'required|in:1,2',
        ]);

        // Se zoom_default estiver vazio ou 0, usar default 180
        if (empty($validated['zoom_default']) || $validated['zoom_default'] == 0) {
            $validated['zoom_default'] = 180;
        }

        // Upload de imagem se houver
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('empreendimentos', 'public');
            $validated['imagem'] = $path;
        }

        // Upload de imagem do mapa se houver
        if ($request->hasFile('imagem_mapa')) {
            $path = $request->file('imagem_mapa')->store('empreendimentos', 'public');
            $validated['imagem_mapa'] = $path;
        }

        $validated['empresa_id'] = $empresaAtual->id;

        Empreendimento::create($validated);

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     * Redireciona para a tela de lotes.
     */
    public function show(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Carregar relacionamentos
        $empreendimento->load(['lotes', 'quadras', 'empresa']);

        return view('empreendimento.show', compact('empreendimento'));
    }

    /**
     * Display the mapa page for the specified empreendimento.
     */
    public function mapa(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        return view('empreendimento.mapa', compact('empreendimento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        return view('empreendimento.edit', compact('empreendimento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'nullable|image|max:10240',
            'imagem_mapa' => 'nullable|image|max:10240',
            'zoom_default' => 'nullable|integer|min:50|max:300',
            'valor_m2' => 'nullable|numeric|min:0',
            'maximo_parcelas' => 'nullable|integer|min:1',
            'sinal' => 'required|in:1,2',
            'sinal_tipo' => 'nullable|in:1,2',
            'sinal_valor' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'quadra_numeracao_tipo' => 'required|in:1,2',
        ]);

        // Se zoom_default estiver vazio ou 0, usar default 180
        if (empty($validated['zoom_default']) || $validated['zoom_default'] == 0) {
            $validated['zoom_default'] = 180;
        }

        // Upload de imagem se houver
        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga se existir
            if ($empreendimento->imagem) {
                Storage::disk('public')->delete($empreendimento->imagem);
            }
            $path = $request->file('imagem')->store('empreendimentos', 'public');
            $validated['imagem'] = $path;
        }

        // Upload de imagem do mapa se houver
        if ($request->hasFile('imagem_mapa')) {
            // Deletar imagem antiga se existir
            if ($empreendimento->imagem_mapa) {
                Storage::disk('public')->delete($empreendimento->imagem_mapa);
            }
            $path = $request->file('imagem_mapa')->store('empreendimentos', 'public');
            $validated['imagem_mapa'] = $path;
        }

        $empreendimento->update($validated);

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se há lotes vendidos vinculados ao empreendimento
        $lotesVendidos = $empreendimento->lotes()
            ->whereHas('status', function($query) {
                $query->where('tipo', 2); // Tipo 2 = Vendido
            })
            ->get();

        if ($lotesVendidos->count() > 0) {
            $nomesLotesVendidos = $lotesVendidos->pluck('nome')->toArray();
            $mensagem = 'Não é possível deletar este empreendimento pois existem lotes vendidos vinculados a ele.';

            if ($lotesVendidos->count() == 1) {
                $mensagem = "Não é possível deletar este empreendimento pois o lote \"{$nomesLotesVendidos[0]}\" está vendido.";
            } else {
                $mensagem = 'Não é possível deletar este empreendimento pois existem ' . $lotesVendidos->count() . ' lotes vendidos vinculados a ele: ' . implode(', ', array_slice($nomesLotesVendidos, 0, 5));
                if (count($nomesLotesVendidos) > 5) {
                    $mensagem .= ' e mais ' . (count($nomesLotesVendidos) - 5) . ' lote(s).';
                } else {
                    $mensagem .= '.';
                }
            }

            return redirect()->back()
                ->with('error', $mensagem);
        }

        // Deletar imagem se existir
        if ($empreendimento->imagem) {
            Storage::disk('public')->delete($empreendimento->imagem);
        }

        // Deletar imagem do mapa se existir
        if ($empreendimento->imagem_mapa) {
            Storage::disk('public')->delete($empreendimento->imagem_mapa);
        }

        $empreendimento->delete();

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento deletado com sucesso.');
    }
}
