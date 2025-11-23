<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class ConfiguracaoEmpresa extends Model
{
    use HasUuid;

    protected $table = 'configuracoes_empresa';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'texto_boas_vindas',
        'frase_empresa',
        'video_institucional',
        'logo_empresa',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
