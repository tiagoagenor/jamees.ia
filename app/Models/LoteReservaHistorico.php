<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class LoteReservaHistorico extends Model
{
    use HasUuid;

    protected $table = 'lote_reserva_historico';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lote_id',
        'cliente_id',
        'usuario_id',
        'data_reserva',
        'data_fim',
        'observacao',
    ];

    public $timestamps = false;

    protected $casts = [
        'data_reserva' => 'datetime',
        'data_fim' => 'datetime',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
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

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
