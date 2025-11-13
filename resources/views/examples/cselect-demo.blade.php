@extends('layouts.app')

@section('title', 'CSelect - Demonstração')

@section('content')
<!-- Incluir Font Awesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">CSelect - Demonstração</h1>
        <p class="text-gray-600">Exemplos de uso do componente CSelect</p>
    </div>

    <!-- Exemplo 1: Básico -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <h2 class="text-xl font-semibold text-gray-800 p-6 pb-0">Exemplo 1: CSelect Básico</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200">
                <h3 class="text-sm font-semibold text-gray-600 mb-4 uppercase">Demonstração</h3>

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
                <div id="debug_example_1" class="p-3 bg-blue-50 border border-blue-200 rounded-md hidden">
                    <p class="text-sm font-semibold text-blue-800 mb-1">🔍 Valor selecionado:</p>
                    <p class="text-xs text-blue-700">
                        <strong>ID:</strong> <span id="debug_hidden_id_1"></span><br>
                        <strong>Name:</strong> <span id="debug_hidden_name_1"></span><br>
                        <strong>Value:</strong> <span id="debug_hidden_value_1" class="font-mono bg-blue-100 px-1"></span>
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <h3 class="text-sm font-semibold text-gray-400 mb-4 uppercase">Código</h3>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <h2 class="text-xl font-semibold text-gray-800 p-6 pb-0">Exemplo 2: CSelect com Title e Subtitle</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200">
                <h3 class="text-sm font-semibold text-gray-600 mb-4 uppercase">Demonstração</h3>

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
                <h3 class="text-sm font-semibold text-gray-400 mb-4 uppercase">Código</h3>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <h2 class="text-xl font-semibold text-gray-800 p-6 pb-0">Exemplo 5: Cliente (Modo Fixed) com "Adicionar Novo"</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Lado Esquerdo: Demonstração -->
            <div class="p-6 border-r border-gray-200">
                <h3 class="text-sm font-semibold text-gray-600 mb-4 uppercase">Demonstração</h3>

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

                <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <p class="text-xs text-gray-600">
                        <strong>Valor selecionado:</strong> <span id="cliente_selected_value_5" class="text-gray-800">Nenhum cliente selecionado</span>
                    </p>
                </div>
            </div>

            <!-- Lado Direito: Código -->
            <div class="bg-gray-900 p-6">
                <h3 class="text-sm font-semibold text-gray-400 mb-4 uppercase">Código</h3>
                <pre class="text-sm text-gray-100 overflow-x-auto"><code><span class="text-purple-400">cSelect</span>(<span class="text-yellow-300">'#cliente'</span>, {
    <span class="text-green-400">name</span>: <span class="text-yellow-300">'cliente_id'</span>,
    <span class="text-green-400">itemValue</span>: <span class="text-yellow-300">'id'</span>,
    <span class="text-green-400">itemTitle</span>: <span class="text-yellow-300">'nome'</span>,
    <span class="text-green-400">itemSubtitle</span>: <span class="text-yellow-300">'email'</span>,
    <span class="text-green-400">items</span>: [...],
    <span class="text-green-400">addNewButton</span>: {
        <span class="text-green-400">text</span>: <span class="text-yellow-300">'Adicionar novo cliente'</span>
    },
    <span class="text-green-400">onAddNew</span>: <span class="text-pink-400">() =&gt;</span> {
        <span class="text-gray-500">// Abrir modal</span>
        document.<span class="text-purple-400">getElementById</span>(<span class="text-yellow-300">'modal'</span>)
            .<span class="text-purple-400">classList</span>.<span class="text-purple-400">remove</span>(<span class="text-yellow-300">'hidden'</span>);
    }
});</code></pre>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Cliente (Exemplo 5) -->
    <div id="modal_cliente_example_5" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-xl font-semibold text-white">Adicionar Novo Cliente</h3>
            </div>

            <div class="p-6">
                <form id="form_cliente_example_5">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                        <input
                            type="text"
                            id="cliente_nome_5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input
                            type="email"
                            id="cliente_email_5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            required
                        >
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors"
                        >
                            Salvar
                        </button>
                        <button
                            type="button"
                            onclick="document.getElementById('modal_cliente_example_5').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            { id: '4', nome: 'Pedro Oliveira', email: 'pedro@exemplo.com' }
        ],
        addNewButton: {
            text: 'Adicionar novo cliente',
            class: ''
        },
        onAddNew: () => {
            console.log('Abrindo modal de novo cliente...');
            document.getElementById('modal_cliente_example_5').classList.remove('hidden');
        },
        onSelect: (value, label) => {
            console.log('Cliente selecionado:', { value, label });
            document.getElementById('cliente_selected_value_5').textContent = `${label} (ID: ${value})`;
        }
    });

    // Formulário do modal (Exemplo 5)
    document.getElementById('form_cliente_example_5').addEventListener('submit', (e) => {
        e.preventDefault();

        const nome = document.getElementById('cliente_nome_5').value;
        const email = document.getElementById('cliente_email_5').value;

        // Simular salvamento
        console.log('Salvando novo cliente:', { nome, email });

        // Gerar ID fictício
        const novoId = (Math.random() * 1000).toFixed(0);

        // Adicionar à lista do select (simulação - na prática seria via AJAX)
        const currentItems = example5.config.items;
        currentItems.push({ id: novoId, nome: nome, email: email });

        // Selecionar o novo cliente automaticamente
        example5.setValue(novoId, nome);

        // Fechar modal e limpar form
        document.getElementById('modal_cliente_example_5').classList.add('hidden');
        document.getElementById('form_cliente_example_5').reset();

        // Feedback
        alert(`Cliente "${nome}" adicionado com sucesso!`);
    });

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
<div class="container mx-auto px-4 py-8 mt-12 border-t-4 border-blue-500">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📚 Documentação de Parâmetros</h1>
        <p class="text-gray-600">Referência completa de todas as opções disponíveis do CSelect</p>
    </div>

    <!-- Sintaxe Básica -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">🚀 Sintaxe Básica</h2>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-2xl font-semibold text-white">⚙️ Parâmetros de Configuração</h2>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Array de objetos com as opções do select. Cada objeto deve ter as propriedades definidas em <code>itemValue</code> e <code>itemLabel</code></td>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade que contém o valor do item</td>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade que contém o texto exibido do item (modo simples)</td>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do título (linha principal). Use com <code>itemSubtitle</code> para layout de duas linhas</td>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Nome da propriedade do subtítulo (linha secundária). Use com <code>itemTitle</code> para layout de duas linhas</td>
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
                        <td class="px-6 py-4 text-sm text-gray-700">Se deve carregar os dados ao abrir o dropdown</td>
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
                </tbody>
            </table>
        </div>
    </div>

    <!-- Métodos Retornados -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
            <h2 class="text-2xl font-semibold text-white">🔧 Métodos Retornados</h2>
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
            </div>
        </div>
    </div>

    <!-- Exemplos Práticos -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">💡 Exemplos Práticos</h2>

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
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
        <h2 class="text-xl font-semibold text-yellow-800 mb-3">⚠️ Notas Importantes</h2>
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

