<?php

namespace App\Http\Controllers;

use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Helpers\PermissionHelper;
use App\Models\ContaEmpresa;
use App\Models\Movimentacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Exibe a página de relatórios de cadastros
     */
    public function cadastros()
    {
        return view('relatorios.cadastros');
    }

    /**
     * Exibe a página de relatórios financeiros
     */
    public function financeiro(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Filtros
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        $filtroValorMinimo = $request->get('valor_minimo', '');
        $filtroValorMaximo = $request->get('valor_maximo', '');
        $filtroContaBancaria = $request->get('conta_bancaria', '');

        // Validar período máximo de 2 anos
        if (!empty($filtroVencimentoInicio) && !empty($filtroVencimentoFim)) {
            $dataInicio = Carbon::parse($filtroVencimentoInicio);
            $dataFim = Carbon::parse($filtroVencimentoFim);
            
            if ($dataInicio->gt($dataFim)) {
                return redirect()->route('relatorios.financeiro')
                    ->with('error', 'A data de início não pode ser maior que a data de fim.')
                    ->withInput();
            }
            
            $diferencaAnos = $dataInicio->diffInYears($dataFim);
            if ($diferencaAnos > 2) {
                return redirect()->route('relatorios.financeiro')
                    ->with('error', 'O período selecionado não pode ser maior que 2 anos.')
                    ->withInput();
            }
        }

        // Query base - buscar todas as movimentações da empresa
        $query = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value)
            ->with(['planoConta', 'centroCusto', 'formaPagamento', 'contaEmpresa', 'entidade']);

        // Aplicar filtro de tipo
        if (!empty($filtroTipo)) {
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($filtroTipo);
            if ($tipoEnum) {
                $query->where('tipo', $tipoEnum->value);
            }
        }

        // Aplicar filtro de situação
        if (!empty($filtroSituacao)) {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->where('situacao', $situacaoEnum->value);
            }
        }

        // Aplicar filtro de vencimento
        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        // Aplicar filtro de valor
        if (!empty($filtroValorMinimo)) {
            $query->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $query->where('valor_total', '<=', $filtroValorMaximo);
        }

        // Aplicar filtro de conta bancária
        if (!empty($filtroContaBancaria)) {
            $query->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Ordenar por vencimento
        $query->orderBy('vencimento', 'desc');

        // Paginar resultados
        $movimentacoes = $query->paginate(20);

        // Preservar filtros na paginação
        $movimentacoes->appends([
            'tipo' => $filtroTipo,
            'situacao' => $filtroSituacao,
            'vencimento_inicio' => $filtroVencimentoInicio,
            'vencimento_fim' => $filtroVencimentoFim,
            'valor_minimo' => $filtroValorMinimo,
            'valor_maximo' => $filtroValorMaximo,
            'conta_bancaria' => $filtroContaBancaria,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value);

        // Aplicar mesmos filtros nas estatísticas
        if (!empty($filtroVencimentoInicio)) {
            $queryEstatisticas->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $queryEstatisticas->where('vencimento', '<=', $filtroVencimentoFim);
        }
        if (!empty($filtroValorMinimo)) {
            $queryEstatisticas->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $queryEstatisticas->where('valor_total', '<=', $filtroValorMaximo);
        }
        if (!empty($filtroContaBancaria)) {
            $queryEstatisticas->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Total a Pagar (tipo = PAGAR e situação != PAGA)
        $queryPagar = clone $queryEstatisticas;
        $queryPagar->where('tipo', MovimentacaoTipoEnum::PAGAR->value)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA->value);
        $totalPagar = $queryPagar->sum('valor_total');

        // Total a Receber (tipo = RECEBER e situação != PAGA)
        $queryReceber = clone $queryEstatisticas;
        $queryReceber->where('tipo', MovimentacaoTipoEnum::RECEBER->value)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA->value);
        $totalReceber = $queryReceber->sum('valor_total');

        // Saldo (Receber - Pagar)
        $saldo = $totalReceber - $totalPagar;

        // Total de movimentações
        $totalMovimentacoes = $queryEstatisticas->count();

        // Dados para gráfico "Distribuição por Situação"
        // Usar getQuery()->get() para obter dados sem casts do Eloquent
        $queryDistribuicao = clone $queryEstatisticas;
        $distribuicaoSituacao = $queryDistribuicao->getQuery()
            ->selectRaw('situacao, COUNT(*) as total')
            ->groupBy('situacao')
            ->get()
            ->mapWithKeys(function ($item) {
                // Converter para int (getQuery()->get() retorna stdClass sem casts)
                $situacaoValue = (int) $item->situacao;
                $situacao = MovimentacaoSituacaoEnum::tryFrom($situacaoValue);
                $label = $situacao ? $situacao->getLabel() : 'Desconhecida';
                return [$label => (int) $item->total];
            })
            ->toArray();

        // Dados para gráfico "Movimentações por Mês"
        $queryMensal = clone $queryEstatisticas;
        $movimentacoesMensal = $queryMensal->selectRaw('DATE_FORMAT(vencimento, "%Y-%m") as mes, COUNT(*) as total, SUM(valor_total) as valor_total')
            ->whereNotNull('vencimento')
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Formatar dados mensais para o gráfico
        $dadosMensais = [];
        $valoresMensais = [];
        $labelsMensais = [];
        
        foreach ($movimentacoesMensal as $item) {
            $data = Carbon::createFromFormat('Y-m', $item->mes);
            $labelsMensais[] = $data->format('M/Y');
            $dadosMensais[] = $item->total;
            $valoresMensais[] = (float) $item->valor_total;
        }

        // Buscar contas bancárias para o filtro
        $contasBancarias = ContaEmpresa::where('empresa_id', $empresaPrincipal->id)
            ->where('status', 1)
            ->with('banco')
            ->orderBy('nome')
            ->get();

        return view('relatorios.financeiro', compact(
            'movimentacoes',
            'totalPagar',
            'totalReceber',
            'saldo',
            'totalMovimentacoes',
            'contasBancarias',
            'filtroTipo',
            'filtroSituacao',
            'filtroVencimentoInicio',
            'filtroVencimentoFim',
            'filtroValorMinimo',
            'filtroValorMaximo',
            'filtroContaBancaria',
            'distribuicaoSituacao',
            'labelsMensais',
            'dadosMensais',
            'valoresMensais'
        ));
    }
}

