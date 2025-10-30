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
        'dominio',
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

    /**
     * Resolve whitelabel by current host domain. If not found, return a record with dominio=null.
     */
    public static function resolveByRequestDomain(?string $host = null): ?self
    {
        $domain = $host ?: (request() ? request()->getHost() : null);
        if ($domain) {
            $found = static::where('dominio', $domain)->first();
            if ($found) {
                return $found;
            }
        }
        return static::whereNull('dominio')->first() ?: static::first();
    }
}
