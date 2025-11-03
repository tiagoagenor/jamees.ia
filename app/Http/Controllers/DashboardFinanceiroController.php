<?php

namespace App\Http\Controllers;

use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Models\ContaEmpresa;
use App\Models\Movimentacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardFinanceiroController extends Controller
{
    /**
     * Exibir dashboard financeiro
     */
    public function index()
    {
        $empresaAtual = Auth::user()->empresaAtual();

        if (!$empresaAtual) {
            abort(403, 'Usuário não possui empresa atual.');
        }

        // Dados das contas a pagar
        $contasPagar = $this->getContasPagar($empresaAtual->id);

        // Dados das contas a receber
        $contasReceber = $this->getContasReceber($empresaAtual->id);

        // Fluxo de caixa (próximos 15 dias)
        $fluxoCaixa = $this->getFluxoCaixa($empresaAtual->id);

        // Saldo das contas bancárias
        $saldoContas = $this->getSaldoContas($empresaAtual->id);

        return view('dashboard.financeiro', compact(
            'contasPagar',
            'contasReceber',
            'fluxoCaixa',
            'saldoContas'
        ));
    }

    /**
     * Obter dados das contas a pagar
     */
    private function getContasPagar($empresaId)
    {
        $query = Movimentacao::daEmpresa($empresaId)
            ->porTipo(MovimentacaoTipoEnum::PAGAR)
            ->with(['entidade', 'formaPagamento']);

        // Resumos por situação
        $resumos = [
            'vencidos' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::VENCIDA)->sum('valor_total'),
            'vence_hoje' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PENDENTE)
                ->whereDate('vencimento', today())->sum('valor_total'),
            'a_vencer' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PENDENTE)
                ->whereDate('vencimento', '>', today())->sum('valor_total'),
            'pagos' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PAGA)->sum('valor_total'),
        ];

        // Últimas movimentações
        $ultimasMovimentacoes = $query->orderBy('criado_em', 'desc')->limit(5)->get();

        return [
            'resumos' => $resumos,
            'ultimas_movimentacoes' => $ultimasMovimentacoes
        ];
    }

    /**
     * Obter dados das contas a receber
     */
    private function getContasReceber($empresaId)
    {
        $query = Movimentacao::daEmpresa($empresaId)
            ->porTipo(MovimentacaoTipoEnum::RECEBER)
            ->with(['entidade', 'formaPagamento']);

        // Resumos por situação
        $resumos = [
            'vencidos' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::VENCIDA)->sum('valor_total'),
            'vence_hoje' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PENDENTE)
                ->whereDate('vencimento', today())->sum('valor_total'),
            'a_vencer' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PENDENTE)
                ->whereDate('vencimento', '>', today())->sum('valor_total'),
            'pagos' => (clone $query)->where('situacao', MovimentacaoSituacaoEnum::PAGA)->sum('valor_total'),
        ];

        // Últimas movimentações
        $ultimasMovimentacoes = $query->orderBy('criado_em', 'desc')->limit(5)->get();

        return [
            'resumos' => $resumos,
            'ultimas_movimentacoes' => $ultimasMovimentacoes
        ];
    }

    /**
     * Obter fluxo de caixa para os próximos 15 dias
     */
    private function getFluxoCaixa($empresaId)
    {
        $dataInicio = today();
        $dataFim = today()->addDays(15);

        // Pagamentos (contas a pagar) - APENAS PAGOS
        $pagamentos = Movimentacao::daEmpresa($empresaId)
            ->porTipo(MovimentacaoTipoEnum::PAGAR)
            ->whereBetween('data_compensacao', [$dataInicio, $dataFim])
            ->where('situacao', MovimentacaoSituacaoEnum::PAGA)
            ->selectRaw('DATE(data_compensacao) as data, SUM(valor_total) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        // Recebimentos (contas a receber) - APENAS PAGOS
        $recebimentos = Movimentacao::daEmpresa($empresaId)
            ->porTipo(MovimentacaoTipoEnum::RECEBER)
            ->whereBetween('data_compensacao', [$dataInicio, $dataFim])
            ->where('situacao', MovimentacaoSituacaoEnum::PAGA)
            ->selectRaw('DATE(data_compensacao) as data, SUM(valor_total) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get()
            ->keyBy('data');

        // Gerar dados para todos os dias do período
        $fluxoCaixa = [];
        $saldoAcumulado = 0;

        for ($data = $dataInicio->copy(); $data->lte($dataFim); $data->addDay()) {
            $dataStr = $data->format('Y-m-d');
            $pagamentoDia = $pagamentos->get($dataStr, (object)['total' => 0]);
            $recebimentoDia = $recebimentos->get($dataStr, (object)['total' => 0]);

            $saldoAcumulado += $recebimentoDia->total - $pagamentoDia->total;

            $fluxoCaixa[] = [
                'data' => $dataStr,
                'data_formatada' => $data->format('d/m/Y'),
                'pagamentos' => $pagamentoDia->total,
                'recebimentos' => $recebimentoDia->total,
                'saldo' => $saldoAcumulado
            ];
        }

        return [
            'periodo' => [
                'inicio' => $dataInicio->format('d/m/Y'),
                'fim' => $dataFim->format('d/m/Y')
            ],
            'dados' => $fluxoCaixa
        ];
    }

    /**
     * Obter saldo das contas bancárias
     */
    private function getSaldoContas($empresaId)
    {
        $contas = ContaEmpresa::daEmpresa($empresaId)
            ->ativas()
            ->with('banco')
            ->orderBy('nome')
            ->get();

        $contasComSaldo = [];
        $saldoTotal = 0;

        foreach ($contas as $conta) {
            // Calcular movimentações da conta
            $movimentacoesReceber = Movimentacao::daEmpresa($empresaId)
                ->porTipo(MovimentacaoTipoEnum::RECEBER)
                ->where('conta_empresa_id', $conta->id)
                ->where('situacao', MovimentacaoSituacaoEnum::PAGA)
                ->sum('valor_total');

            $movimentacoesPagar = Movimentacao::daEmpresa($empresaId)
                ->porTipo(MovimentacaoTipoEnum::PAGAR)
                ->where('conta_empresa_id', $conta->id)
                ->where('situacao', MovimentacaoSituacaoEnum::PAGA)
                ->sum('valor_total');

            // Saldo atual = (Recebimentos - Pagamentos) + Saldo Inicial
            $saldoAtual = ($movimentacoesReceber - $movimentacoesPagar) + $conta->saldo_inicial;

            $contasComSaldo[] = [
                'conta' => $conta,
                'saldo_inicial' => $conta->saldo_inicial,
                'movimentacoes_receber' => $movimentacoesReceber,
                'movimentacoes_pagar' => $movimentacoesPagar,
                'saldo_atual' => $saldoAtual
            ];

            $saldoTotal += $saldoAtual;
        }

        return [
            'contas' => $contasComSaldo,
            'saldo_total' => $saldoTotal
        ];
    }
}
