<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasUuid;
use App\Helpers\PermissionHelper;

class Grupo extends Model
{
    use HasUuid;

    protected $table = 'grupos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'nome',
        'descricao',
        'administrativo',
        'ativo',
    ];

    protected $casts = [
        'administrativo' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function permissoes(): BelongsToMany
    {
        return $this->belongsToMany(Permissao::class, 'grupo_permissao', 'grupo_id', 'permissao_id')
                    ->withPivot('concedida')
                    ->withTimestamps();
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuario_grupo', 'grupo_id', 'usuario_id')
                    ->withTimestamps();
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function temPermissao($modulo, $acao): bool
    {
        if ($this->administrativo) {
            return true;
        }

        return $this->permissoes()
                    ->where('modulo', $modulo)
                    ->where('acao', $acao)
                    ->wherePivot('concedida', true)
                    ->exists();
    }

    /**
     * Scope para filtrar grupos por empresa principal
     */
    public function scopeDaEmpresaPrincipal($query, $empresaPrincipalId = null)
    {
        if (!$empresaPrincipalId) {
            $empresaPrincipalId = PermissionHelper::getEmpresaPrincipalId();
        }

        if ($empresaPrincipalId) {
            return $query->where('empresa_id', $empresaPrincipalId);
        }

        return $query;
    }
}
