<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class FornecedorContato extends Model
{
    use HasUuid;

    protected $table = 'fornecedor_contato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'fornecedor_id',
        'entidade_tipo_id',
        'nome',
        'contato',
        'cargo',
        'observacao',
    ];

    // Relationships
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
