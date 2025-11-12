<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Custom Select - Demonstração - JAMEES</title>
    @include('components.favicon')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/custom-select.js') }}"></script>
    <style>
        .demo-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .demo-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .demo-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="demo-container min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">JAMEES</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors">Voltar</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Custom Select Component
            </h1>
            <p class="text-xl text-gray-600">
                Demonstração do componente de select personalizado com busca e suporte a AJAX
            </p>
        </div>

        <!-- Exemplo 1: Modo Fixo (Fixed Mode) -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Exemplo 1: Modo Fixo (Fixed Mode)
            </h2>
            <p class="text-gray-600 mb-6">
                Neste modo, todos os itens são carregados diretamente no componente. Ideal para listas pequenas e médias.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="plano_conta_fixed_container"></div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Valor selecionado:</strong>
                        </p>
                        <p id="fixed-value-display" class="text-sm font-mono text-gray-800">Nenhum item selecionado</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="plano_conta_fixed_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select --&gt;
&lt;script&gt;
    const fixedItems = [
        {id: 1, codigo: '1.1.1', nome: 'Aluguel', categoria: 'Despesas administrativas e comerciais'},
        {id: 2, codigo: '1.1.2', nome: 'Energia Elétrica', categoria: 'Despesas administrativas e comerciais'},
        // ... mais itens
    ];

    window.createCustomSelect('plano_conta_fixed_container', {
        name: 'plano_conta_fixed',
        id: 'plano_conta_fixed',
        label: 'Plano de Contas',
        placeholder: 'Digite para buscar...',
        mode: 'fixed',
        items: fixedItems,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'categoria',
        itemCode: 'codigo'
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplo 2: Modo AJAX -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-cloud text-green-600 mr-2"></i>
                Exemplo 2: Modo AJAX
            </h2>
            <p class="text-gray-600 mb-6">
                Neste modo, os itens são carregados dinamicamente via AJAX conforme o usuário digita. Ideal para listas grandes.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="plano_conta_ajax_container"></div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Valor selecionado:</strong>
                        </p>
                        <p id="ajax-value-display" class="text-sm font-mono text-gray-800">Nenhum item selecionado</p>

                        <div class="mt-4 space-x-2">
                            <button onclick="document.getElementById('plano_conta_ajax_input').click()" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                Clicar no Input
                            </button>
                            <button onclick="document.getElementById('plano_conta_ajax_input').focus()" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                Focus no Input
                            </button>
                            <button onclick="testBuscarViaAjax()" class="bg-purple-500 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                                Testar buscarViaAjax
                            </button>
                            <button onclick="console.clear()" class="bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                Limpar Console
                            </button>
                        </div>

                        <script>
                            function testBuscarViaAjax() {
                                console.log('=== TESTE MANUAL buscarViaAjax ===');
                                console.log('Função existe?', typeof window.buscarViaAjax);
                                if (typeof window.buscarViaAjax === 'function') {
                                    console.log('Chamando buscarViaAjax...');
                                    window.buscarViaAjax('plano_conta_ajax', '', {
                                        ajaxUrl: 'http://localhost:8000/api/custom-select/search',
                                        ajaxMethod: 'GET',
                                        itemValue: 'id',
                                        itemTitle: 'nome',
                                        itemSubtitle: 'categoria',
                                        itemCode: 'codigo'
                                    });
                                } else {
                                    console.error('buscarViaAjax não está definida!');
                                }
                            }
                        </script>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="plano_conta_ajax_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select --&gt;
&lt;script&gt;
    window.createCustomSelect('plano_conta_ajax_container', {
        name: 'plano_conta_ajax',
        id: 'plano_conta_ajax',
        label: 'Plano de Contas (AJAX)',
        placeholder: 'Digite pelo menos 2 caracteres...',
        mode: 'ajax',
        ajaxUrl: 'http://localhost:8000/api/custom-select/search',
        ajaxMethod: 'GET',
        minSearchLength: 2,
        loadOnOpen: true,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'categoria',
        itemCode: 'codigo'
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplo 3: Com Item Selecionado -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-check-circle text-purple-600 mr-2"></i>
                Exemplo 3: Com Valor Pré-selecionado
            </h2>
            <p class="text-gray-600 mb-6">
                Exemplo mostrando o componente com um item já selecionado.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="plano_conta_pre_selected_container"></div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="plano_conta_pre_selected_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select com valor pré-selecionado --&gt;
&lt;script&gt;
    window.createCustomSelect('plano_conta_pre_selected_container', {
        name: 'plano_conta_pre_selected',
        id: 'plano_conta_pre_selected',
        label: 'Plano de Contas (Pré-selecionado)',
        placeholder: 'Digite para buscar...',
        mode: 'fixed',
        items: fixedItems,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'categoria',
        itemCode: 'codigo',
        value: 3  // Valor pré-selecionado
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplo 4: Com Botão Adicionar Novo -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-plus-circle text-orange-600 mr-2"></i>
                Exemplo 4: Com Botão "Adicionar Novo"
            </h2>
            <p class="text-gray-600 mb-6">
                Exemplo com botão para adicionar um novo item quando não encontrar o desejado.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="plano_conta_add_new_container"></div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="plano_conta_add_new_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select com botão adicionar novo --&gt;
&lt;script&gt;
    window.createCustomSelect('plano_conta_add_new_container', {
        name: 'plano_conta_add_new',
        id: 'plano_conta_add_new',
        label: 'Plano de Contas (com Adicionar Novo)',
        placeholder: 'Digite para buscar...',
        mode: 'fixed',
        items: fixedItems,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'categoria',
        itemCode: 'codigo',
        addNewModal: 'plano-conta-modal',
        addNewText: 'Adicionar novo plano de conta'
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplo 5: Com Botão "Adicionar Novo" - Cliente (Fixed) -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-user-plus text-teal-600 mr-2"></i>
                Exemplo 5: Cliente (Modo Fixed) com "Adicionar Novo"
            </h2>
            <p class="text-gray-600 mb-6">
                Exemplo com lista fixa de clientes e botão para adicionar um novo através de modal.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="cliente_select_container"></div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Valor selecionado:</strong>
                        </p>
                        <p id="cliente-value-display" class="text-sm font-mono text-gray-800">Nenhum cliente selecionado</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="cliente_select_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select de cliente --&gt;
&lt;script&gt;
    const clientesItems = [
        {id: 1, nome: 'João Silva', documento: '123.456.789-00', email: 'joao@exemplo.com'},
        {id: 2, nome: 'Maria Santos', documento: '987.654.321-00', email: 'maria@exemplo.com'},
        {id: 3, nome: 'Empresa ABC Ltda', documento: '12.345.678/0001-90', email: 'contato@abc.com'}
    ];

    window.createCustomSelect('cliente_select_container', {
        name: 'cliente_select',
        id: 'cliente_select',
        label: 'Cliente',
        placeholder: 'Digite para buscar...',
        mode: 'fixed',
        items: clientesItems,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        itemCode: 'documento',
        addNewModal: 'cliente-modal',
        addNewText: 'Adicionar novo cliente'
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemplo 6: Cliente AJAX com Botão "Adicionar Novo" -->
        <div class="demo-card p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-users text-purple-600 mr-2"></i>
                Exemplo 6: Cliente (Modo AJAX) com "Adicionar Novo"
            </h2>
            <p class="text-gray-600 mb-6">
                Busca dinâmica de clientes via AJAX com botão para adicionar novo cliente através de modal.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demonstração</h3>
                    <!-- Container onde o select será montado -->
                    <div id="cliente_ajax_container"></div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Valor selecionado:</strong>
                        </p>
                        <p id="cliente-ajax-value-display" class="text-sm font-mono text-gray-800">Nenhum cliente selecionado</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Código</h3>
                    <div class="code-block">
                        <pre>&lt;!-- HTML: Container onde o select será montado --&gt;
&lt;div id="cliente_ajax_container"&gt;&lt;/div&gt;

&lt;!-- JavaScript: Inicializar o select de cliente com AJAX --&gt;
&lt;script&gt;
    window.createCustomSelect('cliente_ajax_container', {
        name: 'cliente_ajax',
        id: 'cliente_ajax',
        label: 'Cliente (AJAX)',
        placeholder: 'Digite para buscar cliente...',
        mode: 'ajax',
        ajaxUrl: 'http://localhost:8000/api/clientes/search',
        ajaxMethod: 'GET',
        minSearchLength: 2,
        loadOnOpen: true,
        itemValue: 'id',
        itemTitle: 'nome',
        itemSubtitle: 'email',
        itemCode: 'documento',
        addNewModal: 'cliente-modal',
        addNewText: 'Adicionar novo cliente'
    });
&lt;/script&gt;</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentação de Parâmetros -->
        <div class="demo-card p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-book text-indigo-600 mr-2"></i>
                Documentação de Parâmetros
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parâmetro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Padrão</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">name</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">custom_select</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Nome do campo no formulário</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">id</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">null</td>
                            <td class="px-6 py-4 text-sm text-gray-500">ID do elemento (usa 'name' se não fornecido)</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">label</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">''</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Label do campo</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">mode</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">fixed</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Modo de operação: 'fixed' ou 'ajax'</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">array</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">[]</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Array de itens (modo fixed)</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">ajax-url</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">null</td>
                            <td class="px-6 py-4 text-sm text-gray-500">URL para busca AJAX (modo ajax)</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">item-value</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">id</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Chave do valor do item</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">item-title</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">nome</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Chave do título do item</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">item-subtitle</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">null</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Chave do subtítulo do item</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">item-code</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">null</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Chave do código do item</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">min-search-length</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">int</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Mínimo de caracteres para buscar (modo ajax)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal de Plano de Conta -->
    <x-modals.plano-conta-modal />

    <!-- Modal de Cliente -->
    <x-modals.cliente-modal />

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; 2025 JAMEES. Todos os direitos reservados.</p>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Funções para abrir/fechar modais
        window.openModal = function(modalId) {
            console.log('Abrindo modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                console.error('Modal não encontrado:', modalId);
            }
        };

        window.closeModal = function(modalId) {
            console.log('Fechando modal:', modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                // Limpar formulário se existir
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    // Limpar erros de validação
                    const errors = form.querySelectorAll('.text-red-600');
                    errors.forEach(error => {
                        error.classList.add('hidden');
                        error.textContent = '';
                    });
                }
            }
        };

        // Fechar modal ao clicar no backdrop
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-backdrop')) {
                const modalId = e.target.id;
                if (modalId) {
                    closeModal(modalId);
                }
            }
        });

        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id$="-modal"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modal.id);
                    }
                });
            }
        });

        // Atualizar displays de valor selecionado
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar se a função existe
            if (typeof window.createCustomSelect !== 'function') {
                console.error('Função createCustomSelect não encontrada! Certifique-se de incluir o script custom-select.js');
                return;
            }

            // Dados para os selects em modo fixed
            const fixedItems = [
                {id: 1, codigo: '1.1.1', nome: 'Aluguel', categoria: 'Despesas administrativas e comerciais'},
                {id: 2, codigo: '1.1.2', nome: 'Energia Elétrica', categoria: 'Despesas administrativas e comerciais'},
                {id: 3, codigo: '1.1.3', nome: 'Água', categoria: 'Despesas administrativas e comerciais'},
                {id: 4, codigo: '1.1.4', nome: 'Telefone', categoria: 'Despesas administrativas e comerciais'},
                {id: 5, codigo: '1.1.5', nome: 'Confraternizações', categoria: 'Despesas administrativas e comerciais'},
                {id: 6, codigo: '1.2.1', nome: 'Salários', categoria: 'Despesas com pessoal'},
                {id: 7, codigo: '1.2.2', nome: 'Encargos Sociais', categoria: 'Despesas com pessoal'},
                {id: 8, codigo: '1.2.3', nome: 'Vale Transporte', categoria: 'Despesas com pessoal'},
                {id: 9, codigo: '2.1.1', nome: 'Vendas', categoria: 'Receitas operacionais'},
                {id: 10, codigo: '2.1.2', nome: 'Serviços', categoria: 'Receitas operacionais'}
            ];

            const clientesItems = [
                {id: 1, nome: 'João Silva', documento: '123.456.789-00', email: 'joao@exemplo.com'},
                {id: 2, nome: 'Maria Santos', documento: '987.654.321-00', email: 'maria@exemplo.com'},
                {id: 3, nome: 'Empresa ABC Ltda', documento: '12.345.678/0001-90', email: 'contato@abc.com'}
            ];

            // Exemplo 1: Modo Fixed
            window.createCustomSelect('plano_conta_fixed_container', {
                name: 'plano_conta_fixed',
                id: 'plano_conta_fixed',
                label: 'Plano de Contas',
                placeholder: 'Digite para buscar...',
                mode: 'fixed',
                items: fixedItems,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'categoria',
                itemCode: 'codigo'
            });

            // Listener para atualizar display
            setTimeout(() => {
                const fixedHidden = document.getElementById('plano_conta_fixed_hidden');
                if (fixedHidden) {
                    fixedHidden.addEventListener('change', function() {
                        const display = document.getElementById('fixed-value-display');
                        if (display) {
                            display.textContent = this.value || 'Nenhum item selecionado';
                        }
                    });
                }
            }, 100);

            // Exemplo 2: Modo AJAX
            window.createCustomSelect('plano_conta_ajax_container', {
                name: 'plano_conta_ajax',
                id: 'plano_conta_ajax',
                label: 'Plano de Contas (AJAX)',
                placeholder: 'Digite pelo menos 2 caracteres...',
                mode: 'ajax',
                ajaxUrl: 'http://localhost:8000/api/custom-select/search',
                ajaxMethod: 'GET',
                minSearchLength: 2,
                loadOnOpen: true,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'categoria',
                itemCode: 'codigo'
            });

            setTimeout(() => {
                const ajaxHidden = document.getElementById('plano_conta_ajax_hidden');
                if (ajaxHidden) {
                    ajaxHidden.addEventListener('change', function() {
                        const display = document.getElementById('ajax-value-display');
                        if (display) {
                            display.textContent = this.value || 'Nenhum item selecionado';
                        }
                    });
                }
            }, 100);

            // Exemplo 3: Pré-selecionado
            window.createCustomSelect('plano_conta_pre_selected_container', {
                name: 'plano_conta_pre_selected',
                id: 'plano_conta_pre_selected',
                label: 'Plano de Contas (Pré-selecionado)',
                placeholder: 'Digite para buscar...',
                mode: 'fixed',
                items: fixedItems,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'categoria',
                itemCode: 'codigo',
                value: 3
            });

            // Exemplo 4: Com botão adicionar novo
            window.createCustomSelect('plano_conta_add_new_container', {
                name: 'plano_conta_add_new',
                id: 'plano_conta_add_new',
                label: 'Plano de Contas (com Adicionar Novo)',
                placeholder: 'Digite para buscar...',
                mode: 'fixed',
                items: fixedItems,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'categoria',
                itemCode: 'codigo',
                addNewModal: 'plano-conta-modal',
                addNewText: 'Adicionar novo plano de conta'
            });

            // Exemplo 5: Cliente (Fixed)
            window.createCustomSelect('cliente_select_container', {
                name: 'cliente_select',
                id: 'cliente_select',
                label: 'Cliente',
                placeholder: 'Digite para buscar...',
                mode: 'fixed',
                items: clientesItems,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'email',
                addNewModal: 'cliente-modal',
                addNewText: 'Adicionar novo cliente'
            });

            setTimeout(() => {
                const clienteHidden = document.getElementById('cliente_select_hidden');
                if (clienteHidden) {
                    clienteHidden.addEventListener('change', function() {
                        const display = document.getElementById('cliente-value-display');
                        if (display) {
                            display.textContent = this.value || 'Nenhum cliente selecionado';
                        }
                    });
                }
            }, 100);

            // Exemplo 6: Cliente AJAX com Botão Adicionar Novo
            window.createCustomSelect('cliente_ajax_container', {
                name: 'cliente_ajax',
                id: 'cliente_ajax',
                label: 'Cliente (AJAX)',
                placeholder: 'Digite para buscar cliente...',
                mode: 'ajax',
                ajaxUrl: 'http://localhost:8000/api/clientes/search',
                ajaxMethod: 'GET',
                minSearchLength: 2,
                loadOnOpen: true,
                itemValue: 'id',
                itemTitle: 'nome',
                itemSubtitle: 'email',
                addNewModal: 'cliente-modal',
                addNewText: 'Adicionar novo cliente'
            });

            setTimeout(() => {
                const clienteAjaxHidden = document.getElementById('cliente_ajax_hidden');
                if (clienteAjaxHidden) {
                    clienteAjaxHidden.addEventListener('change', function() {
                        const display = document.getElementById('cliente-ajax-value-display');
                        if (display) {
                            display.textContent = this.value || 'Nenhum cliente selecionado';
                        }
                    });
                }
            }, 100);
        });
    </script>
</body>
</html>

