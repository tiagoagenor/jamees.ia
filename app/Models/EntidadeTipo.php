<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class EntidadeTipo extends Model
{
    use HasUuid;

    protected $table = 'entidade_tipo';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'nome',
        'tipo',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
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

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
