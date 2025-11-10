<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class FuncionarioEndereco extends Model
{
    use HasUuid;

    protected $table = 'funcionario_endereco';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'funcionario_id',
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
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }
}
