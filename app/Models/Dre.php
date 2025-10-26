<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;
use App\Enums\DreTipoEnum;

class Dre extends Model
{
    use HasUuid;

    protected $table = 'dre';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empresa_id',
        'dre_id',
        'nome',
        'tipo',
        'status',
    ];

    protected $casts = [
        'tipo' => DreTipoEnum::class,
        'status' => 'boolean',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Dre::class, 'dre_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Dre::class, 'dre_id');
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

    public function scopeAtivos($query)
    {
        return $query->where('status', true);
    }

    public function scopeRaiz($query)
    {
        return $query->whereNull('dre_id');
    }

    public function scopePorTipo($query, DreTipoEnum $tipo)
    {
        return $query->where('tipo', $tipo->value);
    }

    // Methods
    public function isAtivo(): bool
    {
        return $this->status == true;
    }

    public function getTipoLabel(): string
    {
        return $this->tipo->getLabel();
    }

    public function getTipoColor(): string
    {
        return $this->tipo->getColor();
    }

    public function getTipoIcon(): string
    {
        return $this->tipo->getIcon();
    }

    public function isReceita(): bool
    {
        return $this->tipo === DreTipoEnum::RECEITA;
    }

    public function isDespesa(): bool
    {
        return $this->tipo === DreTipoEnum::DESPESA;
    }

    public function isTotalizador(): bool
    {
        return $this->tipo === DreTipoEnum::TOTALIZADOR;
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function getLevel(): int
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }
}
