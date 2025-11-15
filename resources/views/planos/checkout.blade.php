@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="flex flex-col w-full">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-white flex-shrink-0">
        <div class="flex items-center mb-2">
            <a href="{{ route('planos.index') }}" class="text-blue-600 hover:text-blue-800 mr-4 text-sm">
                ← Voltar para Planos
            </a>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Finalizar Assinatura</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $plano->nome }}</p>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-0 w-full">
        <!-- Accordion - Lado Esquerdo -->
        <div class="lg:col-span-2 bg-gray-50 w-full">
            <div class="p-6 w-full">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Configure seu Plano</h2>
                
                <!-- Accordion -->
                <div class="space-y-4" id="checkoutAccordion">
                    <!-- Passo 1: Selecionar Período -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors accordion-header" 
                                onclick="toggleAccordion(1)">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm mr-3">
                                    1
                                </div>
                                <span class="text-lg font-medium text-gray-900">Selecionar Período</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-6 py-4 border-t border-gray-200">
                            <form id="periodoForm" onsubmit="updateCheckout(event, 'periodo')">
                                <div class="space-y-3">
                                    @foreach($periodos as $periodoOption)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50
                                            @if($periodoOption->value === $periodo->value) border-blue-200 bg-blue-50 @endif">
                                            <input type="radio"
                                                   name="periodo"
                                                   value="{{ $periodoOption->value }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                   @if($periodoOption->value === $periodo->value) checked @endif
                                                   onchange="updateCheckout(null, 'periodo')">
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-900">{{ $periodoOption->getLabel() }}</span>
                                                    <span class="text-sm font-bold text-gray-900">
                                                        R$ {{ number_format($plano->getPrecoPorPeriodo($periodoOption->value), 2, ',', '.') }}
                                                    </span>
                                                </div>
                                                @if($periodoOption->getDiscount() > 0)
                                                    <div class="text-xs text-green-600">
                                                        {{ $periodoOption->getDiscount() * 100 }}% de desconto
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Passo 2: Configurar -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors accordion-header" 
                                onclick="toggleAccordion(2)">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm mr-3">
                                    2
                                </div>
                                <span class="text-lg font-medium text-gray-900">Configurar</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-6 py-4 border-t border-gray-200">
                            <form id="configurarForm" onsubmit="updateCheckout(event, 'configurar')">
                                <!-- Usuários Extras -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Usuários Extras</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Plano inclui: <strong>{{ $plano->limite_usuarios ?? 'Ilimitado' }} usuários</strong>
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <button type="button" 
                                                onclick="decrementar('usuarios_extras')"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               id="usuarios_extras" 
                                               name="usuarios_extras"
                                               value="{{ $usuariosExtras }}"
                                               min="0"
                                               class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium"
                                               onchange="calcularTotal()">
                                        <button type="button" 
                                                onclick="incrementar('usuarios_extras')"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                        <div class="flex-1 ml-4">
                                            <p class="text-sm text-gray-600">
                                                <span id="valor-usuarios-extras">R$ {{ number_format($valorUsuariosExtras, 2, ',', '.') }}</span>
                                                <span class="text-gray-500"> (R$ {{ number_format($precoUsuarioAdicional, 2, ',', '.') }} por usuário)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empresas Extras -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Empresas Extras</h3>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Plano inclui: <strong>{{ $plano->limite_empresas ?? 'Ilimitado' }} empresas</strong>
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <button type="button" 
                                                onclick="decrementar('empresas_extras')"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               id="empresas_extras" 
                                               name="empresas_extras"
                                               value="{{ $empresasExtras }}"
                                               min="0"
                                               class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium"
                                               onchange="calcularTotal()">
                                        <button type="button" 
                                                onclick="incrementar('empresas_extras')"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                        <div class="flex-1 ml-4">
                                            <p class="text-sm text-gray-600">
                                                <span id="valor-empresas-extras">R$ {{ number_format($valorEmpresasExtras, 2, ',', '.') }}</span>
                                                <span class="text-gray-500"> (R$ {{ number_format($precoEmpresaAdicional, 2, ',', '.') }} por empresa)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Passo 3: Aplicativos -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors accordion-header" 
                                onclick="toggleAccordion(3)">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm mr-3">
                                    3
                                </div>
                                <span class="text-lg font-medium text-gray-900">Aplicativos</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-6 py-4 border-t border-gray-200">
                            <form id="aplicativosForm" onsubmit="updateCheckout(event, 'aplicativos')">
                                @if($aplicativosDisponiveis->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($aplicativosDisponiveis as $aplicativo)
                                            <div class="aplicativo-card relative border-2 rounded-lg p-3 cursor-pointer transition-all hover:shadow-lg
                                                @if(in_array($aplicativo->id, $aplicativosIds)) border-blue-500 bg-blue-50
                                                @else border-gray-200 bg-white hover:border-blue-300
                                                @endif"
                                                onclick="toggleAplicativo(event, this, '{{ $aplicativo->id }}')">
                                                <input type="checkbox"
                                                       name="aplicativos[]"
                                                       value="{{ $aplicativo->id }}"
                                                       class="absolute top-1.5 right-1.5 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                                       onchange="atualizarCheckboxAplicativo(this); calcularTotal();"
                                                       @if(in_array($aplicativo->id, $aplicativosIds)) checked @endif>
                                                
                                                <div class="text-center">
                                                    <!-- Ícone -->
                                                    <div class="mx-auto w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                                        @if($aplicativo->codigo === 'loteamento')
                                                            <i class="fas fa-map text-blue-600 text-base"></i>
                                                        @else
                                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    
                                                    <h3 class="text-xs font-semibold text-gray-900 mb-1">{{ $aplicativo->nome }}</h3>
                                                    <div class="text-base font-bold text-gray-900">
                                                        R$ {{ number_format($aplicativo->getPrecoPorPeriodo($periodo->value), 2, ',', '.') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        /{{ strtolower($periodo->getLabel()) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500">Nenhum aplicativo disponível no momento.</p>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Passo 4: Pagamento -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors accordion-header" 
                                onclick="toggleAccordion(4)">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm mr-3">
                                    4
                                </div>
                                <span class="text-lg font-medium text-gray-900">Dados de Pagamento</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-6 py-4 border-t border-gray-200">
                            <form id="pagamentoForm" onsubmit="updateCheckout(event, 'pagamento')">
                                <!-- Método de Pagamento -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Método de Pagamento
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="metodo-pagamento-box flex flex-col items-center justify-center p-6 border-2 border-blue-500 bg-blue-50 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors" data-metodo="cartao">
                                            <input type="radio" name="metodo_pagamento" value="cartao" checked class="sr-only">
                                            <div class="mb-3">
                                                <i class="fas fa-credit-card text-3xl text-blue-600"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 text-center">Cartão de Crédito</span>
                                        </label>
                                        <label class="metodo-pagamento-box flex flex-col items-center justify-center p-6 border-2 border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-300 transition-colors" data-metodo="pix">
                                            <input type="radio" name="metodo_pagamento" value="pix" class="sr-only">
                                            <div class="mb-3">
                                                <i class="fas fa-qrcode text-3xl text-gray-600"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 text-center">PIX</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Dados do Cartão -->
                                <div id="dados-cartao" class="mb-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="numero_cartao" class="block text-sm font-medium text-gray-700 mb-2">
                                                Número do Cartão
                                            </label>
                                            <input type="text" 
                                                   id="numero_cartao" 
                                                   name="numero_cartao"
                                                   placeholder="0000 0000 0000 0000"
                                                   maxlength="19"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="nome_cartao" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nome no Cartão
                                            </label>
                                            <input type="text" 
                                                   id="nome_cartao" 
                                                   name="nome_cartao"
                                                   placeholder="Nome como está no cartão"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="validade_cartao" class="block text-sm font-medium text-gray-700 mb-2">
                                                Validade
                                            </label>
                                            <input type="text" 
                                                   id="validade_cartao" 
                                                   name="validade_cartao"
                                                   placeholder="MM/AA"
                                                   maxlength="5"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="cvv_cartao" class="block text-sm font-medium text-gray-700 mb-2">
                                                CVV
                                            </label>
                                            <input type="text" 
                                                   id="cvv_cartao" 
                                                   name="cvv_cartao"
                                                   placeholder="123"
                                                   maxlength="4"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Informações Adicionais -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Adicionais</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700 mb-2">
                                                CPF/CNPJ
                                            </label>
                                            <input type="text" 
                                                   id="cpf_cnpj" 
                                                   name="cpf_cnpj"
                                                   placeholder="000.000.000-00"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                                                Telefone
                                            </label>
                                            <input type="text" 
                                                   id="telefone" 
                                                   name="telefone"
                                                   placeholder="(00) 00000-0000"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout - Lado Direito -->
        <div class="lg:col-span-1 bg-white border-l border-gray-200 w-full">
            <div class="sticky top-8 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>

                <div class="space-y-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ $plano->nome }}</h3>
                        <p class="text-xs text-gray-600">Período: <span id="checkout-periodo">{{ $periodo->getLabel() }}</span></p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Plano base:</span>
                            <span class="text-sm font-medium text-gray-900" id="checkout-preco-base">R$ {{ number_format($precoBase, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center" id="checkout-usuarios-extras" style="display: {{ $usuariosExtras > 0 ? 'flex' : 'none' }};">
                            <span class="text-sm text-gray-600"><span id="checkout-qtd-usuarios">{{ $usuariosExtras }}</span> usuário(s) extra(s):</span>
                            <span class="text-sm font-medium text-gray-900" id="checkout-valor-usuarios">R$ {{ number_format($valorUsuariosExtras, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center" id="checkout-empresas-extras" style="display: {{ $empresasExtras > 0 ? 'flex' : 'none' }};">
                            <span class="text-sm text-gray-600"><span id="checkout-qtd-empresas">{{ $empresasExtras }}</span> empresa(s) extra(s):</span>
                            <span class="text-sm font-medium text-gray-900" id="checkout-valor-empresas">R$ {{ number_format($valorEmpresasExtras, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center" id="checkout-aplicativos" style="display: {{ $valorAplicativos > 0 ? 'flex' : 'none' }};">
                            <span class="text-sm text-gray-600">Aplicativos:</span>
                            <span class="text-sm font-medium text-gray-900" id="checkout-valor-aplicativos">R$ {{ number_format($valorAplicativos, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-blue-600" id="checkout-total-sticky">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('planos.ativar', $plano) }}" id="finalizarForm">
                        @csrf
                        <input type="hidden" name="periodo" id="final-periodo" value="{{ $periodo->value }}">
                        <input type="hidden" name="usuarios_extras" id="final-usuarios-extras" value="{{ $usuariosExtras }}">
                        <input type="hidden" name="empresas_extras" id="final-empresas-extras" value="{{ $empresasExtras }}">
                        <div id="final-aplicativos">
                            @foreach($aplicativosIds as $appId)
                                <input type="hidden" name="aplicativos[]" value="{{ $appId }}">
                            @endforeach
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium mb-2 shadow-lg">
                            Finalizar Pagamento
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 text-center">
                        * Esta é uma tela de demonstração. O pagamento não será processado.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dados do plano
const plano = {
    preco_mensal: {{ $plano->preco_mensal }},
    preco_trimestral: {{ $plano->preco_trimestral }},
    preco_semestral: {{ $plano->preco_semestral }},
    preco_anual: {{ $plano->preco_anual }}
};

const precoUsuarioAdicional = {{ $precoUsuarioAdicional }};
const precoEmpresaAdicional = {{ $precoEmpresaAdicional }};
const periodoAtual = '{{ $periodo->value }}';

@php
$aplicativosData = $aplicativosDisponiveis->mapWithKeys(function($app) {
    return [$app->id => [
        'nome' => $app->nome,
        'preco_mensal' => (float)($app->preco_mensal ?? 0),
        'preco_trimestral' => (float)($app->preco_trimestral ?? 0),
        'preco_semestral' => (float)($app->preco_semestral ?? 0),
        'preco_anual' => (float)($app->preco_anual ?? 0)
    ]];
});
@endphp
const aplicativos = @json($aplicativosData);

// Accordion
function toggleAccordion(step) {
    const button = event.target.closest('.accordion-header') || event.target;
    const container = button.closest('.border');
    const content = container.querySelector('.accordion-content');
    const icon = button.querySelector('.accordion-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}

// Atualizar checkout
function updateCheckout(e, tipo) {
    if (e) e.preventDefault();
    
    const periodo = document.querySelector('input[name="periodo"]:checked')?.value || periodoAtual;
    const usuariosExtras = parseInt(document.getElementById('usuarios_extras')?.value || 0);
    const empresasExtras = parseInt(document.getElementById('empresas_extras')?.value || 0);
    
    // Calcular preço base
    const precoBase = plano[`preco_${periodo}`];
    const valorUsuariosExtras = usuariosExtras * precoUsuarioAdicional;
    const valorEmpresasExtras = empresasExtras * precoEmpresaAdicional;
    
    // Calcular aplicativos
    const checkboxes = document.querySelectorAll('input[name="aplicativos[]"]:checked');
    let valorAplicativos = 0;
    const aplicativosSelecionados = [];
    checkboxes.forEach(checkbox => {
        const appId = checkbox.value;
        if (aplicativos[appId]) {
            const preco = aplicativos[appId][`preco_${periodo}`] || 0;
            valorAplicativos += preco;
            aplicativosSelecionados.push(appId);
        }
    });
    
    // Atualizar preços exibidos nos cards dos aplicativos
    atualizarPrecosAplicativos(periodo);
    
    const valorTotal = precoBase + valorUsuariosExtras + valorEmpresasExtras + valorAplicativos;
    
    // Atualizar UI
    const periodoLabel = document.querySelector(`input[name="periodo"][value="${periodo}"]`)?.closest('label')?.querySelector('.text-sm.font-medium')?.textContent || 'Anual';
    document.getElementById('checkout-periodo').textContent = periodoLabel;
    document.getElementById('checkout-preco-base').textContent = formatarMoeda(precoBase);
    document.getElementById('checkout-valor-usuarios').textContent = formatarMoeda(valorUsuariosExtras);
    document.getElementById('checkout-valor-empresas').textContent = formatarMoeda(valorEmpresasExtras);
    document.getElementById('checkout-valor-aplicativos').textContent = formatarMoeda(valorAplicativos);
    
    // Atualizar total no sticky
    const totalSticky = document.getElementById('checkout-total-sticky');
    if (totalSticky) {
        totalSticky.textContent = formatarMoeda(valorTotal);
    }
    
    // Mostrar/ocultar linhas
    document.getElementById('checkout-usuarios-extras').style.display = usuariosExtras > 0 ? 'flex' : 'none';
    document.getElementById('checkout-empresas-extras').style.display = empresasExtras > 0 ? 'flex' : 'none';
    document.getElementById('checkout-aplicativos').style.display = valorAplicativos > 0 ? 'flex' : 'none';
    
    document.getElementById('checkout-qtd-usuarios').textContent = usuariosExtras;
    document.getElementById('checkout-qtd-empresas').textContent = empresasExtras;
    
    // Atualizar formulário final
    document.getElementById('final-periodo').value = periodo;
    document.getElementById('final-usuarios-extras').value = usuariosExtras;
    document.getElementById('final-empresas-extras').value = empresasExtras;
    
    // Atualizar aplicativos no formulário
    const finalAplicativos = document.getElementById('final-aplicativos');
    finalAplicativos.innerHTML = '';
    aplicativosSelecionados.forEach(appId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'aplicativos[]';
        input.value = appId;
        finalAplicativos.appendChild(input);
    });
    
    // Recarregar página com novos parâmetros
    if (tipo) {
        const params = new URLSearchParams({
            periodo: periodo,
            usuarios_extras: usuariosExtras,
            empresas_extras: empresasExtras,
            ...(aplicativosSelecionados.length > 0 ? { aplicativos: aplicativosSelecionados } : {})
        });
        window.history.pushState({}, '', `{{ route('planos.checkout', $plano) }}?${params.toString()}`);
    }
}

// Funções auxiliares
function incrementar(campo) {
    const input = document.getElementById(campo);
    input.value = parseInt(input.value) + 1;
    calcularTotal();
}

function decrementar(campo) {
    const input = document.getElementById(campo);
    if (parseInt(input.value) > 0) {
        input.value = parseInt(input.value) - 1;
        calcularTotal();
    }
}

function calcularTotal() {
    updateCheckout(null, null);
}

function formatarMoeda(valor) {
    return 'R$ ' + valor.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Toggle aplicativo
function toggleAplicativo(event, card, aplicativoId) {
    if (event.target.type === 'checkbox' || event.target.closest('input[type="checkbox"]')) {
        return;
    }
    
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    atualizarEstiloCard(card, checkbox.checked);
    calcularTotal();
}

function atualizarEstiloCard(card, isChecked) {
    if (isChecked) {
        card.classList.add('border-blue-500', 'bg-blue-50');
        card.classList.remove('border-gray-200', 'bg-white');
    } else {
        card.classList.remove('border-blue-500', 'bg-blue-50');
        card.classList.add('border-gray-200', 'bg-white');
    }
}

function atualizarCheckboxAplicativo(checkbox) {
    const card = checkbox.closest('.aplicativo-card');
    atualizarEstiloCard(card, checkbox.checked);
}

function atualizarPrecosAplicativos(periodo) {
    const periodoLabels = {
        'mensal': 'mensal',
        'trimestral': 'trimestral',
        'semestral': 'semestral',
        'anual': 'anual'
    };
    const periodoLabel = periodoLabels[periodo] || 'anual';
    
    document.querySelectorAll('.aplicativo-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (!checkbox) return;
        
        const appId = checkbox.value;
        
        if (aplicativos[appId]) {
            const preco = aplicativos[appId][`preco_${periodo}`] || 0;
            const precoElement = card.querySelector('.text-lg.font-bold.text-gray-900');
            const periodoElement = card.querySelector('.text-xs.text-gray-500');
            
            if (precoElement) {
                precoElement.textContent = formatarMoeda(preco);
            }
            if (periodoElement) {
                periodoElement.textContent = `/${periodoLabel}`;
            }
        }
    });
}

// Máscaras
document.addEventListener('DOMContentLoaded', function() {
    const metodoPagamentoInputs = document.querySelectorAll('input[name="metodo_pagamento"]');
    const dadosCartao = document.getElementById('dados-cartao');

    function toggleDadosCartao() {
        if (!dadosCartao) return;
        const metodoSelecionado = document.querySelector('input[name="metodo_pagamento"]:checked')?.value;
        if (metodoSelecionado === 'cartao') {
            dadosCartao.style.display = 'block';
        } else {
            dadosCartao.style.display = 'none';
        }
    }

    function atualizarEstiloMetodoPagamento() {
        const boxes = document.querySelectorAll('.metodo-pagamento-box');
        boxes.forEach(box => {
            const input = box.querySelector('input[type="radio"]');
            const icon = box.querySelector('i');
            
            if (!input || !box) return;
            
            if (input.checked) {
                // Método selecionado
                box.className = 'metodo-pagamento-box flex flex-col items-center justify-center p-6 border-2 border-blue-500 bg-blue-50 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors';
                if (icon) {
                    icon.className = 'fas ' + (input.value === 'cartao' ? 'fa-credit-card' : 'fa-qrcode') + ' text-3xl text-blue-600';
                }
            } else {
                // Método não selecionado
                box.className = 'metodo-pagamento-box flex flex-col items-center justify-center p-6 border-2 border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-300 transition-colors';
                if (icon) {
                    icon.className = 'fas ' + (input.value === 'cartao' ? 'fa-credit-card' : 'fa-qrcode') + ' text-3xl text-gray-600';
                }
            }
        });
    }

    // Adicionar evento de clique nos boxes
    document.querySelectorAll('.metodo-pagamento-box').forEach(box => {
        box.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.querySelector('input[type="radio"]');
            if (input) {
                const scrollPosition = window.scrollY || window.pageYOffset;
                input.checked = true;
                toggleDadosCartao();
                atualizarEstiloMetodoPagamento();
                // Manter posição do scroll
                window.scrollTo(0, scrollPosition);
            }
        });
    });

    metodoPagamentoInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const scrollPosition = window.scrollY || window.pageYOffset;
            toggleDadosCartao();
            atualizarEstiloMetodoPagamento();
            // Manter posição do scroll
            window.scrollTo(0, scrollPosition);
        });
    });

    // Inicializar estilos
    atualizarEstiloMetodoPagamento();

    const numeroCartao = document.getElementById('numero_cartao');
    if (numeroCartao) {
        numeroCartao.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
        });
    }

    const validadeCartao = document.getElementById('validade_cartao');
    if (validadeCartao) {
        validadeCartao.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    toggleDadosCartao();
    
    // Abrir primeiro accordion por padrão
    const firstAccordionItem = document.querySelector('#checkoutAccordion > div:first-child');
    if (firstAccordionItem) {
        const firstContent = firstAccordionItem.querySelector('.accordion-content');
        const firstIcon = firstAccordionItem.querySelector('.accordion-icon');
        
        if (firstContent) {
            firstContent.classList.remove('hidden');
        }
        if (firstIcon) {
            firstIcon.style.transform = 'rotate(180deg)';
        }
    }
});
</script>
@endsection

