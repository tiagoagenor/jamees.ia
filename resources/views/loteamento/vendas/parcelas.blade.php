@extends('layouts.app')

@section('title', 'Parcelas da Venda - ' . ($lote->nome ?? 'Lote'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('loteamentos.vendas.index') }}" 
                   class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar para Vendas
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-list-alt text-purple-600 mr-3"></i>
                    Parcelas da Venda
                </h1>
                <p class="text-gray-600 mt-2">Detalhes das parcelas do lote {{ $lote->nome ?? 'Lote #' . substr($lote->id, 0, 8) }}</p>
            </div>
        </div>
    </div>

    <!-- Informações do Lote e Empreendimento -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Informações do Lote -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-map-pin text-green-600 mr-2"></i>
                Informações do Lote
            </h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nome:</span>
                    <span class="font-medium text-gray-900">{{ $lote->nome ?? 'Lote #' . substr($lote->id, 0, 8) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Valor:</span>
                    <span class="font-medium text-gray-900">R$ {{ number_format($lote->valor ?? 0, 2, ',', '.') }}</span>
                </div>
                @if($lote->m2)
                <div class="flex justify-between">
                    <span class="text-gray-600">Área (m²):</span>
                    <span class="font-medium text-gray-900">{{ number_format($lote->m2, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($lote->cliente)
                <div class="flex justify-between">
                    <span class="text-gray-600">Cliente:</span>
                    <span class="font-medium text-gray-900">{{ $lote->cliente->nome }}</span>
                </div>
                @endif
                @if($lote->status)
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $lote->status->cor ?? '#10b981' }}20; color: {{ $lote->status->cor ?? '#10b981' }}">
                        {{ $lote->status->nome }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Informações do Empreendimento -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-building text-blue-600 mr-2"></i>
                Informações do Empreendimento
            </h2>
            <div class="space-y-3">
                @if($lote->empreendimento)
                <div class="flex justify-between">
                    <span class="text-gray-600">Nome:</span>
                    <span class="font-medium text-gray-900">{{ $lote->empreendimento->nome }}</span>
                </div>
                @endif
                @if($lote->quadra)
                <div class="flex justify-between">
                    <span class="text-gray-600">Quadra:</span>
                    <span class="font-medium text-gray-900">{{ $lote->quadra->nome }}</span>
                </div>
                @endif
                @if($lote->empreendimento && $lote->empreendimento->valor_m2)
                <div class="flex justify-between">
                    <span class="text-gray-600">Valor por m²:</span>
                    <span class="font-medium text-gray-900">R$ {{ number_format($lote->empreendimento->valor_m2, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($lote->empreendimento && $lote->empreendimento->juros)
                <div class="flex justify-between">
                    <span class="text-gray-600">Juros:</span>
                    <span class="font-medium text-gray-900">
                        {{ $lote->empreendimento->juros_forma === 'porcentagem' ? number_format($lote->empreendimento->juros, 2, ',', '.') . '%' : 'R$ ' . number_format($lote->empreendimento->juros, 2, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabela Unificada de Parcelas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-receipt text-purple-600 mr-2"></i>
                Valores
            </h2>
        </div>
        @if($parcelas->count() > 0 || $valorEntrada > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Sem Juros</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Com Juros</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Entrada -->
                        @if($valorEntrada > 0)
                            <tr class="hover:bg-gray-50 bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <i class="fas fa-hand-holding-usd text-green-600 mr-1"></i>Entrada
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">-</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $movimentacaoEntrada ? $movimentacaoEntrada->vencimento->format('d/m/Y') : now()->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($valorEntrada, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        R$ {{ number_format($valorEntrada, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movimentacaoEntrada && $movimentacaoEntrada->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Paga
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pendente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endif

                        <!-- Parcelas Anuais -->
                        @foreach($parcelasAnuais->values() as $parcela)
                            @php
                                $movimentacao = $movimentacoes->firstWhere('numero_parcela', $parcela->numero);
                                $estaPaga = $movimentacao && $movimentacao->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i>Anual
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $parcela->numero }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $parcela->vencimento->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($parcela->valor_sem_juros, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        R$ {{ number_format($parcela->valor_com_juros, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($estaPaga)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Paga
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pendente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <!-- Parcelas Mensais -->
                        @foreach($parcelasMensais->values() as $parcela)
                            @php
                                $movimentacao = $movimentacoes->firstWhere('numero_parcela', $parcela->numero);
                                $estaPaga = $movimentacao && $movimentacao->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <i class="fas fa-calendar text-purple-600 mr-1"></i>Mensal
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $parcela->numero }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $parcela->vencimento->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($parcela->valor_sem_juros, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        R$ {{ number_format($parcela->valor_com_juros, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($estaPaga)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Paga
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pendente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                            @php
                                // Calcular totais considerando todas as parcelas (não apenas as da página atual)
                                $todasParcelas = \App\Models\LoteVendaParcela::where('lote_id', $lote->id)->get();
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                R$ {{ number_format($valorEntrada + $todasParcelas->sum('valor_sem_juros'), 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                R$ {{ number_format($valorEntrada + $todasParcelas->sum('valor_com_juros'), 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // Calcular totais considerando todas as parcelas (não apenas as da página atual)
                                    $todasParcelas = \App\Models\LoteVendaParcela::where('lote_id', $lote->id)->get();
                                    $totalPago = ($valorEntrada > 0 && $movimentacaoEntrada && $movimentacaoEntrada->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA ? 1 : 0);
                                    $totalPago += $todasParcelas->filter(function($p) use ($movimentacoes) {
                                        $m = $movimentacoes->firstWhere('numero_parcela', $p->numero);
                                        return $m && $m->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA;
                                    })->count();
                                    $totalItens = ($valorEntrada > 0 ? 1 : 0) + $todasParcelas->count();
                                @endphp
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $totalPago }}/{{ $totalItens }} pagas
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Paginação -->
            @if($parcelas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ $parcelas->firstItem() }} até {{ $parcelas->lastItem() }} de {{ $parcelas->total() }} resultados
                        </div>
                        <div class="flex items-center space-x-1 flex-wrap justify-center">
                            @php
                                $currentPage = $parcelas->currentPage();
                                $lastPage = $parcelas->lastPage();
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
                            @if ($parcelas->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Primeira página">
                                    <i class="fas fa-angle-double-left"></i>
                                </span>
                            @else
                                <a href="{{ $parcelas->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Primeira página">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            @endif

                            {{-- Botão Página Anterior --}}
                            @if ($parcelas->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $parcelas->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Primeira página --}}
                            @if ($start > 1)
                                <a href="{{ $parcelas->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">1</a>
                                @if ($start > 2)
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                @endif
                            @endif

                            {{-- Páginas do range --}}
                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $parcelas->url($page) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                                @endif
                            @endfor

                            {{-- Última página --}}
                            @if ($end < $lastPage)
                                @if ($end < $lastPage - 1)
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                @endif
                                <a href="{{ $parcelas->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $lastPage }}</a>
                            @endif

                            {{-- Botão Próxima Página --}}
                            @if ($parcelas->hasMorePages())
                                <a href="{{ $parcelas->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif

                            {{-- Botão Última Página --}}
                            @if ($parcelas->currentPage() == $lastPage)
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Última página">
                                    <i class="fas fa-angle-double-right"></i>
                                </span>
                            @else
                                <a href="{{ $parcelas->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Última página">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            @endif
                            
                            {{-- Input para ir direto a uma página (opcional, mas útil para muitas páginas) --}}
                            @if ($lastPage > 10)
                                <div class="flex items-center space-x-2 ml-4 pl-4 border-l border-gray-300">
                                    <span class="text-sm text-gray-600">Ir para:</span>
                                    <form method="GET" action="{{ request()->url() }}" class="flex items-center space-x-1">
                                        @foreach(request()->except('page') as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <input type="number" 
                                               name="page" 
                                               min="1" 
                                               max="{{ $lastPage }}" 
                                               value="{{ $currentPage }}"
                                               class="w-16 px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               onchange="this.form.submit()">
                                        <button type="submit" class="px-3 py-1 text-sm text-white bg-blue-600 border border-blue-600 rounded-md hover:bg-blue-700 hover:border-blue-700">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fas fa-receipt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhuma parcela encontrada</h3>
                <p class="text-gray-600">Não há parcelas registradas para esta venda.</p>
            </div>
        @endif
    </div>

    <!-- Resumo Geral -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-calculator text-indigo-600 mr-2"></i>
                Resumo Geral
            </h2>
        </div>
        <div class="px-6 py-4">
            @php
                // Calcular totais considerando todas as parcelas (não apenas as da página atual)
                $todasParcelas = \App\Models\LoteVendaParcela::where('lote_id', $lote->id)->get();
                // Contar parcelas pagas
                $totalPago = ($valorEntrada > 0 && $movimentacaoEntrada && $movimentacaoEntrada->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA ? 1 : 0);
                $totalPago += $todasParcelas->filter(function($p) use ($movimentacoes) {
                    $m = $movimentacoes->firstWhere('numero_parcela', $p->numero);
                    return $m && $m->situacao === \App\Enums\MovimentacaoSituacaoEnum::PAGA;
                })->count();
                $totalItens = ($valorEntrada > 0 ? 1 : 0) + $todasParcelas->count();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Parcelas Pagas</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $totalPago }}/{{ $totalItens }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Entrada</p>
                    <p class="text-2xl font-bold text-green-600">
                        R$ {{ number_format($valorEntrada, 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Parcelas (Sem Juros)</p>
                    <p class="text-2xl font-bold text-gray-900">
                        R$ {{ number_format($todasParcelas->sum('valor_sem_juros'), 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Parcelas (Com Juros)</p>
                    <p class="text-2xl font-bold text-green-600">
                        R$ {{ number_format($todasParcelas->sum('valor_com_juros'), 2, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600 mb-1">Valor Total da Venda</p>
                <p class="text-3xl font-bold text-indigo-600">
                    R$ {{ number_format($valorEntrada + $todasParcelas->sum('valor_com_juros'), 2, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

