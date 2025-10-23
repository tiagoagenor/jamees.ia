<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;

class User extends Authenticatable
{
    use HasUuid, Notifiable;

    protected $table = 'usuario';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'imagem',
        'status',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'criado_em' => 'datetime',
            'atualizado_em' => 'datetime',
            'senha' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
