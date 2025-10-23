<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use App\Traits\HasUuid;

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
        'remember_token',
    ];

    protected $hidden = [
        'senha',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
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

}
