<?php

namespace App\Models;

use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'movimentacao';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empresa_id',
        'plano_conta_id',
        'centro_custo_id',
        'forma_pagamento_id',
        'conta_empresa_id',
        'situacao',
        'tipo',
        'parcela_codigo',
        'numero_parcela',
        'entidade_tipo',
        'entidade_id',
        'descricao',
        'vencimento',
        'observacao',
        'informacao_complementar',
        'valor',
        'juros',
        'juros_tipo',
        'juros_forma',
        'multa',
        'multa_forma',
        'desconto',
        'valor_total',
        'data_compensacao',
        'atualizado_em',
        'criado_em',
    ];

    protected $casts = [
        'situacao' => MovimentacaoSituacaoEnum::class,
        'tipo' => MovimentacaoTipoEnum::class,
        'vencimento' => 'date',
        'data_compensacao' => 'date',
        'valor' => 'decimal:2',
        'juros' => 'decimal:2',
        'multa' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function planoConta(): BelongsTo
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id');
    }

    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class, 'centro_custo_id');
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function contaEmpresa(): BelongsTo
    {
        return $this->belongsTo(ContaEmpresa::class, 'conta_empresa_id');
    }

    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'entidade_id');
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'entidade_id');
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'entidade_id');
    }

    public function transportadora(): BelongsTo
    {
        return $this->belongsTo(Transportadora::class, 'entidade_id');
    }

    /**
     * Get the entity based on entidade_tipo
     */
    public function getEntidade()
    {
        if (!$this->entidade_id || !$this->entidade_tipo) {
            return null;
        }

        return match($this->entidade_tipo) {
            1 => Cliente::find($this->entidade_id),
            2 => Fornecedor::find($this->entidade_id),
            3 => Funcionario::find($this->entidade_id),
            4 => Transportadora::find($this->entidade_id),
            default => null,
        };
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePorTipo($query, MovimentacaoTipoEnum $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorSituacao($query, MovimentacaoSituacaoEnum $situacao)
    {
        return $query->where('situacao', $situacao);
    }

    public function scopePendentes($query)
    {
        return $query->where('situacao', MovimentacaoSituacaoEnum::PENDENTE);
    }

    public function scopePagas($query)
    {
        return $query->where('situacao', MovimentacaoSituacaoEnum::PAGA);
    }

    public function scopeVencidas($query)
    {
        return $query->where('situacao', MovimentacaoSituacaoEnum::VENCIDA);
    }

    public function scopeCanceladas($query)
    {
        return $query->where('situacao', MovimentacaoSituacaoEnum::CANCELADA);
    }

    public function scopeVencidasPorData($query)
    {
        return $query->where('vencimento', '<', now()->toDateString())
                    ->where('situacao', MovimentacaoSituacaoEnum::PENDENTE);
    }

    // Helper methods
    public function isPendente(): bool
    {
        return $this->situacao === MovimentacaoSituacaoEnum::PENDENTE;
    }

    public function isPaga(): bool
    {
        return $this->situacao === MovimentacaoSituacaoEnum::PAGA;
    }

    public function isVencida(): bool
    {
        return $this->situacao === MovimentacaoSituacaoEnum::VENCIDA;
    }

    public function isCancelada(): bool
    {
        return $this->situacao === MovimentacaoSituacaoEnum::CANCELADA;
    }

    public function isPagar(): bool
    {
        return $this->tipo === MovimentacaoTipoEnum::PAGAR;
    }

    public function isReceber(): bool
    {
        return $this->tipo === MovimentacaoTipoEnum::RECEBER;
    }

    public function getSituacaoLabel(): string
    {
        return $this->situacao->getLabel();
    }

    public function getSituacaoColor(): string
    {
        return $this->situacao->getColor();
    }

    public function getSituacaoIcon(): string
    {
        return $this->situacao->getIcon();
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

    public function isVencidaPorData(): bool
    {
        return $this->vencimento < now()->toDateString() && $this->isPendente();
    }

    public function calcularValorTotal(): float
    {
        $valorTotal = $this->valor;

        // Aplicar juros apenas se:
        // - Tipo for "fixo" (sempre aplica)
        // - Tipo for "por_dia" E a movimentação estiver vencida
        if ($this->juros) {
            $jurosTipo = $this->juros_tipo ?? 'fixo';
            $estaVencida = $this->vencimento && $this->vencimento->format('Y-m-d') < now()->format('Y-m-d');

            if ($jurosTipo === 'fixo' || ($jurosTipo === 'por_dia' && $estaVencida)) {
                $valorTotal += $this->juros;
            }
        }

        if ($this->multa) {
            $valorTotal += $this->multa;
        }

        if ($this->desconto) {
            $valorTotal -= $this->desconto;
        }

        return $valorTotal;
    }
}
