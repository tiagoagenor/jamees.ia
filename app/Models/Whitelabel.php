<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;

class Whitelabel extends Model
{
    use HasUuid;

    protected $table = 'whitelabel';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
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

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'whitelabel_id');
    }

    public function ultimosAcessos(): HasMany
    {
        return $this->hasMany(UltimaAcesso::class, 'whitelabel_id');
    }
}
