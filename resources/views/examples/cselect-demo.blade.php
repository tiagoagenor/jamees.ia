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
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Exemplo 3: CSelect com AJAX</h2>

        <div class="mb-4">
            <div id="cselect_example_3"></div>
        </div>

        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm font-semibold text-gray-700 mb-2">Código:</p>
            <pre class="text-sm text-gray-800"><code>&lt;div id="cselect_example_3"&gt;&lt;/div&gt;

&lt;script&gt;
// Inicializar CSelect com AJAX aqui
&lt;/script&gt;</code></pre>
        </div>
    </div>

    <!-- Exemplo 4: Múltipla Seleção -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Exemplo 4: CSelect Múltipla Seleção</h2>

        <div class="mb-4">
            <div id="cselect_example_4"></div>
        </div>

        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm font-semibold text-gray-700 mb-2">Código:</p>
            <pre class="text-sm text-gray-800"><code>&lt;div id="cselect_example_4"&gt;&lt;/div&gt;

&lt;script&gt;
// Inicializar CSelect com múltipla seleção aqui
&lt;/script&gt;</code></pre>
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

        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm font-semibold text-gray-700 mb-2">Código:</p>
            <pre class="text-sm text-gray-800"><code>&lt;div id="cselect_example_6"&gt;&lt;/div&gt;

&lt;script&gt;
// Inicializar CSelect com valor pré-selecionado aqui
&lt;/script&gt;</code></pre>
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
        }
    });

    // Exemplo 3: Com AJAX
    console.log('Inicializar Exemplo 3...');

    // Exemplo 4: Múltipla Seleção
    console.log('Inicializar Exemplo 4...');

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


    // Callback para quando um cliente for criado (Exemplo 5)
    // O modal de cliente já tem sua própria lógica de salvamento via AJAX
    // Aqui apenas atualizamos o select quando o cliente for criado
    // NOTA: O modal já chama selectItemProgrammatically, então aqui apenas adicionamos à lista
    // e atualizamos o display, sem chamar setValue novamente para evitar duplicação
    if (typeof window.onClienteCreated === 'undefined') {
        window.onClienteCreated = function(cliente) {
            console.log('Cliente criado:', cliente);

            // Adicionar à lista do select do Exemplo 5
            if (typeof example5 !== 'undefined' && example5.config) {
                const novoItem = {
                    id: cliente.id.toString(),
                    nome: cliente.nome || cliente.nome_completo,
                    email: cliente.email || ''
                };

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
                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded">setValue(value, label)</code>
                    </h3>
                    <p class="text-gray-700 mb-2">Define o valor selecionado programaticamente.</p>
                    <pre class="bg-gray-900 text-gray-100 p-3 rounded-md overflow-x-auto text-sm"><code>const select = cSelect('#meu_input', {...});
select.setValue('2', 'Boleto Bancário'); // Seleciona o item programaticamente</code></pre>
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

