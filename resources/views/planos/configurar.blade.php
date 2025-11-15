@extends('layouts.app')

@section('title', 'Configurar Plano')

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
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                            2
                        </div>
                        <span class="mt-2 text-sm font-medium text-blue-600">Configurar</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                            3
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-500">Aplicativos</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                </div>
                
                <!-- Step 4 -->
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                            4
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-500">Pagamento</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('planos.show', $plano) }}?periodo={{ $periodo->value }}" class="text-blue-600 hover:text-blue-800 mr-4">
                ← Voltar
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Configurar Plano</h1>
        <p class="mt-2 text-gray-600">{{ $plano->nome }} - Período: {{ $periodo->getLabel() }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configurações -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Personalize seu Plano</h2>

                <form method="POST" action="{{ route('planos.aplicativos', $plano) }}" id="configurarForm">
                    @csrf
                    <input type="hidden" name="periodo" value="{{ $periodo->value }}">

                    <!-- Quantidade de Usuários -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Usuários</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Plano inclui: <strong>{{ $plano->limite_usuarios ?? 'Ilimitado' }} usuários</strong>
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="usuarios_extras" class="block text-sm font-medium text-gray-700 mb-2">
                                Adicionar usuários extras
                            </label>
                            <div class="flex items-center space-x-4">
                                <button type="button" 
                                        onclick="decrementarUsuarios()"
                                        class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="usuarios_extras" 
                                       name="usuarios_extras"
                                       value="{{ $usuariosExtras ?? 0 }}"
                                       min="0"
                                       class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       onchange="calcularTotal()">
                                <button type="button" 
                                        onclick="incrementarUsuarios()"
                                        class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                                <div class="flex-1 ml-4">
                                    <p class="text-sm text-gray-600">
                                        <span id="valor-usuarios-extras">R$ 0,00</span>
                                        <span class="text-gray-500"> (R$ {{ number_format($precoUsuarioAdicional, 2, ',', '.') }} por usuário)</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantidade de Empresas -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Empresas</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Plano inclui: <strong>{{ $plano->limite_empresas ?? 'Ilimitado' }} empresas</strong>
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="empresas_extras" class="block text-sm font-medium text-gray-700 mb-2">
                                Adicionar empresas extras
                            </label>
                            <div class="flex items-center space-x-4">
                                <button type="button" 
                                        onclick="decrementarEmpresas()"
                                        class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="empresas_extras" 
                                       name="empresas_extras"
                                       value="{{ $empresasExtras ?? 0 }}"
                                       min="0"
                                       class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 text-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       onchange="calcularTotal()">
                                <button type="button" 
                                        onclick="incrementarEmpresas()"
                                        class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                                <div class="flex-1 ml-4">
                                    <p class="text-sm text-gray-600">
                                        <span id="valor-empresas-extras">R$ 0,00</span>
                                        <span class="text-gray-500"> (R$ {{ number_format($precoEmpresaAdicional, 2, ',', '.') }} por empresa)</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resumo -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Plano:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plano->nome }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Período:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $periodo->getLabel() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Preço base:</span>
                        <span class="text-sm font-medium text-gray-900" id="resumo-preco-base">R$ {{ number_format($precoBase, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center" id="resumo-usuarios-extras" style="display: none;">
                        <span class="text-sm text-gray-600">Usuários extras:</span>
                        <span class="text-sm font-medium text-gray-900" id="resumo-valor-usuarios">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between items-center" id="resumo-empresas-extras" style="display: none;">
                        <span class="text-sm text-gray-600">Empresas extras:</span>
                        <span class="text-sm font-medium text-gray-900" id="resumo-valor-empresas">R$ 0,00</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-blue-600" id="resumo-total">R$ {{ number_format($precoBase, 2, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" 
                        form="configurarForm"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium">
                    Continuar para Aplicativos
                </button>

                <p class="text-xs text-gray-500 text-center mt-4">
                    * Você poderá revisar antes de confirmar
                </p>
            </div>
        </div>
    </div>
</div>

<script>
const precoBase = {{ $precoBase }};
const precoUsuarioAdicional = {{ $precoUsuarioAdicional }};
const precoEmpresaAdicional = {{ $precoEmpresaAdicional }};

function incrementarUsuarios() {
    const input = document.getElementById('usuarios_extras');
    input.value = parseInt(input.value) + 1;
    calcularTotal();
}

function decrementarUsuarios() {
    const input = document.getElementById('usuarios_extras');
    if (parseInt(input.value) > 0) {
        input.value = parseInt(input.value) - 1;
        calcularTotal();
    }
}

function incrementarEmpresas() {
    const input = document.getElementById('empresas_extras');
    input.value = parseInt(input.value) + 1;
    calcularTotal();
}

function decrementarEmpresas() {
    const input = document.getElementById('empresas_extras');
    if (parseInt(input.value) > 0) {
        input.value = parseInt(input.value) - 1;
        calcularTotal();
    }
}

function calcularTotal() {
    const usuariosExtras = parseInt(document.getElementById('usuarios_extras').value) || 0;
    const empresasExtras = parseInt(document.getElementById('empresas_extras').value) || 0;
    
    const valorUsuariosExtras = usuariosExtras * precoUsuarioAdicional;
    const valorEmpresasExtras = empresasExtras * precoEmpresaAdicional;
    const valorTotal = precoBase + valorUsuariosExtras + valorEmpresasExtras;

    // Atualizar valores
    document.getElementById('valor-usuarios-extras').textContent = 
        formatarMoeda(valorUsuariosExtras);
    document.getElementById('valor-empresas-extras').textContent = 
        formatarMoeda(valorEmpresasExtras);
    
    // Atualizar resumo
    document.getElementById('resumo-valor-usuarios').textContent = 
        formatarMoeda(valorUsuariosExtras);
    document.getElementById('resumo-valor-empresas').textContent = 
        formatarMoeda(valorEmpresasExtras);
    document.getElementById('resumo-total').textContent = 
        formatarMoeda(valorTotal);

    // Mostrar/ocultar linhas extras
    document.getElementById('resumo-usuarios-extras').style.display = 
        usuariosExtras > 0 ? 'flex' : 'none';
    document.getElementById('resumo-empresas-extras').style.display = 
        empresasExtras > 0 ? 'flex' : 'none';
}

function formatarMoeda(valor) {
    return 'R$ ' + valor.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Inicializar cálculo
document.addEventListener('DOMContentLoaded', function() {
    calcularTotal();
});
</script>
@endsection

