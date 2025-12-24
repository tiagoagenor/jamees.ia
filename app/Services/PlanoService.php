<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\EmpresaPlano;
use App\Models\Plano;
use App\Enums\PlanoStatusEnum;
use App\Enums\PlanoPeriodoEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlanoService
{
    /**
     * Ativa um plano de teste gratuito para uma empresa
     */
    public function ativarTesteGratuito(Empresa $empresa, ?int $dias = null): EmpresaPlano
    {
        // Desativar plano atual se existir
        $this->desativarPlanoAtual($empresa);

        $planoTeste = Plano::where('tipo', 'teste')->first();

        if (!$planoTeste) {
            throw new \Exception('Plano de teste não encontrado.');
        }

        // Usar dias_teste do plano se não foi informado um valor específico
        $dias = $dias ?? $planoTeste->dias_teste ?? 10;

        if (!$dias || $dias <= 0) {
            throw new \Exception('Dias de teste não configurados no plano de teste.');
        }

        $dataInicio = now();
        $dataFim = $dataInicio->copy()->addDays($dias);

        return EmpresaPlano::create([
            'empresa_id' => $empresa->id,
            'plano_id' => $planoTeste->id,
            'periodo' => PlanoPeriodoEnum::MENSAL,
            'status' => PlanoStatusEnum::TESTE,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'teste_gratuito' => true,
            'observacoes' => "Período de teste gratuito de {$dias} dias",
        ]);
    }

    /**
     * Ativa um plano pago para uma empresa
     */
    public function ativarPlano(Empresa $empresa, Plano $plano, PlanoPeriodoEnum $periodo, float $valorPago = null): EmpresaPlano
    {
        // Desativar plano atual se existir
        $this->desativarPlanoAtual($empresa);

        $precoBase = $plano->getPrecoPorPeriodo($periodo->value);
        $desconto = $periodo->getDiscount();
        $valorComDesconto = $precoBase * (1 - $desconto);

        $dataInicio = now();
        $dataFim = $dataInicio->copy()->addDays($periodo->getDays());

        return EmpresaPlano::create([
            'empresa_id' => $empresa->id,
            'plano_id' => $plano->id,
            'periodo' => $periodo,
            'status' => PlanoStatusEnum::ATIVO,
            'valor_pago' => $valorPago ?? $valorComDesconto,
            'desconto_aplicado' => $desconto * 100,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'teste_gratuito' => false,
            'observacoes' => "Plano {$plano->nome} ativado para período {$periodo->getLabel()}",
        ]);
    }

    /**
     * Desativa o plano atual de uma empresa
     */
    public function desativarPlanoAtual(Empresa $empresa): void
    {
        $planoAtual = $empresa->getPlanoAtual();

        if ($planoAtual) {
            $planoAtual->update([
                'status' => PlanoStatusEnum::CANCELADO,
                'data_cancelamento' => now(),
            ]);
        }
    }

    /**
     * Verifica se uma empresa tem plano ativo
     */
    public function verificarPlanoAtivo(Empresa $empresa): bool
    {
        return $empresa->isPlanoAtivo();
    }

    /**
     * Obtém o plano atual de uma empresa
     */
    public function obterPlanoAtual(Empresa $empresa): ?EmpresaPlano
    {
        return $empresa->getPlanoAtual();
    }

    /**
     * Verifica planos próximos do vencimento
     */
    public function verificarPlanosProximosVencimento(): array
    {
        return EmpresaPlano::where('data_fim', '<=', now()->addDays(5))
            ->where('data_fim', '>', now())
            ->whereIn('status', [PlanoStatusEnum::ATIVO, PlanoStatusEnum::TESTE])
            ->with(['empresa', 'plano'])
            ->get()
            ->toArray();
    }

    /**
     * Atualiza status de planos expirados
     */
    public function atualizarPlanosExpirados(): int
    {
        return EmpresaPlano::where('data_fim', '<', now())
            ->whereIn('status', [PlanoStatusEnum::ATIVO, PlanoStatusEnum::TESTE])
            ->update(['status' => PlanoStatusEnum::EXPIRADO]);
    }

    /**
     * Obtém histórico de planos de uma empresa
     */
    public function obterHistoricoPlanos(Empresa $empresa)
    {
        return $empresa->planos()
            ->with('plano')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calcula desconto para um período
     */
    public function calcularDesconto(PlanoPeriodoEnum $periodo): array
    {
        $desconto = $periodo->getDiscount();

        return [
            'periodo' => $periodo->getLabel(),
            'desconto_percentual' => $desconto * 100,
            'desconto_decimal' => $desconto,
        ];
    }

    /**
     * Valida se uma empresa pode ativar um plano
     */
    public function podeAtivarPlano(Empresa $empresa, Plano $plano): array
    {
        $planoAtual = $empresa->getPlanoAtual();

        if ($planoAtual && $planoAtual->isTeste()) {
            return [
                'pode' => true,
                'mensagem' => 'Você pode ativar um novo plano mesmo estando em período de teste.',
            ];
        }

        if ($planoAtual && $planoAtual->isAtivo()) {
            return [
                'pode' => false,
                'mensagem' => 'Você já possui um plano ativo. Cancele o plano atual antes de ativar um novo.',
            ];
        }

        return [
            'pode' => true,
            'mensagem' => 'Você pode ativar este plano.',
        ];
    }
}
