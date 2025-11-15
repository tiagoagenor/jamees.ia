@extends('layouts.app')

@section('title', 'Pagamento - ' . $aplicativo->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('aplicativos.index') }}" class="hover:text-blue-600">Aplicativos</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li><a href="{{ route('aplicativos.show', $aplicativo) }}" class="hover:text-blue-600">{{ $aplicativo->nome }}</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-900">Pagamento</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('aplicativos.show', $aplicativo) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                ← Voltar
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Finalizar Pagamento</h1>
        <p class="mt-2 text-gray-600">Confirme os dados e finalize a contratação do aplicativo</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Resumo do Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 sticky top-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Resumo do Pedido</h2>
                
                <!-- Aplicativo -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-start space-x-4">
                        @if($aplicativo->imagem)
                            <img src="{{ $aplicativo->imagem }}" alt="{{ $aplicativo->nome }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $aplicativo->nome }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($periodo) }}</p>
                            @if($aplicativo->categoria)
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    {{ $aplicativo->categoria }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Preços -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900 font-semibold">R$ {{ number_format($preco, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-green-600">R$ {{ number_format($preco, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Informações -->
                <div class="pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Ativação Imediata</p>
                            <p class="text-xs text-gray-600">Acesso liberado após confirmação</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Suporte Incluído</p>
                            <p class="text-xs text-gray-600">Suporte técnico durante todo o período</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Pagamento -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Forma de Pagamento</h2>

                <form method="POST" action="{{ route('aplicativos.processar-pagamento', $aplicativo) }}" id="pagamentoForm">
                    @csrf
                    <input type="hidden" name="periodo" value="{{ $periodo }}">

                    <!-- Seleção de Forma de Pagamento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Escolha a forma de pagamento</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cartão de Crédito -->
                            <div class="relative">
                                <input type="radio" 
                                       name="forma_pagamento" 
                                       id="cartao" 
                                       value="cartao" 
                                       class="peer hidden" 
                                       checked>
                                <label for="cartao" 
                                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-colors">
                                    <div class="flex items-center w-full">
                                        <i class="fas fa-credit-card text-2xl text-blue-600 mr-4"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900">Cartão de Crédito</p>
                                            <p class="text-xs text-gray-600">Pagamento à vista</p>
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- PIX -->
                            <div class="relative">
                                <input type="radio" 
                                       name="forma_pagamento" 
                                       id="pix" 
                                       value="pix" 
                                       class="peer hidden">
                                <label for="pix" 
                                       class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-colors">
                                    <div class="flex items-center w-full">
                                        <i class="fas fa-qrcode text-2xl text-green-600 mr-4"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900">PIX</p>
                                            <p class="text-xs text-gray-600">Aprovação instantânea</p>
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Cartão (aparece quando Cartão é selecionado) -->
                    <div id="dadosCartao" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="numero_cartao" class="block text-sm font-medium text-gray-700 mb-2">Número do Cartão</label>
                                <input type="text" 
                                       id="numero_cartao" 
                                       name="numero_cartao" 
                                       placeholder="0000 0000 0000 0000"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="nome_cartao" class="block text-sm font-medium text-gray-700 mb-2">Nome no Cartão</label>
                                <input type="text" 
                                       id="nome_cartao" 
                                       name="nome_cartao" 
                                       placeholder="NOME COMPLETO"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="validade" class="block text-sm font-medium text-gray-700 mb-2">Validade</label>
                                <input type="text" 
                                       id="validade" 
                                       name="validade" 
                                       placeholder="MM/AA"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                <input type="text" 
                                       id="cvv" 
                                       name="cvv" 
                                       placeholder="123"
                                       maxlength="4"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Dados PIX (aparece quando PIX é selecionado) -->
                    <div id="dadosPix" class="hidden">
                        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 text-center">
                            <i class="fas fa-qrcode text-6xl text-green-600 mb-4"></i>
                            <p class="text-lg font-semibold text-gray-900 mb-2">Pagamento via PIX</p>
                            <p class="text-sm text-gray-600 mb-4">O QR Code será gerado após a confirmação do pedido</p>
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <p class="text-xs text-gray-500 mb-1">Valor a pagar</p>
                                <p class="text-2xl font-bold text-green-600">R$ {{ number_format($preco, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Finalizar -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-bold text-lg transition-colors duration-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-lock mr-2"></i>
                            Finalizar Pagamento
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-3">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Seus dados estão protegidos e seguros
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formaPagamento = document.querySelectorAll('input[name="forma_pagamento"]');
    const dadosCartao = document.getElementById('dadosCartao');
    const dadosPix = document.getElementById('dadosPix');

    formaPagamento.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'cartao') {
                dadosCartao.classList.remove('hidden');
                dadosPix.classList.add('hidden');
            } else if (this.value === 'pix') {
                dadosCartao.classList.add('hidden');
                dadosPix.classList.remove('hidden');
            }
        });
    });

    // Máscara para número do cartão
    const numeroCartao = document.getElementById('numero_cartao');
    if (numeroCartao) {
        numeroCartao.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Máscara para validade
    const validade = document.getElementById('validade');
    if (validade) {
        validade.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Máscara para CVV
    const cvv = document.getElementById('cvv');
    if (cvv) {
        cvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
});
</script>
@endsection

