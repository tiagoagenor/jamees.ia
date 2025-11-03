<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Enums\ContaTipoEnum;

class ContaEmpresa extends Model
{
    use HasUuid;

    protected $table = 'conta_empresa';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'banco_id',
        'empresa_id',
        'tipo',
        'nome',
        'saldo_inicial',
        'status',
    ];

    protected $casts = [
        'tipo' => ContaTipoEnum::class,
        'saldo_inicial' => 'decimal:2',
        'status' => 'boolean',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId = null)
    {
        if (!$empresaId) {
            $empresaId = \App\Helpers\PermissionHelper::getEmpresaPrincipalId();
        }
        if ($empresaId) {
            return $query->where('empresa_id', $empresaId);
        }
        return $query;
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', true);
    }

    public function scopePorTipo($query, ContaTipoEnum $tipo)
    {
        return $query->where('tipo', $tipo->value);
    }

    // Methods
    public function isAtiva(): bool
    {
        return $this->status == true;
    }

    public function getTipoLabel(): string
    {
        return $this->tipo->getLabel();
    }

    public function getTipoColor(): string
    {
        return $this->tipo->getColor();
    }

    public function getTipoIcon(): string
    {
        return $this->tipo->getIcon();
    }

    public function getSaldoInicialFormatado(): string
    {
        return 'R$ ' . number_format($this->saldo_inicial, 2, ',', '.');
    }

    public function isCorrente(): bool
    {
        return $this->tipo === ContaTipoEnum::CORRENTE;
    }

    public function isPoupanca(): bool
    {
        return $this->tipo === ContaTipoEnum::POUPANCA;
    }

    public function isInvestimento(): bool
    {
        return $this->tipo === ContaTipoEnum::INVESTIMENTO;
    }

    public function isCartaoCredito(): bool
    {
        return $this->tipo === ContaTipoEnum::CARTAO_CREDITO;
    }

    public function isCartaoDebito(): bool
    {
        return $this->tipo === ContaTipoEnum::CARTAO_DEBITO;
    }
}
