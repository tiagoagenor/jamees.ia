@extends('layouts.app')

@section('title', 'Pagamento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Wizard Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center w-full max-w-3xl">
                <!-- Step 1 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-sm font-medium text-green-600">Selecionar Período</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-sm font-medium text-green-600">Configurar</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-sm font-medium text-green-600">Aplicativos</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                </div>
                
                <!-- Step 4 -->
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                            4
                        </div>
                        <span class="mt-2 text-sm font-medium text-blue-600">Pagamento</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <form method="POST" action="{{ route('planos.aplicativos', $plano) }}" class="inline">
                @csrf
                <input type="hidden" name="periodo" value="{{ $periodo->value }}">
                <input type="hidden" name="usuarios_extras" value="{{ $usuariosExtras }}">
                <input type="hidden" name="empresas_extras" value="{{ $empresasExtras }}">
                @if(isset($aplicativos) && $aplicativos->count() > 0)
                    @foreach($aplicativos as $aplicativo)
                        <input type="hidden" name="aplicativos[]" value="{{ $aplicativo->id }}">
                    @endforeach
                @endif
                <button type="submit" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Voltar
                </button>
            </form>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Finalizar Pagamento</h1>
        <p class="mt-2 text-gray-600">Confirme os dados e finalize sua assinatura</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulário de Pagamento -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Dados de Pagamento</h2>

                <form method="POST" action="{{ route('planos.ativar', $plano) }}" id="pagamentoForm">
                    @csrf
                    <input type="hidden" name="periodo" value="{{ $periodo->value }}">
                    <input type="hidden" name="usuarios_extras" value="{{ $usuariosExtras }}">
                    <input type="hidden" name="empresas_extras" value="{{ $empresasExtras }}">
                    @if(isset($aplicativos) && $aplicativos->count() > 0)
                        @foreach($aplicativos as $aplicativo)
                            <input type="hidden" name="aplicativos[]" value="{{ $aplicativo->id }}">
                        @endforeach
                    @endif

                    <!-- Método de Pagamento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Método de Pagamento
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border-2 border-blue-200 bg-blue-50 rounded-lg cursor-pointer">
                                <input type="radio" name="metodo_pagamento" value="cartao" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">Cartão de Crédito</span>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="metodo_pagamento" value="boleto" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">Boleto Bancário</span>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="metodo_pagamento" value="pix" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">PIX</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Dados do Cartão (mostrar apenas se cartão selecionado) -->
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
                        <div class="grid grid-cols-2 gap-4 mb-4">
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

        <!-- Resumo do Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>

                <div class="space-y-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ $plano->nome }}</h3>
                        <p class="text-xs text-gray-600">Período: {{ $periodo->getLabel() }}</p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Plano base:</span>
                            <span class="text-sm font-medium text-gray-900">R$ {{ number_format($precoBase, 2, ',', '.') }}</span>
                        </div>
                        @if($usuariosExtras > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $usuariosExtras }} usuário(s) extra(s):</span>
                            <span class="text-sm font-medium text-gray-900">R$ {{ number_format($valorUsuariosExtras, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($empresasExtras > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $empresasExtras }} empresa(s) extra(s):</span>
                            <span class="text-sm font-medium text-gray-900">R$ {{ number_format($valorEmpresasExtras, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        @if(isset($valorAplicativos) && $valorAplicativos > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Aplicativos:</span>
                            <span class="text-sm font-medium text-gray-900">R$ {{ number_format($valorAplicativos, 2, ',', '.') }}</span>
                        </div>
                        @if(isset($aplicativos) && $aplicativos->count() > 0)
                            <div class="mt-2 text-xs text-gray-500">
                                @foreach($aplicativos as $aplicativo)
                                    <div>• {{ $aplicativo->nome }}</div>
                                @endforeach
                            </div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-blue-600">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" 
                        form="pagamentoForm"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium mb-4">
                    Finalizar Pagamento
                </button>

                <p class="text-xs text-gray-500 text-center">
                    * Esta é uma tela de demonstração. O pagamento não será processado.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const metodoPagamentoInputs = document.querySelectorAll('input[name="metodo_pagamento"]');
    const dadosCartao = document.getElementById('dados-cartao');

    function toggleDadosCartao() {
        const metodoSelecionado = document.querySelector('input[name="metodo_pagamento"]:checked').value;
        dadosCartao.style.display = metodoSelecionado === 'cartao' ? 'block' : 'none';
    }

    metodoPagamentoInputs.forEach(input => {
        input.addEventListener('change', toggleDadosCartao);
    });

    // Máscaras
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
});
</script>
@endsection

