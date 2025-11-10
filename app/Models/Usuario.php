<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use App\Traits\HasUuid;
use App\Enums\UsuarioStatusEnum;

class Usuario extends Model implements Authenticatable
{
    use HasUuid, AuthenticatableTrait;

    protected $table = 'usuario';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'imagem',
        'status',
        'principal',
        'remember_token',
    ];

    protected $hidden = [
        'senha',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
        'status' => UsuarioStatusEnum::class,
        'principal' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = now();
            $model->atualizado_em = now();
        });

        static::updating(function ($model) {
            $model->atualizado_em = now();
        });
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(UsuarioEndereco::class, 'usuario_id');
    }

    public function geral(): HasMany
    {
        return $this->hasMany(UsuarioGeral::class, 'usuario_id');
    }

    public function telefones(): HasMany
    {
        return $this->hasMany(UsuarioTelefone::class, 'usuario_id');
    }

    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'usuario_empresa', 'usuario_id', 'empresa_id')
                    ->withPivot('principal', 'status', 'criado_em', 'atualizado_em');
    }

    /**
     * Obter a empresa principal do usuário
     */
    public function empresaPrincipal()
    {
        return $this->empresas()->wherePivot('principal', 1)->first();
    }

    /**
     * Obter a empresa atual do usuário (sessão)
     */
    public function empresaAtual()
    {
        $empresaId = session('empresa_atual_id');
        if ($empresaId) {
            return $this->empresas()->where('empresa.id', $empresaId)->first();
        }
        return $this->empresaPrincipal();
    }

    public function ultimosAcessos(): HasMany
    {
        return $this->hasMany(UltimaAcesso::class, 'usuario_id');
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'usuario_grupo', 'usuario_id', 'grupo_id')
                    ->withTimestamps();
    }

    public function temPermissao($modulo, $acao): bool
    {
        // Buscar grupos da empresa principal do usuário
        $empresaPrincipal = $this->empresas()->wherePivot('principal', 1)->first();

        if (!$empresaPrincipal) {
            return false;
        }

        $grupo = $this->grupos()
            ->where('empresa_id', $empresaPrincipal->id)
            ->first();

        if (!$grupo) {
            return false;
        }

        return $grupo->temPermissao($modulo, $acao);
    }

    public function isAdmin(): bool
    {
        // Verificar se é admin na empresa principal
        $empresaPrincipal = $this->empresas()->wherePivot('principal', 1)->first();

        if (!$empresaPrincipal) {
            return false;
        }

        return $this->grupos()
            ->where('empresa_id', $empresaPrincipal->id)
            ->where('administrativo', true)
            ->exists();
    }

    public function isPrincipal(): bool
    {
        return $this->principal;
    }

    public function canDeleteUser(Usuario $targetUser): bool
    {
        // Usuário principal pode deletar qualquer um
        if ($this->isPrincipal()) {
            return true;
        }

        // Não pode deletar usuário principal
        if ($targetUser->isPrincipal()) {
            return false;
        }

        // Se o usuário atual é admin, pode deletar outros usuários
        if ($this->isAdmin()) {
            return true;
        }

        // Se o usuário atual não é admin, pode deletar apenas usuários não-admin
        return !$targetUser->isAdmin();
    }

    /**
     * Relacionamento com Funcionario (opcional - um usuário pode ter apenas um funcionário)
     */
    public function funcionario(): HasOne
    {
        return $this->hasOne(Funcionario::class, 'usuario_id');
    }

    /**
     * Verificar se o usuário tem funcionário associado
     */
    public function temFuncionario(): bool
    {
        return $this->funcionario()->exists();
    }

    /**
     * Obter o funcionário associado (se houver)
     */
    public function getFuncionario()
    {
        return $this->funcionario;
    }

    /**
     * Relacionamento com HorarioAcesso
     */
    public function horarioAcesso(): HasOne
    {
        return $this->hasOne(UsuarioHorarioAcesso::class, 'usuario_id');
    }

}
