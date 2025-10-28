<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Services\AuditService;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('audit', 'listar')) {
            abort(403, 'Você não tem permissão para visualizar histórico de alterações.');
        }

        // Obter empresa principal e todas as empresas vinculadas
        $user = Auth::user();
        $empresaPrincipal = $user->empresaPrincipal();
        $empresasVinculadas = $user->empresas()->get();

        // Criar lista de IDs de todas as empresas (principal + vinculadas)
        $todasEmpresasIds = $empresasVinculadas->pluck('id')->toArray();
        if ($empresaPrincipal && !in_array($empresaPrincipal->id, $todasEmpresasIds)) {
            $todasEmpresasIds[] = $empresaPrincipal->id;
        }

        // Filtros
        $filtroUsuario = $request->get('usuario', '');
        $filtroAcao = $request->get('acao', '');
        $filtroTipo = $request->get('tipo', '');
        $filtroId = $request->get('id', '');
        $filtroEmpresa = $request->get('empresa', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');
        $filtroDescricao = $request->get('descricao', '');
        $filtroPeriodo = $request->get('periodo', '5_dias'); // Filtro padrão: últimos 5 dias

        // Query base - buscar de todas as empresas vinculadas
        $query = AuditLog::whereIn('empresa_id', $todasEmpresasIds)
            ->with(['user'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de período padrão se não há filtros de data específicos
        if (empty($filtroDataInicio) && empty($filtroDataFim)) {
            switch ($filtroPeriodo) {
                case 'hoje':
                    $query->whereDate('created_at', today());
                    break;
                case 'ontem':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case '3_dias':
                    $query->where('created_at', '>=', now()->subDays(3));
                    break;
                case '5_dias':
                    $query->where('created_at', '>=', now()->subDays(5));
                    break;
                case '7_dias':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case '15_dias':
                    $query->where('created_at', '>=', now()->subDays(15));
                    break;
                case '30_dias':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'todos':
                    // Não aplicar filtro de data
                    break;
            }
        }

        // Aplicar filtros
        if (!empty($filtroUsuario)) {
            $query->where(function($q) use ($filtroUsuario) {
                $q->where('user_name', 'LIKE', '%' . $filtroUsuario . '%')
                  ->orWhere('user_email', 'LIKE', '%' . $filtroUsuario . '%');
            });
        }

        if (!empty($filtroAcao)) {
            $query->where('action', $filtroAcao);
        }

        if (!empty($filtroTipo)) {
            $query->where('model_type', $filtroTipo);
        }

        if (!empty($filtroId)) {
            $query->where('model_id', $filtroId);
        }

        if (!empty($filtroEmpresa)) {
            $query->where('empresa_id', $filtroEmpresa);
        }

        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }

        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        if (!empty($filtroDescricao)) {
            $query->where('description', 'LIKE', '%' . $filtroDescricao . '%');
        }

        $logs = $query->paginate(5);

        // Opções para filtros
        $acoes = [
            'CREATE' => 'Criar',
            'UPDATE' => 'Atualizar',
            'DELETE' => 'Excluir',
            'VIEW' => 'Visualizar',
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'COMPANY_SWITCH' => 'Trocar Empresa',
            'TOGGLE_STATUS' => 'Alterar Status',
            'CONFIRMAR_PAGAMENTO' => 'Confirmar Pagamento',
            'MARCAR_PENDENTE' => 'Marcar Pendente',
            'REATIVAR' => 'Reativar',
            'CUSTOM' => 'Personalizado'
        ];

        $modelos = AuditLog::whereIn('empresa_id', $todasEmpresasIds)
            ->select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        $usuarios = AuditLog::whereIn('empresa_id', $todasEmpresasIds)
            ->select('user_name')
            ->distinct()
            ->orderBy('user_name')
            ->pluck('user_name');

        // Preparar lista de empresas para o filtro
        $empresas = $empresasVinculadas->map(function($empresa) use ($empresaPrincipal) {
            $isPrincipal = $empresaPrincipal && $empresa->id === $empresaPrincipal->id;
            return [
                'id' => $empresa->id,
                'nome' => $empresa->nome_fantasia ?? $empresa->razao_social,
                'is_principal' => $isPrincipal
            ];
        })->sortByDesc('is_principal')->values();

        return view('audit.index', compact(
            'logs',
            'acoes',
            'modelos',
            'usuarios',
            'empresas',
            'filtroUsuario',
            'filtroAcao',
            'filtroTipo',
            'filtroId',
            'filtroEmpresa',
            'filtroDataInicio',
            'filtroDataFim',
            'filtroDescricao',
            'filtroPeriodo'
        ));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('audit', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar detalhes do histórico.');
        }

        $user = Auth::user();
        $empresaPrincipal = $user->empresaPrincipal();
        $empresasVinculadas = $user->empresas()->get();

        $todasEmpresasIds = $empresasVinculadas->pluck('id')->toArray();
        if ($empresaPrincipal && !in_array($empresaPrincipal->id, $todasEmpresasIds)) {
            $todasEmpresasIds[] = $empresaPrincipal->id;
        }

        // Verificar se o log pertence a alguma das empresas do usuário
        if (!in_array($auditLog->empresa_id, $todasEmpresasIds)) {
            abort(403, 'Log não encontrado.');
        }

        $auditLog->load(['user', 'empresa']);

        return view('audit.show', compact('auditLog'));
    }

    /**
     * Get activity statistics
     */
    public function stats(Request $request)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('audit', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar estatísticas.');
        }

        $days = $request->get('days', 30);
        $stats = AuditService::getActivityStats($days);

        return response()->json($stats);
    }

    /**
     * Get logs by user
     */
    public function byUser(Request $request, $userId)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('audit', 'listar')) {
            abort(403, 'Você não tem permissão para visualizar histórico por usuário.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $logs = AuditLog::byEmpresa($empresaAtual->id)
            ->byUser($userId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $user = \App\Models\Usuario::find($userId);

        return view('audit.by-user', compact('logs', 'user'));
    }

    /**
     * Get logs by model
     */
    public function byModel(Request $request, $modelType, $modelId = null)
    {
        // Verificar permissão
        if (!Auth::user()->temPermissao('audit', 'listar')) {
            abort(403, 'Você não tem permissão para visualizar histórico por modelo.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $logs = AuditLog::byEmpresa($empresaAtual->id)
            ->byModel($modelType, $modelId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('audit.by-model', compact('logs', 'modelType', 'modelId'));
    }
}
