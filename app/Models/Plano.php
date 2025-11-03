<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;
use App\Enums\PlanoTipoEnum;

class Plano extends Model
{
    use HasUuid;

    protected $table = 'planos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'preco_mensal',
        'preco_trimestral',
        'preco_semestral',
        'preco_anual',
        'limite_usuarios',
        'limite_empresas',
        'funcionalidades',
        'ativo',
    ];

    protected $casts = [
        'tipo' => PlanoTipoEnum::class,
        'preco_mensal' => 'decimal:2',
        'preco_trimestral' => 'decimal:2',
        'preco_semestral' => 'decimal:2',
        'preco_anual' => 'decimal:2',
        'funcionalidades' => 'array',
        'ativo' => 'boolean',
    ];

    public function empresasPlanos(): HasMany
    {
        return $this->hasMany(EmpresaPlano::class, 'plano_id');
    }

    public function getPrecoPorPeriodo($periodo): float
    {
        return match($periodo) {
            'mensal' => $this->preco_mensal,
            'trimestral' => $this->preco_trimestral,
            'semestral' => $this->preco_semestral,
            'anual' => $this->preco_anual,
            default => $this->preco_mensal,
        };
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeVisiveis($query)
    {
        return $query->where('ativo', true)
                    ->where('tipo', '!=', PlanoTipoEnum::TESTE);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderByRaw("
            CASE tipo
                WHEN 'base' THEN 1
                WHEN 'premium' THEN 2
                WHEN 'master' THEN 3
                WHEN 'personalizado' THEN 4
                ELSE 5
            END
        ");
    }
}
