<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Configuracao extends Model
{
    use HasUuid;

    protected $table = 'configuracoes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'grupo',
        'chave',
        'valor',
        'serialized',
    ];

    protected $casts = [
        'serialized' => 'boolean',
    ];

    /**
     * Obter valor da configuração
     */
    public function getValorAttribute($value)
    {
        if ($this->attributes['serialized'] ?? 0) {
            return unserialize($value);
        }
        return $value;
    }

    /**
     * Definir valor da configuração
     */
    public function setValorAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['valor'] = serialize($value);
            $this->attributes['serialized'] = 1;
        } else {
            $this->attributes['valor'] = $value;
            $this->attributes['serialized'] = 0;
        }
    }

    /**
     * Buscar configuração por empresa, grupo e chave
     */
    public static function buscar($empresaId, $grupo, $chave, $default = null)
    {
        $config = self::where('empresa_id', $empresaId)
            ->where('grupo', $grupo)
            ->where('chave', $chave)
            ->first();
        
        return $config ? $config->valor : $default;
    }

    /**
     * Salvar configuração
     */
    public static function salvar($empresaId, $grupo, $chave, $valor)
    {
        return self::updateOrCreate(
            [
                'empresa_id' => $empresaId,
                'grupo' => $grupo,
                'chave' => $chave,
            ],
            [
                'valor' => $valor,
            ]
        );
    }
}
