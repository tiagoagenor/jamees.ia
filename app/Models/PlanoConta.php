<?php

namespace App\Models;

use App\Enums\PlanoContaMovimentacaoEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanoConta extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'plano_conta';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empresa_id',
        'plano_conta_id',
        'dre_id',
        'nome',
        'movimentacao',
        'ordem_pai',
        'ordem_filho',
        'atualizado_em',
        'criado_em',
    ];

    protected $casts = [
        'movimentacao' => PlanoContaMovimentacaoEnum::class,
        'ordem_pai' => 'integer',
        'ordem_filho' => 'integer',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
    ];

    // Relacionamentos
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function planoContaPai(): BelongsTo
    {
        return $this->belongsTo(PlanoConta::class, 'plano_conta_id');
    }

    public function planoContasFilhos(): HasMany
    {
        return $this->hasMany(PlanoConta::class, 'plano_conta_id')->orderBy('ordem_filho');
    }

    public function dre(): BelongsTo
    {
        return $this->belongsTo(Dre::class, 'dre_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeRaiz($query)
    {
        return $query->whereNull('plano_conta_id');
    }

    public function scopeFilhos($query)
    {
        return $query->whereNotNull('plano_conta_id');
    }

    public function scopePorMovimentacao($query, PlanoContaMovimentacaoEnum $movimentacao)
    {
        return $query->where('movimentacao', $movimentacao);
    }

    // Helper methods
    public function getMovimentacaoLabel(): string
    {
        return $this->movimentacao->getLabel();
    }

    public function getMovimentacaoColor(): string
    {
        return $this->movimentacao->getColor();
    }

    public function getMovimentacaoIcon(): string
    {
        return $this->movimentacao->getIcon();
    }

    public function isDebito(): bool
    {
        return $this->movimentacao === PlanoContaMovimentacaoEnum::DEBITO;
    }

    public function isCredito(): bool
    {
        return $this->movimentacao === PlanoContaMovimentacaoEnum::CREDITO;
    }

    public function isRaiz(): bool
    {
        return is_null($this->plano_conta_id);
    }

    public function isFilho(): bool
    {
        return !is_null($this->plano_conta_id);
    }

    public function getCodigoCompleto(): string
    {
        if ($this->isRaiz()) {
            return str_pad($this->ordem_pai, 2, '0', STR_PAD_LEFT);
        } else {
            return str_pad($this->ordem_pai, 2, '0', STR_PAD_LEFT) . '.' .
                   str_pad($this->ordem_filho, 2, '0', STR_PAD_LEFT);
        }
    }

    public function getNomeCompleto(): string
    {
        $codigo = $this->getCodigoCompleto();
        return "{$codigo} - {$this->nome}";
    }
}
