<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Funcionario;
use App\Models\FuncionarioContato;
use App\Models\FuncionarioEndereco;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;
use Illuminate\Support\Str;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'listar')) {
            abort(403, 'Você não tem permissão para listar funcionários.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $query = Funcionario::daEmpresa($empresaPrincipal->id)
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

        // Ordenação (tri-state: desc -> asc -> sem ordenação)
        $sortBy = $request->get('sort_by');
        $sortDirection = strtolower($request->get('sort_direction')) === 'desc' ? 'desc' : (strtolower($request->get('sort_direction')) === 'asc' ? 'asc' : null);
        $sortable = [
            'nome' => 'nome',
            'documento' => 'documento',
            'email' => 'email',
            'status' => 'status',
            'criado_em' => 'created_at',
        ];
        if ($sortBy && isset($sortable[$sortBy]) && $sortDirection) {
            $query->orderBy($sortable[$sortBy], $sortDirection);
        }

        $funcionarios = $query->paginate(15);
        if ($sortBy && $sortDirection) {
            $funcionarios->appends([
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ]);
        }
        $funcionarios->appends([
            'nome' => $filtroNome,
            'documento' => $filtroDocumento,
            'email' => $filtroEmail,
            'status' => $filtroStatus,
        ]);

        $entidades = $funcionarios;
        return view('entidades.index', compact('entidades', 'filtroNome', 'filtroDocumento', 'filtroEmail', 'filtroStatus', 'sortBy', 'sortDirection'))->with('tipo', 'funcionario');
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar funcionários.');
        }

        return view('entidades.create')->with('tipo', 'funcionario');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar funcionários.');
        }

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

        // Criar funcionário
        $funcionario = Funcionario::create([
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
            'status' => true,
        ]);

        // Criar contatos se fornecidos
        if ($request->filled('contatos')) {
            foreach ($request->contatos as $contatoData) {
                if (!empty($contatoData['nome']) && !empty($contatoData['contato'])) {
                    FuncionarioContato::create([
                        'id' => Str::uuid()->toString(),
                        'funcionario_id' => $funcionario->id,
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
                    FuncionarioEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'funcionario_id' => $funcionario->id,
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
        AuditService::logCreate($funcionario, "Criado funcionário: {$funcionario->nome}");

        return redirect()->route('funcionarios.index')
            ->with('success', 'Funcionário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Funcionario $funcionario)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar funcionários.');
        }

        $funcionario->load(['contatos', 'enderecos', 'empresa']);
        $entidade = $funcionario;

        return view('entidades.show', compact('entidade'))->with('tipo', 'funcionario');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Funcionario $funcionario)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar funcionários.');
        }

        $funcionario->load(['contatos', 'enderecos']);
        $entidade = $funcionario;

        return view('entidades.edit', compact('entidade'))->with('tipo', 'funcionario');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Funcionario $funcionario)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar funcionários.');
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
        $oldValues = $funcionario->getAttributes();

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

        // Atualizar funcionário
        $funcionario->update($newValues);

        // Atualizar contatos
        $funcionario->contatos()->delete();
        if ($request->filled('contatos')) {
            foreach ($request->contatos as $contatoData) {
                if (!empty($contatoData['nome']) && !empty($contatoData['contato'])) {
                    FuncionarioContato::create([
                        'id' => Str::uuid()->toString(),
                        'funcionario_id' => $funcionario->id,
                        'nome' => $contatoData['nome'],
                        'contato' => $contatoData['contato'],
                        'cargo' => $contatoData['cargo'] ?? null,
                        'observacao' => $contatoData['observacao'] ?? null,
                    ]);
                }
            }
        }

        // Atualizar endereços
        $funcionario->enderecos()->delete();
        if ($request->filled('enderecos')) {
            foreach ($request->enderecos as $enderecoData) {
                if (!empty($enderecoData['logradouro'])) {
                    FuncionarioEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'funcionario_id' => $funcionario->id,
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
        if ($hasChanges) {
            AuditService::logUpdate($funcionario, $oldValues, "Atualizado funcionário: {$funcionario->nome}");
        }

        return redirect()->route('funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Funcionario $funcionario)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar funcionários.');
        }

        // Registrar no audit log antes de deletar
        AuditService::logDelete($funcionario, "Excluído funcionário: {$funcionario->nome}");

        $funcionario->delete();

        return redirect()->route('funcionarios.index')
            ->with('success', 'Funcionário excluído com sucesso!');
    }

    /**
     * Toggle status of the employee
     */
    public function toggleStatus(Funcionario $funcionario)
    {
        $oldStatus = $funcionario->status;
        $funcionario->update(['status' => !$funcionario->status]);

        $status = $funcionario->status ? 'ativado' : 'desativado';

        // Registrar no audit log
        AuditService::logUpdate($funcionario, ['status' => $oldStatus], "Status alterado para {$status}: {$funcionario->nome}");

        return redirect()->back()
            ->with('success', "Funcionário {$status} com sucesso!");
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
