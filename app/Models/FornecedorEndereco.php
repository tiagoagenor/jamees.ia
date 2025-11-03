<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class FornecedorEndereco extends Model
{
    use HasUuid;

    protected $table = 'fornecedor_endereco';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'fornecedor_id',
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
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
