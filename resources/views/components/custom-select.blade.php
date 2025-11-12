@props([
    'name' => 'custom_select',
    'id' => null,
    'label' => '',
    'required' => false,
    'placeholder' => 'Digite para buscar',
    'value' => null,
    'items' => [],
    'itemValue' => 'id',
    'itemTitle' => 'nome',
    'itemSubtitle' => null,
    'itemCode' => null,
    'addNewRoute' => null,
    'addNewText' => 'Adicionar novo',
    'addNewModal' => null, // ID do modal para abrir (ex: 'plano-conta-modal')
    'searchable' => true,
    'emptyMessage' => 'Nenhum item encontrado',
    'mode' => 'fixed', // 'fixed' ou 'ajax'
    'ajaxUrl' => null, // URL para busca AJAX
    'ajaxMethod' => 'GET', // Método HTTP para AJAX
    'ajaxParams' => [], // Parâmetros adicionais para enviar no AJAX
    'minSearchLength' => 2, // Número mínimo de caracteres para buscar via AJAX
    'selectedItemData' => null, // Dados do item selecionado quando usando AJAX (para exibir quando há value)
    'loadOnOpen' => false // Se true, carrega itens automaticamente ao abrir o dropdown (modo AJAX)
])

@php
    $id = $id ?? $name;
    $selectedItem = null;

    // Se for modo fixo, buscar item selecionado nos items
    if ($mode === 'fixed' && $value && count($items) > 0) {
        $selectedItem = collect($items)->firstWhere($itemValue, $value);
    }

    // Se for modo AJAX e houver selectedItemData, usar ele
    if ($mode === 'ajax' && $selectedItemData) {
        $selectedItem = $selectedItemData;
    }
@endphp

<div class="custom-select-wrapper" data-select-id="{{ $id }}" @if($mode === 'ajax' && $ajaxUrl) data-ajax-url="{{ $ajaxUrl }}" @endif>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <!-- Input de busca/seleção -->
        <div class="relative">
            <input
                type="text"
                id="{{ $id }}_input"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                placeholder="{{ $selectedItem ? '' : $placeholder }}"
                autocomplete="off"
                @if($required) required @endif
            >

            <!-- Valor selecionado (quando houver) -->
            @if($selectedItem)
                <div id="{{ $id }}_selected_display" class="absolute inset-0 px-3 py-2 flex items-center justify-between" style="pointer-events: none;">
                    <div class="flex-1 min-w-0" style="pointer-events: none;">
                        <div class="text-sm font-medium text-gray-900 truncate">
                            @if($itemCode && isset($selectedItem[$itemCode]))
                                {{ $selectedItem[$itemCode] }} -
                            @endif
                            {{ $selectedItem[$itemTitle] ?? '' }}
                        </div>
                        @if($itemSubtitle && isset($selectedItem[$itemSubtitle]))
                            <div class="text-xs text-gray-500 truncate">
                                {{ $selectedItem[$itemSubtitle] }}
                            </div>
                        @endif
                    </div>
                    <button
                        type="button"
                        class="ml-2 text-gray-400 hover:text-red-600"
                        style="pointer-events: auto;"
                        onclick="clearSelection('{{ $id }}')"
                        title="Remover seleção"
                    >
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endif

            <!-- Ícone de estrutura hierárquica (oculto quando há item selecionado) -->
            <div id="{{ $id }}_sitemap_icon" class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" @if($selectedItem) style="display: none;" @endif>
                <i class="fas fa-sitemap text-gray-400"></i>
            </div>
        </div>

        <!-- Dropdown de resultados -->
        <div
            id="{{ $id }}_dropdown"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
            style="display: none;"
        >
            @if($searchable)
                <div class="sticky top-0 bg-white border-b border-gray-200 p-2">
                    <div class="relative">
                        <input
                            type="text"
                            id="{{ $id }}_search"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="{{ $mode === 'ajax' ? 'Digite pelo menos ' . $minSearchLength . ' caracteres para buscar' : $placeholder }}"
                            autocomplete="off"
                        >
                        @if($mode === 'ajax')
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div id="{{ $id }}_list" class="py-1">
                @if($mode === 'fixed')
                    @foreach($items as $item)
                        <div
                            class="custom-select-item px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                            data-value="{{ $item[$itemValue] ?? '' }}"
                            data-title="{{ $item[$itemTitle] ?? '' }}"
                            data-subtitle="{{ $itemSubtitle ? ($item[$itemSubtitle] ?? '') : '' }}"
                            data-code="{{ $itemCode ? ($item[$itemCode] ?? '') : '' }}"
                            onclick="selectItem('{{ $id }}', this)"
                        >
                            <div class="text-sm font-medium text-gray-900">
                                @if($itemCode && isset($item[$itemCode]))
                                    <span class="font-semibold">{{ $item[$itemCode] }}</span> -
                                @endif
                                {{ $item[$itemTitle] ?? '' }}
                            </div>
                            @if($itemSubtitle && isset($item[$itemSubtitle]))
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $item[$itemSubtitle] }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if(count($items) === 0)
                        <div class="px-4 py-3 text-sm text-gray-500 text-center">
                            {{ $emptyMessage }}
                        </div>
                    @endif
                @else
                    <!-- Modo AJAX - itens serão carregados dinamicamente -->
                    <div id="{{ $id }}_loading" class="px-4 py-3 text-sm text-gray-500 text-center hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Carregando...
                    </div>
                    <div id="{{ $id }}_empty" class="px-4 py-3 text-sm text-gray-500 text-center">
                        {{ $emptyMessage }}
                    </div>
                @endif
            </div>

            @if($addNewRoute || $addNewModal)
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-2">
                    @if($addNewModal)
                        <button
                            type="button"
                            onclick="openModalWithSelect('{{ $addNewModal }}', '{{ $id }}')"
                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            {{ $addNewText }}
                        </button>
                    @else
                        <a
                            href="{{ $addNewRoute }}"
                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            {{ $addNewText }}
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Input hidden para o valor real -->
        <input
            type="hidden"
            name="{{ $name }}"
            id="{{ $id }}_hidden"
            value="{{ $value ?? '' }}"
            @if($required) required @endif
        >
    </div>
</div>

@push('scripts')
<script>
// Função para buscar itens via AJAX (definir ANTES de initializeCustomSelect)
if (typeof window.buscarViaAjax === 'undefined') {
    window.buscarViaAjax = function(selectId, searchTerm, config) {
        console.log('buscarViaAjax chamado para:', selectId, 'com searchTerm:', searchTerm, 'config:', config);
        config = config || {};

        // Obter URL AJAX da configuração passada ou do atributo data
        let ajaxUrl = config.ajaxUrl;
        if (!ajaxUrl || ajaxUrl === 'null' || ajaxUrl === '') {
            const selectWrapper = document.querySelector('.custom-select-wrapper[data-select-id="' + selectId + '"]');
            if (selectWrapper) {
                ajaxUrl = selectWrapper.dataset.ajaxUrl;
            }
        }

        if (!ajaxUrl || ajaxUrl === 'null' || ajaxUrl === '') {
            console.error('AJAX URL não configurada para:', selectId, 'config:', config);
            const loadingEl = document.getElementById(selectId + '_loading');
            const emptyEl = document.getElementById(selectId + '_empty');
            if (loadingEl) loadingEl.classList.add('hidden');
            if (emptyEl) {
                emptyEl.textContent = 'URL AJAX não configurada';
                emptyEl.classList.remove('hidden');
            }
            return;
        }

        const loadingEl = document.getElementById(selectId + '_loading');
        const emptyEl = document.getElementById(selectId + '_empty');
        const listEl = document.getElementById(selectId + '_list');

        // Mostrar loading
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (emptyEl) emptyEl.classList.add('hidden');

        // Parâmetros da requisição
        const params = {
            search: searchTerm || ''
        };

        // Construir URL com parâmetros
        let url;
        try {
            // Se a URL já é completa (começa com http), usar diretamente
            if (ajaxUrl.startsWith('http://') || ajaxUrl.startsWith('https://')) {
                url = new URL(ajaxUrl);
            } else {
                // Se for relativa, construir com base na origem
                url = new URL(ajaxUrl, window.location.origin);
            }

            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                    url.searchParams.append(key, params[key]);
                }
            });
        } catch (error) {
            console.error('Erro ao construir URL:', error, 'ajaxUrl:', ajaxUrl);
            const loadingEl = document.getElementById(selectId + '_loading');
            const emptyEl = document.getElementById(selectId + '_empty');
            if (loadingEl) loadingEl.classList.add('hidden');
            if (emptyEl) {
                emptyEl.textContent = 'Erro ao construir URL AJAX: ' + error.message;
                emptyEl.classList.remove('hidden');
            }
            return;
        }

        console.log('Fazendo requisição AJAX para:', url.toString());

        const method = config.ajaxMethod || 'GET';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(url.toString(), {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Resposta recebida:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (loadingEl) loadingEl.classList.add('hidden');

            // Limpar itens anteriores
            const existingItems = listEl.querySelectorAll('.custom-select-item');
            existingItems.forEach(item => item.remove());

            if (data.items && data.items.length > 0) {
                if (emptyEl) emptyEl.classList.add('hidden');

                // Renderizar itens
                const itemValue = config.itemValue || 'id';
                const itemTitle = config.itemTitle || 'nome';
                const itemSubtitle = config.itemSubtitle || null;
                const itemCode = config.itemCode || null;

                data.items.forEach(item => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'custom-select-item px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
                    itemEl.setAttribute('data-value', item[itemValue] || '');
                    itemEl.setAttribute('data-title', item[itemTitle] || '');
                    itemEl.setAttribute('data-subtitle', itemSubtitle ? (item[itemSubtitle] || '') : '');
                    itemEl.setAttribute('data-code', itemCode ? (item[itemCode] || '') : '');
                    itemEl.onclick = function() {
                        if (typeof window.selectItem === 'function') {
                            window.selectItem(selectId, this);
                        }
                    };

                    let html = '<div class="text-sm font-medium text-gray-900">';
                    if (itemCode && item[itemCode]) {
                        html += '<span class="font-semibold">' + item[itemCode] + '</span> - ';
                    }
                    html += (item[itemTitle] || '') + '</div>';
                    if (itemSubtitle && item[itemSubtitle]) {
                        html += '<div class="text-xs text-gray-500 mt-1">' + item[itemSubtitle] + '</div>';
                    }

                    itemEl.innerHTML = html;
                    listEl.appendChild(itemEl);
                });
            } else {
                if (emptyEl) emptyEl.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar itens:', error);
            if (loadingEl) loadingEl.classList.add('hidden');
            if (emptyEl) {
                emptyEl.textContent = 'Erro ao carregar itens: ' + (error.message || 'Tente novamente.');
                emptyEl.classList.remove('hidden');
            }
        });
    };
}

// Função global para inicializar selects personalizados (definir apenas uma vez)
if (typeof window.initializeCustomSelect === 'undefined') {
    window.initializeCustomSelect = function(selectId) {
    const input = document.getElementById(selectId + '_input');
    const dropdown = document.getElementById(selectId + '_dropdown');
    const searchInput = document.getElementById(selectId + '_search');
    const hiddenInput = document.getElementById(selectId + '_hidden');
    const list = document.getElementById(selectId + '_list');
    const selectedDisplay = document.getElementById(selectId + '_selected_display');

    if (!input || !dropdown) {
        console.error('Custom select elements not found for:', selectId);
        return;
    }

    // Função para abrir dropdown
    function openDropdown() {
        if (!dropdown) {
            console.error('Dropdown not found for:', selectId);
            return;
        }

        console.log('Opening dropdown for:', selectId); // Debug

        // Remover qualquer classe hidden
        dropdown.classList.remove('hidden');

        // Remover o style display: none e definir como block
        // Usar setAttribute para garantir que o style seja aplicado
        dropdown.style.cssText = dropdown.style.cssText.replace(/display\s*:\s*none[^;]*;?/gi, '');
        dropdown.style.display = 'block';

        // Forçar reflow para garantir que o display seja aplicado
        void dropdown.offsetHeight;

        // Se for modo AJAX e loadOnOpen estiver ativado, mostrar loading IMEDIATAMENTE após abrir o dropdown
        if ('{{ $mode }}' === 'ajax' && {{ $loadOnOpen ? 'true' : 'false' }}) {
            const listEl = document.getElementById(selectId + '_list');
            const existingItems = listEl ? listEl.querySelectorAll('.custom-select-item') : [];

            // Só carregar se não houver itens já carregados
            if (existingItems.length === 0) {
                // Mostrar loading IMEDIATAMENTE após abrir o dropdown
                const loadingEl = document.getElementById(selectId + '_loading');
                const emptyEl = document.getElementById(selectId + '_empty');
                if (loadingEl) {
                    loadingEl.classList.remove('hidden');
                }
                if (emptyEl) {
                    emptyEl.classList.add('hidden');
                }
            }
        }

        console.log('Dropdown display after open:', dropdown.style.display); // Debug
        console.log('Dropdown visible:', dropdown.offsetHeight > 0); // Debug

        // Se for modo AJAX e loadOnOpen estiver ativado, carregar itens iniciais
        if ('{{ $mode }}' === 'ajax' && {{ $loadOnOpen ? 'true' : 'false' }}) {
            const listEl = document.getElementById(selectId + '_list');
            const existingItems = listEl ? listEl.querySelectorAll('.custom-select-item') : [];

            // Só carregar se não houver itens já carregados
            if (existingItems.length === 0) {
                // Obter URL do atributo data ou do blade
                const selectWrapper = document.querySelector('.custom-select-wrapper[data-select-id="' + selectId + '"]');
                const urlFromData = selectWrapper ? selectWrapper.dataset.ajaxUrl : null;
                const urlFromBlade = '{{ $ajaxUrl }}' || null;
                const finalUrl = urlFromData || urlFromBlade;

                if (!finalUrl || finalUrl === 'null' || finalUrl === '') {
                    console.error('URL AJAX não encontrada para:', selectId);
                    const loadingEl = document.getElementById(selectId + '_loading');
                    const emptyEl = document.getElementById(selectId + '_empty');
                    if (loadingEl) loadingEl.classList.add('hidden');
                    if (emptyEl) {
                        emptyEl.textContent = 'URL AJAX não configurada';
                        emptyEl.classList.remove('hidden');
                    }
                    return;
                }

                const ajaxConfig = {
                    ajaxUrl: finalUrl,
                    ajaxMethod: '{{ $ajaxMethod }}',
                    itemValue: '{{ $itemValue }}',
                    itemTitle: '{{ $itemTitle }}',
                    itemSubtitle: '{{ $itemSubtitle }}',
                    itemCode: '{{ $itemCode }}'
                };

                // Pequeno delay para garantir que o loading apareça antes da requisição
                setTimeout(() => {
                    if (typeof window.buscarViaAjax === 'function') {
                        console.log('Chamando buscarViaAjax para:', selectId);
                        window.buscarViaAjax(selectId, '', ajaxConfig);
                    } else {
                        console.error('buscarViaAjax não está definida ainda');
                        // Tentar novamente após um pequeno delay
                        setTimeout(() => {
                            if (typeof window.buscarViaAjax === 'function') {
                                window.buscarViaAjax(selectId, '', ajaxConfig);
                            } else {
                                console.error('buscarViaAjax ainda não está definida após timeout');
                                // Esconder loading se der erro
                                const loadingEl = document.getElementById(selectId + '_loading');
                                const emptyEl = document.getElementById(selectId + '_empty');
                                if (loadingEl) loadingEl.classList.add('hidden');
                                if (emptyEl) {
                                    emptyEl.textContent = 'Erro ao carregar. Tente novamente.';
                                    emptyEl.classList.remove('hidden');
                                }
                            }
                        }, 200);
                    }
                }, 100);
            }
        }

        if (searchInput) {
            setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }, 50);
        }
    }

    // Abrir ao focar no input
    input.addEventListener('focus', function(e) {
        console.log('Input focused:', selectId); // Debug
        e.stopPropagation();
        openDropdown();
    });

    // Abrir ao clicar diretamente no input
    input.addEventListener('click', function(e) {
        console.log('Input clicked:', selectId); // Debug
        e.stopPropagation();
        openDropdown();
    });

    // Adicionar listener no container do input para capturar cliques (incluindo quando há item selecionado)
    const inputContainer = input.parentElement;
    if (inputContainer) {
        inputContainer.addEventListener('click', function(e) {
            // Se clicou no botão de remover, não fazer nada (deixar o onclick do botão funcionar)
            if (e.target.closest('button[onclick*="clearSelection"]')) {
                return;
            }
            // Se clicou no container (incluindo área do item selecionado ou input), abrir dropdown
            console.log('Container clicked:', selectId); // Debug
            e.stopPropagation();
            openDropdown();
        });
    }

    // Buscar itens apenas quando necessário
    function getItems() {
        if (!list) return [];
        return list.querySelectorAll('.custom-select-item');
    }

    // Busca
    if (searchInput) {
        let searchTimeout = null;

        // O carregamento inicial é feito no openDropdown(), não precisamos duplicar aqui

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            // Limpar timeout anterior
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            if ('{{ $mode }}' === 'ajax') {
                // Modo AJAX - buscar via requisição
                const minLength = {{ $minSearchLength }};
                const loadOnOpen = {{ $loadOnOpen ? 'true' : 'false' }};

                // Permitir busca se:
                // 1. O termo tiver o tamanho mínimo OU
                // 2. O termo estiver vazio E loadOnOpen estiver ativo
                if (searchTerm.length >= minLength || (searchTerm.length === 0 && loadOnOpen)) {
                    // Mostrar loading
                    const loadingEl = document.getElementById(selectId + '_loading');
                    const emptyEl = document.getElementById(selectId + '_empty');
                    const listEl = document.getElementById(selectId + '_list');

                    if (loadingEl) loadingEl.classList.remove('hidden');
                    if (emptyEl) emptyEl.classList.add('hidden');

                    // Limpar itens anteriores
                    const existingItems = listEl.querySelectorAll('.custom-select-item');
                    existingItems.forEach(item => item.remove());

                    // Debounce - aguardar 300ms antes de fazer a requisição (só se houver busca)
                    // Obter URL do atributo data ou do blade
                    const selectWrapper = document.querySelector('.custom-select-wrapper[data-select-id="' + selectId + '"]');
                    const urlFromData = selectWrapper ? selectWrapper.dataset.ajaxUrl : null;
                    const urlFromBlade = '{{ $ajaxUrl }}' || null;
                    const finalUrl = urlFromData || urlFromBlade;

                    if (!finalUrl || finalUrl === 'null' || finalUrl === '') {
                        console.error('URL AJAX não encontrada no evento input para:', selectId);
                        const loadingEl = document.getElementById(selectId + '_loading');
                        const emptyEl = document.getElementById(selectId + '_empty');
                        if (loadingEl) loadingEl.classList.add('hidden');
                        if (emptyEl) {
                            emptyEl.textContent = 'URL AJAX não configurada';
                            emptyEl.classList.remove('hidden');
                        }
                        return;
                    }

                    const ajaxConfig = {
                        ajaxUrl: finalUrl,
                        ajaxMethod: '{{ $ajaxMethod }}',
                        itemValue: '{{ $itemValue }}',
                        itemTitle: '{{ $itemTitle }}',
                        itemSubtitle: '{{ $itemSubtitle }}',
                        itemCode: '{{ $itemCode }}'
                    };

                    if (searchTerm.length > 0) {
                        searchTimeout = setTimeout(() => {
                            if (typeof window.buscarViaAjax === 'function') {
                                window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                            } else {
                                console.error('buscarViaAjax não está definida');
                            }
                        }, 300);
                    } else {
                        // Se for busca vazia (loadOnOpen), buscar imediatamente
                        if (typeof window.buscarViaAjax === 'function') {
                            window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                        } else {
                            console.error('buscarViaAjax não está definida');
                        }
                    }
                } else if (searchTerm.length === 0 && !loadOnOpen) {
                    // Limpar resultados se o campo estiver vazio e loadOnOpen estiver desativado
                    const listEl = document.getElementById(selectId + '_list');
                    const existingItems = listEl.querySelectorAll('.custom-select-item');
                    existingItems.forEach(item => item.remove());

                    const emptyEl = document.getElementById(selectId + '_empty');
                    const loadingEl = document.getElementById(selectId + '_loading');
                    if (emptyEl) emptyEl.classList.remove('hidden');
                    if (loadingEl) loadingEl.classList.add('hidden');
                }
            } else {
                // Modo fixo - buscar localmente
                const items = getItems();
                items.forEach(item => {
                    const title = (item.dataset.title || '').toLowerCase();
                    const subtitle = (item.dataset.subtitle || '').toLowerCase();
                    const code = (item.dataset.code || '').toLowerCase();
                    const searchLower = searchTerm.toLowerCase();

                    if (title.includes(searchLower) || subtitle.includes(searchLower) || code.includes(searchLower)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    }

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-wrapper[data-select-id="' + selectId + '"]')) {
            closeDropdown();
        }
    });

    // Função para fechar dropdown
    function closeDropdown() {
        dropdown.classList.add('hidden');
        dropdown.style.display = 'none';
    }

    // Fechar dropdown ao pressionar Escape
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
            input.blur();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
                input.blur();
            }
        });
    }

    // Garantir que o dropdown esteja oculto inicialmente
    if (dropdown) {
        closeDropdown();
    }
    }; // Fechar a função initializeCustomSelect
}

// Funções globais para seleção e limpeza (definir apenas uma vez)
if (typeof window.selectItem === 'undefined') {
    window.selectItem = function(selectId, element) {
        const hiddenInput = document.getElementById(selectId + '_hidden');
        const displayInput = document.getElementById(selectId + '_input');
        const dropdown = document.getElementById(selectId + '_dropdown');

        const value = element.dataset.value;
        const title = element.dataset.title;
        const subtitle = element.dataset.subtitle;
        const code = element.dataset.code;

        // Atualizar valor hidden
        hiddenInput.value = value;

        // Atualizar display
        let displayText = '';
        if (code) {
            displayText = code + ' - ';
        }
        displayText += title;

        // Criar HTML do item selecionado
        let selectedHTML = `
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate">
                    ${displayText}
                </div>
        `;

        if (subtitle) {
            selectedHTML += `
                <div class="text-xs text-gray-500 truncate">
                    ${subtitle}
                </div>
            `;
        }

        selectedHTML += `
            </div>
            <button
                type="button"
                class="ml-2 text-gray-400 hover:text-red-600 pointer-events-auto"
                onclick="clearSelection('${selectId}')"
                title="Remover seleção"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        `;

        // Ocultar ícone de sitemap quando há item selecionado
        const sitemapIcon = document.getElementById(selectId + '_sitemap_icon');
        if (sitemapIcon) {
            sitemapIcon.style.display = 'none';
        }

        // Criar wrapper para o conteúdo selecionado
        const wrapper = document.createElement('div');
        wrapper.id = selectId + '_selected_display';
        wrapper.className = 'absolute inset-0 px-3 py-2 flex items-center justify-between';
        wrapper.style.pointerEvents = 'none';
        wrapper.innerHTML = selectedHTML;

        // O botão de remover precisa ter pointer-events
        const removeButton = wrapper.querySelector('button');
        if (removeButton) {
            removeButton.style.pointerEvents = 'auto';
        }

        // Limpar input e adicionar wrapper
        displayInput.value = '';
        displayInput.placeholder = '';
        const existingDisplay = displayInput.parentElement.querySelector('#' + selectId + '_selected_display');
        if (existingDisplay) {
            existingDisplay.remove();
        }
        wrapper.id = selectId + '_selected_display';
        displayInput.parentElement.appendChild(wrapper);

        // Fechar dropdown
        const dropdownEl = document.getElementById(selectId + '_dropdown');
        if (dropdownEl) {
            dropdownEl.classList.add('hidden');
            dropdownEl.style.setProperty('display', 'none', 'important');
        }
        displayInput.blur();

        // Disparar evento change
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    };
}

// Função para selecionar um item programaticamente (sem precisar do elemento DOM)
if (typeof window.selectItemProgrammatically === 'undefined') {
    window.selectItemProgrammatically = function(selectId, itemData) {
        const hiddenInput = document.getElementById(selectId + '_hidden');
        const displayInput = document.getElementById(selectId + '_input');
        const dropdown = document.getElementById(selectId + '_dropdown');

        if (!hiddenInput || !displayInput) {
            console.error('Select elements not found for:', selectId);
            return;
        }

        // Obter valores do item
        const value = itemData.id || itemData.value;
        const title = itemData.nome || itemData.title || itemData.nome_completo || '';
        const subtitle = itemData.email || itemData.subtitle || itemData.categoria || '';
        const code = itemData.documento || itemData.codigo || itemData.code || '';

        // Atualizar valor hidden
        hiddenInput.value = value;

        // Criar HTML do item selecionado
        let displayText = '';
        if (code) {
            displayText = code + ' - ';
        }
        displayText += title;

        let selectedHTML = `
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate">
                    ${displayText}
                </div>
        `;

        if (subtitle) {
            selectedHTML += `
                <div class="text-xs text-gray-500 truncate">
                    ${subtitle}
                </div>
            `;
        }

        selectedHTML += `
            </div>
            <button
                type="button"
                class="ml-2 text-gray-400 hover:text-red-600 pointer-events-auto"
                onclick="clearSelection('${selectId}')"
                title="Remover seleção"
            >
                <i class="fas fa-trash-alt"></i>
            </button>
        `;

        // Ocultar ícone de sitemap quando há item selecionado
        const sitemapIcon = document.getElementById(selectId + '_sitemap_icon');
        if (sitemapIcon) {
            sitemapIcon.style.display = 'none';
        }

        // Criar wrapper para o conteúdo selecionado
        const wrapper = document.createElement('div');
        wrapper.id = selectId + '_selected_display';
        wrapper.className = 'absolute inset-0 px-3 py-2 flex items-center justify-between';
        wrapper.style.pointerEvents = 'none';
        wrapper.innerHTML = selectedHTML;

        // O botão de remover precisa ter pointer-events
        const removeButton = wrapper.querySelector('button');
        if (removeButton) {
            removeButton.style.pointerEvents = 'auto';
        }

        // Limpar input e adicionar wrapper
        displayInput.value = '';
        displayInput.placeholder = '';
        const existingDisplay = displayInput.parentElement.querySelector('#' + selectId + '_selected_display');
        if (existingDisplay) {
            existingDisplay.remove();
        }
        displayInput.parentElement.appendChild(wrapper);

        // Fechar dropdown se estiver aberto
        if (dropdown) {
            dropdown.classList.add('hidden');
            dropdown.style.display = 'none';
        }
        displayInput.blur();

        // Disparar evento change
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    };
}

// Função para abrir modal e armazenar o select ID
if (typeof window.openModalWithSelect === 'undefined') {
    window.openModalWithSelect = function(modalId, selectId) {
        // Armazenar o select ID no modal para uso posterior
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.dataset.selectId = selectId;
        }
        openModal(modalId);
    };
}

if (typeof window.clearSelection === 'undefined') {
    window.clearSelection = function(selectId) {
        const hiddenInput = document.getElementById(selectId + '_hidden');
        const displayInput = document.getElementById(selectId + '_input');
        const dropdown = document.getElementById(selectId + '_dropdown');
        const searchInput = document.getElementById(selectId + '_search');

        // Limpar valores
        hiddenInput.value = '';
        displayInput.value = '';
        displayInput.placeholder = dropdown.querySelector('input[type="text"]')?.placeholder || 'Digite para buscar';

        // Remover wrapper do item selecionado
        const existingDisplay = displayInput.parentElement.querySelector('#' + selectId + '_selected_display');
        if (existingDisplay) {
            existingDisplay.remove();
        }

        // Mostrar ícone de sitemap novamente quando não há item selecionado
        const sitemapIcon = document.getElementById(selectId + '_sitemap_icon');
        if (sitemapIcon) {
            sitemapIcon.style.display = '';
        }

        // Limpar busca
        if (searchInput) {
            searchInput.value = '';
            // Mostrar todos os itens novamente
            const items = dropdown.querySelectorAll('.custom-select-item');
            items.forEach(item => {
                item.style.display = '';
            });
        }

        // Disparar evento change
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    };
}

// Inicializar este select quando o DOM estiver pronto
(function() {
    const selectId = '{{ $id }}';
    function init() {
        if (document.getElementById(selectId + '_input') && document.getElementById(selectId + '_dropdown')) {
            console.log('Initializing custom select for:', selectId);
            initializeCustomSelect(selectId);
        } else {
            // Tentar novamente após um pequeno delay se os elementos ainda não existirem
            setTimeout(init, 100);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush

