<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entidade;
use App\Models\EntidadeContato;
use App\Models\EntidadeEndereco;
use App\Enums\EntidadeTipoEnum;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Str;

class EntidadeController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function clientes(Request $request)
    {
        return $this->indexByType($request, EntidadeTipoEnum::CLIENTE, 'cliente');
    }

    /**
     * Display a listing of suppliers.
     */
    public function fornecedores(Request $request)
    {
        return $this->indexByType($request, EntidadeTipoEnum::FORNECEDOR, 'fornecedor');
    }

    /**
     * Display a listing of employees.
     */
    public function funcionarios(Request $request)
    {
        return $this->indexByType($request, EntidadeTipoEnum::FUNCIONARIO, 'funcionario');
    }

    /**
     * Display a listing of transporters.
     */
    public function transportadoras(Request $request)
    {
        return $this->indexByType($request, EntidadeTipoEnum::TRANSPORTADORA, 'transportadora');
    }

    /**
     * Common method to display entities by type.
     */
    private function indexByType(Request $request, EntidadeTipoEnum $tipoEnum, string $tipo)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $query = Entidade::daEmpresa($empresaPrincipal->id)
            ->porTipo($tipoEnum)
            ->with(['contatos', 'enderecos']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('nome_fantasia', 'like', "%{$search}%")
                  ->orWhere('razao_social', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status') == 'ativo');
        }

        $entidades = $query->orderBy('nome', 'asc')->paginate(15);

        return view('entidades.index', compact('entidades', 'tipo'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function createCliente()
    {
        $tipo = 'cliente';
        $tipoEnum = EntidadeTipoEnum::CLIENTE;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function createFornecedor()
    {
        $tipo = 'fornecedor';
        $tipoEnum = EntidadeTipoEnum::FORNECEDOR;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function createFuncionario()
    {
        $tipo = 'funcionario';
        $tipoEnum = EntidadeTipoEnum::FUNCIONARIO;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new transporter.
     */
    public function createTransportadora()
    {
        $tipo = 'transportadora';
        $tipoEnum = EntidadeTipoEnum::TRANSPORTADORA;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Store a newly created client.
     */
    public function storeCliente(Request $request)
    {
        return $this->storeByType($request, EntidadeTipoEnum::CLIENTE, 'cliente');
    }

    /**
     * Store a newly created supplier.
     */
    public function storeFornecedor(Request $request)
    {
        return $this->storeByType($request, EntidadeTipoEnum::FORNECEDOR, 'fornecedor');
    }

    /**
     * Store a newly created employee.
     */
    public function storeFuncionario(Request $request)
    {
        return $this->storeByType($request, EntidadeTipoEnum::FUNCIONARIO, 'funcionario');
    }

    /**
     * Store a newly created transporter.
     */
    public function storeTransportadora(Request $request)
    {
        return $this->storeByType($request, EntidadeTipoEnum::TRANSPORTADORA, 'transportadora');
    }

    /**
     * Common method to store entities by type.
     */
    private function storeByType(Request $request, EntidadeTipoEnum $tipoEnum, string $tipo)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone_comercial' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'tipo_pessoa' => 'required|in:1,2',
            'contatos' => 'nullable|array',
            'contatos.*.nome' => 'required_with:contatos|string|max:255',
            'contatos.*.contato' => 'required_with:contatos|string|max:255',
            'contatos.*.cargo' => 'nullable|string|max:255',
            'contatos.*.observacao' => 'nullable|string',
            'enderecos' => 'nullable|array',
            'enderecos.*.cep' => 'nullable|string|max:10',
            'enderecos.*.logradouro' => 'required_with:enderecos|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:20',
            'enderecos.*.complemento' => 'nullable|string|max:255',
            'enderecos.*.bairro' => 'required_with:enderecos|string|max:255',
            'enderecos.*.cidade' => 'required_with:enderecos|string|max:255',
            'enderecos.*.estado' => 'required_with:enderecos|string|max:2',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'documento.required' => 'O documento é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório.',
        ]);

        // Criar entidade
        $entidade = Entidade::create([
            'id' => Str::uuid()->toString(),
            'empresa_id' => $empresaPrincipal->id,
            'nome' => $request->nome,
            'nome_fantasia' => $request->nome_fantasia,
            'razao_social' => $request->razao_social,
            'documento' => preg_replace('/\D/', '', $request->documento),
            'email' => $request->email,
            'telefone_comercial' => preg_replace('/\D/', '', $request->telefone_comercial),
            'celular' => preg_replace('/\D/', '', $request->celular),
            'site' => $request->site,
            'observacao' => $request->observacao,
            'tipo_pessoa' => $request->tipo_pessoa,
            'tipo_relacionamento' => $tipoEnum,
            'status' => true,
        ]);

        // Criar contatos se fornecidos
        if ($request->filled('contatos')) {
            foreach ($request->contatos as $contatoData) {
                if (!empty($contatoData['nome']) && !empty($contatoData['contato'])) {
                    EntidadeContato::create([
                        'id' => Str::uuid()->toString(),
                        'entidade_id' => $entidade->id,
                        'nome' => $contatoData['nome'],
                        'contato' => $contatoData['contato'],
                        'cargo' => $contatoData['cargo'] ?? null,
                        'observacao' => $contatoData['observacao'] ?? null,
                    ]);
                }
            }
        }

        // Criar endereços se fornecidos
        if ($request->filled('enderecos')) {
            foreach ($request->enderecos as $enderecoData) {
                if (!empty($enderecoData['logradouro'])) {
                    EntidadeEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'entidade_id' => $entidade->id,
                        'cep' => preg_replace('/\D/', '', $enderecoData['cep']),
                        'logradouro' => $enderecoData['logradouro'],
                        'numero' => $enderecoData['numero'],
                        'bairro' => $enderecoData['bairro'],
                        'cidade' => $enderecoData['cidade'],
                        'estado' => $enderecoData['estado'],
                        'complemento' => $enderecoData['complemento'],
                    ]);
                }
            }
        }

        return redirect()->route($tipo . 's.index')
            ->with('success', ucfirst($tipo) . ' criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entidade $entidade)
    {
        $entidade->load(['contatos', 'enderecos', 'empresa']);

        return view('entidades.show', compact('entidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entidade $entidade)
    {
        $entidade->load(['contatos', 'enderecos']);
        $tipo = strtolower($entidade->tipo_relacionamento->name);

        return view('entidades.edit', compact('entidade', 'tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entidade $entidade)
    {

        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone_comercial' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'tipo_pessoa' => 'required|in:1,2',
        ]);

        // Atualizar entidade
        $entidade->update([
            'nome' => $request->nome,
            'nome_fantasia' => $request->nome_fantasia,
            'razao_social' => $request->razao_social,
            'documento' => preg_replace('/\D/', '', $request->documento),
            'email' => $request->email,
            'telefone_comercial' => preg_replace('/\D/', '', $request->telefone_comercial),
            'celular' => preg_replace('/\D/', '', $request->celular),
            'site' => $request->site,
            'observacao' => $request->observacao,
            'tipo_pessoa' => $request->tipo_pessoa,
        ]);

        // Atualizar contatos
        $entidade->contatos()->delete();
        if ($request->filled('contatos')) {
            foreach ($request->contatos as $contatoData) {
                if (!empty($contatoData['nome']) && !empty($contatoData['contato'])) {
                    EntidadeContato::create([
                        'id' => Str::uuid()->toString(),
                        'entidade_id' => $entidade->id,
                        'nome' => $contatoData['nome'],
                        'contato' => $contatoData['contato'],
                        'cargo' => $contatoData['cargo'] ?? null,
                        'observacao' => $contatoData['observacao'] ?? null,
                    ]);
                }
            }
        }

        // Atualizar endereços
        $entidade->enderecos()->delete();
        if ($request->filled('enderecos')) {
            foreach ($request->enderecos as $enderecoData) {
                if (!empty($enderecoData['logradouro'])) {
                    EntidadeEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'entidade_id' => $entidade->id,
                        'cep' => preg_replace('/\D/', '', $enderecoData['cep']),
                        'logradouro' => $enderecoData['logradouro'],
                        'numero' => $enderecoData['numero'],
                        'bairro' => $enderecoData['bairro'],
                        'cidade' => $enderecoData['cidade'],
                        'estado' => $enderecoData['estado'],
                        'complemento' => $enderecoData['complemento'],
                    ]);
                }
            }
        }

        $tipo = strtolower($entidade->tipo_relacionamento->name);
        return redirect()->route($tipo . 's.index')
            ->with('success', ucfirst($tipo) . ' atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entidade $entidade)
    {
        $tipo = strtolower($entidade->tipo_relacionamento->name);
        $entidade->delete();

        return redirect()->route($tipo . 's.index')
            ->with('success', ucfirst($tipo) . ' excluído com sucesso!');
    }

    /**
     * Toggle status of the entity
     */
    public function toggleStatus(Entidade $entidade)
    {
        $entidade->update(['status' => !$entidade->status]);

        $status = $entidade->status ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', ucfirst(strtolower($entidade->tipo_relacionamento->name)) . " {$status} com sucesso!");
    }

    /**
     * Get EntidadeTipoEnum from string
     */
    private function getTipoEnum(string $tipo): EntidadeTipoEnum
    {
        return match($tipo) {
            'cliente' => EntidadeTipoEnum::CLIENTE,
            'fornecedor' => EntidadeTipoEnum::FORNECEDOR,
            'funcionario' => EntidadeTipoEnum::FUNCIONARIO,
            'transportadora' => EntidadeTipoEnum::TRANSPORTADORA,
            default => EntidadeTipoEnum::CLIENTE,
        };
    }

    /**
     * Store a newly created contato for the entidade.
     */
    public function storeContato(Request $request, Entidade $entidade)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'contato' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'observacao' => 'nullable|string',
        ]);

        $entidade->contatos()->create($request->all());

        return redirect()->back()->with('success', 'Contato adicionado com sucesso!');
    }

    /**
     * Update the specified contato.
     */
    public function updateContato(Request $request, Entidade $entidade, EntidadeContato $contato)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'contato' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'observacao' => 'nullable|string',
        ]);

        $contato->update($request->all());

        return redirect()->back()->with('success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove the specified contato.
     */
    public function destroyContato(Entidade $entidade, EntidadeContato $contato)
    {
        $contato->delete();

        return redirect()->back()->with('success', 'Contato excluído com sucesso!');
    }

    /**
     * Store a newly created endereco for the entidade.
     */
    public function storeEndereco(Request $request, Entidade $entidade)
    {
        $request->validate([
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
        ]);

        $entidade->enderecos()->create($request->all());

        return redirect()->back()->with('success', 'Endereço adicionado com sucesso!');
    }

    /**
     * Update the specified endereco.
     */
    public function updateEndereco(Request $request, Entidade $entidade, EntidadeEndereco $endereco)
    {
        $request->validate([
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
        ]);

        $endereco->update($request->all());

        return redirect()->back()->with('success', 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove the specified endereco.
     */
    public function destroyEndereco(Entidade $entidade, EntidadeEndereco $endereco)
    {
        $endereco->delete();

        return redirect()->back()->with('success', 'Endereço excluído com sucesso!');
    }
}
