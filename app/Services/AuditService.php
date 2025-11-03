<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuditService
{
    /**
     * Registrar uma ação no audit log
     */
    public static function log(
        string $action,
        string $modelType,
        ?string $modelId = null,
        ?string $modelName = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
        ?array $metadata = null
    ): void {
        try {
            $user = Auth::user();
            $empresaAtual = PermissionHelper::getEmpresaAtual();
            $request = request();

            AuditLog::create([
                'id' => Str::uuid(),
                'user_id' => $user?->id,
                'user_name' => $user?->nome,
                'user_email' => $user?->email,
                'empresa_id' => $empresaAtual?->id,
                'empresa_nome' => $empresaAtual?->nome_fantasia ?? $empresaAtual?->razao_social,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'model_name' => $modelName,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'url' => $request?->fullUrl(),
                'description' => $description,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo da aplicação
            Log::error('Erro ao registrar audit log: ' . $e->getMessage());
        }
    }

    /**
     * Registrar criação de um modelo
     */
    public static function logCreate(Model $model, ?string $description = null): void
    {
        // Para criação, vamos criar oldValues vazios baseados nos campos do modelo
        $attributes = $model->getAttributes();
        $oldValues = [];
        foreach ($attributes as $key => $value) {
            $oldValues[$key] = null; // Todos os valores antigos são null para criação
        }

        self::log(
            action: 'CREATE',
            modelType: class_basename($model),
            modelId: $model->id,
            modelName: self::getModelName($model),
            oldValues: $oldValues,
            newValues: $attributes,
            description: $description ?? "Criado {$model->getModelName()}",
        );
    }

    /**
     * Registrar atualização de um modelo
     */
    public static function logUpdate(Model $model, array $oldValues, ?string $description = null): void
    {
        $newValues = $model->getAttributes();

        // Verificar se houve mudanças reais
        $hasRealChanges = false;
        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;

            // Normalizar valores vazios para comparação
            $oldValueNormalized = self::normalizeValue($oldValue);
            $newValueNormalized = self::normalizeValue($newValue);

            if ($oldValueNormalized !== $newValueNormalized) {
                $hasRealChanges = true;
                break;
            }
        }

        // Só registrar se houve mudanças reais
        if ($hasRealChanges) {
            self::log(
                action: 'UPDATE',
                modelType: class_basename($model),
                modelId: $model->id,
                modelName: self::getModelName($model),
                oldValues: $oldValues,
                newValues: $newValues,
                description: $description ?? "Atualizado {$model->getModelName()}",
            );
        }
    }

    /**
     * Registrar exclusão de um modelo
     */
    public static function logDelete(Model $model, ?string $description = null): void
    {
        self::log(
            action: 'DELETE',
            modelType: class_basename($model),
            modelId: $model->id,
            modelName: self::getModelName($model),
            oldValues: $model->getAttributes(),
            description: $description ?? "Excluído {$model->getModelName()}",
        );
    }

    /**
     * Registrar visualização de um modelo
     */
    public static function logView(Model $model, ?string $description = null): void
    {
        self::log(
            action: 'VIEW',
            modelType: class_basename($model),
            modelId: $model->id,
            modelName: self::getModelName($model),
            description: $description ?? "Visualizado {$model->getModelName()}",
        );
    }

    /**
     * Registrar login do usuário
     */
    public static function logLogin(Usuario $user): void
    {
        self::log(
            action: 'LOGIN',
            modelType: 'Usuario',
            modelId: $user->id,
            modelName: $user->nome,
            description: "Login realizado",
        );
    }

    /**
     * Registrar logout do usuário
     */
    public static function logLogout(Usuario $user): void
    {
        self::log(
            action: 'LOGOUT',
            modelType: 'Usuario',
            modelId: $user->id,
            modelName: $user->nome,
            description: "Logout realizado",
        );
    }

    /**
     * Registrar troca de empresa
     */
    public static function logCompanySwitch(Empresa $empresa): void
    {
        self::log(
            action: 'UPDATE',
            modelType: 'Usuario',
            modelId: Auth::id(),
            modelName: Auth::user()?->nome,
            description: "Trocou para empresa: " . ($empresa->nome_fantasia ?? $empresa->razao_social),
            metadata: [
                'empresa_anterior' => session('empresa_anterior_id'),
                'empresa_atual' => $empresa->id,
            ]
        );
    }

    /**
     * Registrar ação customizada
     */
    public static function logCustom(
        string $action,
        string $description,
        ?string $modelType = null,
        ?string $modelId = null,
        ?string $modelName = null,
        ?array $metadata = null
    ): void {
        self::log(
            action: $action,
            modelType: $modelType ?? 'Sistema',
            modelId: $modelId,
            modelName: $modelName,
            description: $description,
            metadata: $metadata,
        );
    }

    /**
     * Obter nome amigável do modelo
     */
    private static function getModelName(Model $model): ?string
    {
        // Tentar obter um nome amigável baseado nos campos comuns
        $nameFields = ['nome', 'nome_fantasia', 'razao_social', 'descricao', 'titulo'];

        foreach ($nameFields as $field) {
            if (isset($model->$field) && !empty($model->$field)) {
                return $model->$field;
            }
        }

        // Se não encontrar, usar o ID
        return "ID: {$model->id}";
    }

    /**
     * Obter logs por empresa atual
     */
    public static function getLogsByEmpresaAtual($limit = 50)
    {
        $empresaAtual = PermissionHelper::getEmpresaAtual();

        if (!$empresaAtual) {
            return collect();
        }

        return AuditLog::byEmpresa($empresaAtual->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter logs por usuário
     */
    public static function getLogsByUser($userId, $limit = 50)
    {
        return AuditLog::byUser($userId)
            ->with(['empresa'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter logs por modelo
     */
    public static function getLogsByModel($modelType, $modelId = null, $limit = 50)
    {
        return AuditLog::byModel($modelType, $modelId)
            ->with(['user', 'empresa'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter estatísticas de atividade
     */
    public static function getActivityStats($days = 30, $empresasIds = null)
    {
        // Se não foram fornecidas empresas, usar empresa atual
        if ($empresasIds === null) {
            $empresaAtual = PermissionHelper::getEmpresaAtual();
            if (!$empresaAtual) {
                return [];
            }
            $empresasIds = [$empresaAtual->id];
        }

        $query = AuditLog::whereIn('empresa_id', $empresasIds)
            ->where('created_at', '>=', now()->subDays($days));

        return [
            'total_actions' => $query->count(),
            'actions_by_type' => $query->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'actions_by_user' => $query->selectRaw('user_name, COUNT(*) as count')
                ->groupBy('user_name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'user_name'),
            'actions_by_model' => $query->selectRaw('model_type, COUNT(*) as count')
                ->groupBy('model_type')
                ->orderBy('count', 'desc')
                ->pluck('count', 'model_type'),
        ];
    }

    /**
     * Normalizar valores para comparação (tratar valores vazios como null)
     */
    private static function normalizeValue($value)
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }

        return $value;
    }
}
