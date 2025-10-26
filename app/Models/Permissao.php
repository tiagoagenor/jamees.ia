<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasUuid;
use App\Helpers\PermissionHelper;

class Permissao extends Model
{
    use HasUuid;

    protected $table = 'permissoes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'modulo',
        'acao',
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'grupo_permissao', 'permissao_id', 'grupo_id')
                    ->withPivot('concedida')
                    ->withTimestamps();
    }

    public function scopePorModulo($query, $modulo)
    {
        return $query->where('modulo', $modulo);
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para filtrar permissões por empresa principal através dos grupos
     * Como as permissões são globais e vinculadas aos grupos por empresa,
     * este scope retorna todas as permissões ativas para que possam ser
     * associadas aos grupos da empresa principal
     */
    public function scopeDaEmpresaPrincipal($query, $empresaPrincipalId = null)
    {
        // As permissões são globais, então retornamos todas as ativas
        // O filtro por empresa acontece no nível dos grupos
        return $query->where('ativo', true);
    }
}
