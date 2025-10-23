<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class UsuarioEndereco extends Model
{
    use HasUuid;

    protected $table = 'usuario_endereco';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'usuario_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'uf',
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
