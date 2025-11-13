/**
 * CSelect - Componente de Select Personalizado
 * Uso: cSelect('#meu_input', { name: 'campo', items: [...] })
 */

(function() {
    'use strict';

    /**
     * Função principal para criar um CSelect
     * @param {string} selector - Seletor CSS do input (#id, .class, etc)
     * @param {object} options - Configurações do CSelect
     */
    window.cSelect = function(selector, options = {}) {
        // Configurar opções padrão
        const config = {
            name: options.name || '',
            items: options.items || getDefaultItems(),
            minSearchLength: options.minSearchLength || 0,
            placeholder: options.placeholder || 'Digite para buscar',
            itemValue: options.itemValue || 'value',
            itemLabel: options.itemLabel || 'label',
            itemTitle: options.itemTitle || null,
            itemSubtitle: options.itemSubtitle || null,
            loadOnOpen: options.loadOnOpen !== false,
            addNewButton: options.addNewButton || null, // { text: 'Adicionar novo', class: 'btn-class' }
            onSelect: options.onSelect || null,
            onClear: options.onClear || null,
            onAddNew: options.onAddNew || null,
            debug: options.debug === true
        };

        // Função helper para logs (só funciona se debug: true)
        const log = (...args) => {
            if (config.debug) {
                console.log(...args);
            }
        };

        log('🚀 Inicializando CSelect:', selector, options);

        // Buscar o input
        const input = document.querySelector(selector);

        if (!input) {
            console.error('❌ Input não encontrado:', selector);
            return;
        }

        // Verificar se já foi inicializado
        if (input.dataset.cselectInitialized === 'true') {
            if (config.debug) console.warn('⚠️ CSelect já inicializado:', selector);
            return;
        }

        log('📋 Config:', config);

        // Configurar o CSelect
        setupCSelect(input, config, log);

        return {
            input: input,
            config: config,
            clear: () => clearSelectionByInput(input),
            getValue: () => getHiddenInput(input)?.value || '',
            setValue: (value, label) => selectItemProgrammatically(input, value, label)
        };
    };

    /**
     * Itens padrão (mock)
     */
    function getDefaultItems() {
        return [
            { value: '1', label: 'A Combinar' },
            { value: '2', label: 'Boleto Bancário' },
            { value: '3', label: 'Carnê' },
            { value: '4', label: 'Cartão de Crédito' },
            { value: '5', label: 'Cartão de Débito' },
            { value: '6', label: 'Cheque' },
            { value: '7', label: 'Devolução de Mercadorias' },
            { value: '8', label: 'Dinheiro à Vista' }
        ];
    }

    /**
     * Configura um CSelect individual
     */
    function setupCSelect(input, config, log) {
        // Marcar como inicializado
        input.dataset.cselectInitialized = 'true';

        // Obter ou criar container pai
        let container = input.closest('.cselect-container');
        if (!container) {
            // Criar container se não existir
            container = document.createElement('div');
            container.className = 'cselect-container relative';
            input.parentNode.insertBefore(container, input);
            container.appendChild(input);
        }

        // Adicionar classes ao input
        if (!input.classList.contains('cselect-input')) {
            input.classList.add('cselect-input');
        }

        // Criar dropdown se não existir
        let dropdown = container.querySelector('.cselect-dropdown');
        if (!dropdown) {
            dropdown = createDropdown(input, config);
            container.appendChild(dropdown);
        }

        // Guardar config e log no input para uso posterior
        input._cselectConfig = config;
        input._cselectLog = log;

        // Adicionar event listeners
        attachEventListeners(input, dropdown, container, config, log);
    }

    /**
     * Cria o dropdown HTML
     */
    function createDropdown(input, config) {
        const dropdown = document.createElement('div');
        dropdown.className = 'cselect-dropdown hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg overflow-hidden';
        dropdown.id = `${input.id}_dropdown`;

        // Container com scroll para a lista
        const listContainer = document.createElement('div');
        listContainer.className = 'cselect-list-container max-h-60 overflow-y-auto';

        const ul = document.createElement('ul');
        ul.className = 'cselect-list';

        config.items.forEach(item => {
            const li = document.createElement('li');
            li.className = 'cselect-item px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors';
            li.dataset.value = item[config.itemValue];

            // Se tiver title e subtitle, criar estrutura com duas linhas
            if (config.itemTitle && config.itemSubtitle) {
                const title = item[config.itemTitle] || '';
                const subtitle = item[config.itemSubtitle] || '';

                li.innerHTML = `
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-900">${title}</span>
                        <span class="text-sm text-gray-500">${subtitle}</span>
                    </div>
                `;
                li.dataset.title = title;
                li.dataset.subtitle = subtitle;
            } else {
                // Modo simples (apenas label)
                li.textContent = item[config.itemLabel];
                li.dataset.label = item[config.itemLabel];
            }

            ul.appendChild(li);
        });

        listContainer.appendChild(ul);
        dropdown.appendChild(listContainer);

        // Adicionar botão "Adicionar Novo" se configurado (fixo no final)
        if (config.addNewButton) {
            const buttonText = config.addNewButton.text || 'Adicionar novo';
            const buttonClass = config.addNewButton.class || '';

            const addNewBtn = document.createElement('button');
            addNewBtn.type = 'button';
            addNewBtn.className = `cselect-add-new w-full px-4 py-3 text-left bg-green-500 hover:bg-green-600 text-white font-medium transition-colors border-t border-green-600 flex items-center justify-center gap-2 ${buttonClass}`;
            addNewBtn.innerHTML = `
                <i class="fas fa-plus"></i>
                <span>${buttonText}</span>
            `;

            dropdown.appendChild(addNewBtn);
        }

        return dropdown;
    }

    /**
     * Anexar event listeners
     */
    function attachEventListeners(input, dropdown, container, config, log) {
        // Abrir dropdown ao focar no input
        input.addEventListener('focus', () => {
            log('📂 Abrindo dropdown...');
            openDropdown(dropdown);
        });

        // Abrir dropdown ao clicar no input
        input.addEventListener('click', (e) => {
            e.stopPropagation();
            log('🖱️ Click no input...');
            openDropdown(dropdown);
        });

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                closeDropdown(dropdown);
            }
        });

        // Selecionar item do dropdown
        const items = dropdown.querySelectorAll('.cselect-item');
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const value = item.dataset.value;

                // Pegar label, title ou subtitle dependendo do modo
                let displayText = '';
                if (item.dataset.title) {
                    displayText = item.dataset.title;
                } else if (item.dataset.label) {
                    displayText = item.dataset.label;
                } else {
                    displayText = item.textContent.trim();
                }

                const subtitle = item.dataset.subtitle || null;

                log('✅ Item selecionado:', displayText, value, subtitle);
                selectItem(input, dropdown, container, value, displayText, config, log, subtitle);
            });
        });

        // Event listener para botão "Adicionar Novo"
        const addNewBtn = dropdown.querySelector('.cselect-add-new');
        if (addNewBtn) {
            addNewBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                log('➕ Botão "Adicionar Novo" clicado');

                // Fechar dropdown
                closeDropdown(dropdown);

                // Callback onAddNew
                if (config.onAddNew && typeof config.onAddNew === 'function') {
                    config.onAddNew();
                }
            });
        }
    }

    /**
     * Abrir dropdown
     */
    function openDropdown(dropdown) {
        dropdown.classList.remove('hidden');
    }

    /**
     * Fechar dropdown
     */
    function closeDropdown(dropdown) {
        dropdown.classList.add('hidden');
    }

    /**
     * Selecionar um item
     */
    function selectItem(input, dropdown, container, value, label, config, log, subtitle = null) {
        log('🎯 Selecionando item:', { value, label, subtitle });

        // Fechar dropdown
        closeDropdown(dropdown);

        // Esconder input original
        input.style.display = 'none';

        // Criar display do item selecionado
        const selectedDisplay = createSelectedDisplay(value, label, input.id, subtitle);

        // Inserir antes do dropdown
        container.insertBefore(selectedDisplay, dropdown);

        // Criar input hidden para enviar no formulário
        const hiddenName = config.name;

        let hiddenInput = container.querySelector('.cselect-hidden-input');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.className = 'cselect-hidden-input';
            hiddenInput.name = hiddenName;
            hiddenInput.id = `${input.id}_hidden`;
            container.appendChild(hiddenInput);
            log('✅ Input hidden criado:', hiddenInput.id);
            log('📌 Name do hidden input:', hiddenName);
        }
        hiddenInput.value = value;
        log('📝 Valor do input hidden atualizado:', { id: hiddenInput.id, name: hiddenInput.name, value: value });

        // Callback onSelect
        if (config.onSelect && typeof config.onSelect === 'function') {
            config.onSelect(value, label);
        }

        // Event listener para remover seleção
        const trashIcon = selectedDisplay.querySelector('.cselect-clear');
        if (trashIcon) {
            trashIcon.addEventListener('click', (e) => {
                e.stopPropagation();
                clearSelection(input, dropdown, container, selectedDisplay, hiddenInput, config, log);
            });
        }
    }

    /**
     * Criar display do item selecionado (fake input)
     */
    function createSelectedDisplay(value, label, inputId, subtitle = null) {
        const div = document.createElement('div');
        div.className = 'cselect-selected flex items-center justify-between w-full px-3 py-2 border border-gray-300 rounded-md bg-white';
        div.id = `${inputId}_selected`;

        // Sempre usar layout simples (uma linha) - subtitle não é exibido quando selecionado
        div.innerHTML = `
            <span class="cselect-selected-text">${label}</span>
            <button type="button" class="cselect-clear text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        `;

        return div;
    }

    /**
     * Limpar seleção (clicar na lixeira)
     */
    function clearSelection(input, dropdown, container, selectedDisplay, hiddenInput, config, log) {
        log('🗑️ Removendo seleção...');

        // Remover display selecionado
        if (selectedDisplay && selectedDisplay.parentNode) {
            selectedDisplay.remove();
        }

        // Limpar hidden input
        if (hiddenInput) {
            hiddenInput.value = '';
        }

        // Mostrar input original novamente
        input.style.display = '';
        input.value = '';

        // Callback onClear
        if (config.onClear && typeof config.onClear === 'function') {
            config.onClear();
        }

        // Focar no input
        input.focus();
    }

    /**
     * Funções auxiliares
     */
    function getHiddenInput(input) {
        const container = input.closest('.cselect-container');
        return container ? container.querySelector('.cselect-hidden-input') : null;
    }

    function clearSelectionByInput(input) {
        const container = input.closest('.cselect-container');
        if (!container) return;

        const selectedDisplay = container.querySelector('.cselect-selected');
        const hiddenInput = container.querySelector('.cselect-hidden-input');
        const dropdown = container.querySelector('.cselect-dropdown');
        const config = input._cselectConfig || {};
        const log = input._cselectLog || (() => {});

        if (selectedDisplay) {
            clearSelection(input, dropdown, container, selectedDisplay, hiddenInput, config, log);
        }
    }

    function selectItemProgrammatically(input, value, label) {
        const container = input.closest('.cselect-container');
        if (!container) return;

        const dropdown = container.querySelector('.cselect-dropdown');
        const config = input._cselectConfig || {};
        const log = input._cselectLog || (() => {});

        selectItem(input, dropdown, container, value, label, config, log);
    }
})();

