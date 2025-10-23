<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\EmpresaContato;
use App\Models\EmpresaEndereco;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Empresa::with('whitelabel', 'contatos', 'enderecos');

        // Filtros
        if ($request->filled('nome_fantasia')) {
            $query->where('nome_fantasia', 'like', '%' . $request->nome_fantasia . '%');
        }

        if ($request->filled('razao_social')) {
            $query->where('razao_social', 'like', '%' . $request->razao_social . '%');
        }

        if ($request->filled('cnpj')) {
            $query->where('cnpj', 'like', '%' . $request->cnpj . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('whitelabel_id')) {
            $query->where('whitelabel_id', $request->whitelabel_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'nome_fantasia');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['nome_fantasia', 'razao_social', 'cnpj', 'status', 'criado_em'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $empresas = $query->paginate(15)->withQueryString();
        $whitelabels = Whitelabel::all();

        return view('empresas.index', compact('empresas', 'whitelabels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $whitelabels = Whitelabel::all();
        return view('empresas.create', compact('whitelabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'whitelabel_id' => 'required|exists:whitelabel,id',
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'tipo' => 'required|string|in:PJ,PF',
            'status' => 'nullable|integer',
            'principal' => 'nullable|integer',
            'nome_referencia' => 'nullable|string|max:255',
            'inscricao_estadual' => 'nullable|string|max:255',
            'inscricao_estadual_isenta' => 'nullable|string|max:255',
            'inscricao_municipal' => 'nullable|string|max:255',
            'cnae' => 'nullable|string|max:255',
            'regime_tributario' => 'nullable|string|max:255',
            'regime_especial' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            // Contatos
            'contatos' => 'nullable|array',
            'contatos.*.tipo' => 'required_with:contatos|string|in:telefone,email,site,whatsapp',
            'contatos.*.dado' => 'required_with:contatos|string|max:255',
            // Endereços
            'enderecos' => 'nullable|array',
            'enderecos.*.cep' => 'nullable|string|max:255',
            'enderecos.*.logradouro' => 'nullable|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:255',
            'enderecos.*.complemento' => 'nullable|string|max:255',
            'enderecos.*.bairro' => 'nullable|string|max:255',
            'enderecos.*.uf' => 'nullable|string|max:2',
        ]);

        try {
            // Criar empresa
            $empresa = Empresa::create([
                'id' => Str::uuid()->toString(),
                'whitelabel_id' => $request->whitelabel_id,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
                'cnpj' => $request->cnpj,
                'tipo' => $request->tipo,
                'status' => $request->status ?? 1,
                'principal' => $request->principal ?? 0,
                'nome_referencia' => $request->nome_referencia,
                'inscricao_estadual' => $request->inscricao_estadual,
                'inscricao_estadual_isenta' => $request->inscricao_estadual_isenta,
                'inscricao_municipal' => $request->inscricao_municipal,
                'cnae' => $request->cnae,
                'regime_tributario' => $request->regime_tributario,
                'regime_especial' => $request->regime_especial,
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Adicionar contatos
            if ($request->contatos) {
                foreach ($request->contatos as $contato) {
                    if ($contato['dado']) {
                        EmpresaContato::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'tipo' => $contato['tipo'],
                            'dado' => $contato['dado'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            // Adicionar endereços
            if ($request->enderecos) {
                foreach ($request->enderecos as $endereco) {
                    if ($endereco['cep'] || $endereco['logradouro'] || $endereco['bairro']) {
                        EmpresaEndereco::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'cep' => $endereco['cep'],
                            'logradouro' => $endereco['logradouro'],
                            'numero' => $endereco['numero'],
                            'complemento' => $endereco['complemento'],
                            'bairro' => $endereco['bairro'],
                            'uf' => $endereco['uf'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa criada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar empresa: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        $empresa->load('whitelabel', 'contatos', 'enderecos');
        return view('empresas.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        $whitelabels = Whitelabel::all();
        $empresa->load('contatos', 'enderecos');
        return view('empresas.edit', compact('empresa', 'whitelabels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'whitelabel_id' => 'required|exists:whitelabel,id',
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'tipo' => 'required|string|in:PJ,PF',
            'status' => 'nullable|integer',
            'principal' => 'nullable|integer',
            'nome_referencia' => 'nullable|string|max:255',
            'inscricao_estadual' => 'nullable|string|max:255',
            'inscricao_estadual_isenta' => 'nullable|string|max:255',
            'inscricao_municipal' => 'nullable|string|max:255',
            'cnae' => 'nullable|string|max:255',
            'regime_tributario' => 'nullable|string|max:255',
            'regime_especial' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            // Contatos
            'contatos' => 'nullable|array',
            'contatos.*.tipo' => 'required_with:contatos|string|in:telefone,email,site,whatsapp',
            'contatos.*.dado' => 'required_with:contatos|string|max:255',
            // Endereços
            'enderecos' => 'nullable|array',
            'enderecos.*.cep' => 'nullable|string|max:255',
            'enderecos.*.logradouro' => 'nullable|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:255',
            'enderecos.*.complemento' => 'nullable|string|max:255',
            'enderecos.*.bairro' => 'nullable|string|max:255',
            'enderecos.*.uf' => 'nullable|string|max:2',
        ]);

        try {
            $empresa->update([
                'whitelabel_id' => $request->whitelabel_id,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
                'cnpj' => $request->cnpj,
                'tipo' => $request->tipo,
                'status' => $request->status ?? $empresa->status,
                'principal' => $request->principal ?? $empresa->principal,
                'nome_referencia' => $request->nome_referencia,
                'inscricao_estadual' => $request->inscricao_estadual,
                'inscricao_estadual_isenta' => $request->inscricao_estadual_isenta,
                'inscricao_municipal' => $request->inscricao_municipal,
                'cnae' => $request->cnae,
                'regime_tributario' => $request->regime_tributario,
                'regime_especial' => $request->regime_especial,
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'atualizado_em' => now(),
            ]);

            // Atualizar contatos
            $empresa->contatos()->delete();
            if ($request->contatos) {
                foreach ($request->contatos as $contato) {
                    if ($contato['dado']) {
                        EmpresaContato::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'tipo' => $contato['tipo'],
                            'dado' => $contato['dado'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            // Atualizar endereços
            $empresa->enderecos()->delete();
            if ($request->enderecos) {
                foreach ($request->enderecos as $endereco) {
                    if ($endereco['cep'] || $endereco['logradouro'] || $endereco['bairro']) {
                        EmpresaEndereco::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'cep' => $endereco['cep'],
                            'logradouro' => $endereco['logradouro'],
                            'numero' => $endereco['numero'],
                            'complemento' => $endereco['complemento'],
                            'bairro' => $endereco['bairro'],
                            'uf' => $endereco['uf'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa atualizada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar empresa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        try {
            $empresa->delete();
            return redirect()->route('empresas.index')
                ->with('success', 'Empresa excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir empresa: ' . $e->getMessage());
        }
    }
}
