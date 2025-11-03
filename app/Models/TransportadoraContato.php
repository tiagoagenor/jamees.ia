<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class TransportadoraContato extends Model
{
    use HasUuid;

    protected $table = 'transportadora_contato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'transportadora_id',
        'entidade_tipo_id',
        'nome',
        'contato',
        'cargo',
        'observacao',
    ];

    // Relationships
    public function transportadora(): BelongsTo
    {
        return $this->belongsTo(Transportadora::class, 'transportadora_id');
    }
}
