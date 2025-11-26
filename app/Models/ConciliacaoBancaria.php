<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;

class ConciliacaoBancaria extends Model
{
    use HasUuid;

    protected $table = 'conciliacoes_bancarias';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'conta_empresa_id',
        'empresa_id',
        'usuario_id',
        'data_inicio',
        'data_fim',
        'descricao',
        'arquivo_ofx_path',
        'conciliado',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'conciliado' => 'boolean',
    ];

    // Relationships
    public function contaEmpresa(): BelongsTo
    {
        return $this->belongsTo(ContaEmpresa::class, 'conta_empresa_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function transacoes(): HasMany
    {
        return $this->hasMany(TransacaoOfx::class, 'conciliacao_bancaria_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId = null)
    {
        if (!$empresaId) {
            $empresaId = \App\Helpers\PermissionHelper::getEmpresaPrincipalId();
        }
        if ($empresaId) {
            return $query->where('empresa_id', $empresaId);
        }
        return $query;
    }

    // Methods
    public function isConciliado(): bool
    {
        return $this->conciliado;
    }

    public function getTotalTransacoes(): int
    {
        return $this->transacoes()->count();
    }

    public function getTotalConciliadas(): int
    {
        return $this->transacoes()->where('conciliado', true)->count();
    }
}
