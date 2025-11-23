<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasUuid;
use App\Enums\IdeiaStatusEnum;

class Ideia extends Model
{
    use HasUuid;

    protected $table = 'ideias';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'usuario_id',
        'titulo',
        'descricao',
        'categoria',
        'status',
        'votos',
        'porcentagem',
    ];

    protected $casts = [
        'status' => IdeiaStatusEnum::class,
        'votos' => 'integer',
        'porcentagem' => 'integer',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(IdeiaComentario::class, 'ideia_id')->orderBy('created_at', 'asc');
    }

    public function votosUsuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'ideia_votos', 'ideia_id', 'usuario_id')
            ->withTimestamps();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function usuarioVotou($usuarioId): bool
    {
        if (!$usuarioId) {
            return false;
        }
        $this->load('votosUsuarios');
        return $this->votosUsuarios->contains('id', $usuarioId);
    }

    public function atualizarVotos(): void
    {
        $this->load('votosUsuarios');
        $this->votos = $this->votosUsuarios->count();
        $this->save();
    }
}
