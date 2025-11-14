@extends('layouts.app')

@section('title', 'CSelect - Demonstração')

@section('content')
<!-- Incluir Font Awesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Incluir SweetAlert2 para notificações -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white py-12 mb-12 shadow-xl">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <div class="inline-block bg-white bg-opacity-20 rounded-full p-4 mb-4">
                <i class="fas fa-code text-4xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-3">CSelect</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                Componente de Select Personalizado para Laravel - Moderno, Flexível e Fácil de Usar
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm backdrop-blur-sm">
                    <i class="fas fa-check-circle mr-2"></i>Customizável
                </span>
                <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm backdrop-blur-sm">
                    <i class="fas fa-check-circle mr-2"></i>Responsivo
                </span>
                <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm backdrop-blur-sm">
                    <i class="fas fa-check-circle mr-2"></i>AJAX Ready
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-12">

    <!-- Exemplo 1: Básico -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-blue-500 text-white rounded-lg p-2">
                    <i class="fas fa-play text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 1: CSelect Básico</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Forma de pagamento *</label>
                    <input
                        type="text"
                        id="forma_pagamento_example_1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar"
                        autocomplete="off"
                    >
                </div>

                <!-- Debug: Mostrar valor do input hidden -->
                <div id="debug_example_1" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg hidden">
                    <p class="text-xs font-semibold text-blue-800 mb-2 uppercase tracking-wide">
                        <i class="fas fa-info-circle mr-1"></i>Valor selecionado
                    </p>
                    <div class="space-y-1 text-xs text-blue-700">
                        <p><strong class="font-semibold">ID:</strong> <span id="debug_hidden_id_1" class="font-mono bg-blue-100 px-2 py-0.5 rounded"></span></p>
                        <p><strong class="font-semibold">Name:</strong> <span id="debug_hidden_name_1" class="font-mono bg-blue-100 px-2 py-0.5 rounded"></span></p>
                        <p><strong class="font-semibold">Value:</strong> <span id="debug_hidden_value_1" class="font-mono bg-blue-100 px-2 py-0.5 rounded"></span></p>
                    </div>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-gray-500">&lt;!-- Input principal SEM name --&gt;</span>
<span class="text-blue-400">&lt;input</span>
    <span class="text-green-400">type</span>=<span class="text-yellow-300">"text"</span>
    <span class="text-green-400">id</span>=<span class="text-yellow-300">"forma_pagamento"</span>
    <span class="text-green-400">class</span>=<span class="text-yellow-300">"..."</span>
<span class="text-blue-400">&gt;</span>

<span class="text-blue-400">&lt;script</span> <span class="text-green-400">src</span>=<span class="text-yellow-300">"/js/cselect.js"</span><span class="text-blue-400">&gt;&lt;/script&gt;</span>
<span class="text-blue-400">&lt;script&gt;</span>
<span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#forma_pagamento'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'forma_pagamento_id'</span>,
    <span class="text-green-400">debug</span>: <span class="text-orange-400">true</span>,
    <span class="text-green-400">items</span>: [
        { <span class="text-green-400">value</span>: <span class="text-yellow-300">'1'</span>, <span class="text-green-400">label</span>: <span class="text-yellow-300">'A Combinar'</span> },
        { <span class="text-green-400">value</span>: <span class="text-yellow-300">'2'</span>, <span class="text-green-400">label</span>: <span class="text-yellow-300">'Boleto Bancário'</span> }
    ]
});
<span class="text-blue-400">&lt;/script&gt;</span></code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 2: Com Dados Fixos e Subtitle -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-green-500 text-white rounded-lg p-2">
                    <i class="fas fa-layer-group text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 2: CSelect com Title e Subtitle</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Produto *</label>
                    <input
                        type="text"
                        id="produto_example_2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar produto..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="produto_selected_value_2" class="text-sm font-medium text-gray-800">
                        Nenhum produto selecionado
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#produto'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'produto_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'categoria'</span>,
    <span class="text-green-400">items</span>: [
        { <span class="text-green-400">id</span>: <span class="text-yellow-300">'1'</span>, <span class="text-green-400">nome</span>: <span class="text-yellow-300">'Notebook Dell'</span>, <span class="text-green-400">categoria</span>: <span class="text-yellow-300">'Informática'</span> },
        { <span class="text-green-400">id</span>: <span class="text-yellow-300">'2'</span>, <span class="text-green-400">nome</span>: <span class="text-yellow-300">'Mouse Logitech'</span>, <span class="text-green-400">categoria</span>: <span class="text-yellow-300">'Acessórios'</span> }
    ]
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 3: Com AJAX -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-orange-500 text-white rounded-lg p-2">
                    <i class="fas fa-cloud-download-alt text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 3: CSelect com AJAX</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (AJAX) *</label>
                    <input
                        type="text"
                        id="cliente_example_3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_3" class="text-sm font-medium text-gray-800">
                        Nenhum cliente selecionado
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">minSearchLength</span>: <span class="text-orange-400">0</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>, <span class="text-gray-500">// Nome do parâmetro na URL (padrão: 'search')</span>
        <span class="text-green-400">headers</span>: {
            <span class="text-green-400">'Content-Type'</span>: <span class="text-yellow-300">'application/json'</span>
        }
    }
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 4: AJAX com Botão "Adicionar Novo" -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-teal-500 text-white rounded-lg p-2">
                    <i class="fas fa-cloud-plus text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 4: CSelect com AJAX e Botão "Adicionar Novo"</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (AJAX + Adicionar) *</label>
                    <input
                        type="text"
                        id="cliente_example_4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_4" class="text-sm font-medium text-gray-800">
                        Nenhum cliente selecionado
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">minSearchLength</span>: <span class="text-orange-400">0</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>, <span class="text-gray-500">// Nome do parâmetro na URL</span>
        <span class="text-green-400">headers</span>: {
            <span class="text-green-400">'Content-Type'</span>: <span class="text-yellow-300">'application/json'</span>
        }
    },
    <span class="text-green-400">addButton</span>: {
        <span class="text-green-400">text</span>: <span class="text-yellow-300">'Adicionar novo cliente'</span>
    },
    <span class="text-green-400">onClickButton</span>: <span class="text-pink-400">(selectId) =&gt;</span> {
        window.<span class="text-purple-400">openModal</span>(<span class="text-yellow-300">'cliente-modal'</span>, selectId);
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, value, label);
    }
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 5: Com Botão "Adicionar Novo" -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-purple-500 text-white rounded-lg p-2">
                    <i class="fas fa-plus-circle text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 5: Cliente (Modo Fixed) com "Adicionar Novo"</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <input
                        type="text"
                        id="cliente_example_5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_5" class="text-sm font-medium text-gray-800">
                        Nenhum cliente selecionado
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">items</span>: [...],
    <span class="text-green-400">addButton</span>: {
        <span class="text-green-400">text</span>: <span class="text-yellow-300">'Adicionar novo cliente'</span>
    },
    <span class="text-green-400">onClickButton</span>: <span class="text-pink-400">(selectId) =&gt;</span> {
        <span class="text-gray-500">// selectId é passado automaticamente pelo CSelect</span>
        <span class="text-gray-500">// Abrir modal e definir selectId automaticamente</span>
        window.<span class="text-purple-400">openModal</span>(<span class="text-yellow-300">'meu-modal'</span>, selectId);
    }
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Modal de Cliente -->
    <x-modals.cliente-modal />

    <!-- Exemplo 6: Com Valor Pré-selecionado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Exemplo 6: CSelect com Valor Pré-selecionado</h2>

        <div class="mb-4">
            <div id="cselect_example_6"></div>
        </div>

        <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
            <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
            </p>
            <p id="example_6_selected_value" class="text-sm font-medium text-gray-800">
                Nenhum valor selecionado
            </p>
        </div>

        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm font-semibold text-gray-700 mb-2">Código:</p>
            <pre class="text-sm text-gray-800"><code>&lt;div id="cselect_example_6"&gt;&lt;/div&gt;

&lt;script&gt;
// Inicializar CSelect com valor pré-selecionado aqui
&lt;/script&gt;</code></pre>
        </div>
    </div>

    <!-- Exemplo 8: AJAX com Valor Pré-selecionado -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-pink-50 to-rose-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-pink-500 text-white rounded-lg p-2">
                    <i class="fas fa-check-circle text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 8: CSelect com AJAX e Valor Pré-selecionado</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (AJAX com valor pré-selecionado) *</label>
                    <input
                        type="text"
                        id="cliente_example_8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_8" class="text-sm font-medium text-gray-800">
                        Carregando valor pré-selecionado...
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example8</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">minSearchLength</span>: <span class="text-orange-400">0</span>,
    <span class="text-green-400">value</span>: <span class="text-yellow-300">'0b8f5ac1-2ee0-447a-8669-9bbd5d6b5ba7'</span>, <span class="text-gray-500">// ID do item pré-selecionado</span>
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, value, label);
    }
});

<span class="text-gray-500">// O CSelect automaticamente busca e seleciona o item com o ID especificado em 'value'</span>
<span class="text-gray-500">// Para modo AJAX, ele busca da API. Para modo fixed, busca na lista de items.</span></code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 9: CSelect sem Input Hidden -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-500 text-white rounded-lg p-2">
                    <i class="fas fa-eye-slash text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 9: CSelect sem Input Hidden</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (sem input hidden) *</label>
                    <input
                        type="text"
                        id="cliente_example_9"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Valor gerenciado via JavaScript:</label>
                    <input
                        type="text"
                        id="cliente_value_manual"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600"
                        placeholder="Valor será atualizado aqui..."
                        readonly
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_9" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>Importante
                    </p>
                    <p class="text-xs text-yellow-700">
                        Este exemplo não cria o input hidden. O valor é gerenciado manualmente via JavaScript usando o callback <code>onSelect</code>.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example9</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_9'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">hideHiddenInput</span>: <span class="text-orange-400">true</span>, <span class="text-gray-500">// Não cria input hidden</span>
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        <span class="text-gray-500">// Gerenciar valor manualmente</span>
        <span class="text-purple-400">const</span> <span class="text-blue-400">manualInput</span> = document.<span class="text-purple-400">getElementById</span>(<span class="text-yellow-300">'cliente_value_manual'</span>);
        <span class="text-purple-400">if</span> (manualInput) {
            manualInput.value = value;
        }
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, value, label);
    }
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 10: CSelect com Campo Disabled -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-gray-500 text-white rounded-lg p-2">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 10: CSelect com Campo Disabled</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (use os botões para desabilitar/habilitar) *</label>
                    <input
                        type="text"
                        id="cliente_example_10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (campo habilitado para comparação) *</label>
                    <input
                        type="text"
                        id="cliente_example_10_enabled"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado (disabled)
                    </p>
                    <p id="cliente_selected_value_10" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-green-500 mr-1"></i>Valor selecionado (enabled)
                    </p>
                    <p id="cliente_selected_value_10_enabled" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>

                <div class="mt-4 flex gap-2">
                    <button
                        type="button"
                        onclick="example10.disable()"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-sm"
                    >
                        Desabilitar Campo
                    </button>
                    <button
                        type="button"
                        onclick="example10.enable()"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
                    >
                        Habilitar Campo
                    </button>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>Importante
                    </p>
                    <p class="text-xs text-yellow-700">
                        Campos disabled não permitem interação. Use quando o campo deve ser apenas visual ou quando o valor já está definido e não pode ser alterado. Use os botões acima para testar os métodos <code>disable()</code> e <code>enable()</code>.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-gray-500">// HTML: Adicionar atributo disabled no input</span>
<span class="text-purple-400">&lt;input</span>
    <span class="text-green-400">type</span>=<span class="text-yellow-300">"text"</span>
    <span class="text-green-400">id</span>=<span class="text-yellow-300">"cliente_example_10"</span>
    <span class="text-green-400">disabled</span>
    <span class="text-green-400">class</span>=<span class="text-yellow-300">"..."</span>
<span class="text-purple-400">&gt;</span>

<span class="text-gray-500">// JavaScript: CSelect funciona normalmente com campos disabled</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">example10</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_10'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">value</span>: <span class="text-yellow-300">'c1702650-d9fb-48e9-b67b-112fe8ce9ab8'</span>, <span class="text-gray-500">// Valor pré-selecionado</span>
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, value, label);
    }
});

<span class="text-gray-500">// Para habilitar/desabilitar programaticamente:</span>
<span class="text-gray-500">// Desabilitar</span>
example10.<span class="text-purple-400">disable</span>();
<span class="text-gray-500">// Habilitar</span>
example10.<span class="text-purple-400">enable</span>();</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 11: CSelect com setValue() -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-500 text-white rounded-lg p-2">
                    <i class="fas fa-code text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 11: CSelect com setValue()</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (use setValue para selecionar) *</label>
                    <input
                        type="text"
                        id="cliente_example_11"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 flex gap-2">
                    <button
                        type="button"
                        onclick="example11.setValue('c1702650-d9fb-48e9-b67b-112fe8ce9ab8')"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm"
                    >
                        Usar setValue() (buscar via AJAX)
                    </button>
                    <button
                        type="button"
                        onclick="example11.clear()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_selected_value_11" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example11</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_11'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>
    }
});

<span class="text-gray-500">// Selecionar valor programaticamente</span>
<span class="text-gray-500">// Se o item não estiver na lista, busca automaticamente via AJAX</span>
example11.<span class="text-purple-400">setValue</span>(<span class="text-yellow-300">'c1702650-d9fb-48e9-b67b-112fe8ce9ab8'</span>);
<span class="text-gray-500">// O label será obtido automaticamente da API</span>

<span class="text-gray-500">// Limpar seleção</span>
example11.<span class="text-purple-400">clear</span>();</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 12: CSelect com hideHiddenInput -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-orange-500 text-white rounded-lg p-2">
                    <i class="fas fa-eye-slash text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 12: CSelect com hideHiddenInput</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (com hideHiddenInput: false) *</label>
                    <input
                        type="text"
                        id="cliente_example_12_false"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (com hideHiddenInput: true) *</label>
                    <input
                        type="text"
                        id="cliente_example_12_true"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle text-blue-600 mr-1"></i>Diferença
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        <strong>hideHiddenInput: false</strong> (padrão): Cria o input hidden para enviar no formulário.
                    </p>
                    <p class="text-xs text-blue-700">
                        <strong>hideHiddenInput: true</strong>: Não cria o input hidden. Use quando quiser gerenciar o valor manualmente.
                    </p>
                </div>

                <div class="mb-4 flex gap-2">
                    <button
                        type="button"
                        onclick="updateExample12Values();"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
                    >
                        Atualizar Valores (getValue)
                    </button>
                    <button
                        type="button"
                        onclick="example12False.clear(); example12True.clear(); updateExample12Values();"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar Ambos
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-code text-green-500 mr-1"></i>Valores obtidos com getValue()
                    </p>
                    <p id="example_12_values" class="text-xs text-gray-700">
                        Nenhum valor selecionado ainda
                    </p>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle text-blue-600 mr-1"></i>Verificar no DevTools
                    </p>
                    <p class="text-xs text-blue-700">
                        Abra o DevTools (F12) e inspecione os elementos. O primeiro campo terá um input hidden, o segundo não.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-gray-500">// Exemplo com hideHiddenInput: false (padrão)</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">example12False</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_12_false'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">hideHiddenInput</span>: <span class="text-orange-400">false</span>, <span class="text-gray-500">// Cria input hidden</span>
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado (com input hidden):'</span>, { value, label });
    }
});

<span class="text-gray-500">// Exemplo com hideHiddenInput: true</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">example12True</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_12_true'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">hideHiddenInput</span>: <span class="text-orange-400">true</span>, <span class="text-gray-500">// NÃO cria input hidden</span>
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>
    },
    <span class="text-green-400">onSelect</span>: <span class="text-pink-400">(value, label) =&gt;</span> {
        <span class="text-gray-500">// Gerenciar valor manualmente</span>
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Valor selecionado:'</span>, value);
    }
});

<span class="text-gray-500">// Obter valores (funciona em ambos os casos)</span>
<span class="text-gray-500">// hideHiddenInput: false - retorna do input hidden</span>
<span class="text-gray-500">// hideHiddenInput: true - retorna do config.selectedValue</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">valor1</span> = example12False.<span class="text-purple-400">getValue</span>();
<span class="text-purple-400">const</span> <span class="text-blue-400">valor2</span> = example12True.<span class="text-purple-400">getValue</span>();</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 13: CSelect com getValue() -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 text-white rounded-lg p-2">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 13: CSelect com getValue()</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (selecione um valor) *</label>
                    <input
                        type="text"
                        id="cliente_example_13"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 flex gap-2">
                    <button
                        type="button"
                        onclick="getValueExample()"
                        class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition-colors text-sm"
                    >
                        Obter Valor (getValue)
                    </button>
                    <button
                        type="button"
                        onclick="example13.setValue('c1702650-d9fb-48e9-b67b-112fe8ce9ab8')"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm"
                    >
                        Definir Valor
                    </button>
                    <button
                        type="button"
                        onclick="example13.clear()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-emerald-500 mr-1"></i>Valor atual
                    </p>
                    <p id="cliente_value_display_13" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-lightbulb text-blue-600 mr-1"></i>Dica
                    </p>
                    <p class="text-xs text-blue-700">
                        Use o botão "Obter Valor" para recuperar o valor selecionado atual usando o método <code>getValue()</code>. O valor será exibido acima e no console.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example13</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_13'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>
    }
});

<span class="text-gray-500">// Obter o valor selecionado</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">valor</span> = example13.<span class="text-purple-400">getValue</span>();
console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Valor selecionado:'</span>, valor);

<span class="text-gray-500">// Exemplo de uso em uma função</span>
<span class="text-purple-400">function</span> <span class="text-blue-400">getValueExample</span>() {
    <span class="text-purple-400">const</span> <span class="text-blue-400">valor</span> = example13.<span class="text-purple-400">getValue</span>();
    <span class="text-purple-400">if</span> (valor) {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Valor encontrado:'</span>, valor);
        document.<span class="text-purple-400">getElementById</span>(<span class="text-yellow-300">'cliente_value_display_13'</span>)
            .textContent = <span class="text-yellow-300">`Valor: ${valor}`</span>;
    } <span class="text-purple-400">else</span> {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Nenhum valor selecionado'</span>);
        document.<span class="text-purple-400">getElementById</span>(<span class="text-yellow-300">'cliente_value_display_13'</span>)
            .textContent = <span class="text-yellow-300">'Nenhum valor selecionado'</span>;
    }
}</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 14: CSelect com Refresh Button -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-purple-500 text-white rounded-lg p-2">
                    <i class="fas fa-sync-alt text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 14: CSelect com Botão de Atualizar (Refresh)</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (com botão de atualizar) *</label>
                    <input
                        type="text"
                        id="cliente_example_14"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 flex gap-2">
                    <button
                        type="button"
                        onclick="example14.refresh()"
                        class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition-colors text-sm"
                    >
                        <i class="fas fa-sync-alt mr-1"></i>
                        Forçar Refresh (refresh())
                    </button>
                    <button
                        type="button"
                        onclick="example14.clear()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-purple-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_value_display_14" class="text-sm font-medium text-gray-800">
                        Nenhum cliente selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-lightbulb text-blue-600 mr-1"></i>Dica
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        Quando o input está ativo (sem item selecionado), você verá um ícone de atualizar (sincronização) no canto direito do input, na mesma posição onde a lixeira aparece quando há um item selecionado. Este botão recarrega os dados da API com o termo de busca atual. O botão só aparece quando <code>http.refresh: true</code> está configurado e o input está visível.
                    </p>
                    <p class="text-xs text-blue-700">
                        Você também pode forçar um refresh programaticamente usando o método <code>refresh()</code>, que limpa o cache e busca novamente os dados da API. Use o botão acima para testar.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example14</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_14'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">minSearchLength</span>: <span class="text-orange-400">0</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>,
        <span class="text-green-400">refresh</span>: <span class="text-orange-400">true</span> <span class="text-gray-500">// Ativa o botão de atualizar</span>
    },
    <span class="text-green-400">onSelect</span>: (<span class="text-blue-400">value</span>, <span class="text-blue-400">label</span>) => {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, { value, label });
    }
});

<span class="text-gray-500">// Forçar refresh programaticamente</span>
example14.<span class="text-purple-400">refresh</span>(); <span class="text-gray-500">// Limpa cache e busca novamente</span></code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 15: CSelect com setValue() e forceRefresh -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-500 text-white rounded-lg p-2">
                    <i class="fas fa-sync text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 15: CSelect com setValue() e forceRefresh</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <input
                        type="text"
                        id="cliente_example_15"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID para setValue (exemplo)</label>
                    <input
                        type="text"
                        id="cliente_id_example_15"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Digite o ID do cliente..."
                        value="c1702650-d9fb-48e9-b67b-112fe8ce9ab8"
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 flex flex-col gap-2">
                    <button
                        type="button"
                        onclick="const id = document.getElementById('cliente_id_example_15').value; if(id) example15.setValue(id, '', false);"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm"
                    >
                        setValue(id, '', false) - Usar Cache
                    </button>
                    <button
                        type="button"
                        onclick="const id = document.getElementById('cliente_id_example_15').value; if(id) example15.setValue(id, '', true);"
                        class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600 transition-colors text-sm"
                    >
                        setValue(id, '', true) - Force Refresh
                    </button>
                    <button
                        type="button"
                        onclick="example15.clear()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar
                    </button>
                </div>

                <div class="mt-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-indigo-500 mr-1"></i>Valor selecionado
                    </p>
                    <p id="cliente_value_display_15" class="text-sm font-medium text-gray-800">
                        Nenhum cliente selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-lightbulb text-blue-600 mr-1"></i>Dica
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        O método <code>setValue(id, label, forceRefresh)</code> aceita um terceiro parâmetro opcional <code>forceRefresh</code> (padrão: <code>false</code>).
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        Quando <code>forceRefresh</code> é <code>false</code> (padrão), o método usa o cache se disponível, evitando requisições desnecessárias.
                    </p>
                    <p class="text-xs text-blue-700">
                        Quando <code>forceRefresh</code> é <code>true</code>, o método ignora o cache e sempre faz uma nova busca AJAX, garantindo dados atualizados.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">example15</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente_example_15'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">minSearchLength</span>: <span class="text-orange-400">0</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>,
        <span class="text-green-400">method</span>: <span class="text-yellow-300">'GET'</span>,
        <span class="text-green-400">searchParam</span>: <span class="text-yellow-300">'search'</span>
    },
    <span class="text-green-400">onSelect</span>: (<span class="text-blue-400">value</span>, <span class="text-blue-400">label</span>) => {
        console.<span class="text-purple-400">log</span>(<span class="text-yellow-300">'Cliente selecionado:'</span>, { value, label });
    }
});

<span class="text-gray-500">// Usar cache (padrão)</span>
example15.<span class="text-purple-400">setValue</span>(<span class="text-yellow-300">'id-do-cliente'</span>, <span class="text-yellow-300">''</span>, <span class="text-orange-400">false</span>);

<span class="text-gray-500">// Forçar refresh (ignorar cache)</span>
example15.<span class="text-purple-400">setValue</span>(<span class="text-yellow-300">'id-do-cliente'</span>, <span class="text-yellow-300">''</span>, <span class="text-orange-400">true</span>);</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 16: CSelect com hideHiddenInput() dinâmico -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-pink-50 to-rose-50 px-6 py-4 border-b border-gray-200 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-pink-500 text-white rounded-lg p-2">
                    <i class="fas fa-toggle-on text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Exemplo 16: CSelect com hideHiddenInput() dinâmico</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-eye text-blue-500"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Demonstração</h3>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <input
                        type="text"
                        id="cliente_example_16"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Digite para buscar cliente..."
                        autocomplete="off"
                    >
                </div>

                <div class="mb-4 flex flex-col gap-2">
                    <button
                        type="button"
                        onclick="example16.hideHiddenInput(true); updateExample16Status();"
                        class="px-4 py-2 bg-pink-500 text-white rounded-md hover:bg-pink-600 transition-colors text-sm"
                    >
                        hideHiddenInput(true) - Remover Input Hidden
                    </button>
                    <button
                        type="button"
                        onclick="example16.hideHiddenInput(false); updateExample16Status();"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm"
                    >
                        hideHiddenInput(false) - Criar Input Hidden
                    </button>
                    <button
                        type="button"
                        onclick="example16.clear(); updateExample16Status();"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm"
                    >
                        Limpar
                    </button>
                </div>

                <div class="mb-4 p-4 bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-info-circle text-pink-500 mr-1"></i>Status do Input Hidden
                    </p>
                    <p id="example_16_status" class="text-sm font-medium text-gray-800">
                        Verificando...
                    </p>
                </div>

                <div class="mb-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                        <i class="fas fa-code text-green-500 mr-1"></i>Valor atual (getValue)
                    </p>
                    <p id="example_16_value" class="text-sm font-medium text-gray-800">
                        Nenhum valor selecionado
                    </p>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-800 mb-2">
                        <i class="fas fa-lightbulb text-blue-600 mr-1"></i>Dica
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        Use <code>hideHiddenInput(true)</code> para remover o input hidden e gerenciar o valor manualmente via JavaScript.
                    </p>
                    <p class="text-xs text-blue-700 mb-2">
                        Use <code>hideHiddenInput(false)</code> para criar o input hidden novamente e permitir que o valor seja enviado no formulário.
                    </p>
                    <p class="text-xs text-blue-700">
                        Abra o DevTools (F12) e inspecione o elemento para ver o input hidden sendo criado/removido.
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-green-400"></i>
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Código</h3>
                </div>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">const</span> <span class="text-blue-400">select</span> = <span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#meu_input'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">http</span>: {
        <span class="text-green-400">url</span>: <span class="text-yellow-300">'http://localhost:8000/api/clientes/search'</span>
    }
});

<span class="text-gray-500">// Remover input hidden</span>
select.<span class="text-purple-400">hideHiddenInput</span>(<span class="text-orange-400">true</span>);

<span class="text-gray-500">// Criar input hidden novamente</span>
select.<span class="text-purple-400">hideHiddenInput</span>(<span class="text-orange-400">false</span>);

<span class="text-gray-500">// O valor é preservado durante a transição</span>
<span class="text-purple-400">const</span> <span class="text-blue-400">valor</span> = select.<span class="text-purple-400">getValue</span>();</code></pre>
            </div>
        </div>
    </div>

    <!-- Exemplo 7: Formulário Completo -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Exemplo 7: Formulário Completo com Validação</h2>

        <form id="cselect_form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                <input
                    type="text"
                    name="nome"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <div>
                <div id="cselect_example_7"></div>
            </div>

            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                <p class="text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>Valor selecionado
                </p>
                <p id="example_7_selected_value" class="text-sm font-medium text-gray-800">
                    Nenhum valor selecionado
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                <textarea
                    name="observacoes"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            <div class="flex gap-4">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
                >
                    Salvar
                </button>
                <button
                    type="reset"
                    class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors"
                >
                    Limpar
                </button>
            </div>
        </form>

        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm font-semibold text-gray-700 mb-2">Código:</p>
            <pre class="text-sm text-gray-800"><code>&lt;form id="cselect_form"&gt;
    &lt;div id="cselect_example_7"&gt;&lt;/div&gt;
    &lt;!-- outros campos --&gt;
&lt;/form&gt;

&lt;script&gt;
// Inicializar CSelect e validação aqui
&lt;/script&gt;</code></pre>
        </div>
    </div>
</div>

<!-- Incluir CSelect CSS -->
<link rel="stylesheet" href="{{ asset('css/cselect.css') }}">

<!-- Incluir CSelect JS -->
<script src="{{ asset('js/cselect.js') }}"></script>

<script>
    // Área reservada para inicialização dos exemplos
    console.log('CSelect Demo Page Loaded');

    // Exemplo 1: Básico
    const example1 = cSelect('#forma_pagamento_example_1', {
        name: 'forma_pagamento_id',
        debug: true, // ✅ Ativar logs do console
        items: [
            { value: '1', label: 'A Combinar' },
            { value: '2', label: 'Boleto Bancário' },
            { value: '3', label: 'Carnê' },
            { value: '4', label: 'Cartão de Crédito' },
            { value: '5', label: 'Cartão de Débito' },
            { value: '6', label: 'Cheque' },
            { value: '7', label: 'Devolução de Mercadorias' },
            { value: '8', label: 'Dinheiro à Vista' }
        ],
        onSelect: (value, label) => {
            console.log('✅ Callback onSelect chamado:', { value, label });

            // Mostrar debug
            setTimeout(() => {
                const hiddenInput = document.getElementById('forma_pagamento_example_1_hidden');
                if (hiddenInput) {
                    document.getElementById('debug_example_1').classList.remove('hidden');
                    document.getElementById('debug_hidden_id_1').textContent = hiddenInput.id;
                    document.getElementById('debug_hidden_name_1').textContent = hiddenInput.name;
                    document.getElementById('debug_hidden_value_1').textContent = hiddenInput.value;
                }
            }, 100);
        },
        onClear: () => {
            console.log('🗑️ Callback onClear chamado');
            document.getElementById('debug_example_1').classList.add('hidden');
        }
    });

    // Exemplo 2: Com Title e Subtitle
    const example2 = cSelect('#produto_example_2', {
        name: 'produto_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'categoria',
        items: [
            { id: '1', nome: 'Notebook Dell Inspiron 15', categoria: 'Informática' },
            { id: '2', nome: 'Mouse Logitech MX Master 3', categoria: 'Acessórios' },
            { id: '3', nome: 'Teclado Mecânico RGB', categoria: 'Periféricos' },
            { id: '4', nome: 'Monitor LG UltraWide 34"', categoria: 'Informática' },
            { id: '5', nome: 'Webcam Logitech C920', categoria: 'Acessórios' },
            { id: '6', nome: 'Headset HyperX Cloud', categoria: 'Áudio' },
            { id: '7', nome: 'SSD Samsung 1TB', categoria: 'Armazenamento' },
            { id: '8', nome: 'Cadeira Gamer DXRacer', categoria: 'Mobiliário' }
        ],
        onSelect: (value, label) => {
            console.log('Produto selecionado:', { value, label });
            document.getElementById('produto_selected_value_2').textContent = `${label} (ID: ${value})`;
        }
    });

    // Exemplo 3: Com AJAX
    const example3 = cSelect('#cliente_example_3', {
        name: 'cliente_id',
        debug: true,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET'
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado via AJAX:', { value, label });
            document.getElementById('cliente_selected_value_3').textContent = `${label} (ID: ${value})`;
        }
    });

    // Exemplo 4: AJAX com Botão "Adicionar Novo"
    const example4 = cSelect('#cliente_example_4', {
        name: 'cliente_id',
        debug: true,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            searchParam: 'search'
        },
        addButton: {
            text: 'Adicionar novo cliente',
            class: ''
        },
        onClickButton: (selectId) => {
            console.log('Abrindo modal de novo cliente via AJAX...', selectId);
            // O selectId é passado automaticamente pelo CSelect
            // Abrir modal de cliente (o selectId será definido automaticamente)
            window.openModal('cliente-modal', selectId);
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado via AJAX:', { value, label });
            document.getElementById('cliente_selected_value_4').textContent = `${label} (ID: ${value})`;
        }
    });

    // Exemplo 5: Cliente com Botão "Adicionar Novo"
    const example5 = cSelect('#cliente_example_5', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        items: [
            { id: '1', nome: 'João Silva', email: 'joao@exemplo.com' },
            { id: '2', nome: 'Maria Santos', email: 'maria@exemplo.com' },
            { id: '3', nome: 'Empresa ABC Ltda', email: 'contato@abc.com' },
            { id: '4', nome: 'Pedro Oliveira', email: 'pedro@exemplo.com' },
            { id: '5', nome: 'Ana Costa', email: 'ana.costa@exemplo.com' },
            { id: '6', nome: 'Carlos Mendes', email: 'carlos.mendes@exemplo.com' },
            { id: '7', nome: 'Fernanda Lima', email: 'fernanda.lima@exemplo.com' },
            { id: '8', nome: 'Roberto Alves', email: 'roberto.alves@exemplo.com' },
            { id: '9', nome: 'Juliana Ferreira', email: 'juliana.ferreira@exemplo.com' },
            { id: '10', nome: 'Lucas Souza', email: 'lucas.souza@exemplo.com' },
            { id: '11', nome: 'Patricia Rocha', email: 'patricia.rocha@exemplo.com' },
            { id: '12', nome: 'Rafael Martins', email: 'rafael.martins@exemplo.com' },
            { id: '13', nome: 'Camila Barbosa', email: 'camila.barbosa@exemplo.com' },
            { id: '14', nome: 'Thiago Ribeiro', email: 'thiago.ribeiro@exemplo.com' },
            { id: '15', nome: 'Amanda Dias', email: 'amanda.dias@exemplo.com' },
            { id: '16', nome: 'Bruno Carvalho', email: 'bruno.carvalho@exemplo.com' },
            { id: '17', nome: 'Gabriela Nunes', email: 'gabriela.nunes@exemplo.com' },
            { id: '18', nome: 'Marcelo Araújo', email: 'marcelo.araujo@exemplo.com' },
            { id: '19', nome: 'Larissa Monteiro', email: 'larissa.monteiro@exemplo.com' },
            { id: '20', nome: 'Felipe Cardoso', email: 'felipe.cardoso@exemplo.com' }
        ],
        addButton: {
            text: 'Adicionar novo cliente',
            class: ''
        },
        onClickButton: (selectId) => {
            console.log('Abrindo modal de novo cliente...', selectId);
            // O selectId é passado automaticamente pelo CSelect
            // Abrir modal de cliente (o selectId será definido automaticamente)
            window.openModal('cliente-modal', selectId);
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado:', { value, label });
            document.getElementById('cliente_selected_value_5').textContent = `${label} (ID: ${value})`;
        }
    });


    // Callback para quando um cliente for criado (Exemplos 4 e 5)
    // O modal de cliente já tem sua própria lógica de salvamento via AJAX
    // Aqui apenas atualizamos o select quando o cliente for criado
    // NOTA: O modal já chama selectItemProgrammatically, então aqui apenas adicionamos à lista
    // e atualizamos o display, sem chamar setValue novamente para evitar duplicação
    if (typeof window.onClienteCreated === 'undefined') {
        window.onClienteCreated = function(cliente) {
            console.log('Cliente criado:', cliente);

            const novoItem = {
                id: cliente.id.toString(),
                nome: cliente.nome || cliente.nome_completo,
                email: cliente.email || ''
            };

            // Atualizar Exemplo 4 (AJAX) - apenas atualizar display se necessário
            if (typeof example4 !== 'undefined') {
                const displayEl = document.getElementById('cliente_selected_value_4');
                if (displayEl) {
                    displayEl.textContent = `${novoItem.nome} (ID: ${novoItem.id})`;
                }
            }

            // Adicionar à lista do select do Exemplo 5 (dados fixos)
            if (typeof example5 !== 'undefined' && example5.config) {
                // Verificar se o item já não existe na lista
                const itemExists = example5.config.items.some(item => item.id === novoItem.id);
                if (!itemExists) {
                    example5.config.items.push(novoItem);
                }

                // Atualizar display (não chamar setValue aqui pois o modal já faz isso)
                const displayEl = document.getElementById('cliente_selected_value_5');
                if (displayEl) {
                    displayEl.textContent = `${novoItem.nome} (ID: ${novoItem.id})`;
                }
            }
        };
    }

    // Exemplo 6: Com Valor Pré-selecionado
    console.log('Inicializar Exemplo 6...');

    // Exemplo 8: AJAX com Valor Pré-selecionado
    const example8 = cSelect('#cliente_example_8', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        value: 'c1702650-d9fb-48e9-b67b-112fe8ce9ab8', // ID do item pré-selecionado
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado (pré-selecionado):', { value, label });
            document.getElementById('cliente_selected_value_8').textContent = `${label} (ID: ${value})`;
        }
    });

    // Exemplo 9: CSelect sem Input Hidden
    const example9 = cSelect('#cliente_example_9', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        hideHiddenInput: true, // Não cria o input hidden
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            // Gerenciar valor manualmente (sem input hidden)
            const manualInput = document.getElementById('cliente_value_manual');
            if (manualInput) {
                manualInput.value = value;
            }

            // Atualizar display
            document.getElementById('cliente_selected_value_9').textContent = `${label} (ID: ${value})`;

            console.log('Cliente selecionado (sem input hidden):', { value, label });
            console.log('Input hidden não foi criado. Valor gerenciado via JavaScript.');
        },
        onClear: () => {
            // Limpar valor manual quando a seleção for removida
            const manualInput = document.getElementById('cliente_value_manual');
            if (manualInput) {
                manualInput.value = '';
            }
            document.getElementById('cliente_selected_value_9').textContent = 'Nenhum valor selecionado';
        }
    });

    // Exemplo 10: CSelect com Campo Disabled
    const example10 = cSelect('#cliente_example_10', {
        name: 'cliente_id_disabled',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_selected_value_10').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado:', { value, label });
        }
    });

    // Tornar example10 disponível globalmente para os botões
    window.example10 = example10;

    // Exemplo 11: CSelect com setValue()
    const example11 = cSelect('#cliente_example_11', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_selected_value_11').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado:', { value, label });
        }
    });
    window.example11 = example11;

    // Exemplo 13: CSelect com getValue()
    const example13 = cSelect('#cliente_example_13', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_value_display_13').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado:', { value, label });
        }
    });
    window.example13 = example13;

    // Função para demonstrar getValue()
    window.getValueExample = function() {
        const valor = example13.getValue();
        if (valor) {
            console.log('✅ Valor encontrado:', valor);
            document.getElementById('cliente_value_display_13').textContent = `Valor: ${valor}`;
        } else {
            console.log('⚠️ Nenhum valor selecionado');
            document.getElementById('cliente_value_display_13').textContent = 'Nenhum valor selecionado';
        }
    };

    // Exemplo 14: CSelect com Refresh Button
    const example14 = cSelect('#cliente_example_14', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search',
            refresh: true // Ativa o botão de atualizar no dropdown
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_value_display_14').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado:', { value, label });
        }
    });
    window.example14 = example14;

    // Exemplo 15: CSelect com setValue() e forceRefresh
    const example15 = cSelect('#cliente_example_15', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_value_display_15').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado:', { value, label });
        }
    });
    window.example15 = example15;

    // Exemplo 16: CSelect com hideHiddenInput() dinâmico
    const example16 = cSelect('#cliente_example_16', {
        name: 'cliente_id',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            updateExample16Status();
            updateExample16Value();
            console.log('Cliente selecionado:', { value, label });
        }
    });
    window.example16 = example16;

    // Função para atualizar o status do input hidden
    window.updateExample16Status = function() {
        const container = document.getElementById('cliente_example_16')?.closest('.cselect-container');
        const hiddenInput = container?.querySelector('.cselect-hidden-input');
        const statusEl = document.getElementById('example_16_status');
        
        if (statusEl) {
            if (hiddenInput) {
                statusEl.innerHTML = '<span class="text-green-600">✅ Input hidden existe</span><br><small class="text-gray-600">ID: ' + hiddenInput.id + '</small>';
            } else {
                statusEl.innerHTML = '<span class="text-pink-600">❌ Input hidden não existe</span><br><small class="text-gray-600">Valor gerenciado via config.selectedValue</small>';
            }
        }
        
        // Também atualizar o valor
        updateExample16Value();
    };

    // Função para atualizar o valor exibido
    window.updateExample16Value = function() {
        const valueEl = document.getElementById('example_16_value');
        if (valueEl && example16) {
            const valor = example16.getValue();
            valueEl.textContent = valor || 'Nenhum valor selecionado';
        }
    };

    // Atualizar status inicial
    setTimeout(() => {
        updateExample16Status();
        updateExample16Value();
    }, 100);

    // Exemplo 12: CSelect com hideHiddenInput
    const example12False = cSelect('#cliente_example_12_false', {
        name: 'cliente_id_false',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        hideHiddenInput: false, // Cria input hidden (padrão)
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado (com input hidden):', { value, label });
            updateExample12Values();
        }
    });
    window.example12False = example12False;

    const example12True = cSelect('#cliente_example_12_true', {
        name: 'cliente_id_true',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        hideHiddenInput: true, // NÃO cria input hidden
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado (sem input hidden):', { value, label });
            console.log('Valor deve ser gerenciado manualmente via JavaScript');
            updateExample12Values();
        }
    });
    window.example12True = example12True;

    // Função para atualizar os valores exibidos
    function updateExample12Values() {
        const val1 = example12False.getValue();
        const val2 = example12True.getValue();
        const display = document.getElementById('example_12_values');
        if (display) {
            let html = '<div class="space-y-1">';
            html += `<div><strong>hideHiddenInput: false</strong>: ${val1 || 'Nenhum valor'}</div>`;
            html += `<div><strong>hideHiddenInput: true</strong>: ${val2 || 'Nenhum valor'}</div>`;
            html += '</div>';
            display.innerHTML = html;
        }
    }

    // Exemplo 10 (enabled): Para comparação
    const example10Enabled = cSelect('#cliente_example_10_enabled', {
        name: 'cliente_id_enabled',
        debug: false,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        minSearchLength: 0,
        http: {
            url: 'http://localhost:8000/api/clientes/search',
            method: 'GET',
            searchParam: 'search'
        },
        onSelect: (value, label) => {
            document.getElementById('cliente_selected_value_10_enabled').textContent = `${label} (ID: ${value})`;
            console.log('Cliente selecionado (enabled):', { value, label });
        }
    });

    // Exemplo 7: Formulário Completo
    const form = document.getElementById('cselect_form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulário submetido!');

            // Validação será implementada aqui
            const formData = new FormData(form);
            console.log('Dados do formulário:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }

            alert('Formulário validado com sucesso!');
        });
    }
</script>

<!-- Documentação de Parâmetros -->
<div class="container mx-auto px-4 py-12 mt-16">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-12 text-white">
        <div class="text-center">
            <div class="inline-block bg-white bg-opacity-20 rounded-full p-4 mb-4">
                <i class="fas fa-book text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3">📚 Documentação de Parâmetros</h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">
                Referência completa de todas as opções disponíveis do CSelect
            </p>
        </div>
    </div>

    <!-- Sintaxe Básica -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-8 mb-8 border border-blue-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-blue-500 text-white rounded-lg p-2">
                <i class="fas fa-rocket"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">🚀 Sintaxe Básica</h2>
        </div>
        <pre class="bg-gray-900 text-gray-100 p-4 rounded-md overflow-x-auto"><code>cSelect(selector, options)</code></pre>

        <div class="mt-4">
            <p class="text-gray-700 mb-2"><strong>Exemplo:</strong></p>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>const select = cSelect('#meu_input', {
    name: 'campo_id',
    items: [
        { value: '1', label: 'Opção 1' },
        { value: '2', label: 'Opção 2' }
    ]
});</code></pre>
        </div>
    </div>

    <!-- Tabela de Parâmetros -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-5 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <i class="fas fa-cog text-white text-2xl"></i>
                <h2 class="text-2xl font-bold text-white">⚙️ Parâmetros de Configuração</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Parâmetro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Padrão</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Obrigatório</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Descrição</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- name -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">name</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>''</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Sim</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome do input hidden que será enviado no formulário</td>
                    </tr>

                    <!-- items -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">items</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">array</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>[]</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Array de objetos com as opções do select. Cada objeto deve ter as propriedades definidas em <code>itemValue</code> e <code>itemLabel</code> (ou <code>itemTitle</code>/<code>itemSubtitle</code> para layout de duas linhas)</td>
                    </tr>

                    <!-- minSearchLength -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">minSearchLength</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">number</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>0</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Número mínimo de caracteres para iniciar a busca (útil para AJAX)</td>
                    </tr>

                    <!-- placeholder -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">placeholder</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><code>'Digite para buscar'</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Texto placeholder do input</td>
                    </tr>

                    <!-- itemValue -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">itemValue</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>'value'</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do objeto que contém o valor único do item (será usado no input hidden e no callback <code>onSelect</code>)</td>
                    </tr>

                    <!-- itemLabel -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">itemLabel</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>'label'</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do objeto que contém o texto exibido do item (modo simples, uma linha). Use este parâmetro quando não estiver usando <code>itemTitle</code> e <code>itemSubtitle</code></td>
                    </tr>

                    <!-- itemTitle -->
                    <tr class="hover:bg-gray-50 bg-green-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-green-100 text-green-800 px-2 py-1 rounded">itemTitle</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do objeto que contém o título (linha principal em negrito). Use junto com <code>itemSubtitle</code> para criar um layout de duas linhas no dropdown. Quando um item é selecionado, apenas o título é exibido (o subtítulo não aparece)</td>
                    </tr>

                    <!-- itemSubtitle -->
                    <tr class="hover:bg-gray-50 bg-green-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-green-100 text-green-800 px-2 py-1 rounded">itemSubtitle</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do objeto que contém o subtítulo (linha secundária em cinza). Use junto com <code>itemTitle</code> para criar um layout de duas linhas no dropdown. O subtítulo aparece apenas no dropdown, não no item selecionado</td>
                    </tr>

                    <!-- loadOnOpen -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">loadOnOpen</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">boolean</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>true</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Se <code>true</code>, carrega os dados automaticamente ao abrir o dropdown. Útil para requisições AJAX ou quando os dados precisam ser carregados sob demanda</td>
                    </tr>

                    <!-- debug -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">debug</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">boolean</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>false</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Se <code>true</code>, exibe logs no console para debug. Se <code>false</code>, não exibe nenhum log</td>
                    </tr>

                    <!-- onSelect -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">onSelect</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">function</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Callback executado quando um item é selecionado. Recebe <code>(value, label)</code> como parâmetros</td>
                    </tr>

                    <!-- onClear -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">onClear</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">function</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Callback executado quando a seleção é removida (click na lixeira)</td>
                    </tr>

                    <!-- value -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">value</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">ID do item que será pré-selecionado ao inicializar. Para modo AJAX, o CSelect busca automaticamente da API. Para modo fixed, busca na lista de <code>items</code></td>
                    </tr>

                    <!-- selectedItemData -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">selectedItemData</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">object</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Dados completos do item pré-selecionado (útil para modo AJAX quando você já tem os dados do item). Use junto com <code>value</code> para evitar uma requisição adicional à API</td>
                    </tr>

                    <!-- hideHiddenInput -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">hideHiddenInput</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">boolean</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>false</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Se <code>true</code>, não cria o input hidden que normalmente é usado para enviar o valor no formulário. Útil quando você quer gerenciar o valor de outra forma (ex: via JavaScript ou outro campo)</td>
                    </tr>

                    <!-- addButton -->
                    <tr class="hover:bg-gray-50 bg-teal-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-teal-100 text-teal-800 px-2 py-1 rounded">addButton</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">object</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Configuração do botão "Adicionar Novo" que aparece fixo no final do dropdown. Formato: <code>{ text: 'Adicionar novo', class: 'btn-class' }</code>. Se <code>modal</code> ou <code>modalHtml</code> estiver configurado, o botão abrirá o modal automaticamente</td>
                    </tr>

                    <!-- onClickButton -->
                    <tr class="hover:bg-gray-50 bg-teal-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-teal-100 text-teal-800 px-2 py-1 rounded">onClickButton</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">function</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Callback executado quando o botão "Adicionar Novo" é clicado. Recebe o <code>selectId</code> (ID do input) como parâmetro. Use apenas se não estiver usando <code>modal</code> ou <code>modalHtml</code>. Se o modal estiver configurado, ele será aberto automaticamente e este callback não será executado. Exemplo: <code>onClickButton: (selectId) => { window.openModal('meu-modal', selectId); }</code></td>
                    </tr>

                    <!-- modal -->
                    <tr class="hover:bg-gray-50 bg-purple-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-purple-100 text-purple-800 px-2 py-1 rounded">modal</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">ID ou classe CSS do modal existente no DOM (ex: <code>'#meu-modal'</code> ou <code>'.modal-classe'</code>). O modal será aberto automaticamente ao clicar no botão "Adicionar Novo"</td>
                    </tr>

                    <!-- modalHtml -->
                    <tr class="hover:bg-gray-50 bg-purple-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-purple-100 text-purple-800 px-2 py-1 rounded">modalHtml</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">HTML do modal para criar dinamicamente. Use quando não tiver um modal existente no DOM. O modal será criado e adicionado ao <code>body</code></td>
                    </tr>

                    <!-- modalId -->
                    <tr class="hover:bg-gray-50 bg-purple-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-purple-100 text-purple-800 px-2 py-1 rounded">modalId</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">ID único para o modal criado dinamicamente (usado apenas com <code>modalHtml</code>). Se não fornecido, será gerado automaticamente</td>
                    </tr>

                    <!-- http -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">object</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Configuração para requisições AJAX. Objeto com propriedades: <code>url</code> (string, obrigatório), <code>method</code> (string, padrão: 'GET'), <code>headers</code> (object, opcional), <code>searchParam</code> (string, padrão: 'search'), <code>refresh</code> (boolean, padrão: false). Exemplo: <code>{ url: '/api/clientes', method: 'GET', refresh: true }</code></td>
                    </tr>

                    <!-- http.url -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http.url</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>null</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">URL da API para buscar os dados. Quando configurado, o CSelect fará requisições AJAX para buscar os itens. O termo de busca será adicionado como parâmetro de query (nome do parâmetro definido em <code>http.searchParam</code>)</td>
                    </tr>

                    <!-- http.method -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http.method</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>'GET'</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Método HTTP para a requisição AJAX. Valores aceitos: <code>'GET'</code>, <code>'POST'</code>, <code>'PUT'</code>, <code>'PATCH'</code>. Para métodos POST/PUT/PATCH, o termo de busca será enviado no body como JSON</td>
                    </tr>

                    <!-- http.headers -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http.headers</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">object</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>{}</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Headers customizados para a requisição AJAX. Se não fornecido ou se não tiver <code>Content-Type</code>, será adicionado automaticamente <code>'Content-Type': 'application/json'</code>. Para rotas <code>/api/*</code>, o header <code>Authorization: Bearer {token}</code> é adicionado automaticamente (JWT)</td>
                    </tr>

                    <!-- http.searchParam -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http.searchParam</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">string</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>'search'</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Nome do parâmetro de query usado na URL para enviar o termo de busca. Exemplo: se <code>searchParam: 'q'</code>, a URL será <code>/api/clientes?q=termo</code>. Se não fornecido, usa <code>'search'</code> como padrão</td>
                    </tr>

                    <!-- http.refresh -->
                    <tr class="hover:bg-gray-50 bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm font-mono bg-orange-100 text-orange-800 px-2 py-1 rounded">http.refresh</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">boolean</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><code>false</code></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Opcional</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Se <code>true</code>, exibe um botão de atualizar (ícone de sincronização) no canto direito do input quando ele está ativo (sem item selecionado). O botão força uma nova busca AJAX ignorando o cache. O botão aparece na mesma posição onde a lixeira aparece quando há um item selecionado</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Métodos Retornados -->
    <div class="bg-white rounded-xl shadow-lg mb-8 border border-gray-100">
        <div class="bg-gradient-to-r from-green-600 via-teal-600 to-emerald-600 px-6 py-5 rounded-t-xl overflow-hidden">
            <div class="flex items-center gap-3">
                <i class="fas fa-tools text-white text-2xl"></i>
                <h2 class="text-2xl font-bold text-white">🔧 Métodos Retornados</h2>
            </div>
        </div>

        <div class="p-6">
            <p class="text-gray-700 mb-4">A função <code class="bg-gray-100 px-2 py-1 rounded">cSelect()</code> retorna um objeto com os seguintes métodos:</p>

            <div class="space-y-4">
                <!-- getValue() -->
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-blue-100 text-blue-800 px-2 py-1 rounded">getValue()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Retorna o valor atualmente selecionado.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});
const valor = select.getValue(); // Retorna '1', '2', etc.</code></pre>
                </div>

                <!-- setValue() -->
                <div class="border-l-4 border-green-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded">setValue(value, label, forceRefresh)</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Define o valor selecionado programaticamente.</p>
                    <p class="text-gray-600 text-sm mb-2"><strong>Parâmetros:</strong></p>
                    <ul class="text-gray-600 text-sm mb-2 list-disc list-inside space-y-1">
                        <li><code>value</code> (string, obrigatório): ID do item a ser selecionado</li>
                        <li><code>label</code> (string, opcional): Label do item. Se não fornecido e houver AJAX configurado, o CSelect buscará automaticamente da API</li>
                        <li><code>forceRefresh</code> (boolean, opcional, padrão: <code>false</code>): Se <code>true</code>, ignora o cache e sempre faz uma nova busca AJAX. Se <code>false</code>, usa o cache se disponível</li>
                    </ul>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});

// Usar cache se disponível (padrão)
select.setValue('2', 'Boleto Bancário');
select.setValue('2', '', false); // Equivale ao acima

// Forçar refresh (ignorar cache)
select.setValue('2', '', true);</code></pre>
                </div>

                <!-- clear() -->
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-red-100 text-red-800 px-2 py-1 rounded">clear()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Limpa a seleção atual.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});
select.clear(); // Remove a seleção e volta ao input</code></pre>
                </div>

                <!-- openModal() -->
                <div class="border-l-4 border-purple-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-purple-100 text-purple-800 px-2 py-1 rounded">openModal()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Abre o modal associado ao CSelect (se configurado com <code>modal</code> ou <code>modalHtml</code>).</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', { modal: '#meu-modal' });
select.openModal(); // Abre o modal</code></pre>
                </div>

                <!-- closeModal() -->
                <div class="border-l-4 border-purple-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-purple-100 text-purple-800 px-2 py-1 rounded">closeModal()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Fecha o modal associado ao CSelect.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', { modal: '#meu-modal' });
select.closeModal(); // Fecha o modal</code></pre>
                </div>

                <!-- disable() -->
                <div class="border-l-4 border-gray-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded">disable()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Desabilita o input do CSelect, impedindo interação do usuário. Fecha o dropdown se estiver aberto.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});
select.disable(); // Desabilita o campo</code></pre>
                </div>

                <!-- enable() -->
                <div class="border-l-4 border-gray-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded">enable()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Habilita o input do CSelect, permitindo interação do usuário.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});
select.enable(); // Habilita o campo</code></pre>
                </div>

                <!-- refresh() -->
                <div class="border-l-4 border-indigo-500 pl-4 py-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <code class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">refresh()</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Força uma atualização dos dados via AJAX, ignorando o cache. Limpa o cache do termo atual e faz uma nova busca.</p>
                    <p class="text-gray-600 text-sm mb-2"><strong>Nota:</strong> Disponível apenas quando <code>http</code> está configurado.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {
    http: { url: '/api/clientes' }
});
select.refresh(); // Força atualização ignorando cache</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Exemplos Práticos -->
    <div class="bg-gradient-to-r from-purple-50 via-pink-50 to-rose-50 rounded-xl shadow-lg p-8 mb-8 border border-purple-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-purple-500 text-white rounded-lg p-2">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">💡 Exemplos Práticos</h2>
        </div>

        <!-- Exemplo 1: Básico -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">1. Configuração Básica</h3>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>cSelect('#forma_pagamento', {
    name: 'forma_pagamento_id',
    items: [
        { value: '1', label: 'Dinheiro' },
        { value: '2', label: 'Cartão' }
    ]
});</code></pre>
        </div>

        <!-- Exemplo 2: Com Callbacks -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">2. Com Callbacks</h3>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>cSelect('#categoria', {
    name: 'categoria_id',
    items: [...],
    debug: true,
    onSelect: (value, label) => {
        console.log('Selecionado:', value, label);
        // Fazer algo quando selecionar
    },
    onClear: () => {
        console.log('Seleção removida');
        // Fazer algo quando limpar
    }
});</code></pre>
        </div>

        <!-- Exemplo 3: Propriedades Customizadas (Modo Simples) -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">3. Propriedades Customizadas - Modo Simples</h3>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>cSelect('#cliente', {
    name: 'cliente_id',
    itemValue: 'id',      // Usar 'id' ao invés de 'value'
    itemLabel: 'nome',    // Usar 'nome' ao invés de 'label'
    items: [
        { id: '1', nome: 'João Silva' },
        { id: '2', nome: 'Maria Santos' }
    ]
});</code></pre>
        </div>

        <!-- Exemplo 3.5: Title e Subtitle -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">3.5. Com Title e Subtitle (Duas Linhas)</h3>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>cSelect('#produto', {
    name: 'produto_id',
    itemValue: 'id',
    itemTitle: 'nome',          // Linha principal (negrito)
    itemSubtitle: 'categoria',  // Linha secundária (cinza)
    items: [
        { id: '1', nome: 'Notebook Dell', categoria: 'Informática' },
        { id: '2', nome: 'Mouse Logitech', categoria: 'Acessórios' }
    ]
});</code></pre>
        </div>

        <!-- Exemplo 4: Usando Métodos -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">4. Usando Métodos Retornados</h3>
            <pre class="bg-white border border-gray-300 p-4 rounded-md overflow-x-auto"><code>const select = cSelect('#produto', {
    name: 'produto_id',
    items: [...]
});

// Pegar valor atual
const valorAtual = select.getValue();

// Definir valor programaticamente
select.setValue('5', 'Produto XYZ');

// Limpar seleção
select.clear();</code></pre>
        </div>
    </div>

    <!-- Notas Importantes -->
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-400 p-8 rounded-r-xl shadow-md">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-yellow-400 text-white rounded-lg p-2">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="text-xl font-bold text-yellow-800">⚠️ Notas Importantes</h2>
        </div>
        <ul class="list-disc list-inside space-y-2 text-yellow-900">
            <li>O input HTML <strong>NÃO precisa</strong> ter o atributo <code>name</code> - ele é passado via JavaScript</li>
            <li>O componente cria automaticamente um <code>&lt;input type="hidden"&gt;</code> com o <code>name</code> especificado</li>
            <li>O parâmetro <code>name</code> é <strong>obrigatório</strong> para que o valor seja enviado no formulário</li>
            <li>Use <code>debug: true</code> durante o desenvolvimento para ver logs detalhados no console</li>
            <li>Os callbacks <code>onSelect</code> e <code>onClear</code> são executados APÓS a seleção/limpeza</li>
            <li>O parâmetro <code>items</code> pode ser atualizado dinamicamente via AJAX (implementação futura)</li>
        </ul>
    </div>
</div>
@endsection

