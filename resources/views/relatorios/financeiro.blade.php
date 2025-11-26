@extends('layouts.app')

@section('title', 'Relatórios - Financeiro')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-dollar-sign text-blue-600 mr-3"></i>
                    Relatórios - Financeiro
                </h1>
                <p class="text-gray-600 mt-2">Visualize e exporte relatórios financeiros detalhados</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('relatorios.financeiro') }}" class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtros
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Movimentação</label>
                <select name="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ $filtroTipo == '1' ? 'selected' : '' }}>Contas a Pagar</option>
                    <option value="2" {{ $filtroTipo == '2' ? 'selected' : '' }}>Contas a Receber</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Situação</label>
                <select name="situacao" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="1" {{ $filtroSituacao == '1' ? 'selected' : '' }}>Pendente</option>
                    <option value="2" {{ $filtroSituacao == '2' ? 'selected' : '' }}>Paga</option>
                    <option value="3" {{ $filtroSituacao == '3' ? 'selected' : '' }}>Vencida</option>
                    <option value="4" {{ $filtroSituacao == '4' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                <input type="date" name="vencimento_inicio" id="vencimento_inicio" value="{{ $filtroVencimentoInicio }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-red-600 mt-1 hidden" id="erro-data-inicio"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                <input type="date" name="vencimento_fim" id="vencimento_fim" value="{{ $filtroVencimentoFim }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-red-600 mt-1 hidden" id="erro-data-fim"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Valor Mínimo</label>
                <input type="number" step="0.01" name="valor_minimo" value="{{ $filtroValorMinimo }}" placeholder="0,00" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Valor Máximo</label>
                <input type="number" step="0.01" name="valor_maximo" value="{{ $filtroValorMaximo }}" placeholder="0,00" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Conta Bancária</label>
                <select name="conta_bancaria" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    @foreach($contasBancarias as $conta)
                        <option value="{{ $conta->id }}" {{ $filtroContaBancaria == $conta->id ? 'selected' : '' }}>
                            {{ $conta->nome }} - {{ $conta->banco->nome ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            <a href="{{ route('relatorios.financeiro') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Limpar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>
                Filtrar
            </button>
        </div>
    </form>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total a Pagar</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">R$ {{ number_format($totalPagar, 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total a Receber</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">R$ {{ number_format($totalReceber, 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Saldo</p>
                    <p class="text-2xl font-bold {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">R$ {{ number_format($saldo, 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-balance-scale text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total de Movimentações</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalMovimentacoes, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6 relative">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Distribuição por Situação
                </h3>
                <button onclick="abrirModalGrafico('distribuicao')" class="text-gray-500 hover:text-blue-600 transition-colors" title="Expandir gráfico">
                    <i class="fas fa-expand text-lg"></i>
                </button>
            </div>
            <div class="h-64">
                <canvas id="graficoDistribuicao"></canvas>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 relative">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Movimentações por Mês
                </h3>
                <button onclick="abrirModalGrafico('mensal')" class="text-gray-500 hover:text-blue-600 transition-colors" title="Expandir gráfico">
                    <i class="fas fa-expand text-lg"></i>
                </button>
            </div>
            <div class="h-80">
                <canvas id="graficoMensal"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal para Gráfico de Distribuição -->
    <div id="modalDistribuicao" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Distribuição por Situação
                </h3>
                <button onclick="fecharModalGrafico('distribuicao')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="h-96">
                    <canvas id="graficoDistribuicaoModal"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Gráfico Mensal -->
    <div id="modalMensal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Movimentações por Mês
                </h3>
                <button onclick="fecharModalGrafico('mensal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="h-96">
                    <canvas id="graficoMensalModal"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Resultados -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Resultados
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('relatorios.financeiro.exportar-csv', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm inline-flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar Excel
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Situação</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conta Bancária</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($movimentacoes as $movimentacao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($movimentacao->tipo->value == 1)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        Contas a Pagar
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        Contas a Receber
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $movimentacao->descricao }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $movimentacao->vencimento ? \Carbon\Carbon::parse($movimentacao->vencimento)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium {{ $movimentacao->tipo->value == 1 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $movimentacao->tipo->value == 1 ? '-' : '+' }}R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($movimentacao->situacao->value == 1)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendente
                                    </span>
                                @elseif($movimentacao->situacao->value == 2)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paga
                                    </span>
                                @elseif($movimentacao->situacao->value == 3)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Vencida
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Cancelada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $movimentacao->contaEmpresa ? $movimentacao->contaEmpresa->nome : 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Nenhum resultado encontrado</p>
                                <p class="text-sm mt-2">Aplique os filtros para visualizar os relatórios</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        @if($movimentacoes->hasPages())
            <div class="mt-6 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando {{ $movimentacoes->firstItem() }} até {{ $movimentacoes->lastItem() }} de {{ $movimentacoes->total() }} resultados
                    </div>
                    <div class="flex items-center space-x-1 flex-wrap justify-center">
                        @php
                            $currentPage = $movimentacoes->currentPage();
                            $lastPage = $movimentacoes->lastPage();
                            $onEachSide = 2; // Número de páginas a mostrar de cada lado da página atual
                            
                            // Calcular o range de páginas a mostrar
                            $start = max(1, $currentPage - $onEachSide);
                            $end = min($lastPage, $currentPage + $onEachSide);
                            
                            // Ajustar se estiver muito perto do início ou fim
                            if ($start == 1) {
                                $end = min($lastPage, $start + ($onEachSide * 2) + 1);
                            }
                            if ($end == $lastPage) {
                                $start = max(1, $end - ($onEachSide * 2) - 1);
                            }
                        @endphp
                        
                        {{-- Botão Primeira Página --}}
                        @if ($movimentacoes->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Primeira página">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $movimentacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Primeira página">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        {{-- Botão Página Anterior --}}
                        @if ($movimentacoes->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $movimentacoes->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Primeira página --}}
                        @if ($start > 1)
                            <a href="{{ $movimentacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">1</a>
                            @if ($start > 2)
                                <span class="px-3 py-2 text-sm text-gray-500">...</span>
                            @endif
                        @endif

                        {{-- Páginas do range --}}
                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $movimentacoes->url($page) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Última página --}}
                        @if ($end < $lastPage)
                            @if ($end < $lastPage - 1)
                                <span class="px-3 py-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $movimentacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $lastPage }}</a>
                        @endif

                        {{-- Botão Próxima Página --}}
                        @if ($movimentacoes->hasMorePages())
                            <a href="{{ $movimentacoes->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        {{-- Botão Última Página --}}
                        @if ($movimentacoes->currentPage() == $lastPage)
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Última página">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @else
                            <a href="{{ $movimentacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Última página">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Mensagens de erro/sucesso -->
@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50" id="mensagem-erro">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('mensagem-erro').remove()" class="ml-4 text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(function() {
            const msg = document.getElementById('mensagem-erro');
            if (msg) msg.remove();
        }, 5000);
    </script>
@endif

<!-- Validação de período máximo de 2 anos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataInicio = document.getElementById('vencimento_inicio');
    const dataFim = document.getElementById('vencimento_fim');
    const erroDataInicio = document.getElementById('erro-data-inicio');
    const erroDataFim = document.getElementById('erro-data-fim');
    const form = dataInicio ? dataInicio.closest('form') : null;

    if (!dataInicio || !dataFim || !form) return;

    function validarPeriodo() {
        // Limpar erros anteriores
        erroDataInicio.classList.add('hidden');
        erroDataFim.classList.add('hidden');
        dataInicio.classList.remove('border-red-500');
        dataFim.classList.remove('border-red-500');

        if (!dataInicio.value || !dataFim.value) {
            return true;
        }

        const inicio = new Date(dataInicio.value);
        const fim = new Date(dataFim.value);

        // Verificar se data início é maior que data fim
        if (inicio > fim) {
            erroDataInicio.textContent = 'A data de início não pode ser maior que a data de fim.';
            erroDataInicio.classList.remove('hidden');
            dataInicio.classList.add('border-red-500');
            dataFim.classList.add('border-red-500');
            return false;
        }

        // Calcular diferença em anos
        const diffTime = Math.abs(fim - inicio);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const diffYears = diffDays / 365.25;

        if (diffYears > 2) {
            erroDataFim.textContent = 'O período selecionado não pode ser maior que 2 anos.';
            erroDataFim.classList.remove('hidden');
            dataInicio.classList.add('border-red-500');
            dataFim.classList.add('border-red-500');
            return false;
        }

        return true;
    }

    function atualizarDataLimite() {
        if (dataInicio.value) {
            const inicio = new Date(dataInicio.value);
            const dataMaxima = new Date(inicio);
            dataMaxima.setFullYear(inicio.getFullYear() + 2);
            
            // Formatar para YYYY-MM-DD
            const anoMaximo = dataMaxima.getFullYear();
            const mesMaximo = String(dataMaxima.getMonth() + 1).padStart(2, '0');
            const diaMaximo = String(dataMaxima.getDate()).padStart(2, '0');
            const dataMaximaFormatada = `${anoMaximo}-${mesMaximo}-${diaMaximo}`;
            
            dataFim.setAttribute('max', dataMaximaFormatada);
            dataFim.setAttribute('min', dataInicio.value);
        } else {
            dataFim.removeAttribute('max');
            dataFim.removeAttribute('min');
        }

        if (dataFim.value) {
            const fim = new Date(dataFim.value);
            const dataMinima = new Date(fim);
            dataMinima.setFullYear(fim.getFullYear() - 2);
            
            // Formatar para YYYY-MM-DD
            const anoMinimo = dataMinima.getFullYear();
            const mesMinimo = String(dataMinima.getMonth() + 1).padStart(2, '0');
            const diaMinimo = String(dataMinima.getDate()).padStart(2, '0');
            const dataMinimaFormatada = `${anoMinimo}-${mesMinimo}-${diaMinimo}`;
            
            dataInicio.setAttribute('max', dataFim.value);
            dataInicio.setAttribute('min', dataMinimaFormatada);
        } else {
            dataInicio.removeAttribute('max');
        }
    }

    dataInicio.addEventListener('change', function() {
        atualizarDataLimite();
        validarPeriodo();
    });

    dataFim.addEventListener('change', function() {
        atualizarDataLimite();
        validarPeriodo();
    });

    form.addEventListener('submit', function(e) {
        if (!validarPeriodo()) {
            e.preventDefault();
            return false;
        }
    });

    // Inicializar limites ao carregar a página
    atualizarDataLimite();
    validarPeriodo();
});
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados para os gráficos
    const distribuicaoSituacao = @json($distribuicaoSituacao);
    const labelsMensais = @json($labelsMensais);
    const valoresPagar = @json($valoresPagar);
    const valoresReceber = @json($valoresReceber);

    // Cores para as situações
    const coresSituacao = {
        'Pendente': '#fbbf24', // yellow
        'Paga': '#10b981', // green
        'Vencida': '#ef4444', // red
        'Cancelada': '#6b7280' // gray
    };

    // Função para criar gráfico de distribuição
    function criarGraficoDistribuicao(canvasId, tamanhoFonte = 12) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const labels = Object.keys(distribuicaoSituacao);
        const dados = Object.values(distribuicaoSituacao);
        const cores = labels.map(label => coresSituacao[label] || '#9ca3af');

        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: dados,
                    backgroundColor: cores,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: tamanhoFonte
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Distribuição por Situação (Pizza) - Original
    let chartDistribuicao = criarGraficoDistribuicao('graficoDistribuicao', 12);
    let chartDistribuicaoModal = null;

    // Função para criar gráfico mensal
    function criarGraficoMensal(canvasId, tamanhoFonte = 12) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsMensais,
                datasets: [
                    {
                        label: 'Contas a Pagar',
                        data: valoresPagar,
                        backgroundColor: valoresPagar.map(valor => valor === 0 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(239, 68, 68, 0.6)'), // Vermelho mais transparente para 0
                        borderColor: valoresPagar.map(valor => valor === 0 ? 'rgba(239, 68, 68, 0.3)' : 'rgba(239, 68, 68, 1)'),
                        borderWidth: 1
                    },
                    {
                        label: 'Contas a Receber',
                        data: valoresReceber,
                        backgroundColor: valoresReceber.map(valor => valor === 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(16, 185, 129, 0.6)'), // Verde mais transparente para 0
                        borderColor: valoresReceber.map(valor => valor === 0 ? 'rgba(16, 185, 129, 0.3)' : 'rgba(16, 185, 129, 1)'),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: tamanhoFonte
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        filter: function(tooltipItem) {
                            // Sempre mostrar tooltip, mesmo para valores 0
                            return true;
                        },
                        callbacks: {
                            label: function(context) {
                                const valor = context.parsed.y;
                                // Sempre mostrar o valor, mesmo se for 0
                                return context.dataset.label + ': R$ ' + valor.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: false, // Barras lado a lado, não empilhadas
                        ticks: {
                            font: {
                                size: tamanhoFonte - 1
                            },
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            },
                            stepSize: null // Permitir que o Chart.js calcule automaticamente os intervalos
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: tamanhoFonte - 1
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Movimentações por Mês (Barras) - Original
    let chartMensal = criarGraficoMensal('graficoMensal', 12);
    let chartMensalModal = null;

    // Funções para abrir e fechar modais
    window.abrirModalGrafico = function(tipo) {
        const modalId = tipo === 'distribuicao' ? 'modalDistribuicao' : 'modalMensal';
        const canvasId = tipo === 'distribuicao' ? 'graficoDistribuicaoModal' : 'graficoMensalModal';
        const modal = document.getElementById(modalId);
        
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Criar gráfico no modal se ainda não foi criado
            setTimeout(function() {
                if (tipo === 'distribuicao' && !chartDistribuicaoModal) {
                    chartDistribuicaoModal = criarGraficoDistribuicao(canvasId, 16);
                } else if (tipo === 'mensal' && !chartMensalModal) {
                    chartMensalModal = criarGraficoMensal(canvasId, 16);
                } else {
                    // Atualizar gráfico existente
                    if (tipo === 'distribuicao' && chartDistribuicaoModal) {
                        chartDistribuicaoModal.update();
                    } else if (tipo === 'mensal' && chartMensalModal) {
                        chartMensalModal.update();
                    }
                }
            }, 100);
        }
    };

    window.fecharModalGrafico = function(tipo) {
        const modalId = tipo === 'distribuicao' ? 'modalDistribuicao' : 'modalMensal';
        const modal = document.getElementById(modalId);
        
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    // Fechar modal ao clicar fora
    document.getElementById('modalDistribuicao')?.addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalGrafico('distribuicao');
        }
    });

    document.getElementById('modalMensal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalGrafico('mensal');
        }
    });
});
</script>
@endsection

