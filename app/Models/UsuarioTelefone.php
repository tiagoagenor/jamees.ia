<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class UsuarioTelefone extends Model
{
    use HasUuid;

    protected $table = 'usuario_telefone';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'usuario_id',
        'tipo',
        'ddi',
        'ddd',
        'numero',
        'criado_em',
        'atualizado_em',
    ];

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = now();
            $model->atualizado_em = now();
        });

        static::updating(function ($model) {
            $model->atualizado_em = now();
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
