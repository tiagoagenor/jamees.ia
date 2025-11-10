<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;

class Lote extends Model
{
    use HasUuid;

    protected $table = 'lote';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'quadra_id',
        'empreendimento_id',
        'lote_status_id',
        'cliente_id',
        'nome',
        'frente',
        'fundo',
        'lateral_direita',
        'lateral_esquerda',
        'valor_m2',
        'm2',
        'm2_tipo',
        'valor',
        'observacao',
        'posicao_pino',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
        'frente' => 'float',
        'fundo' => 'float',
        'lateral_direita' => 'float',
        'lateral_esquerda' => 'float',
        'valor_m2' => 'float',
        'm2' => 'float',
        'valor' => 'float',
        'posicao_pino' => 'array',
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

    public function quadra(): BelongsTo
    {
        return $this->belongsTo(Quadra::class, 'quadra_id');
    }

    public function empreendimento(): BelongsTo
    {
        return $this->belongsTo(Empreendimento::class, 'empreendimento_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LoteStatus::class, 'lote_status_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(LoteComentario::class, 'lote_id')->orderBy('criado_em', 'desc');
    }

    public function reservaHistorico(): HasMany
    {
        return $this->hasMany(LoteReservaHistorico::class, 'lote_id')->orderBy('data_reserva', 'desc');
    }

    public function vendaParcelas(): HasMany
    {
        return $this->hasMany(LoteVendaParcela::class, 'lote_id')->orderBy('numero');
    }
}
