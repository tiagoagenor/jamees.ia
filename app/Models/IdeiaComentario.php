<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class IdeiaComentario extends Model
{
    use HasUuid;

    protected $table = 'ideia_comentarios';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ideia_id',
        'usuario_id',
        'comentario',
    ];

    public function ideia(): BelongsTo
    {
        return $this->belongsTo(Ideia::class, 'ideia_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
