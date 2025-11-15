@extends('layouts.app')

@section('title', 'Contratar Plano')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Wizard Steps -->
    <div id="wizard-steps-container">
        <x-wizard-steps :currentStep="1" :totalSteps="4" />
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('planos.index') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para Planos
            </a>
        </div>
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $plano->nome }}</h1>
            <p class="text-lg text-gray-600">{{ $plano->descricao }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('planos.ativar', $plano) }}" id="contratarForm">
        @csrf
        
        <!-- PASSO 1: Selecionar Período -->
        <div id="step-1" class="step-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Escolha o Período</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @foreach($periodos as $periodo)
                                <label class="periodo-option text-center p-5 border-2 rounded-xl cursor-pointer transition-all duration-200
                                    @if($periodo->value === 'anual') border-blue-500 bg-gradient-to-br from-blue-50 to-blue-100 shadow-md
                                    @else border-gray-200 bg-white hover:border-gray-300
                                    @endif">
                                    <input type="radio" name="periodo" value="{{ $periodo->value }}" 
                                           class="hidden" 
                                           data-preco="{{ $plano->getPrecoPorPeriodo($periodo->value) }}"
                                           data-label="{{ $periodo->getLabel() }}"
                                           data-desconto="{{ $periodo->getDiscount() * 100 }}"
                                           @if($periodo->value === 'anual') checked @endif>
                                    <div class="text-sm font-medium text-gray-500 mb-2">{{ $periodo->getLabel() }}</div>
                                    <div class="text-xl font-bold text-gray-900 mb-1">
                                        R$ {{ number_format($plano->getPrecoPorPeriodo($periodo->value), 2, ',', '.') }}
                                    </div>
                                    @if($periodo->getDiscount() > 0)
                                        <div class="text-xs font-medium text-green-600">{{ $periodo->getDiscount() * 100 }}% desconto</div>
                                    @else
                                        <div class="text-xs text-gray-500">Sem desconto</div>
                                    @endif
                                    @if($periodo->value === 'anual')
                                        <div class="mt-2 text-xs font-bold text-blue-600">RECOMENDADO</div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo</h2>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Plano:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $plano->nome }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Período:</span>
                                <span class="text-sm font-medium text-gray-900" id="resumo-periodo">Anual</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Preço base:</span>
                                <span class="text-sm font-medium text-gray-900" id="resumo-preco-base">R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-blue-600" id="resumo-total-step1">R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASSO 2: Configurar -->
        <div id="step-2" class="step-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Personalize seu Plano</h2>

                        <!-- Usuários Extras -->
                        <div class="mb-8">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Usuários</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Plano inclui: <strong>{{ $plano->limite_usuarios ?? 'Ilimitado' }} usuários</strong>
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adicionar usuários extras
                                </label>
                                <div class="flex items-center space-x-4">
                                    <button type="button" onclick="decrementar('usuarios_extras')"
                                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" id="usuarios_extras" name="usuarios_extras" value="0" min="0"
                                           class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium">
                                    <button type="button" onclick="incrementar('usuarios_extras')"
                                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                    <div class="flex-1 ml-4">
                                        <p class="text-sm text-gray-600">
                                            <span id="valor-usuarios-extras">R$ 0,00</span>
                                            <span class="text-gray-500"> (R$ 10,00 por usuário)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empresas Extras -->
                        <div class="mb-8">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Empresas</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Plano inclui: <strong>{{ $plano->limite_empresas ?? 'Ilimitado' }} empresas</strong>
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adicionar empresas extras
                                </label>
                                <div class="flex items-center space-x-4">
                                    <button type="button" onclick="decrementar('empresas_extras')"
                                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" id="empresas_extras" name="empresas_extras" value="0" min="0"
                                           class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium">
                                    <button type="button" onclick="incrementar('empresas_extras')"
                                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                    <div class="flex-1 ml-4">
                                        <p class="text-sm text-gray-600">
                                            <span id="valor-empresas-extras">R$ 0,00</span>
                                            <span class="text-gray-500"> (R$ 50,00 por empresa)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo</h2>
                        <div class="space-y-3 mb-6" id="resumo-step2"></div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-blue-600" id="resumo-total-step2">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASSO 3: Aplicativos -->
        <div id="step-3" class="step-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Aplicativos Disponíveis</h2>
                        <p class="text-sm text-gray-600 mb-6">Escolha os aplicativos que deseja incluir (opcional)</p>

                        @if($aplicativos->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="aplicativos-container">
                                @foreach($aplicativos as $aplicativo)
                                    <div class="aplicativo-card relative border-2 rounded-xl p-6 cursor-pointer transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1 border-gray-200 bg-white hover:border-blue-300"
                                         onclick="toggleAplicativo(event, this, '{{ $aplicativo->id }}')">
                                        <input type="checkbox" name="aplicativos[]" value="{{ $aplicativo->id }}"
                                               class="absolute top-4 right-4 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer hidden"
                                               data-preco="{{ $aplicativo->getPrecoPorPeriodo('anual') }}"
                                               onchange="atualizarCheckboxAplicativo(this); calcularTotalStep3();">
                                        
                                        <div class="text-center mb-4">
                                            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                                                @if($aplicativo->codigo === 'loteamento')
                                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900">{{ $aplicativo->nome }}</h3>
                                        </div>

                                        <div class="text-center mb-4">
                                            <div class="text-3xl font-bold text-blue-600 mb-1" data-preco-display>
                                                R$ {{ number_format($aplicativo->getPrecoPorPeriodo('anual'), 2, ',', '.') }}
                                            </div>
                                            <div class="text-sm text-gray-500 font-medium" data-periodo-display>/anual</div>
                                        </div>

                                        @if($aplicativo->descricao)
                                            <button type="button" onclick="toggleDescricao(this); event.stopPropagation();"
                                                    class="w-full mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center transition-colors">
                                                <span class="descricao-texto">Ver descrição</span>
                                                <svg class="w-4 h-4 ml-1 descricao-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div class="aplicativo-descricao hidden mt-4 pt-4 border-t border-gray-200">
                                                <p class="text-sm text-gray-600">{{ $aplicativo->descricao }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Nenhum aplicativo disponível no momento.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo</h2>
                        <div class="space-y-3 mb-6" id="resumo-step3"></div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-blue-600" id="resumo-total-step3">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASSO 4: Pagamento -->
        <div id="step-4" class="step-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Dados de Pagamento</h2>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Método de Pagamento</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-blue-200 bg-blue-50 rounded-lg cursor-pointer">
                                    <input type="radio" name="metodo_pagamento" value="cartao" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center">
                                            <svg class="h-6 w-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-900">Cartão de Crédito</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="metodo_pagamento" value="boleto" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3 flex-1">
                                        <span class="font-medium text-gray-900">Boleto Bancário</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="dados-cartao" class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dados do Cartão</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número do Cartão</label>
                                    <input type="text" name="numero_cartao" placeholder="0000 0000 0000 0000" maxlength="19"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome no Cartão</label>
                                    <input type="text" name="nome_cartao" placeholder="Nome completo"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Validade</label>
                                        <input type="text" name="validade_cartao" placeholder="MM/AA" maxlength="5"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                        <input type="text" name="cvv_cartao" placeholder="123" maxlength="4"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo Final</h2>
                        <div class="space-y-3 mb-6" id="resumo-step4"></div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-green-600" id="resumo-total-step4">R$ 0,00</span>
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg text-base font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center mt-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Finalizar Pagamento</span>
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-4">
                            * Esta é uma tela de demonstração. O pagamento não será processado.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Navegação -->
        <div class="flex justify-between items-center mt-8 bg-white rounded-lg shadow-md p-6">
            <button type="button" id="btn-voltar" onclick="voltarPasso()" 
                    class="hidden inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </button>
            <div class="flex-1"></div>
            <button type="button" id="btn-proximo" onclick="proximoPasso()"
                    class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <span id="btn-proximo-texto">Continuar</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;
const precoUsuarioAdicional = 10.00;
const precoEmpresaAdicional = 50.00;

// Dados do plano
const plano = {
    preco_mensal: {{ $plano->preco_mensal }},
    preco_trimestral: {{ $plano->preco_trimestral }},
    preco_semestral: {{ $plano->preco_semestral }},
    preco_anual: {{ $plano->preco_anual }}
};

// Dados dos aplicativos
const aplicativos = @json($aplicativosData ?? []);

function atualizarWizard(step) {
    // Atualizar indicador visual do passo atual
    currentStep = step;
    
    // Atualizar wizard steps visualmente
    const wizardContainer = document.getElementById('wizard-steps-container');
    if (wizardContainer) {
        // Atualizar os círculos, linhas e labels do wizard
        const stepContainers = wizardContainer.querySelectorAll('[class*="flex flex-col items-center"]');
        const lines = wizardContainer.querySelectorAll('[class*="h-1"]');
        
        const stepLabels = ['Selecionar Período', 'Configurar', 'Aplicativos', 'Pagamento'];
        
        stepContainers.forEach((container, idx) => {
            const stepNum = idx + 1;
            const stepCircle = container.querySelector('[class*="rounded-full"]');
            const stepLabel = container.querySelector('span');
            
            if (stepNum < step) {
                // Passo completo
                stepCircle.className = 'w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold shadow-lg transition-all duration-300';
                stepCircle.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                if (stepLabel) {
                    stepLabel.className = 'mt-2 text-xs font-medium text-green-600';
                    stepLabel.textContent = stepLabels[idx];
                }
            } else if (stepNum === step) {
                // Passo atual
                stepCircle.className = 'w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold shadow-lg ring-4 ring-blue-200 transition-all duration-300';
                stepCircle.textContent = stepNum;
                if (stepLabel) {
                    stepLabel.className = 'mt-2 text-xs font-medium text-blue-600';
                    stepLabel.textContent = stepLabels[idx];
                }
            } else {
                // Passo futuro
                stepCircle.className = 'w-12 h-12 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-semibold transition-all duration-300';
                stepCircle.textContent = stepNum;
                if (stepLabel) {
                    stepLabel.className = 'mt-2 text-xs font-medium text-gray-400';
                    stepLabel.textContent = stepLabels[idx];
                }
            }
        });
        
        lines.forEach((line, idx) => {
            if (idx + 1 < step) {
                line.className = 'flex-1 h-1 mx-3 transition-all duration-300 bg-green-500';
            } else {
                line.className = 'flex-1 h-1 mx-3 transition-all duration-300 bg-gray-200';
            }
        });
    }

    // Mostrar/ocultar passos
    for (let i = 1; i <= totalSteps; i++) {
        const stepEl = document.getElementById(`step-${i}`);
        if (i === step) {
            stepEl.classList.remove('hidden');
        } else {
            stepEl.classList.add('hidden');
        }
    }

    // Atualizar botões
    const btnVoltar = document.getElementById('btn-voltar');
    const btnProximo = document.getElementById('btn-proximo');
    const btnProximoTexto = document.getElementById('btn-proximo-texto');

    if (step === 1) {
        btnVoltar.classList.add('hidden');
    } else {
        btnVoltar.classList.remove('hidden');
    }

    if (step === totalSteps) {
        btnProximo.classList.add('hidden');
    } else {
        btnProximo.classList.remove('hidden');
        btnProximoTexto.textContent = step === totalSteps - 1 ? 'Finalizar' : 'Continuar';
    }

    // Atualizar resumos
    atualizarResumos();
}

function proximoPasso() {
    if (validarPasso(currentStep)) {
        if (currentStep < totalSteps) {
            currentStep++;
            atualizarWizard(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}

function voltarPasso() {
    if (currentStep > 1) {
        currentStep--;
        atualizarWizard(currentStep);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function validarPasso(step) {
    if (step === 1) {
        const periodo = document.querySelector('input[name="periodo"]:checked');
        if (!periodo) {
            alert('Por favor, selecione um período.');
            return false;
        }
    }
    return true;
}

function incrementar(campo) {
    const input = document.getElementById(campo);
    input.value = parseInt(input.value) + 1;
    calcularTotalStep2();
}

function decrementar(campo) {
    const input = document.getElementById(campo);
    if (parseInt(input.value) > 0) {
        input.value = parseInt(input.value) - 1;
        calcularTotalStep2();
    }
}

function calcularTotalStep1() {
    const periodo = document.querySelector('input[name="periodo"]:checked');
    if (!periodo) return;

    const periodoValue = periodo.value;
    const preco = plano[`preco_${periodoValue}`];
    const label = periodo.dataset.label;
    const desconto = periodo.dataset.desconto;

    document.getElementById('resumo-periodo').textContent = label;
    document.getElementById('resumo-preco-base').textContent = formatarMoeda(preco);
    document.getElementById('resumo-total-step1').textContent = formatarMoeda(preco);

    // Atualizar preços dos aplicativos
    atualizarPrecosAplicativos(periodoValue);
}

function calcularTotalStep2() {
    const periodo = document.querySelector('input[name="periodo"]:checked');
    if (!periodo) return;

    const periodoValue = periodo.value;
    const precoBase = plano[`preco_${periodoValue}`];
    const usuariosExtras = parseInt(document.getElementById('usuarios_extras').value) || 0;
    const empresasExtras = parseInt(document.getElementById('empresas_extras').value) || 0;

    const valorUsuariosExtras = usuariosExtras * precoUsuarioAdicional;
    const valorEmpresasExtras = empresasExtras * precoEmpresaAdicional;
    const valorTotal = precoBase + valorUsuariosExtras + valorEmpresasExtras;

    // Atualizar resumo
    const resumo = document.getElementById('resumo-step2');
    resumo.innerHTML = `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Plano:</span>
            <span class="text-sm font-medium text-gray-900">{{ $plano->nome }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Período:</span>
            <span class="text-sm font-medium text-gray-900">${periodo.dataset.label}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Preço base:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(precoBase)}</span>
        </div>
        ${usuariosExtras > 0 ? `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Usuários extras:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(valorUsuariosExtras)}</span>
        </div>
        ` : ''}
        ${empresasExtras > 0 ? `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Empresas extras:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(valorEmpresasExtras)}</span>
        </div>
        ` : ''}
    `;

    document.getElementById('resumo-total-step2').textContent = formatarMoeda(valorTotal);
    document.getElementById('valor-usuarios-extras').textContent = formatarMoeda(valorUsuariosExtras);
    document.getElementById('valor-empresas-extras').textContent = formatarMoeda(valorEmpresasExtras);
}

function calcularTotalStep3() {
    const periodo = document.querySelector('input[name="periodo"]:checked');
    if (!periodo) return;

    const periodoValue = periodo.value;
    const precoBase = plano[`preco_${periodoValue}`];
    const usuariosExtras = parseInt(document.getElementById('usuarios_extras').value) || 0;
    const empresasExtras = parseInt(document.getElementById('empresas_extras').value) || 0;

    const valorUsuariosExtras = usuariosExtras * precoUsuarioAdicional;
    const valorEmpresasExtras = empresasExtras * precoEmpresaAdicional;

    // Calcular aplicativos selecionados
    let valorAplicativos = 0;
    const aplicativosSelecionados = [];
    document.querySelectorAll('input[name="aplicativos[]"]:checked').forEach(checkbox => {
        const aplicativoId = checkbox.value;
        const aplicativo = aplicativos.find(a => a.id === aplicativoId);
        if (aplicativo) {
            const preco = aplicativo[`preco_${periodoValue}`] || aplicativo.preco_mensal;
            valorAplicativos += preco;
            aplicativosSelecionados.push(aplicativo.nome);
        }
    });

    const valorTotal = precoBase + valorUsuariosExtras + valorEmpresasExtras + valorAplicativos;

    // Atualizar resumo
    const resumo = document.getElementById('resumo-step3');
    resumo.innerHTML = `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Plano:</span>
            <span class="text-sm font-medium text-gray-900">{{ $plano->nome }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Período:</span>
            <span class="text-sm font-medium text-gray-900">${periodo.dataset.label}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Preço base:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(precoBase)}</span>
        </div>
        ${usuariosExtras > 0 ? `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Usuários extras:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(valorUsuariosExtras)}</span>
        </div>
        ` : ''}
        ${empresasExtras > 0 ? `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Empresas extras:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(valorEmpresasExtras)}</span>
        </div>
        ` : ''}
        ${valorAplicativos > 0 ? `
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Aplicativos:</span>
            <span class="text-sm font-medium text-gray-900">${formatarMoeda(valorAplicativos)}</span>
        </div>
        ` : ''}
    `;

    document.getElementById('resumo-total-step3').textContent = formatarMoeda(valorTotal);
}

function atualizarResumos() {
    if (currentStep >= 2) calcularTotalStep2();
    if (currentStep >= 3) calcularTotalStep3();
    if (currentStep >= 4) calcularTotalStep4();
}

function calcularTotalStep4() {
    calcularTotalStep3();
    const total = document.getElementById('resumo-total-step3').textContent;
    document.getElementById('resumo-step4').innerHTML = document.getElementById('resumo-step3').innerHTML;
    document.getElementById('resumo-total-step4').textContent = total;
}

function atualizarPrecosAplicativos(periodoValue) {
    document.querySelectorAll('[data-preco-display]').forEach(el => {
        const card = el.closest('.aplicativo-card');
        const checkbox = card.querySelector('input[type="checkbox"]');
        const aplicativo = aplicativos.find(a => a.id === checkbox.value);
        if (aplicativo) {
            const preco = aplicativo[`preco_${periodoValue}`] || aplicativo.preco_mensal;
            el.textContent = formatarMoeda(preco);
            checkbox.dataset.preco = preco;
        }
    });

    const labels = { 'mensal': 'Mensal', 'trimestral': 'Trimestral', 'semestral': 'Semestral', 'anual': 'Anual' };
    document.querySelectorAll('[data-periodo-display]').forEach(el => {
        el.textContent = `/${labels[periodoValue] || 'mensal'}`;
    });
}

function toggleAplicativo(event, card, aplicativoId) {
    if (event.target.type === 'checkbox' || event.target.closest('input[type="checkbox"]')) return;
    if (event.target.closest('button')) return;

    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    atualizarCheckboxAplicativo(checkbox);
    calcularTotalStep3();
}

function atualizarCheckboxAplicativo(checkbox) {
    const card = checkbox.closest('.aplicativo-card');
    if (checkbox.checked) {
        card.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md');
        card.classList.remove('border-gray-200', 'bg-white');
    } else {
        card.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md');
        card.classList.add('border-gray-200', 'bg-white');
    }
}

function toggleDescricao(button) {
    const card = button.closest('.aplicativo-card');
    const descricao = card.querySelector('.aplicativo-descricao');
    const texto = button.querySelector('.descricao-texto');
    const icon = button.querySelector('.descricao-icon');

    if (descricao.classList.contains('hidden')) {
        descricao.classList.remove('hidden');
        texto.textContent = 'Ocultar descrição';
        icon.style.transform = 'rotate(180deg)';
    } else {
        descricao.classList.add('hidden');
        texto.textContent = 'Ver descrição';
        icon.style.transform = 'rotate(0deg)';
    }
}

function formatarMoeda(valor) {
    return 'R$ ' + parseFloat(valor).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Período selection
    document.querySelectorAll('input[name="periodo"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Atualizar visual dos cards
            document.querySelectorAll('.periodo-option').forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-gradient-to-br', 'from-blue-50', 'to-blue-100', 'shadow-md');
                opt.classList.add('border-gray-200', 'bg-white');
            });
            this.closest('.periodo-option').classList.remove('border-gray-200', 'bg-white');
            this.closest('.periodo-option').classList.add('border-blue-500', 'bg-gradient-to-br', 'from-blue-50', 'to-blue-100', 'shadow-md');
            
            calcularTotalStep1();
        });
    });

    // Usuários e empresas extras
    document.getElementById('usuarios_extras').addEventListener('change', calcularTotalStep2);
    document.getElementById('empresas_extras').addEventListener('change', calcularTotalStep2);

    // Aplicativos
    document.querySelectorAll('input[name="aplicativos[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            atualizarCheckboxAplicativo(this);
            calcularTotalStep3();
        });
    });

    // Método de pagamento
    document.querySelectorAll('input[name="metodo_pagamento"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const dadosCartao = document.getElementById('dados-cartao');
            dadosCartao.style.display = this.value === 'cartao' ? 'block' : 'none';
        });
    });

    // Inicializar
    calcularTotalStep1();
    atualizarWizard(1);
});
</script>

<style>
.periodo-option input:checked + * {
    border-color: #3B82F6;
}
</style>
@endsection

