<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Aplicativo extends Model
{
    use HasUuid;

    protected $table = 'aplicativos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'descricao',
        'codigo',
        'preco_mensal',
        'preco_trimestral',
        'preco_semestral',
        'preco_anual',
        'ativo',
    ];

    protected $casts = [
        'preco_mensal' => 'decimal:2',
        'preco_trimestral' => 'decimal:2',
        'preco_semestral' => 'decimal:2',
        'preco_anual' => 'decimal:2',
        'ativo' => 'boolean',
    ];

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
}
