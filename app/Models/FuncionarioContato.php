<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class FuncionarioContato extends Model
{
    use HasUuid;

    protected $table = 'funcionario_contato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'funcionario_id',
        'entidade_tipo_id',
        'nome',
        'contato',
        'cargo',
        'observacao',
    ];

    // Relationships
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }
}
