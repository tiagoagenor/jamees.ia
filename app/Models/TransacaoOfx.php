<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class TransacaoOfx extends Model
{
    use HasUuid;

    protected $table = 'transacoes_ofx';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'conciliacao_bancaria_id',
        'tipo',
        'data',
        'valor',
        'descricao',
        'numero_documento',
        'fitid',
        'conciliado',
        'conta_pagar_id',
        'conta_receber_id',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'date',
        'valor' => 'decimal:2',
        'conciliado' => 'boolean',
    ];

    // Relationships
    public function conciliacaoBancaria(): BelongsTo
    {
        return $this->belongsTo(ConciliacaoBancaria::class, 'conciliacao_bancaria_id');
    }

    public function contaPagar(): BelongsTo
    {
        return $this->belongsTo(Movimentacao::class, 'conta_pagar_id');
    }

    public function contaReceber(): BelongsTo
    {
        return $this->belongsTo(Movimentacao::class, 'conta_receber_id');
    }

    // Scopes
    public function scopeConciliadas($query)
    {
        return $query->where('conciliado', true);
    }

    public function scopeNaoConciliadas($query)
    {
        return $query->where('conciliado', false);
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Methods
    public function isCredito(): bool
    {
        return $this->tipo === 'CREDIT';
    }

    public function isDebito(): bool
    {
        return $this->tipo === 'DEBIT';
    }

    public function getValorFormatado(): string
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function isConciliado(): bool
    {
        return $this->conciliado;
    }
}
