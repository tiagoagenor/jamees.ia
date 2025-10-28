<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class AuditLog extends Model
{
    use HasUuid;

    protected $table = 'audit_logs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'user_name',
        'user_email',
        'empresa_id',
        'empresa_nome',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'description',
        'metadata',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getActionLabel(): string
    {
        return match($this->action) {
            'CREATE' => 'Criado',
            'UPDATE' => 'Atualizado',
            'DELETE' => 'Excluído',
            'LOGIN' => 'Login',
            'LOGOUT' => 'Logout',
            'VIEW' => 'Visualizado',
            'EXPORT' => 'Exportado',
            'IMPORT' => 'Importado',
            default => $this->action
        };
    }

    public function getActionColor(): string
    {
        return match($this->action) {
            'CREATE' => 'success',
            'UPDATE' => 'warning',
            'DELETE' => 'danger',
            'LOGIN' => 'info',
            'LOGOUT' => 'secondary',
            'VIEW' => 'primary',
            'EXPORT' => 'info',
            'IMPORT' => 'success',
            default => 'secondary'
        };
    }

    public function getActionIcon(): string
    {
        return match($this->action) {
            'CREATE' => 'plus-circle',
            'UPDATE' => 'edit',
            'DELETE' => 'trash',
            'LOGIN' => 'sign-in-alt',
            'LOGOUT' => 'sign-out-alt',
            'VIEW' => 'eye',
            'EXPORT' => 'download',
            'IMPORT' => 'upload',
            default => 'info-circle'
        };
    }

    public function getModelLabel(): string
    {
        return match($this->model_type) {
            'Movimentacao' => 'Movimentação',
            'Usuario' => 'Usuário',
            'Empresa' => 'Empresa',
            'ContaEmpresa' => 'Conta Bancária',
            'FormaPagamento' => 'Forma de Pagamento',
            'PlanoConta' => 'Plano de Conta',
            'CentroCusto' => 'Centro de Custo',
            'Entidade' => 'Entidade',
            'Grupo' => 'Grupo',
            'Permissao' => 'Permissão',
            'Dre' => 'DRE',
            default => $this->model_type
        };
    }

    public function hasAuditChanges(): bool
    {
        return !empty($this->old_values) || !empty($this->new_values);
    }

    public function getChangesSummary(): string
    {
        if (!$this->hasAuditChanges()) {
            return 'Sem alterações registradas';
        }

        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $field => $newValue) {
                $oldValue = $this->old_values[$field] ?? null;

                // Normalizar valores para comparação
                $oldValueNormalized = $this->normalizeValue($oldValue);
                $newValueNormalized = $this->normalizeValue($newValue);

                if ($oldValueNormalized !== $newValueNormalized) {
                    $oldDisplay = $oldValueNormalized === null ? '(vazio)' : $oldValueNormalized;
                    $newDisplay = $newValueNormalized === null ? '(vazio)' : $newValueNormalized;
                    $changes[] = "{$field}: {$oldDisplay} → {$newDisplay}";
                }
            }
        }

        return implode(', ', $changes);
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

    public function getFormattedDate(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getRelativeDate(): string
    {
        return $this->created_at->diffForHumans();
    }
}
