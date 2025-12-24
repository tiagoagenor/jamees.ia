@extends('layouts.app')

@section('page-title', 'Dashboard Financeiro - Nova Versão')

@section('content')
<div class="space-y-6">
    <!-- Header com título e ações -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Financeiro</h1>
            <p class="text-gray-600 mt-1">Visão geral das suas finanças</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard.financeiro') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Versão Anterior
            </a>
        </div>
    </div>

    <!-- Cards de resumo geral -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total a Pagar -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-white text-xl"></i>
                </div>
                <span class="text-xs font-medium text-red-700 bg-red-200 px-2 py-1 rounded-full">A Pagar</span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-red-600 font-medium">Total a Pagar</p>
                <p class="text-2xl font-bold text-red-700 mt-1">
                    R$ {{ number_format($contasPagar['resumos']['vencidos'] + $contasPagar['resumos']['vence_hoje'] + $contasPagar['resumos']['a_vencer'], 2, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Total a Receber -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-white text-xl"></i>
                </div>
                <span class="text-xs font-medium text-green-700 bg-green-200 px-2 py-1 rounded-full">A Receber</span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-green-600 font-medium">Total a Receber</p>
                <p class="text-2xl font-bold text-green-700 mt-1">
                    R$ {{ number_format($contasReceber['resumos']['vencidos'] + $contasReceber['resumos']['vence_hoje'] + $contasReceber['resumos']['a_vencer'], 2, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Saldo Total -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-xl"></i>
                </div>
                <span class="text-xs font-medium text-blue-700 bg-blue-200 px-2 py-1 rounded-full">Saldo</span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-blue-600 font-medium">Saldo Total</p>
                <p class="text-2xl font-bold {{ $saldoContas['saldo_total'] >= 0 ? 'text-blue-700' : 'text-red-700' }} mt-1">
                    R$ {{ number_format($saldoContas['saldo_total'], 2, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Vencidos -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <span class="text-xs font-medium text-orange-700 bg-orange-200 px-2 py-1 rounded-full">Atenção</span>
            </div>
            <div class="mt-4">
                <p class="text-sm text-orange-600 font-medium">Vencidos</p>
                <p class="text-2xl font-bold text-orange-700 mt-1">
                    R$ {{ number_format($contasPagar['resumos']['vencidos'] + $contasReceber['resumos']['vencidos'], 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contas a Pagar - Card expandido -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-down text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Contas a Pagar</h3>
                        <p class="text-sm text-gray-500">Resumo das suas obrigações</p>
                    </div>
                </div>
                <a href="{{ route('contas-a-pagar.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Ver todas <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Resumos em cards pequenos -->
            <div class="grid grid-cols-4 gap-3 mb-6">
                <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                    <p class="text-xs text-red-600 font-medium mb-1">Vencidos</p>
                    <p class="text-lg font-bold text-red-700">
                        R$ {{ number_format($contasPagar['resumos']['vencidos'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-orange-50 rounded-lg p-3 border border-orange-100">
                    <p class="text-xs text-orange-600 font-medium mb-1">Vence Hoje</p>
                    <p class="text-lg font-bold text-orange-700">
                        R$ {{ number_format($contasPagar['resumos']['vence_hoje'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                    <p class="text-xs text-blue-600 font-medium mb-1">A Vencer</p>
                    <p class="text-lg font-bold text-blue-700">
                        R$ {{ number_format($contasPagar['resumos']['a_vencer'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                    <p class="text-xs text-gray-600 font-medium mb-1">Pagos</p>
                    <p class="text-lg font-bold text-gray-700">
                        R$ {{ number_format($contasPagar['resumos']['pagos'], 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Últimas movimentações -->
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Últimas Movimentações</h4>
                @forelse($contasPagar['ultimas_movimentacoes'] as $movimentacao)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-invoice-dollar text-red-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $movimentacao->entidade->nome ?? 'Sem entidade' }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $movimentacao->descricao }}</div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="font-semibold text-gray-900">
                            R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $movimentacao->vencimento->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                    <p>Nenhuma movimentação encontrada</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Contas a Receber -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Contas a Receber</h3>
                        <p class="text-sm text-gray-500">Seus recebimentos</p>
                    </div>
                </div>
                <a href="{{ route('contas-a-receber.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Ver todas <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Resumos compactos -->
            <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                    <span class="text-xs text-red-600 font-medium">Vencidos</span>
                    <span class="text-sm font-bold text-red-700">
                        R$ {{ number_format($contasReceber['resumos']['vencidos'], 2, ',', '.') }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-2 bg-orange-50 rounded-lg">
                    <span class="text-xs text-orange-600 font-medium">Vence Hoje</span>
                    <span class="text-sm font-bold text-orange-700">
                        R$ {{ number_format($contasReceber['resumos']['vence_hoje'], 2, ',', '.') }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                    <span class="text-xs text-blue-600 font-medium">A Vencer</span>
                    <span class="text-sm font-bold text-blue-700">
                        R$ {{ number_format($contasReceber['resumos']['a_vencer'], 2, ',', '.') }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                    <span class="text-xs text-gray-600 font-medium">Recebidos</span>
                    <span class="text-sm font-bold text-gray-700">
                        R$ {{ number_format($contasReceber['resumos']['pagos'], 2, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Últimas movimentações -->
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Últimas Movimentações</h4>
                @forelse($contasReceber['ultimas_movimentacoes'] as $movimentacao)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm text-gray-900 truncate">{{ $movimentacao->entidade->nome ?? 'Sem entidade' }}</div>
                        <div class="text-xs text-gray-500">{{ $movimentacao->vencimento->format('d/m/Y') }}</div>
                    </div>
                    <div class="text-right ml-2">
                        <div class="font-semibold text-sm text-green-700">
                            R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                    <p class="text-xs">Nenhuma movimentação</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Segunda linha: Fluxo de Caixa e Saldo das Contas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Fluxo de Caixa -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Fluxo de Caixa</h3>
                        <p class="text-sm text-gray-500">{{ $fluxoCaixa['periodo']['inicio'] }} a {{ $fluxoCaixa['periodo']['fim'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Gráfico ApexCharts -->
            <div id="fluxoCaixaChart" class="h-80"></div>
        </div>

        <!-- Saldo das Contas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Saldo das Contas</h3>
                        <p class="text-sm text-gray-500">Resumo bancário</p>
                    </div>
                </div>
            </div>

            <!-- Saldo total destacado -->
            <div class="mb-4 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-100">
                <p class="text-xs text-indigo-600 font-medium mb-1">Saldo Total</p>
                <p class="text-2xl font-bold {{ $saldoContas['saldo_total'] >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                    R$ {{ number_format($saldoContas['saldo_total'], 2, ',', '.') }}
                </p>
            </div>

            <!-- Lista de contas com scroll -->
            <div class="space-y-2 max-h-64 overflow-y-auto pr-2">
                @forelse($saldoContas['contas'] as $contaData)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 flex items-center justify-center">
                            @if($contaData['conta']->banco && $contaData['conta']->banco->imagem)
                                <img src="{{ asset('img/bancos/' . $contaData['conta']->banco->imagem) }}"
                                     alt="{{ $contaData['conta']->banco->nome_normalizado }}"
                                     class="w-full h-full object-contain"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400" style="display: none;">
                                    <i class="fas fa-university text-sm"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <i class="fas fa-university text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm text-gray-900 truncate">{{ $contaData['conta']->nome }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $contaData['conta']->banco->nome_normalizado ?? 'Banco' }}</div>
                        </div>
                    </div>
                    <div class="text-right ml-2 flex-shrink-0">
                        <div class="font-semibold text-sm {{ $contaData['saldo_atual'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            R$ {{ number_format($contaData['saldo_atual'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-university text-3xl text-gray-300 mb-2"></i>
                    <p class="text-xs">Nenhuma conta bancária encontrada</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados do fluxo de caixa
    const fluxoCaixaData = @json($fluxoCaixa['dados']);

    // Preparar dados para o gráfico
    const categories = fluxoCaixaData.map(item => item.data_formatada);
    const recebimentos = fluxoCaixaData.map(item => parseFloat(item.recebimentos));
    const pagamentos = fluxoCaixaData.map(item => -parseFloat(item.pagamentos)); // Negativo para baixo
    const saldo = fluxoCaixaData.map(item => parseFloat(item.saldo));

    // Configuração do gráfico
    const options = {
        series: [
            {
                name: 'Contas a Receber',
                type: 'column',
                data: recebimentos,
                color: '#10B981' // Verde
            },
            {
                name: 'Contas a Pagar',
                type: 'column',
                data: pagamentos,
                color: '#EF4444' // Vermelho
            },
            {
                name: 'Saldo Atual',
                type: 'line',
                data: saldo,
                color: '#6366F1' // Indigo
            }
        ],
        chart: {
            type: 'line',
            height: 320,
            toolbar: {
                show: false
            },
            fontFamily: 'Roboto, sans-serif'
        },
        stroke: {
            width: [0, 0, 3],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '60%',
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: categories,
            labels: {
                rotate: -45,
                style: {
                    fontSize: '12px',
                    fontFamily: 'Roboto, sans-serif'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Valores (R$)',
                style: {
                    fontFamily: 'Roboto, sans-serif'
                }
            },
            labels: {
                formatter: function(value) {
                    return 'R$ ' + value.toLocaleString('pt-BR', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                },
                style: {
                    fontFamily: 'Roboto, sans-serif'
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontFamily: 'Roboto, sans-serif'
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(value, { seriesIndex }) {
                    // Para pagamentos (índice 1), mostrar valor positivo
                    if (seriesIndex === 1) {
                        value = Math.abs(value);
                    }
                    return 'R$ ' + value.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        },
        colors: ['#10B981', '#EF4444', '#6366F1']
    };

    // Criar o gráfico
    const chart = new ApexCharts(document.querySelector("#fluxoCaixaChart"), options);
    chart.render();
});
</script>
@endpush
@endsection

