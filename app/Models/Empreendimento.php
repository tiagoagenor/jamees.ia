<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;

class Empreendimento extends Model
{
    use HasUuid;

    protected $table = 'empreendimento';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'nome',
        'imagem',
        'imagem_mapa',
        'zoom_default',
        'valor_m2',
        'maximo_parcelas',
        'sinal',
        'sinal_tipo',
        'sinal_valor',
        'juros_forma',
        'juros',
        'multa_forma',
        'multa',
        'juros_por_parcela',
        'status',
        'quadra_numeracao_tipo',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
        'valor_m2' => 'float',
        'sinal_valor' => 'float',
        'juros' => 'float',
        'multa' => 'float',
        'juros_por_parcela' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = now();
        });

        static::updating(function ($model) {
            $model->atualizado_em = now();
        });
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'empreendimento_id');
    }

    public function quadras(): HasMany
    {
        return $this->hasMany(Quadra::class, 'empreendimento_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('status', 1);
    }

    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}
