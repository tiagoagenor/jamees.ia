<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Enums\AtualizacaoTipoEnum;

class AtualizacaoSistema extends Model
{
    use HasUuid;

    protected $table = 'atualizacao_sistema';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tipo',
        'titulo',
        'minitexto',
        'texto',
    ];

    protected $casts = [
        'tipo' => AtualizacaoTipoEnum::class,
    ];

    public function getTipoLabelAttribute(): string
    {
        return $this->tipo->label();
    }

    public function getTipoIconAttribute(): string
    {
        return $this->tipo->icon();
    }

    public function getTipoColorAttribute(): string
    {
        return $this->tipo->color();
    }
}
