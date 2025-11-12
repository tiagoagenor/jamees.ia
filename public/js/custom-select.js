/**
 * Custom Select - Função para criar selects personalizados dinamicamente
 *
 * @param {string|HTMLElement} target - ID, classe ou elemento HTML onde o select será montado
 * @param {object} options - Configurações do select
 * @param {string} options.name - Nome do campo (obrigatório)
 * @param {string} options.id - ID único do select (padrão: options.name)
 * @param {string} options.label - Label do campo
 * @param {string} options.placeholder - Placeholder do input
 * @param {boolean} options.required - Se o campo é obrigatório
 * @param {string} options.mode - Modo: 'fixed' ou 'ajax' (padrão: 'fixed')
 * @param {array} options.items - Array de itens (modo fixed)
 * @param {string} options.itemValue - Campo que representa o valor (padrão: 'id')
 * @param {string} options.itemTitle - Campo que representa o título (padrão: 'nome')
 * @param {string} options.itemSubtitle - Campo que representa o subtítulo (opcional)
 * @param {string} options.itemCode - Campo que representa o código (opcional)
 * @param {string} options.ajaxUrl - URL para busca AJAX (modo ajax)
 * @param {string} options.ajaxMethod - Método HTTP (padrão: 'GET')
 * @param {number} options.minSearchLength - Mínimo de caracteres para buscar (padrão: 2)
 * @param {boolean} options.loadOnOpen - Carregar itens ao abrir dropdown (padrão: false)
 * @param {string} options.value - Valor selecionado inicial
 * @param {object} options.selectedItemData - Dados do item selecionado (modo ajax)
 * @param {string} options.addNewRoute - Rota para adicionar novo item
 * @param {string} options.addNewText - Texto do botão adicionar novo
 * @param {string} options.addNewModal - ID do modal para adicionar novo
 * @param {boolean} options.searchable - Se é pesquisável (padrão: true)
 * @param {string} options.emptyMessage - Mensagem quando vazio (padrão: 'Nenhum item encontrado')
 */
window.createCustomSelect = function(target, options) {
    // Validações
    if (!options || !options.name) {
        console.error('Custom Select: "name" é obrigatório nas opções');
        return;
    }

    // Obter elemento alvo
    let targetEl;
    if (typeof target === 'string') {
        if (target.startsWith('.')) {
            targetEl = document.querySelector(target);
        } else if (target.startsWith('#')) {
            targetEl = document.getElementById(target.substring(1));
        } else {
            targetEl = document.getElementById(target);
        }
    } else if (target instanceof HTMLElement) {
        targetEl = target;
    }

    if (!targetEl) {
        console.error('Custom Select: Elemento alvo não encontrado:', target);
        return;
    }

    // Configurações padrão
    const config = {
        id: options.id || options.name,
        name: options.name,
        label: options.label || '',
        placeholder: options.placeholder || 'Digite para buscar',
        required: options.required || false,
        mode: options.mode || 'fixed',
        items: options.items || [],
        itemValue: options.itemValue || 'id',
        itemTitle: options.itemTitle || 'nome',
        itemSubtitle: options.itemSubtitle || null,
        itemCode: options.itemCode || null,
        ajaxUrl: options.ajaxUrl || null,
        ajaxMethod: options.ajaxMethod || 'GET',
        minSearchLength: options.minSearchLength || 2,
        loadOnOpen: options.loadOnOpen || false,
        value: options.value || null,
        selectedItemData: options.selectedItemData || null,
        addNewRoute: options.addNewRoute || null,
        addNewText: options.addNewText || 'Adicionar novo',
        addNewModal: options.addNewModal || null,
        searchable: options.searchable !== false,
        emptyMessage: options.emptyMessage || 'Nenhum item encontrado'
    };

    console.log('[Custom Select] Criando select:', config.id, 'Mode:', config.mode);

    // Encontrar item selecionado (modo fixed)
    let selectedItem = null;
    if (config.mode === 'fixed' && config.value && config.items.length > 0) {
        selectedItem = config.items.find(item => item[config.itemValue] == config.value);
    } else if (config.mode === 'ajax' && config.selectedItemData) {
        selectedItem = config.selectedItemData;
    }

    // Gerar HTML do select
    const selectId = config.id;
    const wrapperId = selectId + '_wrapper';

    let html = `<div class="custom-select-wrapper" id="${wrapperId}" data-select-id="${selectId}"`;
    if (config.mode === 'ajax' && config.ajaxUrl) {
        html += ` data-ajax-url="${config.ajaxUrl}"`;
    }
    html += `>`;

    // Label
    if (config.label) {
        html += `
            <label for="${selectId}" class="block text-sm font-medium text-gray-700 mb-2">
                ${config.label}
                ${config.required ? '<span class="text-red-500">*</span>' : ''}
            </label>
        `;
    }

    // Container do input
    html += `
        <div class="relative">
            <div class="custom-select-input-container relative" id="${selectId}_input_container">
    `;

    // Input principal (mostra item selecionado ou placeholder)
    if (selectedItem) {
        const displayTitle = selectedItem[config.itemTitle] || '';
        const displaySubtitle = config.itemSubtitle && selectedItem[config.itemSubtitle] ? selectedItem[config.itemSubtitle] : '';
        const displayCode = config.itemCode && selectedItem[config.itemCode] ? selectedItem[config.itemCode] : '';

        html += `
            <div class="custom-select-selected-display flex items-center justify-between p-2 border border-gray-300 rounded-md bg-white cursor-pointer" id="${selectId}_selected_display">
                <div class="flex-1">
                    ${displayCode ? `<span class="font-semibold text-gray-900">${displayCode}</span> - ` : ''}
                    <span class="text-gray-900">${displayTitle}</span>
                    ${displaySubtitle ? `<div class="text-xs text-gray-500 mt-1">${displaySubtitle}</div>` : ''}
                </div>
                <button type="button" onclick="window.clearCustomSelect('${selectId}')" class="ml-2 text-gray-400 hover:text-red-600" title="Remover seleção">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    } else {
        html += `
            <input
                type="text"
                id="${selectId}_input"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="${config.placeholder}"
                autocomplete="off"
                ${config.required ? 'required' : ''}
            >
        `;
    }

    // Input hidden para o valor real
    html += `
            <input
                type="hidden"
                name="${config.name}"
                id="${selectId}_hidden"
                value="${config.value || ''}"
                ${config.required ? 'required' : ''}
            >
        </div>
    `;

    // Dropdown
    html += `
        <div
            id="${selectId}_dropdown"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden"
            style="max-height: 300px; overflow-y: auto;"
        >
            <div id="${selectId}_list" class="py-1">
    `;

    // Itens iniciais (modo fixed)
    if (config.mode === 'fixed' && config.items.length > 0) {
        config.items.forEach(item => {
            const itemValue = item[config.itemValue] || '';
            const itemTitle = item[config.itemTitle] || '';
            const itemSubtitle = config.itemSubtitle && item[config.itemSubtitle] ? item[config.itemSubtitle] : '';
            const itemCode = config.itemCode && item[config.itemCode] ? item[config.itemCode] : '';

            html += `
                <div
                    class="custom-select-item px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                    data-value="${itemValue}"
                    data-title="${itemTitle}"
                    data-subtitle="${itemSubtitle}"
                    data-code="${itemCode}"
                >
                    <div class="text-sm font-medium text-gray-900">
                        ${itemCode ? `<span class="font-semibold">${itemCode}</span> - ` : ''}
                        ${itemTitle}
                    </div>
                    ${itemSubtitle ? `<div class="text-xs text-gray-500 mt-1">${itemSubtitle}</div>` : ''}
                </div>
            `;
        });
    }

    // Loading
    html += `
                <div id="${selectId}_loading" class="px-4 py-3 text-center text-gray-500 hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Carregando...
                </div>
    `;

    // Empty
    html += `
                <div id="${selectId}_empty" class="px-4 py-3 text-center text-gray-500 hidden">
                    ${config.emptyMessage}
                </div>
    `;

    html += `
            </div>
    `;

    // Botão adicionar novo
    if (config.addNewRoute || config.addNewModal) {
        html += `
            <div class="sticky bottom-0 bg-white border-t border-gray-200 p-2">
        `;
        if (config.addNewModal) {
            html += `
                <button
                    type="button"
                    onclick="if(typeof openModal === 'function') { const modal = document.getElementById('${config.addNewModal}'); if(modal) modal.dataset.selectId = '${selectId}'; openModal('${config.addNewModal}'); }"
                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    ${config.addNewText}
                </button>
            `;
        } else {
            html += `
                <a
                    href="${config.addNewRoute}"
                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    ${config.addNewText}
                </a>
            `;
        }
        html += `
            </div>
        `;
    }

    html += `
        </div>
    </div>
    `;

    // Inserir HTML no elemento alvo
    targetEl.innerHTML = html;

    // Armazenar configuração para uso posterior (ex: clearCustomSelect)
    if (!window._customSelectConfigs) {
        window._customSelectConfigs = {};
    }
    window._customSelectConfigs[selectId] = config;

    // Inicializar funcionalidades JavaScript
    initializeCustomSelectJS(selectId, config);
};

/**
 * Limpar seleção do custom select - Mostra o input novamente para permitir nova seleção
 */
window.clearCustomSelect = function(selectId) {
    console.log('===== CLEAR CUSTOM SELECT =====');
    console.log('SelectId:', selectId);

    const hiddenInput = document.getElementById(selectId + '_hidden');
    const selectedDisplay = document.getElementById(selectId + '_selected_display');
    let input = document.getElementById(selectId + '_input');
    const dropdown = document.getElementById(selectId + '_dropdown');
    const inputContainer = document.getElementById(selectId + '_input_container');
    const wrapper = document.getElementById(selectId + '_wrapper');
    const config = window._customSelectConfigs && window._customSelectConfigs[selectId];

    console.log('Elementos encontrados:', {
        hiddenInput: !!hiddenInput,
        selectedDisplay: !!selectedDisplay,
        input: !!input,
        dropdown: !!dropdown,
        inputContainer: !!inputContainer,
        wrapper: !!wrapper,
        config: !!config
    });

    if (!config) {
        console.error('Config não encontrada para:', selectId);
        return;
    }

    // Remover o display do item selecionado
    if (selectedDisplay) {
        console.log('Removendo selectedDisplay');
        selectedDisplay.remove();
    }

    // Limpar hidden input
    if (hiddenInput) {
        hiddenInput.value = '';
    }

    // Criar ou mostrar o input novamente
    if (!input) {
        console.log('Input não existe, criando novo...');
        // Se o input não existe, criar um novo
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.id = selectId + '_input';
        newInput.className = 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent';
        newInput.placeholder = config.placeholder;
        newInput.autocomplete = 'off';
        if (config.required) newInput.required = true;

        // Inserir no inputContainer antes do hidden input
        if (inputContainer) {
            const hidden = inputContainer.querySelector('input[type="hidden"]');
            if (hidden) {
                inputContainer.insertBefore(newInput, hidden);
            } else {
                inputContainer.appendChild(newInput);
            }
        }

        input = newInput;
        console.log('Novo input criado:', input.id);
    } else {
        console.log('Input existe, limpando e mostrando...');
        input.value = '';
        input.style.display = 'block';
        input.style.visibility = 'visible';
    }

    // Fechar dropdown se estiver aberto
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.style.display = 'none';
    }

    console.log('Aguardando para reanexar listeners...');

    // Reanexar event listeners para garantir que o dropdown funcione
    setTimeout(() => {
        const currentInput = document.getElementById(selectId + '_input');
        console.log('Input após timeout:', !!currentInput);

        if (currentInput && config) {
            console.log('Reanexando listeners...');
            // Reanexar listeners
            reattachEventListeners(selectId, config);

            // Focar no input
            setTimeout(() => {
                const inputToFocus = document.getElementById(selectId + '_input');
                if (inputToFocus) {
                    console.log('Focando no input...');
                    inputToFocus.focus();
                }
            }, 50);
        } else {
            console.error('Input não encontrado após limpar seleção:', selectId);
        }
    }, 150);

    console.log('===== FIM CLEAR CUSTOM SELECT =====');
};

/**
 * Reanexar event listeners após limpar seleção
 */
function reattachEventListeners(selectId, config) {
    const input = document.getElementById(selectId + '_input');
    const dropdown = document.getElementById(selectId + '_dropdown');

    if (!input || !dropdown) {
        console.error('Elementos não encontrados para reanexar listeners:', selectId, 'input:', !!input, 'dropdown:', !!dropdown);
        return;
    }

    // Função para abrir dropdown - buscar dropdown dinamicamente
    function openDropdown() {
        const currentDropdown = document.getElementById(selectId + '_dropdown');
        if (!currentDropdown) {
            console.error('Dropdown não encontrado para:', selectId);
            return;
        }

        console.log('Abrindo dropdown para:', selectId);

        currentDropdown.classList.remove('hidden');
        currentDropdown.style.cssText = currentDropdown.style.cssText.replace(/display\s*:\s*none[^;]*;?/gi, '');
        currentDropdown.style.display = 'block';
        void currentDropdown.offsetHeight;

        // Se modo AJAX com loadOnOpen, carregar itens
        if (config.mode === 'ajax' && config.loadOnOpen) {
            const listEl = document.getElementById(selectId + '_list');
            const existingItems = listEl ? listEl.querySelectorAll('.custom-select-item') : [];

            if (existingItems.length === 0) {
                const loadingEl = document.getElementById(selectId + '_loading');
                const emptyEl = document.getElementById(selectId + '_empty');
                if (loadingEl) loadingEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');

                setTimeout(() => {
                    if (typeof window.buscarViaAjax === 'function') {
                        window.buscarViaAjax(selectId, '', {
                            ajaxUrl: config.ajaxUrl,
                            ajaxMethod: config.ajaxMethod,
                            itemValue: config.itemValue,
                            itemTitle: config.itemTitle,
                            itemSubtitle: config.itemSubtitle,
                            itemCode: config.itemCode
                        });
                    }
                }, 50);
            }
        }

        // Focar no input
        setTimeout(() => {
            const currentInput = document.getElementById(selectId + '_input');
            if (currentInput) {
                currentInput.focus();
                currentInput.select();
            }
        }, 50);
    }

    // Remover todos os listeners antigos clonando o elemento
    const newInput = input.cloneNode(true);
    // Remover a flag de inicialização para permitir reanexar
    delete newInput.dataset.customSelectInitialized;
    input.parentNode.replaceChild(newInput, input);

    const actualInput = document.getElementById(selectId + '_input');

    if (!actualInput) {
        console.error('Input não encontrado após clonar:', selectId);
        return;
    }

    console.log('[reattachEventListeners] Reanexando listeners para:', selectId);

    // Marcar como inicializado
    actualInput.dataset.customSelectInitialized = 'true';

    // Event listeners - focus
    actualInput.addEventListener('focus', function(e) {
        console.log('[reattachEventListeners] Focus event disparado para:', selectId);
        e.stopPropagation();
        openDropdown();
    });

    // Event listeners - click
    actualInput.addEventListener('click', function(e) {
        console.log('[reattachEventListeners] Click event disparado para:', selectId);
        e.stopPropagation();
        openDropdown();
    });

    // Busca (modo fixed)
    if (config.mode === 'fixed') {
        actualInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const list = document.getElementById(selectId + '_list');
            if (!list) return;

            const items = list.querySelectorAll('.custom-select-item');
            items.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const subtitle = item.getAttribute('data-subtitle') || '';
                const code = item.getAttribute('data-code') || '';
                const text = (code + ' ' + title + ' ' + subtitle).toLowerCase();

                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Busca (modo AJAX)
    if (config.mode === 'ajax') {
        let searchTimeout = null;

        actualInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            const minLength = config.minSearchLength;
            const loadOnOpen = config.loadOnOpen;

            if (searchTimeout) clearTimeout(searchTimeout);

            if (searchTerm.length >= minLength || (searchTerm.length === 0 && loadOnOpen)) {
                const loadingEl = document.getElementById(selectId + '_loading');
                const emptyEl = document.getElementById(selectId + '_empty');
                const listEl = document.getElementById(selectId + '_list');

                if (loadingEl) loadingEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');

                const existingItems = listEl.querySelectorAll('.custom-select-item');
                existingItems.forEach(item => item.remove());

                const ajaxConfig = {
                    ajaxUrl: config.ajaxUrl,
                    ajaxMethod: config.ajaxMethod,
                    itemValue: config.itemValue,
                    itemTitle: config.itemTitle,
                    itemSubtitle: config.itemSubtitle,
                    itemCode: config.itemCode
                };

                if (searchTerm.length > 0) {
                    searchTimeout = setTimeout(() => {
                        if (typeof window.buscarViaAjax === 'function') {
                            window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                        }
                    }, 300);
                } else {
                    if (typeof window.buscarViaAjax === 'function') {
                        window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                    }
                }
            }
        });
    }

    // Reanexar click nos itens do dropdown
    const list = document.getElementById(selectId + '_list');
    if (list) {
        console.log('[reattachEventListeners] Reanexando click nos itens para:', selectId);

        // Remover listener antigo clonando a lista
        const newList = list.cloneNode(true);
        list.parentNode.replaceChild(newList, list);

        // Adicionar novo listener
        const currentList = document.getElementById(selectId + '_list');
        if (currentList) {
            currentList.addEventListener('click', (e) => {
                console.log('[reattachEventListeners] Click em item do dropdown:', selectId);
                const item = e.target.closest('.custom-select-item');
                if (item) {
                    console.log('[reattachEventListeners] Item encontrado, selecionando...');
                    if (typeof window.selectCustomItem === 'function') {
                        window.selectCustomItem(selectId, item);
                    }
                }
            });
        }
    }

    // Fechar ao clicar fora - usar uma função única por selectId para poder remover depois
    const closeHandlerName = 'closeDropdown_' + selectId;
    if (window[closeHandlerName]) {
        document.removeEventListener('click', window[closeHandlerName]);
    }

    window[closeHandlerName] = function(e) {
        const wrapper = document.getElementById(selectId + '_wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const currentDropdown = document.getElementById(selectId + '_dropdown');
            if (currentDropdown) {
                currentDropdown.classList.add('hidden');
                currentDropdown.style.display = 'none';
            }
        }
    };

    document.addEventListener('click', window[closeHandlerName]);
}

/**
 * Inicializar funcionalidades JavaScript do custom select
 */
function initializeCustomSelectJS(selectId, config) {
    const input = document.getElementById(selectId + '_input');
    const dropdown = document.getElementById(selectId + '_dropdown');
    const searchInput = document.getElementById(selectId + '_search');
    const hiddenInput = document.getElementById(selectId + '_hidden');
    const list = document.getElementById(selectId + '_list');
    const selectedDisplay = document.getElementById(selectId + '_selected_display');

    console.log('[initializeCustomSelectJS] Iniciando para:', selectId);

    if (!input && !selectedDisplay) {
        console.error('Custom Select: Elementos não encontrados para:', selectId);
        return;
    }

    // Verificar se já foi inicializado para evitar duplicação
    if (input && input.dataset.customSelectInitialized === 'true') {
        console.log('[initializeCustomSelectJS] Já inicializado, pulando:', selectId);
        return;
    }

    // Marcar como inicializado
    if (input) {
        input.dataset.customSelectInitialized = 'true';
    }

    // Se houver selectedDisplay, criar input oculto para busca
    let actualInput = input;
    if (selectedDisplay && !input) {
        // Criar input de busca que aparece quando clicar
        const inputContainer = document.getElementById(selectId + '_input_container');
        actualInput = document.createElement('input');
        actualInput.type = 'text';
        actualInput.id = selectId + '_input';
        actualInput.className = 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent';
        actualInput.placeholder = config.placeholder;
        actualInput.style.display = 'none';
        if (inputContainer) {
            inputContainer.appendChild(actualInput);
        }
    }

    // Função para abrir dropdown
    function openDropdown() {
        if (!dropdown) return;

        dropdown.classList.remove('hidden');
        dropdown.style.cssText = dropdown.style.cssText.replace(/display\s*:\s*none[^;]*;?/gi, '');
        dropdown.style.display = 'block';
        void dropdown.offsetHeight;

        // Se modo AJAX com loadOnOpen, carregar itens
        if (config.mode === 'ajax' && config.loadOnOpen) {
            const listEl = document.getElementById(selectId + '_list');
            const existingItems = listEl ? listEl.querySelectorAll('.custom-select-item') : [];

            if (existingItems.length === 0) {
                // Mostrar loading
                const loadingEl = document.getElementById(selectId + '_loading');
                const emptyEl = document.getElementById(selectId + '_empty');
                if (loadingEl) loadingEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');

                // Fazer requisição AJAX
                setTimeout(() => {
                    if (typeof window.buscarViaAjax === 'function') {
                        window.buscarViaAjax(selectId, '', {
                            ajaxUrl: config.ajaxUrl,
                            ajaxMethod: config.ajaxMethod,
                            itemValue: config.itemValue,
                            itemTitle: config.itemTitle,
                            itemSubtitle: config.itemSubtitle,
                            itemCode: config.itemCode
                        });
                    }
                }, 50);
            }
        }

        // Focar no input de busca
        if (actualInput) {
            setTimeout(() => {
                if (actualInput) {
                    actualInput.focus();
                    actualInput.select();
                }
            }, 50);
        }
    }

    // Função para fechar dropdown
    function closeDropdown() {
        if (dropdown) {
            dropdown.classList.add('hidden');
            dropdown.style.display = 'none';
        }
    }

    // Event listeners - apenas se ainda não foi inicializado
    if (actualInput) {
        console.log('[initializeCustomSelectJS] Adicionando listeners para:', selectId);

        actualInput.addEventListener('focus', (e) => {
            console.log('[initializeCustomSelectJS] Focus event para:', selectId);
            e.stopPropagation();
            openDropdown();
        });

        actualInput.addEventListener('click', (e) => {
            console.log('[initializeCustomSelectJS] Click event para:', selectId);
            e.stopPropagation();
            openDropdown();
        });
    }

    if (selectedDisplay) {
        selectedDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            openDropdown();
        });
    }

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById(selectId + '_wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            closeDropdown();
        }
    });

    // Busca (modo fixed)
    if (actualInput && config.mode === 'fixed') {
        actualInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const items = list.querySelectorAll('.custom-select-item');

            items.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const subtitle = item.getAttribute('data-subtitle') || '';
                const code = item.getAttribute('data-code') || '';
                const text = (code + ' ' + title + ' ' + subtitle).toLowerCase();

                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Busca (modo AJAX)
    if (actualInput && config.mode === 'ajax') {
        let searchTimeout = null;

        actualInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            const minLength = config.minSearchLength;
            const loadOnOpen = config.loadOnOpen;

            if (searchTimeout) clearTimeout(searchTimeout);

            if (searchTerm.length >= minLength || (searchTerm.length === 0 && loadOnOpen)) {
                const loadingEl = document.getElementById(selectId + '_loading');
                const emptyEl = document.getElementById(selectId + '_empty');
                const listEl = document.getElementById(selectId + '_list');

                if (loadingEl) loadingEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');

                const existingItems = listEl.querySelectorAll('.custom-select-item');
                existingItems.forEach(item => item.remove());

                const ajaxConfig = {
                    ajaxUrl: config.ajaxUrl,
                    ajaxMethod: config.ajaxMethod,
                    itemValue: config.itemValue,
                    itemTitle: config.itemTitle,
                    itemSubtitle: config.itemSubtitle,
                    itemCode: config.itemCode
                };

                if (searchTerm.length > 0) {
                    searchTimeout = setTimeout(() => {
                        if (typeof window.buscarViaAjax === 'function') {
                            window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                        }
                    }, 300);
                } else {
                    if (typeof window.buscarViaAjax === 'function') {
                        window.buscarViaAjax(selectId, searchTerm, ajaxConfig);
                    }
                }
            }
        });
    }

    // Selecionar item
    window.selectCustomItem = window.selectCustomItem || function(selectId, itemEl) {
        console.log('===== SELECT CUSTOM ITEM =====');
        console.log('SelectId:', selectId);

        const value = itemEl.getAttribute('data-value');
        const title = itemEl.getAttribute('data-title');
        const subtitle = itemEl.getAttribute('data-subtitle');
        const code = itemEl.getAttribute('data-code');

        console.log('Item selecionado:', { value, title, subtitle, code });

        const hiddenInput = document.getElementById(selectId + '_hidden');
        const input = document.getElementById(selectId + '_input');
        const selectedDisplay = document.getElementById(selectId + '_selected_display');
        const dropdown = document.getElementById(selectId + '_dropdown');
        const inputContainer = document.getElementById(selectId + '_input_container');

        console.log('Elementos ANTES da modificação:', {
            hiddenInput: !!hiddenInput,
            input: !!input,
            selectedDisplay: !!selectedDisplay,
            dropdown: !!dropdown,
            inputContainer: !!inputContainer
        });

        if (hiddenInput) hiddenInput.value = value;

        // Remover display anterior
        if (selectedDisplay) selectedDisplay.remove();
        if (input) input.style.display = 'none';

        // Criar novo display
        let displayHtml = `
            <div class="custom-select-selected-display flex items-center justify-between p-2 border border-gray-300 rounded-md bg-white cursor-pointer" id="${selectId}_selected_display">
                <div class="flex-1">
                    ${code ? `<span class="font-semibold text-gray-900">${code}</span> - ` : ''}
                    <span class="text-gray-900">${title}</span>
                    ${subtitle ? `<div class="text-xs text-gray-500 mt-1">${subtitle}</div>` : ''}
                </div>
                <button type="button" onclick="window.clearCustomSelect('${selectId}')" class="ml-2 text-gray-400 hover:text-red-600" title="Remover seleção">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        console.log('Inserindo selectedDisplay no inputContainer...');

        // Em vez de substituir todo o innerHTML, vamos inserir o display e manter o hidden input
        const hiddenInputEl = inputContainer ? inputContainer.querySelector('input[type="hidden"]') : null;

        // Criar elemento do display
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = displayHtml;
        const newDisplayEl = tempDiv.firstElementChild;

        // Inserir antes do hidden input
        if (inputContainer) {
            if (hiddenInputEl) {
                inputContainer.insertBefore(newDisplayEl, hiddenInputEl);
            } else {
                inputContainer.appendChild(newDisplayEl);
            }
        }

        console.log('Elementos DEPOIS da modificação:');
        const dropdownAfter = document.getElementById(selectId + '_dropdown');
        console.log('Dropdown ainda existe?', !!dropdownAfter);

        // Reanexar event listeners no display
        const newDisplay = document.getElementById(selectId + '_selected_display');
        if (newDisplay) {
            newDisplay.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dropdown) {
                    dropdown.classList.remove('hidden');
                    dropdown.style.display = 'block';
                }
            });
        }

        console.log('Fechando/escondendo dropdown...');
        // Fechar dropdown (apenas esconder, não remover)
        if (dropdown) {
            dropdown.classList.add('hidden');
            dropdown.style.display = 'none';
        }

        // Disparar evento change
        if (hiddenInput) {
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        console.log('===== FIM SELECT CUSTOM ITEM =====');
    };

    // Adicionar click nos itens
    if (list) {
        list.addEventListener('click', (e) => {
            const item = e.target.closest('.custom-select-item');
            if (item) {
                if (typeof window.selectCustomItem === 'function') {
                    window.selectCustomItem(selectId, item);
                }
            }
        });
    }
}

// Função buscarViaAjax (se não existir)
if (typeof window.buscarViaAjax === 'undefined') {
    window.buscarViaAjax = function(selectId, searchTerm, config) {
        config = config || {};
        let ajaxUrl = config.ajaxUrl;

        if (!ajaxUrl || ajaxUrl === 'null' || ajaxUrl === '') {
            console.error('AJAX URL não configurada para:', selectId);
            return;
        }

        const loadingEl = document.getElementById(selectId + '_loading');
        const emptyEl = document.getElementById(selectId + '_empty');
        const listEl = document.getElementById(selectId + '_list');

        if (loadingEl) loadingEl.classList.remove('hidden');
        if (emptyEl) emptyEl.classList.add('hidden');

        const params = { search: searchTerm || '' };
        let url;
        try {
            if (ajaxUrl.startsWith('http://') || ajaxUrl.startsWith('https://')) {
                url = new URL(ajaxUrl);
            } else {
                url = new URL(ajaxUrl, window.location.origin);
            }
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                    url.searchParams.append(key, params[key]);
                }
            });
        } catch (error) {
            console.error('Erro ao construir URL:', error);
            return;
        }

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
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (loadingEl) loadingEl.classList.add('hidden');

            const existingItems = listEl.querySelectorAll('.custom-select-item');
            existingItems.forEach(item => item.remove());

            if (data.items && data.items.length > 0) {
                if (emptyEl) emptyEl.classList.add('hidden');

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

/**
 * Selecionar item programaticamente (usado após criar novo item via modal)
 */
window.selectItemProgrammatically = function(selectId, itemData) {
    console.log('[selectItemProgrammatically] Selecionando item para:', selectId, itemData);

    const config = window._customSelectConfigs && window._customSelectConfigs[selectId];
    if (!config) {
        console.error('[selectItemProgrammatically] Config não encontrada para:', selectId);
        return;
    }

    // Criar um item temporário com os dados
    const tempDiv = document.createElement('div');
    tempDiv.className = 'custom-select-item';
    tempDiv.setAttribute('data-value', itemData.id || itemData[config.itemValue]);
    tempDiv.setAttribute('data-title', itemData.nome || itemData[config.itemTitle]);
    tempDiv.setAttribute('data-subtitle', itemData.email || itemData[config.itemSubtitle] || '');
    tempDiv.setAttribute('data-code', itemData.documento || itemData[config.itemCode] || '');

    // Usar a função selectCustomItem existente
    if (typeof window.selectCustomItem === 'function') {
        window.selectCustomItem(selectId, tempDiv);
        console.log('[selectItemProgrammatically] Item selecionado com sucesso!');
    } else {
        console.error('[selectItemProgrammatically] Função selectCustomItem não encontrada');
    }
};

