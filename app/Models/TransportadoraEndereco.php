<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class TransportadoraEndereco extends Model
{
    use HasUuid;

    protected $table = 'transportadora_endereco';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'transportadora_id',
        'entidade_tipo_id',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'complemento',
    ];

    // Relationships
    public function transportadora(): BelongsTo
    {
        return $this->belongsTo(Transportadora::class, 'transportadora_id');
    }
}
