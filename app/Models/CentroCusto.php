<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentroCusto extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'centro_custo';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empresa_id',
        'nome',
        'status',
        'atualizado_em',
        'criado_em',
    ];

    protected $casts = [
        'status' => 'integer',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    // Relacionamentos
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInativos($query)
    {
        return $query->where('status', 0);
    }

    // Helper methods
    public function isAtivo(): bool
    {
        return $this->status === 1;
    }

    public function isInativo(): bool
    {
        return $this->status === 0;
    }

    public function getStatusLabel(): string
    {
        return $this->isAtivo() ? 'Ativo' : 'Inativo';
    }

    public function getStatusColor(): string
    {
        return $this->isAtivo() ? 'green' : 'red';
    }

    public function getStatusIcon(): string
    {
        return $this->isAtivo() ? 'fas fa-check-circle' : 'fas fa-times-circle';
    }
}
