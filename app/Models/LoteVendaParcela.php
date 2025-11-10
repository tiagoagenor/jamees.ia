<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class LoteVendaParcela extends Model
{
    use HasUuid;

    protected $table = 'lote_venda_parcela';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lote_id',
        'numero',
        'tipo',
        'valor_sem_juros',
        'valor_com_juros',
        'vencimento',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
        'valor_sem_juros' => 'decimal:2',
        'valor_com_juros' => 'decimal:2',
        'vencimento' => 'date',
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
}
