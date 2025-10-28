<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entidade;
use App\Models\EntidadeContato;
use App\Models\EntidadeEndereco;
use App\Enums\EntidadeTipoEnum;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;
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
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'listar')) {
            abort(403, 'Você não tem permissão para listar entidades.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $query = Entidade::daEmpresa($empresaPrincipal->id)
            ->porTipo($tipoEnum)
            ->with(['contatos', 'enderecos']);

        // Filtros
        $filtroNome = $request->get('nome');
        if ($filtroNome) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'like', "%{$filtroNome}%")
                  ->orWhere('nome_fantasia', 'like', "%{$filtroNome}%")
                  ->orWhere('razao_social', 'like', "%{$filtroNome}%");
            });
        }

        $filtroDocumento = $request->get('documento');
        if ($filtroDocumento) {
            $query->where('documento', 'like', "%{$filtroDocumento}%");
        }

        $filtroEmail = $request->get('email');
        if ($filtroEmail) {
            $query->where('email', 'like', "%{$filtroEmail}%");
        }

        $filtroStatus = $request->get('status', 'todos');
        if ($filtroStatus !== 'todos') {
            $query->where('status', $filtroStatus === 'ativos');
        }

        $entidades = $query->orderBy('nome', 'asc')->paginate(15);

        return view('entidades.index', compact('entidades', 'tipo', 'filtroNome', 'filtroDocumento', 'filtroEmail', 'filtroStatus'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function createCliente()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        $tipo = 'cliente';
        $tipoEnum = EntidadeTipoEnum::CLIENTE;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function createFornecedor()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        $tipo = 'fornecedor';
        $tipoEnum = EntidadeTipoEnum::FORNECEDOR;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function createFuncionario()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        $tipo = 'funcionario';
        $tipoEnum = EntidadeTipoEnum::FUNCIONARIO;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Show the form for creating a new transporter.
     */
    public function createTransportadora()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        $tipo = 'transportadora';
        $tipoEnum = EntidadeTipoEnum::TRANSPORTADORA;

        return view('entidades.create', compact('tipo', 'tipoEnum'));
    }

    /**
     * Store a newly created client.
     */
    public function storeCliente(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        return $this->storeByType($request, EntidadeTipoEnum::CLIENTE, 'cliente');
    }

    /**
     * Store a newly created supplier.
     */
    public function storeFornecedor(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        return $this->storeByType($request, EntidadeTipoEnum::FORNECEDOR, 'fornecedor');
    }

    /**
     * Store a newly created employee.
     */
    public function storeFuncionario(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

        return $this->storeByType($request, EntidadeTipoEnum::FUNCIONARIO, 'funcionario');
    }

    /**
     * Store a newly created transporter.
     */
    public function storeTransportadora(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar entidades.');
        }

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

        // Registrar no audit log
        AuditService::logCreate($entidade, "Criado {$tipo}: {$entidade->nome}");

        return redirect()->route($this->getRouteName($tipo, 'index'))
            ->with('success', ucfirst($tipo) . ' criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entidade $entidade)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar entidades.');
        }

        $entidade->load(['contatos', 'enderecos', 'empresa']);

        return view('entidades.show', compact('entidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entidade $entidade)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar entidades.');
        }

        $entidade->load(['contatos', 'enderecos']);
        $tipo = strtolower($entidade->tipo_relacionamento->name);

        return view('entidades.edit', compact('entidade', 'tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entidade $entidade)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar entidades.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone_comercial' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'tipo_pessoa' => 'required|in:1,2',
        ]);

        // Salvar valores antigos para audit log
        $oldValues = $entidade->getAttributes();

        // Preparar novos valores
        $newValues = [
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
        ];

        // Verificar se houve alterações nos dados principais
        $hasChanges = false;
        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;

            // Normalizar valores vazios para comparação
            $oldValueNormalized = $this->normalizeValue($oldValue);
            $newValueNormalized = $this->normalizeValue($newValue);

            if ($oldValueNormalized !== $newValueNormalized) {
                $hasChanges = true;
                break;
            }
        }

        // Atualizar entidade
        $entidade->update($newValues);

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

        // Registrar no audit log apenas se houve alterações
        $tipo = strtolower($entidade->tipo_relacionamento->name);
        if ($hasChanges) {
            AuditService::logUpdate($entidade, $oldValues, "Atualizado {$tipo}: {$entidade->nome}");
        }

        return redirect()->route($this->getRouteName($tipo, 'index'))
            ->with('success', ucfirst($tipo) . ' atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entidade $entidade)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar entidades.');
        }

        $tipo = strtolower($entidade->tipo_relacionamento->name);

        // Registrar no audit log antes de deletar
        AuditService::logDelete($entidade, "Excluído {$tipo}: {$entidade->nome}");

        $entidade->delete();

        return redirect()->route($this->getRouteName($tipo, 'index'))
            ->with('success', ucfirst($tipo) . ' excluído com sucesso!');
    }

    /**
     * Toggle status of the entity
     */
    public function toggleStatus(Entidade $entidade)
    {
        $oldStatus = $entidade->status;
        $entidade->update(['status' => !$entidade->status]);

        $status = $entidade->status ? 'ativado' : 'desativado';
        $tipo = strtolower($entidade->tipo_relacionamento->name);

        // Registrar no audit log
        AuditService::logUpdate($entidade, ['status' => $oldStatus], "Status alterado para {$status}: {$entidade->nome}");

        return redirect()->back()
            ->with('success', ucfirst($tipo) . " {$status} com sucesso!");
    }

    /**
     * Get correct route name for entity type
     */
    private function getRouteName(string $tipo, string $action): string
    {
        $pluralMap = [
            'cliente' => 'clientes',
            'fornecedor' => 'fornecedores',
            'funcionario' => 'funcionarios',
            'transportadora' => 'transportadoras',
        ];

        $plural = $pluralMap[$tipo] ?? $tipo . 's';
        return $plural . '.' . $action;
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

    /**
     * Normalizar valores para comparação (tratar valores vazios como null)
     */
    private function normalizeValue($value)
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }

        return $value;
    }
}
