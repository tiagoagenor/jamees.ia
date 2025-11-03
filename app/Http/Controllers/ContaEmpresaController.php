<?php

namespace App\Http\Controllers;

use App\Models\ContaEmpresa;
use App\Models\Empresa;
use App\Models\Banco;
use App\Enums\ContaTipoEnum;
use App\Helpers\PermissionHelper;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContaEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Filtros
        $filtroStatus = $request->get('status', 'todos');
        $filtroNome = $request->get('nome', '');

        $query = ContaEmpresa::daEmpresa($empresaAtual->id)
            ->with('banco')
            ->orderBy('nome');

        // Aplicar filtro de status
        if ($filtroStatus === 'ativas') {
            $query->ativas();
        } elseif ($filtroStatus === 'inativas') {
            $query->where('status', 0);
        }
        // Se for 'todos', não aplica filtro

        // Aplicar filtro por nome
        if (!empty($filtroNome)) {
            $query->where('nome', 'LIKE', '%' . $filtroNome . '%');
        }

        $contas = $query->get();

        return view('conta-empresa.index', compact('contas', 'filtroStatus', 'filtroNome'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $tipos = ContaTipoEnum::cases();
        $bancos = Banco::ativos()->orderBy('nome_normalizado')->get();

        return view('conta-empresa.create', compact('tipos', 'bancos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'tipo' => 'required|integer|in:1,2,3,4,5',
            'nome' => 'required|string|max:255',
            'saldo_inicial' => 'nullable|numeric|min:0',
            'status' => 'boolean',
        ], [
            'banco_id.required' => 'O banco é obrigatório.',
            'banco_id.exists' => 'Banco selecionado não existe.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'Tipo inválido.',
            'nome.required' => 'O nome é obrigatório.',
            'saldo_inicial.numeric' => 'Saldo inicial deve ser um número.',
            'saldo_inicial.min' => 'Saldo inicial não pode ser negativo.',
        ]);

        $contaEmpresa = ContaEmpresa::create([
            'id' => Str::uuid()->toString(),
            'banco_id' => $request->banco_id,
            'empresa_id' => $empresaAtual->id,
            'tipo' => ContaTipoEnum::from($request->tipo),
            'nome' => $request->nome,
            'saldo_inicial' => $request->saldo_inicial ?? 0,
            'status' => $request->boolean('status', true),
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log
        AuditService::logCreate($contaEmpresa, "Criou conta bancária: {$contaEmpresa->nome}");

        return redirect()->route('conta-empresa.index')
            ->with('success', 'Conta bancária criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContaEmpresa $contaEmpresa)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual || $contaEmpresa->empresa_id !== $empresaAtual->id) {
            abort(403, 'Acesso negado.');
        }

        return view('conta-empresa.show', compact('contaEmpresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContaEmpresa $contaEmpresa)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual || $contaEmpresa->empresa_id !== $empresaAtual->id) {
            abort(403, 'Acesso negado.');
        }

        $tipos = ContaTipoEnum::cases();
        $bancos = Banco::ativos()->orderBy('nome_normalizado')->get();

        return view('conta-empresa.edit', compact('contaEmpresa', 'tipos', 'bancos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContaEmpresa $contaEmpresa)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual || $contaEmpresa->empresa_id !== $empresaAtual->id) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'tipo' => 'required|integer|in:1,2,3,4,5',
            'nome' => 'required|string|max:255',
            'saldo_inicial' => 'nullable|numeric|min:0',
            'status' => 'boolean',
        ], [
            'banco_id.required' => 'O banco é obrigatório.',
            'banco_id.exists' => 'Banco selecionado não existe.',
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.in' => 'Tipo inválido.',
            'nome.required' => 'O nome é obrigatório.',
            'saldo_inicial.numeric' => 'Saldo inicial deve ser um número.',
            'saldo_inicial.min' => 'Saldo inicial não pode ser negativo.',
        ]);

        // Capturar valores antigos antes da atualização
        $oldValues = $contaEmpresa->getAttributes();

        $contaEmpresa->update([
            'banco_id' => $request->banco_id,
            'tipo' => ContaTipoEnum::from($request->tipo),
            'nome' => $request->nome,
            'saldo_inicial' => $request->saldo_inicial ?? 0,
            'status' => $request->boolean('status', true),
            'atualizado_em' => now(),
        ]);

        // Registrar no audit log apenas se houve mudanças
        if ($contaEmpresa->wasChanged()) {
            AuditService::logUpdate($contaEmpresa, $oldValues, "Atualizou conta bancária: {$contaEmpresa->nome}");
        }

        return redirect()->route('conta-empresa.index')
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContaEmpresa $contaEmpresa)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual || $contaEmpresa->empresa_id !== $empresaAtual->id) {
            abort(403, 'Acesso negado.');
        }

        // Registrar no audit log antes da exclusão
        AuditService::logDelete($contaEmpresa, "Excluiu conta bancária: {$contaEmpresa->nome}");

        $contaEmpresa->delete();

        return redirect()->route('conta-empresa.index')
            ->with('success', 'Conta bancária excluída com sucesso!');
    }

    /**
     * Toggle status of the conta
     */
    public function toggleStatus(ContaEmpresa $contaEmpresa)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual || $contaEmpresa->empresa_id !== $empresaAtual->id) {
            abort(403, 'Acesso negado.');
        }

        // Capturar valores antigos antes da atualização
        $oldValues = $contaEmpresa->getAttributes();

        $contaEmpresa->update(['status' => !$contaEmpresa->status]);

        // Registrar no audit log
        $status = $contaEmpresa->status ? 'ativada' : 'desativada';
        AuditService::logUpdate($contaEmpresa, $oldValues, "Alterou status da conta bancária: {$contaEmpresa->nome} - {$status}");

        return redirect()->back()
            ->with('success', "Conta bancária {$status} com sucesso!");
    }
}
