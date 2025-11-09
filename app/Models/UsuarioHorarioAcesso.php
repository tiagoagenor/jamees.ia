<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class UsuarioHorarioAcesso extends Model
{
    use HasUuid;

    protected $table = 'usuario_horario_acesso';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'usuario_id',
        'ativo',
        'hora_entrada',
        'hora_almoco_inicio',
        'hora_almoco_fim',
        'hora_saida',
        'dias_permitidos',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'dias_permitidos' => 'array',
    ];

    /**
     * Relacionamento com Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
