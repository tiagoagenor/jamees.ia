<?php

namespace App\Models;

use App\Enums\FormaPagamentoModalidadeEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormaPagamento extends Model
{
    use HasUuid;

    protected $table = 'forma_pagamento';
    public $timestamps = false;

    protected $fillable = [
        'empresa_id',
        'conta_empresa_id',
        'nome',
        'numero_parcelas',
        'intercalo_parcelas',
        'primeira_parcela',
        'modalidade',
        'taxa_banco',
        'taxa_operadora',
        'juros_multa',
        'juros_mora',
        'disponivel',
        'confirmacao_automatica',
        'gerar_boleto',
        'permite_deletar',
        'criado_em',
        'atualizado_em',
    ];

    protected $casts = [
        'modalidade' => FormaPagamentoModalidadeEnum::class,
        'taxa_banco' => 'decimal:2',
        'taxa_operadora' => 'decimal:2',
        'juros_multa' => 'decimal:2',
        'juros_mora' => 'decimal:2',
        'disponivel' => 'boolean',
        'confirmacao_automatica' => 'boolean',
        'gerar_boleto' => 'boolean',
        'permite_deletar' => 'boolean',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function contaEmpresa(): BelongsTo
    {
        return $this->belongsTo(ContaEmpresa::class, 'conta_empresa_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeDisponiveis($query)
    {
        return $query->where('disponivel', true);
    }

    // Helper methods
    public function getModalidadeLabel(): string
    {
        return $this->modalidade->getLabel();
    }

    public function getModalidadeColor(): string
    {
        return $this->modalidade->getColor();
    }

    public function getModalidadeIcon(): string
    {
        return $this->modalidade->getIcon();
    }

    public function isDisponivel(): bool
    {
        return $this->disponivel;
    }

    public function isConfirmacaoAutomatica(): bool
    {
        return $this->confirmacao_automatica;
    }

    public function isGerarBoleto(): bool
    {
        return $this->gerar_boleto;
    }

    public function isPermiteDeletar(): bool
    {
        return $this->permite_deletar;
    }

    public function getTaxaBancoFormatada(): string
    {
        return 'R$ ' . number_format($this->taxa_banco, 2, ',', '.');
    }

    public function getTaxaOperadoraFormatada(): string
    {
        return 'R$ ' . number_format($this->taxa_operadora, 2, ',', '.');
    }

    public function getJurosMultaFormatado(): string
    {
        return 'R$ ' . number_format($this->juros_multa, 2, ',', '.');
    }

    public function getJurosMoraFormatado(): string
    {
        return 'R$ ' . number_format($this->juros_mora, 2, ',', '.');
    }

    public function getTotalTaxas(): float
    {
        return $this->taxa_banco + $this->taxa_operadora + $this->juros_multa + $this->juros_mora;
    }

    public function getTotalTaxasFormatado(): string
    {
        return 'R$ ' . number_format($this->getTotalTaxas(), 2, ',', '.');
    }
}
