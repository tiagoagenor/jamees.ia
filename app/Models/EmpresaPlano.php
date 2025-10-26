<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;
use App\Enums\PlanoPeriodoEnum;
use App\Enums\PlanoStatusEnum;
use Carbon\Carbon;

class EmpresaPlano extends Model
{
    use HasUuid;

    protected $table = 'empresa_plano';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'plano_id',
        'periodo',
        'status',
        'valor_pago',
        'desconto_aplicado',
        'data_inicio',
        'data_fim',
        'data_cancelamento',
        'observacoes',
        'teste_gratuito',
    ];

    protected $casts = [
        'periodo' => PlanoPeriodoEnum::class,
        'status' => PlanoStatusEnum::class,
        'valor_pago' => 'decimal:2',
        'desconto_aplicado' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'data_cancelamento' => 'date',
        'teste_gratuito' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    public function isAtivo(): bool
    {
        return $this->status === PlanoStatusEnum::ATIVO &&
               $this->data_fim > now();
    }

    public function isTeste(): bool
    {
        return $this->status === PlanoStatusEnum::TESTE;
    }

    public function isExpirado(): bool
    {
        return $this->data_fim < now();
    }

    public function diasRestantes(): int
    {
        return max(0, (int) now()->diffInDays($this->data_fim, false));
    }

    public function isProximoDoVencimento(): bool
    {
        return $this->diasRestantes() <= 5 && $this->diasRestantes() > 0;
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', PlanoStatusEnum::ATIVO)
                    ->where('data_fim', '>', now());
    }

    public function scopeTeste($query)
    {
        return $query->where('status', PlanoStatusEnum::TESTE);
    }

    public function scopeExpirados($query)
    {
        return $query->where('data_fim', '<', now());
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeAtual($query, $empresaId)
    {
        return $query->porEmpresa($empresaId)
                    ->whereIn('status', [PlanoStatusEnum::ATIVO, PlanoStatusEnum::TESTE])
                    ->where('data_fim', '>', now())
                    ->orderBy('data_fim', 'desc')
                    ->first();
    }
}
