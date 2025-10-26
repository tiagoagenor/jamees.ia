@extends('layouts.app')

@section('title', 'Detalhes do Plano')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('planos.index') }}"
               class="text-blue-600 hover:text-blue-800 mr-4">
                ← Voltar para Planos
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $plano->nome }}</h1>
        <p class="mt-2 text-gray-600">{{ $plano->descricao }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detalhes do Plano -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes do Plano</h2>

                <!-- Preços por Período -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Mensal</div>
                        <div class="text-lg font-bold text-gray-900">
                            R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500">Sem desconto</div>
                    </div>

                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Trimestral</div>
                        <div class="text-lg font-bold text-gray-900">
                            R$ {{ number_format($plano->preco_trimestral, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-green-600">5% desconto</div>
                    </div>

                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Semestral</div>
                        <div class="text-lg font-bold text-gray-900">
                            R$ {{ number_format($plano->preco_semestral, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-green-600">10% desconto</div>
                    </div>

                    <div class="text-center p-4 border border-blue-200 bg-blue-50 rounded-lg">
                        <div class="text-sm text-blue-600 mb-1 font-medium">Anual</div>
                        <div class="text-lg font-bold text-blue-900">
                            R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-green-600 font-medium">20% desconto</div>
                    </div>
                </div>

                <!-- Limites -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Limites</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Usuários:</span>
                                <span class="font-medium">{{ $plano->limite_usuarios ?? 'Ilimitado' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Empresas:</span>
                                <span class="font-medium">{{ $plano->limite_empresas ?? 'Ilimitado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Funcionalidades -->
                @if($plano->funcionalidades)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Funcionalidades Incluídas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($plano->funcionalidades as $funcionalidade)
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $funcionalidade }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Seleção de Período e Ativação -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Ativar Plano</h2>

                @if($planoAtual && $planoAtual->plano_id === $plano->id)
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-green-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Plano Ativo</h3>
                        <p class="text-gray-600 mb-4">Este é seu plano atual</p>
                        <a href="{{ route('planos.index') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Voltar para Planos
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('planos.ativar', $plano) }}">
                        @csrf

                        <!-- Seleção de Período -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Escolha o Período</label>
                            <div class="space-y-3">
                                @foreach($periodos as $periodo)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50
                                        @if($periodo->value === 'anual') border-blue-200 bg-blue-50 @endif">
                                        <input type="radio"
                                               name="periodo"
                                               value="{{ $periodo->value }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                               @if($periodo->value === 'anual') checked @endif>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-900">{{ $periodo->getLabel() }}</span>
                                                <span class="text-sm font-bold text-gray-900">
                                                    R$ {{ number_format($plano->getPrecoPorPeriodo($periodo->value), 2, ',', '.') }}
                                                </span>
                                            </div>
                                            @if($periodo->getDiscount() > 0)
                                                <div class="text-xs text-green-600">
                                                    {{ $periodo->getDiscount() * 100 }}% de desconto
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Resumo do Preço -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Período selecionado:</span>
                                <span class="text-sm font-medium text-gray-900" id="periodo-selecionado">Anual</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Preço base:</span>
                                <span class="text-sm text-gray-900" id="preco-base">R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Desconto:</span>
                                <span class="text-sm text-green-600" id="desconto">20%</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-lg font-bold text-gray-900" id="preco-total">R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Botão de Ativação -->
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium mb-4">
                            @if($planoAtual && $planoAtual->status->value === 'teste')
                                Ativar Plano (Sem Custo)
                            @else
                                Ativar Plano (Sem Custo)
                            @endif
                        </button>

                        <p class="text-xs text-gray-500 text-center">
                            * Este é um sistema de demonstração. Não há cobrança real.
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!$planoAtual || $planoAtual->plano_id !== $plano->id)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados dos períodos
    const periodos = {
        'mensal': { label: 'Mensal', discount: 0 },
        'trimestral': { label: 'Trimestral', discount: 5 },
        'semestral': { label: 'Semestral', discount: 10 },
        'anual': { label: 'Anual', discount: 20 }
    };

    // Dados do plano
    const plano = {
        preco_mensal: {{ $plano->preco_mensal }},
        preco_trimestral: {{ $plano->preco_trimestral }},
        preco_semestral: {{ $plano->preco_semestral }},
        preco_anual: {{ $plano->preco_anual }}
    };

    console.log('Dados carregados:', { periodos, plano });

    // Elementos DOM
    const radioButtons = document.querySelectorAll('input[name="periodo"]');
    const periodoSelecionado = document.getElementById('periodo-selecionado');
    const precoBase = document.getElementById('preco-base');
    const desconto = document.getElementById('desconto');
    const precoTotal = document.getElementById('preco-total');

    function updatePrice() {
        const selectedPeriodo = document.querySelector('input[name="periodo"]:checked').value;
        const periodoData = periodos[selectedPeriodo];
        const preco = plano[`preco_${selectedPeriodo}`];

        console.log('Atualizando preço:', {
            periodo: selectedPeriodo,
            label: periodoData.label,
            preco: preco,
            desconto: periodoData.discount
        });

        // Atualizar elementos
        periodoSelecionado.textContent = periodoData.label;
        precoBase.textContent = `R$ ${preco.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        desconto.textContent = `${periodoData.discount}%`;
        precoTotal.textContent = `R$ ${preco.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    }

    // Adicionar event listeners
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Período alterado para:', this.value);
            updatePrice();
        });
    });

    // Inicializar com o valor padrão (anual)
    console.log('Inicializando com período padrão...');
    updatePrice();
});
</script>
@endif
@endsection
