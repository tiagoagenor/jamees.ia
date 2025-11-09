<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class ClienteContato extends Model
{
    use HasUuid;

    protected $table = 'cliente_contato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cliente_id',
        'entidade_tipo_id',
        'nome',
        'contato',
        'cargo',
        'observacao',
    ];

    // Relationships
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
