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
        
        // Definir datas padrão apenas se não há nenhum parâmetro na URL (primeira carga)
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        
        // Se não há nenhum parâmetro na requisição, definir datas padrão
        if (!$request->hasAny(['tipo', 'situacao', 'vencimento_inicio', 'vencimento_fim', 'valor_minimo', 'valor_maximo', 'conta_bancaria', 'page'])) {
            $filtroVencimentoInicio = Carbon::now()->startOfYear()->format('Y-m-d'); // 01 de janeiro do ano atual
            $filtroVencimentoFim = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último dia do mês atual
        }
        
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
        // Determinar período para gerar todos os meses (usar as datas filtradas, que já incluem padrão se necessário)
        $dataInicioPeriodo = !empty($filtroVencimentoInicio) 
            ? Carbon::parse($filtroVencimentoInicio)->startOfMonth()
            : Carbon::now()->startOfYear()->startOfMonth();
        $dataFimPeriodo = !empty($filtroVencimentoFim) 
            ? Carbon::parse($filtroVencimentoFim)->endOfMonth()
            : Carbon::now()->endOfMonth();

        // Gerar todos os meses do período
        $todosMeses = [];
        $dataAtual = $dataInicioPeriodo->copy()->startOfMonth();
        while ($dataAtual->lte($dataFimPeriodo)) {
            $mesKey = $dataAtual->format('Y-m');
            $todosMeses[$mesKey] = [
                'mes' => $mesKey,
                'total' => 0,
                'valor_total' => 0
            ];
            $dataAtual->addMonth();
        }

        // Buscar dados reais do banco - separar por tipo (entrada/saída)
        // Contas a Receber (entrada) - tipo = 2
        $queryReceber = clone $queryEstatisticas;
        $movimentacoesReceber = $queryReceber->where('tipo', MovimentacaoTipoEnum::RECEBER->value)
            ->selectRaw('DATE_FORMAT(vencimento, "%Y-%m") as mes, SUM(valor_total) as valor_total')
            ->whereNotNull('vencimento')
            ->groupBy('mes')
            ->get()
            ->keyBy('mes');

        // Contas a Pagar (saída) - tipo = 1
        $queryPagar = clone $queryEstatisticas;
        $movimentacoesPagar = $queryPagar->where('tipo', MovimentacaoTipoEnum::PAGAR->value)
            ->selectRaw('DATE_FORMAT(vencimento, "%Y-%m") as mes, SUM(valor_total) as valor_total')
            ->whereNotNull('vencimento')
            ->groupBy('mes')
            ->get()
            ->keyBy('mes');

        // Mesclar dados reais com todos os meses (preenchendo com 0 os meses sem dados)
        foreach ($todosMeses as $mesKey => &$mesData) {
            $valorReceber = isset($movimentacoesReceber[$mesKey]) ? (float) $movimentacoesReceber[$mesKey]->valor_total : 0;
            $valorPagar = isset($movimentacoesPagar[$mesKey]) ? (float) $movimentacoesPagar[$mesKey]->valor_total : 0;
            
            // Saldo do mês (Receber - Pagar)
            $mesData['valor_total'] = $valorReceber - $valorPagar;
            $mesData['valor_receber'] = $valorReceber;
            $mesData['valor_pagar'] = $valorPagar;
        }
        unset($mesData);

        // Ordenar por mês e formatar para o gráfico
        ksort($todosMeses);
        $valoresPagar = [];
        $valoresReceber = [];
        $labelsMensais = [];
        
        // Array de meses em português
        $mesesPortugues = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];
        
        foreach ($todosMeses as $mesData) {
            $data = Carbon::createFromFormat('Y-m', $mesData['mes']);
            $mesNumero = (int) $data->format('n'); // 1-12
            $ano = $data->format('Y');
            $mesNome = $mesesPortugues[$mesNumero] ?? $data->format('M');
            $labelsMensais[] = $mesNome . '/' . $ano;
            // Separar valores de pagar e receber
            $valoresPagar[] = $mesData['valor_pagar'];
            $valoresReceber[] = $mesData['valor_receber'];
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
            'valoresPagar',
            'valoresReceber'
        ));
    }

    /**
     * Exporta relatório financeiro para CSV
     */
    public function exportarFinanceiroCsv(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Aplicar os mesmos filtros do método financeiro()
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        $filtroValorMinimo = $request->get('valor_minimo', '');
        $filtroValorMaximo = $request->get('valor_maximo', '');
        $filtroContaBancaria = $request->get('conta_bancaria', '');

        // Se não há filtros, usar datas padrão
        if (!$request->hasAny(['tipo', 'situacao', 'vencimento_inicio', 'vencimento_fim', 'valor_minimo', 'valor_maximo', 'conta_bancaria'])) {
            $filtroVencimentoInicio = Carbon::now()->startOfYear()->format('Y-m-d');
            $filtroVencimentoFim = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        // Query base - buscar todas as movimentações da empresa (sem paginação)
        $query = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value)
            ->with(['planoConta', 'centroCusto', 'formaPagamento', 'contaEmpresa', 'entidade']);

        // Aplicar filtros (mesma lógica do método financeiro)
        if (!empty($filtroTipo)) {
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($filtroTipo);
            if ($tipoEnum) {
                $query->where('tipo', $tipoEnum->value);
            }
        }

        if (!empty($filtroSituacao)) {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->where('situacao', $situacaoEnum->value);
            }
        }

        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        if (!empty($filtroValorMinimo)) {
            $query->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $query->where('valor_total', '<=', $filtroValorMaximo);
        }

        if (!empty($filtroContaBancaria)) {
            $query->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Ordenar por vencimento
        $query->orderBy('vencimento', 'desc');

        // Buscar TODOS os resultados (sem paginação) - exporta todas as páginas
        // Usa get() ao invés de paginate() para exportar todos os registros que atendem aos filtros
        $movimentacoes = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_financeiro_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($movimentacoes) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Tipo',
                'Descrição',
                'Vencimento',
                'Valor',
                'Situação',
                'Conta Bancária',
                'Plano de Contas',
                'Centro de Custo',
                'Forma de Pagamento',
                'Entidade',
                'Data de Compensação',
                'Observação'
            ], ';');

            // Dados
            foreach ($movimentacoes as $movimentacao) {
                $tipo = $movimentacao->tipo->value == 1 ? 'Contas a Pagar' : 'Contas a Receber';
                $situacao = $movimentacao->situacao->getLabel();
                $vencimento = $movimentacao->vencimento ? Carbon::parse($movimentacao->vencimento)->format('d/m/Y') : 'N/A';
                $valor = number_format($movimentacao->valor_total, 2, ',', '.');
                $contaBancaria = $movimentacao->contaEmpresa ? $movimentacao->contaEmpresa->nome : 'N/A';
                $planoConta = $movimentacao->planoConta ? $movimentacao->planoConta->nome : 'N/A';
                $centroCusto = $movimentacao->centroCusto ? $movimentacao->centroCusto->nome : 'N/A';
                $formaPagamento = $movimentacao->formaPagamento ? $movimentacao->formaPagamento->nome : 'N/A';
                $entidade = $movimentacao->entidade ? $movimentacao->entidade->nome : 'N/A';
                $dataCompensacao = $movimentacao->data_compensacao ? Carbon::parse($movimentacao->data_compensacao)->format('d/m/Y') : 'N/A';
                $observacao = $movimentacao->observacao ?? '';

                fputcsv($file, [
                    $tipo,
                    $movimentacao->descricao,
                    $vencimento,
                    $valor,
                    $situacao,
                    $contaBancaria,
                    $planoConta,
                    $centroCusto,
                    $formaPagamento,
                    $entidade,
                    $dataCompensacao,
                    $observacao
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

