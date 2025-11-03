@extends('layouts.app')

@section('page-title', 'Dashboard Financeiro')

@section('content')
<div class="space-y-6">
    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-2">

        <!-- Contas a Pagar -->
        <div class="lg:col-span-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Contas a Pagar</h3>
                <a href="{{ route('contas-a-pagar.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Ver todas
                </a>
            </div>

            <!-- Resumos -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-sm text-gray-600">Vencidos</div>
                    <div class="text-lg font-semibold text-red-600">
                        R$ {{ number_format($contasPagar['resumos']['vencidos'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">Vence Hoje</div>
                    <div class="text-lg font-semibold text-orange-600">
                        R$ {{ number_format($contasPagar['resumos']['vence_hoje'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">A Vencer</div>
                    <div class="text-lg font-semibold text-blue-600">
                        R$ {{ number_format($contasPagar['resumos']['a_vencer'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">Pagos</div>
                    <div class="text-lg font-semibold text-gray-600">
                        R$ {{ number_format($contasPagar['resumos']['pagos'], 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Últimas movimentações -->
            <div class="space-y-3">
                @forelse($contasPagar['ultimas_movimentacoes'] as $movimentacao)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $movimentacao->entidade->nome ?? 'Sem entidade' }}</div>
                        <div class="text-sm text-gray-500">{{ $movimentacao->descricao }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">
                            R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Vence em {{ $movimentacao->vencimento->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    Nenhuma movimentação encontrada
                </div>
                @endforelse
            </div>
        </div>

        <!-- Contas a Receber -->
        <div class="lg:col-span-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Contas a Receber</h3>
                <a href="{{ route('contas-a-receber.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Ver todas
                </a>
            </div>

            <!-- Resumos -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-sm text-gray-600">Vencidos</div>
                    <div class="text-lg font-semibold text-red-600">
                        R$ {{ number_format($contasReceber['resumos']['vencidos'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">Vence Hoje</div>
                    <div class="text-lg font-semibold text-orange-600">
                        R$ {{ number_format($contasReceber['resumos']['vence_hoje'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">A Vencer</div>
                    <div class="text-lg font-semibold text-blue-600">
                        R$ {{ number_format($contasReceber['resumos']['a_vencer'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600">Recebidos</div>
                    <div class="text-lg font-semibold text-gray-600">
                        R$ {{ number_format($contasReceber['resumos']['pagos'], 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Últimas movimentações -->
            <div class="space-y-3">
                @forelse($contasReceber['ultimas_movimentacoes'] as $movimentacao)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $movimentacao->entidade->nome ?? 'Sem entidade' }}</div>
                        <div class="text-sm text-gray-500">{{ $movimentacao->descricao }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">
                            R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Vence em {{ $movimentacao->vencimento->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    Nenhuma movimentação encontrada
                </div>
                @endforelse
            </div>
        </div>

        <!-- Fluxo de Caixa -->
        <div class="lg:col-span-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Fluxo de Caixa</h3>
                <div class="text-sm text-gray-600">
                    {{ $fluxoCaixa['periodo']['inicio'] }} a {{ $fluxoCaixa['periodo']['fim'] }}
                </div>
            </div>

            <!-- Gráfico ApexCharts -->
            <div id="fluxoCaixaChart" class="h-80"></div>
        </div>

        <!-- Saldo das Contas -->
        <div class="lg:col-span-4 bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Saldo das Contas</h3>
                <div class="text-lg font-bold {{ $saldoContas['saldo_total'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Total: R$ {{ number_format($saldoContas['saldo_total'], 2, ',', '.') }}
                </div>
            </div>

            <!-- Lista de contas com scroll -->
            <div class="flex-1 overflow-y-auto max-h-80 space-y-3 pr-2">
                @forelse($saldoContas['contas'] as $contaData)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full overflow-hidden mr-3 flex-shrink-0 bg-gray-100 flex items-center justify-center">
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
                        <div>
                            <div class="font-medium text-gray-900">{{ $contaData['conta']->nome }}</div>
                            <div class="text-sm text-gray-500">{{ $contaData['conta']->banco->nome_normalizado ?? 'Banco' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold {{ $contaData['saldo_atual'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($contaData['saldo_atual'], 2, ',', '.') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    Nenhuma conta bancária encontrada
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
                color: '#3B82F6' // Azul
            }
        ],
        chart: {
            type: 'line',
            height: 320,
            toolbar: {
                show: false
            }
        },
        stroke: {
            width: [0, 0, 3],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '60%'
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
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Valores (R$)'
            },
            labels: {
                formatter: function(value) {
                    return 'R$ ' + value.toLocaleString('pt-BR', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center'
        },
        grid: {
            borderColor: '#f1f1f1'
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
        }
    };

    // Criar o gráfico
    const chart = new ApexCharts(document.querySelector("#fluxoCaixaChart"), options);
    chart.render();
});
</script>
@endpush
@endsection
