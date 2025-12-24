<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class FeatureFlag extends Model
{
    use HasUuid;

    protected $table = 'feature_flags';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'escopo',
        'empresa_id',
        'usuario_id',
        'ativo',
        'configuracao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'configuracao' => 'array',
    ];

    /**
     * Relacionamento com Empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Relacionamento com Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Scope para feature flags globais
     */
    public function scopeGlobais($query)
    {
        return $query->where('escopo', 'global');
    }

    /**
     * Scope para feature flags por empresa
     */
    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('escopo', 'empresa')
                    ->where('empresa_id', $empresaId);
    }

    /**
     * Scope para feature flags por usuário
     */
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('escopo', 'usuario')
                    ->where('usuario_id', $usuarioId);
    }

    /**
     * Scope para feature flags ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Verificar se uma feature flag está ativa
     */
    public static function estaAtiva(string $codigo, $empresaId = null, $usuarioId = null): bool
    {
        // Primeiro verificar escopo de usuário (mais específico)
        if ($usuarioId) {
            $flag = self::where('codigo', $codigo)
                ->where('escopo', 'usuario')
                ->where('usuario_id', $usuarioId)
                ->where('ativo', true)
                ->first();
            
            if ($flag) {
                return true;
            }
        }

        // Depois verificar escopo de empresa
        if ($empresaId) {
            $flag = self::where('codigo', $codigo)
                ->where('escopo', 'empresa')
                ->where('empresa_id', $empresaId)
                ->where('ativo', true)
                ->first();
            
            if ($flag) {
                return true;
            }
        }

        // Por último verificar escopo global
        $flag = self::where('codigo', $codigo)
            ->where('escopo', 'global')
            ->where('ativo', true)
            ->first();

        return $flag !== null;
    }

    /**
     * Ativar uma feature flag
     */
    public static function ativar(string $codigo, string $escopo = 'global', $empresaId = null, $usuarioId = null): self
    {
        return self::updateOrCreate(
            [
                'codigo' => $codigo,
                'escopo' => $escopo,
                'empresa_id' => $empresaId,
                'usuario_id' => $usuarioId,
            ],
            [
                'ativo' => true,
            ]
        );
    }

    /**
     * Desativar uma feature flag
     */
    public static function desativar(string $codigo, string $escopo = 'global', $empresaId = null, $usuarioId = null): bool
    {
        return self::where('codigo', $codigo)
            ->where('escopo', $escopo)
            ->where('empresa_id', $empresaId)
            ->where('usuario_id', $usuarioId)
            ->update(['ativo' => false]);
    }
}
