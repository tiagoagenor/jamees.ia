<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fornecedor;
use App\Models\FornecedorContato;
use App\Models\FornecedorEndereco;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;
use Illuminate\Support\Str;

class FornecedorController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'listar')) {
            abort(403, 'Você não tem permissão para listar fornecedores.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $query = Fornecedor::daEmpresa($empresaPrincipal->id)
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

        $fornecedores = $query->paginate(15);
        if ($sortBy && $sortDirection) {
            $fornecedores->appends([
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ]);
        }
        $fornecedores->appends([
            'nome' => $filtroNome,
            'documento' => $filtroDocumento,
            'email' => $filtroEmail,
            'status' => $filtroStatus,
        ]);

        $entidades = $fornecedores;
        return view('entidades.index', compact('entidades', 'filtroNome', 'filtroDocumento', 'filtroEmail', 'filtroStatus', 'sortBy', 'sortDirection'))->with('tipo', 'fornecedor');
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar fornecedores.');
        }

        return view('entidades.create')->with('tipo', 'fornecedor');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'criar')) {
            abort(403, 'Você não tem permissão para criar fornecedores.');
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

        // Criar fornecedor
        $fornecedor = Fornecedor::create([
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
                    FornecedorContato::create([
                        'id' => Str::uuid()->toString(),
                        'fornecedor_id' => $fornecedor->id,
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
                    FornecedorEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'fornecedor_id' => $fornecedor->id,
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
        AuditService::logCreate($fornecedor, "Criado fornecedor: {$fornecedor->nome}");

        return redirect()->route('fornecedores.index')
            ->with('success', 'Fornecedor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fornecedor $fornecedor)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar fornecedores.');
        }

        $fornecedor->load(['contatos', 'enderecos', 'empresa']);
        $entidade = $fornecedor;

        return view('entidades.show', compact('entidade'))->with('tipo', 'fornecedor');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fornecedor $fornecedor)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar fornecedores.');
        }

        $fornecedor->load(['contatos', 'enderecos']);
        $entidade = $fornecedor;

        return view('entidades.edit', compact('entidade'))->with('tipo', 'fornecedor');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'editar')) {
            abort(403, 'Você não tem permissão para editar fornecedores.');
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
        $oldValues = $fornecedor->getAttributes();

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

        // Atualizar fornecedor
        $fornecedor->update($newValues);

        // Atualizar contatos
        $fornecedor->contatos()->delete();
        if ($request->filled('contatos')) {
            foreach ($request->contatos as $contatoData) {
                if (!empty($contatoData['nome']) && !empty($contatoData['contato'])) {
                    FornecedorContato::create([
                        'id' => Str::uuid()->toString(),
                        'fornecedor_id' => $fornecedor->id,
                        'nome' => $contatoData['nome'],
                        'contato' => $contatoData['contato'],
                        'cargo' => $contatoData['cargo'] ?? null,
                        'observacao' => $contatoData['observacao'] ?? null,
                    ]);
                }
            }
        }

        // Atualizar endereços
        $fornecedor->enderecos()->delete();
        if ($request->filled('enderecos')) {
            foreach ($request->enderecos as $enderecoData) {
                if (!empty($enderecoData['logradouro'])) {
                    FornecedorEndereco::create([
                        'id' => Str::uuid()->toString(),
                        'fornecedor_id' => $fornecedor->id,
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
            AuditService::logUpdate($fornecedor, $oldValues, "Atualizado fornecedor: {$fornecedor->nome}");
        }

        return redirect()->route('fornecedores.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('entidades', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar fornecedores.');
        }

        // Registrar no audit log antes de deletar
        AuditService::logDelete($fornecedor, "Excluído fornecedor: {$fornecedor->nome}");

        $fornecedor->delete();

        return redirect()->route('fornecedores.index')
            ->with('success', 'Fornecedor excluído com sucesso!');
    }

    /**
     * Toggle status of the supplier
     */
    public function toggleStatus(Fornecedor $fornecedor)
    {
        $oldStatus = $fornecedor->status;
        $fornecedor->update(['status' => !$fornecedor->status]);

        $status = $fornecedor->status ? 'ativado' : 'desativado';

        // Registrar no audit log
        AuditService::logUpdate($fornecedor, ['status' => $oldStatus], "Status alterado para {$status}: {$fornecedor->nome}");

        return redirect()->back()
            ->with('success', "Fornecedor {$status} com sucesso!");
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
