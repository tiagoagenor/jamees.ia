/**
 * CSelect - Componente de Select Personalizado
 * Uso: cSelect('#meu_input', { name: 'campo', items: [...] })
 */

(function() {
    'use strict';

    /**
     * Funções globais para gerenciar modais
     * Essas funções são usadas pelo CSelect quando modais são configurados
     */

    // Função para abrir modal
    if (typeof window.openModal === 'undefined') {
        window.openModal = function(modalId, selectId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Definir selectId no modal se fornecido
                if (selectId) {
                    modal.dataset.selectId = selectId;
                }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('Modal não encontrado:', modalId);
            }
        };
    }

    // Função para fechar modal
    if (typeof window.closeModal === 'undefined') {
        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';

                // Limpar formulário se existir
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    // Limpar erros de validação
                    const errors = form.querySelectorAll('.text-red-600, .error-message');
                    errors.forEach(error => {
                        error.classList.add('hidden');
                        if (error.textContent) {
                            error.textContent = '';
                        }
                    });
                }
            }
        };
    }

    // Fechar modal ao clicar no backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modalId = e.target.id;
            if (modalId) {
                window.closeModal(modalId);
            }
        }
    });

    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[id$="-modal"]:not(.hidden), .cselect-modal-visible');
            openModals.forEach(modal => {
                if (modal.id) {
                    window.closeModal(modal.id);
                }
            });
        }
    });

    /**
     * Funções auxiliares para máscaras de input
     */

    // Aplicar máscara de CPF/CNPJ
    window.applyCpfCnpjMask = function(input, tipoPessoa) {
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            const tipo = tipoPessoa || document.getElementById('modal_tipo_pessoa')?.value || '1';

            if (tipo == '2') { // PJ - CNPJ
                if (value.length > 14) value = value.substring(0, 14);
                if (value.length >= 2) {
                    e.target.value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                } else {
                    e.target.value = value;
                }
            } else { // PF - CPF
                if (value.length > 11) value = value.substring(0, 11);
                if (value.length >= 3) {
                    e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else {
                    e.target.value = value;
                }
            }
        });
    };

    // Aplicar máscara de telefone
    window.applyPhoneMask = function(input) {
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.substring(0, 11);

            if (value.length >= 2) {
                if (value.length <= 10) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                }
            } else if (value.length > 0) {
                e.target.value = `(${value}`;
            } else {
                e.target.value = '';
            }
        });
    };

    // Aplicar máscara de CEP
    window.applyCepMask = function(input) {
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.substring(0, 8);
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });
    };

    /**
     * Funções auxiliares para gerenciar itens dinâmicos (contatos, endereços, etc)
     */

    // Adicionar item dinâmico
    window.addDynamicItem = function(config) {
        const {
            containerId,
            itemClass = 'dynamic-item',
            itemIndex,
            template,
            onAdd = null
        } = config;

        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Container não encontrado:', containerId);
            return;
        }

        const item = document.createElement('div');
        item.className = itemClass;
        item.innerHTML = template(itemIndex);

        container.appendChild(item);

        // Aplicar máscaras se necessário
        const cepInput = item.querySelector('input[name*="[cep]"]');
        if (cepInput) {
            window.applyCepMask(cepInput);
        }

        // Callback após adicionar
        if (onAdd && typeof onAdd === 'function') {
            onAdd(item);
        }

        return item;
    };

    // Remover item dinâmico
    window.removeDynamicItem = function(button, itemClass = 'dynamic-item') {
        const item = button.closest('.' + itemClass);
        if (item) {
            item.remove();
        }
    };

    /**
     * Função genérica para submeter formulário via AJAX
     */
    window.submitFormAjax = function(config) {
        const {
            formId,
            url,
            method = 'POST',
            onSuccess = null,
            onError = null,
            onFinally = null,
            selectId = null,
            selectItemFunction = null
        } = config;

        const form = document.getElementById(formId);
        if (!form) {
            console.error('Formulário não encontrado:', formId);
            return;
        }

        const formData = new FormData(form);

        // Limpar erros anteriores
        form.querySelectorAll('[id$="_error"], .error-message').forEach(el => {
            el.classList.add('hidden');
            if (el.textContent) {
                el.textContent = '';
            }
        });

        // Mostrar loading
        const submitButton = form.querySelector('button[type="submit"], button.onclick');
        let originalButtonState = null;
        if (submitButton) {
            originalButtonState = {
                disabled: submitButton.disabled,
                innerHTML: submitButton.innerHTML
            };
            submitButton.disabled = true;
            if (submitButton.innerHTML) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            }
        }

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw { response: { json: () => Promise.resolve(data) }, status: response.status };
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Fechar modal se existir
                const modal = form.closest('.cselect-modal-overlay, [id$="-modal"]');
                if (modal && modal.id) {
                    window.closeModal(modal.id);
                }

                // Mostrar mensagem de sucesso
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: data.message || 'Salvo com sucesso!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                // Selecionar item no select se configurado
                if (selectId && selectItemFunction && typeof selectItemFunction === 'function') {
                    setTimeout(() => {
                        selectItemFunction(selectId, data);
                    }, 100);
                }

                // Callback de sucesso
                if (onSuccess && typeof onSuccess === 'function') {
                    onSuccess(data);
                }
            } else {
                throw new Error(data.message || 'Erro ao salvar');
            }
        })
        .catch(error => {
            console.error('Erro:', error);

            // Se for erro de validação
            if (error.response) {
                error.response.json().then(data => {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = form.querySelector(`#modal_${field}_error, #${field}_error`);
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    } else if (data.message) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: data.message
                            });
                        }
                    }
                });
            } else {
                // Erro genérico
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: error.message || 'Erro ao salvar. Tente novamente.'
                    });
                }
            }

            // Callback de erro
            if (onError && typeof onError === 'function') {
                onError(error);
            }
        })
        .finally(() => {
            // Restaurar estado do botão
            if (submitButton && originalButtonState) {
                submitButton.disabled = originalButtonState.disabled;
                submitButton.innerHTML = originalButtonState.innerHTML;
            }

            // Callback finally
            if (onFinally && typeof onFinally === 'function') {
                onFinally();
            }
        });
    };

    /**
     * Função principal para criar um CSelect
     * @param {string} selector - Seletor CSS do input (#id, .class, etc)
     * @param {object} options - Configurações do CSelect
     */
    window.cSelect = function(selector, options = {}) {
        // Configurar opções padrão
        const config = {
            name: options.name || '',
            items: options.items !== undefined ? options.items : (options.http ? [] : getDefaultItems()),
            minSearchLength: options.minSearchLength || 0,
            placeholder: options.placeholder || 'Digite para buscar',
            itemValue: options.itemValue || 'value',
            itemLabel: options.itemLabel || 'label',
            itemTitle: options.itemTitle || null,
            itemSubtitle: options.itemSubtitle || null,
            loadOnOpen: options.loadOnOpen !== false,
            http: options.http || null, // { url: '...', method: 'GET', headers: {...} }
            addButton: options.addButton || null, // { text: 'Adicionar novo', class: 'btn-class' }
            modal: options.modal || null, // ID ou classe do modal existente (#id ou .class)
            modalHtml: options.modalHtml || null, // HTML do modal para criar dinamicamente
            modalId: options.modalId || null, // ID único para o modal criado dinamicamente
            onSelect: options.onSelect || null,
            onClear: options.onClear || null,
            onClickButton: options.onClickButton || null,
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

        // Configurar modal se fornecido
        let modalElement = null;
        if (config.modal) {
            // Buscar modal existente por ID ou classe
            modalElement = document.querySelector(config.modal);
            if (!modalElement) {
                console.warn('⚠️ Modal não encontrado:', config.modal);
            } else {
                // Adicionar listeners ao modal existente
                setupModalListeners(modalElement, log);
            }
        } else if (config.modalHtml) {
            // Criar modal dinamicamente
            const modalId = config.modalId || `cselect-modal-${input.id}-${Date.now()}`;
            modalElement = createModalFromHtml(config.modalHtml, modalId, log);
            if (modalElement) {
                document.body.appendChild(modalElement);
                // Adicionar listeners ao modal criado
                setupModalListeners(modalElement, log);
            }
        }

        // Guardar instância globalmente para acesso posterior
        if (!window.cSelectInstances) {
            window.cSelectInstances = {};
        }
        window.cSelectInstances[input.id] = {
            input: input,
            config: config,
            modal: modalElement,
            clear: () => clearSelectionByInput(input),
            getValue: () => getHiddenInput(input)?.value || '',
            setValue: (value, label) => selectItemProgrammatically(input, value, label),
            openModal: () => openCSelectModal(modalElement, log, input.id),
            closeModal: () => closeCSelectModal(modalElement, log)
        };

        return window.cSelectInstances[input.id];
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

        // Adicionar margin-bottom se houver botão "Adicionar Novo" para não esconder o último item
        if (config.addButton) {
            ul.style.marginBottom = '1px'; // Altura aproximada do botão + padding
        }

        config.items.forEach(item => {
            const li = document.createElement('li');
            li.className = 'cselect-item px-3 py-1.5 hover:bg-blue-50 cursor-pointer transition-colors text-sm';
            li.dataset.value = item[config.itemValue];

            // Se tiver title e subtitle, criar estrutura com duas linhas
            if (config.itemTitle && config.itemSubtitle) {
                const title = item[config.itemTitle] || '';
                const subtitle = item[config.itemSubtitle] || '';

                li.innerHTML = `
                    <div class="flex flex-col gap-0.5">
                        <span class="font-medium text-gray-900 text-sm leading-tight">${title}</span>
                        <span class="text-xs text-gray-500 leading-tight">${subtitle}</span>
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

        // Adicionar botão "Adicionar Novo" se configurado (fixo no final)
        if (config.addButton) {
            const buttonText = config.addButton.text || 'Adicionar novo';
            const buttonClass = config.addButton.class || '';

            // Container sticky para o botão (dentro do listContainer para funcionar com scroll)
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'sticky bottom-0 bg-white border-t border-gray-200';

            const addNewBtn = document.createElement('button');
            addNewBtn.type = 'button';
            addNewBtn.className = `cselect-add-new w-full flex items-center justify-center px-3 py-1.5 bg-green-600 text-white hover:bg-green-700 transition-colors text-sm font-medium ${buttonClass}`;
            addNewBtn.innerHTML = `
                <i class="fas fa-plus mr-1.5 text-xs"></i>
                <span>${buttonText}</span>
            `;

            buttonContainer.appendChild(addNewBtn);
            listContainer.appendChild(buttonContainer);
        }

        dropdown.appendChild(listContainer);

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
            // Se não for AJAX e não houver texto no input, mostrar todos os items
            if (!config.http && !input.value.trim()) {
                filterLocalItems(dropdown, '', config, log);
            }
        });

        // Abrir dropdown ao clicar no input
        input.addEventListener('click', (e) => {
            e.stopPropagation();
            log('🖱️ Click no input...');
            openDropdown(dropdown);
            // Se não for AJAX e não houver texto no input, mostrar todos os items
            if (!config.http && !input.value.trim()) {
                filterLocalItems(dropdown, '', config, log);
            }
        });

        // Busca AJAX quando configurado
        if (config.http && config.http.url) {
            let searchTimeout = null;

            input.addEventListener('input', (e) => {
                const searchTerm = e.target.value.trim();
                const minLength = config.minSearchLength || 0;
                const loadOnOpen = config.loadOnOpen !== false;

                // Resetar highlight quando digitar
                if (input._cselectResetHighlight) {
                    input._cselectResetHighlight();
                }

                // Limpar timeout anterior
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Verificar se deve fazer a busca
                const shouldSearch = searchTerm.length >= minLength || (searchTerm.length === 0 && loadOnOpen);

                if (shouldSearch) {
                    // Debounce de 300ms
                    searchTimeout = setTimeout(() => {
                        performAjaxSearch(input, dropdown, searchTerm, config, log);
                    }, 300);
                } else {
                    // Se não atender aos critérios, limpar lista
                    clearDropdownItems(dropdown);
                }
            });

            // Carregar dados ao abrir se loadOnOpen for true
            if (config.loadOnOpen !== false) {
                input.addEventListener('focus', () => {
                    if (input.value.trim().length === 0) {
                        performAjaxSearch(input, dropdown, '', config, log);
                    }
                }, { once: false });
            }
        } else {
            // Filtro local quando não há AJAX
            input.addEventListener('input', (e) => {
                const searchTerm = e.target.value.trim().toLowerCase();
                const minLength = config.minSearchLength || 0;

                // Resetar highlight quando digitar
                if (input._cselectResetHighlight) {
                    input._cselectResetHighlight();
                }

                // Abrir dropdown ao digitar
                openDropdown(dropdown);

                // Se o termo de busca for menor que o mínimo, mostrar todos os items
                if (searchTerm.length < minLength) {
                    filterLocalItems(dropdown, '', config, log);
                    return;
                }

                // Filtrar items localmente
                filterLocalItems(dropdown, searchTerm, config, log);
            });
        }

        // Navegação por teclado (setas e Enter)
        let currentHighlightIndex = -1;

        input.addEventListener('keydown', (e) => {
            const items = dropdown.querySelectorAll('.cselect-item:not(.cselect-item-disabled)');

            if (items.length === 0) {
                return;
            }

            // Seta para baixo
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                e.stopPropagation();

                openDropdown(dropdown);

                // Remover highlight anterior
                if (currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                    items[currentHighlightIndex].classList.remove('bg-blue-100');
                }

                // Se estiver no início ou sem highlight, começar do primeiro item
                if (currentHighlightIndex < 0) {
                    currentHighlightIndex = 0;
                } else {
                    // Avançar para próximo item
                    currentHighlightIndex = (currentHighlightIndex + 1) % items.length;
                }

                // Adicionar highlight
                if (items[currentHighlightIndex]) {
                    items[currentHighlightIndex].classList.add('bg-blue-100');
                    // Scroll para o item se necessário
                    items[currentHighlightIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }

                log('⬇️ Navegando para item:', currentHighlightIndex);
            }

            // Seta para cima
            else if (e.key === 'ArrowUp') {
                e.preventDefault();
                e.stopPropagation();

                openDropdown(dropdown);

                // Remover highlight anterior
                if (currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                    items[currentHighlightIndex].classList.remove('bg-blue-100');
                }

                // Voltar para item anterior (ou último se estiver no primeiro)
                currentHighlightIndex = currentHighlightIndex <= 0 ? items.length - 1 : currentHighlightIndex - 1;

                // Adicionar highlight
                if (items[currentHighlightIndex]) {
                    items[currentHighlightIndex].classList.add('bg-blue-100');
                    // Scroll para o item se necessário
                    items[currentHighlightIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }

                log('⬆️ Navegando para item:', currentHighlightIndex);
            }

            // Enter - selecionar item destacado
            else if (e.key === 'Enter') {
                if (currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                    e.preventDefault();
                    e.stopPropagation();

                    const item = items[currentHighlightIndex];
                    const value = item.dataset.value;
                    let displayText = '';

                    if (item.dataset.title) {
                        displayText = item.dataset.title;
                    } else if (item.dataset.label) {
                        displayText = item.dataset.label;
                    } else {
                        displayText = item.textContent.trim();
                    }

                    const subtitle = item.dataset.subtitle || null;

                    log('✅ Item selecionado via Enter:', displayText, value, subtitle);
                    selectItem(input, dropdown, container, value, displayText, config, log, subtitle);

                    // Resetar índice
                    currentHighlightIndex = -1;
                }
            }

            // ESC - fechar dropdown
            else if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
                closeDropdown(dropdown);
                currentHighlightIndex = -1;
            }
        });

        // Guardar referência para resetar highlight quando items forem atualizados
        input._cselectHighlightIndex = () => currentHighlightIndex;
        input._cselectResetHighlight = () => {
            currentHighlightIndex = -1;
            const items = dropdown.querySelectorAll('.cselect-item');
            items.forEach(item => item.classList.remove('bg-blue-100'));
        };

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                closeDropdown(dropdown);
                currentHighlightIndex = -1;
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
                e.preventDefault();
                log('➕ Botão "Adicionar Novo" clicado');

                // Fechar dropdown
                closeDropdown(dropdown);

                // Se tiver modal configurado, abrir o modal
                const instance = window.cSelectInstances ? window.cSelectInstances[input.id] : null;
                if (instance && instance.modal) {
                    // Passar o ID do input como selectId para o modal
                    openCSelectModal(instance.modal, log, input.id);
                } else if (config.onClickButton && typeof config.onClickButton === 'function') {
                    // Se não tiver modal, usar callback customizado
                    // Passar o ID do input como parâmetro para o callback
                    config.onClickButton(input.id);
                }
            });
        }
    }

    /**
     * Limpar itens do dropdown
     */
    function clearDropdownItems(dropdown) {
        const ul = dropdown.querySelector('.cselect-list');
        if (ul) {
            ul.innerHTML = '';
        }
    }

    /**
     * Filtrar items localmente (quando não há AJAX)
     */
    function filterLocalItems(dropdown, searchTerm, config, log) {
        const listContainer = dropdown.querySelector('.cselect-list-container');
        if (!listContainer) {
            log('❌ Container de lista não encontrado');
            return;
        }

        const ul = listContainer.querySelector('.cselect-list');
        if (!ul) {
            log('❌ Lista não encontrada');
            return;
        }

        // Manter margin-bottom se houver botão "Adicionar Novo"
        const hasAddButton = listContainer.querySelector('.cselect-add-new') !== null;
        if (hasAddButton) {
            ul.style.marginBottom = '1px';
        }

        // Resetar highlight quando items forem atualizados
        const dropdownId = dropdown.id;
        const inputId = dropdownId.replace('_dropdown', '');
        const input = document.getElementById(inputId);
        if (input && input._cselectResetHighlight) {
            input._cselectResetHighlight();
        }

        // Limpar itens existentes
        const existingItems = ul.querySelectorAll('.cselect-item');
        existingItems.forEach(item => item.remove());

        // Filtrar items baseado no termo de busca
        let filteredItems = config.items || [];

        if (searchTerm) {
            filteredItems = config.items.filter(item => {
                // Buscar no label (modo simples)
                if (config.itemLabel && !config.itemTitle) {
                    const label = (item[config.itemLabel] || '').toLowerCase();
                    return label.includes(searchTerm);
                }

                // Buscar no title e subtitle (modo duas linhas)
                if (config.itemTitle) {
                    const title = (item[config.itemTitle] || '').toLowerCase();
                    const subtitle = config.itemSubtitle ? (item[config.itemSubtitle] || '').toLowerCase() : '';
                    return title.includes(searchTerm) || subtitle.includes(searchTerm);
                }

                // Fallback: buscar em qualquer propriedade string do item
                return Object.values(item).some(value => {
                    if (typeof value === 'string') {
                        return value.toLowerCase().includes(searchTerm);
                    }
                    return false;
                });
            });
        }

        // Adicionar items filtrados
        if (filteredItems.length > 0) {
            filteredItems.forEach(item => {
                const li = document.createElement('li');
                li.className = 'cselect-item px-3 py-1.5 hover:bg-blue-50 cursor-pointer transition-colors text-sm';
                li.dataset.value = item[config.itemValue];

                // Se tiver title e subtitle, criar estrutura com duas linhas
                if (config.itemTitle && config.itemSubtitle) {
                    const title = item[config.itemTitle] || '';
                    const subtitle = item[config.itemSubtitle] || '';

                    li.innerHTML = `
                        <div class="flex flex-col gap-0.5">
                            <span class="font-medium text-gray-900 text-sm leading-tight">${title}</span>
                            <span class="text-xs text-gray-500 leading-tight">${subtitle}</span>
                        </div>
                    `;
                    li.dataset.title = title;
                    li.dataset.subtitle = subtitle;
                } else {
                    // Modo simples (apenas label)
                    const label = item[config.itemLabel] || '';
                    li.textContent = label;
                    li.dataset.label = label;
                }

                ul.appendChild(li);
            });

            // Reanexar event listeners aos novos itens
            const newItems = ul.querySelectorAll('.cselect-item');
            newItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = item.dataset.value;
                    let displayText = '';
                    if (item.dataset.title) {
                        displayText = item.dataset.title;
                    } else if (item.dataset.label) {
                        displayText = item.dataset.label;
                    } else {
                        displayText = item.textContent.trim();
                    }
                    const subtitle = item.dataset.subtitle || null;

                    // Buscar container e input para passar para selectItem
                    const dropdownId = dropdown.id;
                    const inputId = dropdownId.replace('_dropdown', '');
                    const input = document.getElementById(inputId);
                    const container = input ? input.closest('.cselect-container') : null;

                    if (input && container) {
                        const instance = window.cSelectInstances ? window.cSelectInstances[input.id] : null;
                        const config = instance ? instance.config : null;
                        const log = instance ? instance.input._cselectLog : () => {};

                        log('✅ Item selecionado:', displayText, value, subtitle);
                        selectItem(input, dropdown, container, value, displayText, config, log, subtitle);
                    }
                });
            });
        } else {
            // Mostrar mensagem de "nenhum resultado"
            const li = document.createElement('li');
            li.className = 'cselect-item px-3 py-1.5 text-gray-500 text-sm text-center';
            li.textContent = 'Nenhum resultado encontrado';
            ul.appendChild(li);
        }
    }

    /**
     * Atualizar itens do dropdown com dados da API
     */
    function updateDropdownItems(dropdown, items, config, log) {
        const listContainer = dropdown.querySelector('.cselect-list-container');
        if (!listContainer) {
            log('❌ Container de lista não encontrado');
            return;
        }

        const ul = listContainer.querySelector('.cselect-list');
        if (!ul) {
            log('❌ Lista não encontrada');
            return;
        }

        // Manter margin-bottom se houver botão "Adicionar Novo"
        const hasAddButton = listContainer.querySelector('.cselect-add-new') !== null;
        if (hasAddButton) {
            ul.style.marginBottom = '1px';
        }

        // Resetar highlight quando items forem atualizados
        const dropdownId = dropdown.id;
        const inputId = dropdownId.replace('_dropdown', '');
        const input = document.getElementById(inputId);
        if (input && input._cselectResetHighlight) {
            input._cselectResetHighlight();
        }

        // Limpar itens existentes (exceto o botão "Adicionar Novo" se existir)
        const existingItems = ul.querySelectorAll('.cselect-item');
        existingItems.forEach(item => item.remove());

        // Adicionar novos itens
        if (items && items.length > 0) {
            items.forEach(item => {
                const li = document.createElement('li');
                li.className = 'cselect-item px-3 py-1.5 hover:bg-blue-50 cursor-pointer transition-colors text-sm';
                li.dataset.value = item[config.itemValue];

                // Se tiver title e subtitle, criar estrutura com duas linhas
                if (config.itemTitle && config.itemSubtitle) {
                    const title = item[config.itemTitle] || '';
                    const subtitle = item[config.itemSubtitle] || '';

                    li.innerHTML = `
                        <div class="flex flex-col gap-0.5">
                            <span class="font-medium text-gray-900 text-sm leading-tight">${title}</span>
                            <span class="text-xs text-gray-500 leading-tight">${subtitle}</span>
                        </div>
                    `;
                    li.dataset.title = title;
                    li.dataset.subtitle = subtitle;
                } else {
                    // Modo simples (apenas label)
                    const label = item[config.itemLabel] || '';
                    li.textContent = label;
                    li.dataset.label = label;
                }

                ul.appendChild(li);
            });

            // Reanexar event listeners aos novos itens
            const newItems = ul.querySelectorAll('.cselect-item');
            newItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = item.dataset.value;
                    let displayText = '';
                    if (item.dataset.title) {
                        displayText = item.dataset.title;
                    } else if (item.dataset.label) {
                        displayText = item.dataset.label;
                    } else {
                        displayText = item.textContent.trim();
                    }
                    const subtitle = item.dataset.subtitle || null;

                    // Buscar container e input para passar para selectItem
                    const dropdownId = dropdown.id;
                    const inputId = dropdownId.replace('_dropdown', '');
                    const input = document.getElementById(inputId);
                    const container = input ? input.closest('.cselect-container') : null;

                    if (input && container) {
                        const instance = window.cSelectInstances ? window.cSelectInstances[input.id] : null;
                        const config = instance ? instance.config : null;
                        const log = instance ? instance.input._cselectLog : () => {};

                        log('✅ Item selecionado:', displayText, value, subtitle);
                        selectItem(input, dropdown, container, value, displayText, config, log, subtitle);
                    }
                });
            });
        } else {
            // Mostrar mensagem de "nenhum resultado"
            const li = document.createElement('li');
            li.className = 'cselect-item px-3 py-1.5 text-gray-500 text-sm text-center';
            li.textContent = 'Nenhum resultado encontrado';
            ul.appendChild(li);
        }
    }

    /**
     * Realizar busca AJAX
     */
    function performAjaxSearch(input, dropdown, searchTerm, config, log) {
        if (!config.http || !config.http.url) {
            log('❌ Configuração HTTP não encontrada');
            return;
        }

        log('🔍 Buscando via AJAX:', searchTerm);

        // Abrir dropdown antes de fazer a requisição
        openDropdown(dropdown);

        // Mostrar loading
        const listContainer = dropdown.querySelector('.cselect-list-container');
        const ul = listContainer ? listContainer.querySelector('.cselect-list') : null;
        if (ul) {
            ul.innerHTML = '<li class="cselect-item px-3 py-1.5 text-gray-500 text-sm text-center">Carregando...</li>';
        }

        // Preparar nome do parâmetro de busca (search como padrão se não especificado)
        const searchParam = config.http.searchParam || 'search';

        // Preparar URL
        let url = config.http.url;
        if (searchTerm) {
            const separator = url.includes('?') ? '&' : '?';
            url += `${separator}${searchParam}=${encodeURIComponent(searchTerm)}`;
        }

        // Preparar headers
        let headers = {};

        // Se não tiver headers ou se tiver headers mas não tiver Content-Type, adicionar como default
        if (!config.http.headers || !config.http.headers['Content-Type']) {
            headers['Content-Type'] = 'application/json';
        }

        // Adicionar headers customizados se existirem
        if (config.http.headers) {
            headers = { ...headers, ...config.http.headers };
        }

        // Preparar método (GET como padrão se não especificado)
        const method = (config.http.method || 'GET').toUpperCase();

        // Preparar opções do fetch
        const fetchOptions = {
            method: method,
            headers: headers
        };

        // Adicionar body para métodos POST, PUT, PATCH
        if (['POST', 'PUT', 'PATCH'].includes(method)) {
            const bodyData = {};
            bodyData[searchParam] = searchTerm;
            fetchOptions.body = JSON.stringify(bodyData);
        }

        log('📡 Requisição AJAX:', { url, method, headers });

        // Fazer requisição
        fetch(url, fetchOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                log('✅ Dados recebidos:', data);

                // Esperar que a resposta seja um array ou tenha uma propriedade 'items' ou 'data'
                let items = [];
                if (Array.isArray(data)) {
                    items = data;
                } else if (data.items && Array.isArray(data.items)) {
                    items = data.items;
                } else if (data.data && Array.isArray(data.data)) {
                    items = data.data;
                } else {
                    log('⚠️ Formato de dados não reconhecido:', data);
                }

                // Atualizar dropdown com os itens
                updateDropdownItems(dropdown, items, config, log);

                // Abrir dropdown se não estiver aberto
                openDropdown(dropdown);
            })
            .catch(error => {
                log('❌ Erro na requisição AJAX:', error);

                // Mostrar mensagem de erro
                if (ul) {
                    ul.innerHTML = '<li class="cselect-item px-3 py-1.5 text-red-500 text-sm text-center">Erro ao carregar dados</li>';
                }
            });
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

        // Remover display selecionado existente se houver (evitar duplicação)
        const existingSelected = container.querySelector('.cselect-selected');
        if (existingSelected) {
            existingSelected.remove();
            log('🗑️ Removendo display selecionado existente');
        }

        // Esconder input original
        input.style.display = 'none';

        // Criar display do item selecionado
        const selectedDisplay = createSelectedDisplay(value, label, input.id, subtitle, input);

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
    function createSelectedDisplay(value, label, inputId, subtitle = null, originalInput = null) {
        const div = document.createElement('div');
        div.className = 'cselect-selected flex items-center justify-between w-full px-3 py-1.5 border border-gray-300 rounded-md bg-white text-sm';
        div.id = `${inputId}_selected`;

        // Copiar altura e padding do input original se disponível
        if (originalInput) {
            const inputStyle = window.getComputedStyle(originalInput);
            const inputHeight = inputStyle.height;
            const inputPaddingTop = inputStyle.paddingTop;
            const inputPaddingBottom = inputStyle.paddingBottom;
            const inputLineHeight = inputStyle.lineHeight;

            // Aplicar altura mínima e padding para manter o mesmo tamanho
            div.style.minHeight = inputHeight;
            div.style.height = inputHeight;
            if (inputPaddingTop) div.style.paddingTop = inputPaddingTop;
            if (inputPaddingBottom) div.style.paddingBottom = inputPaddingBottom;
            if (inputLineHeight && inputLineHeight !== 'normal') div.style.lineHeight = inputLineHeight;
        }

        // Sempre usar layout simples (uma linha) - subtitle não é exibido quando selecionado
        div.innerHTML = `
            <span class="cselect-selected-text text-sm">${label}</span>
            <button type="button" class="cselect-clear text-gray-400 hover:text-red-500 transition-colors text-xs p-1">
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
     * Criar modal a partir de HTML
     */
    function createModalFromHtml(html, modalId, log) {
        try {
            // Criar container temporário para parsear HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html.trim();

            // Buscar o elemento modal no HTML
            let modalElement = tempDiv.querySelector('.cselect-modal') || tempDiv.firstElementChild;

            if (!modalElement) {
                // Se não encontrar, criar estrutura básica completa
                modalElement = document.createElement('div');
                modalElement.className = 'cselect-modal cselect-modal-overlay cselect-modal-hidden';
                modalElement.innerHTML = `
                    <div class="cselect-modal-content">
                        <div class="cselect-modal-header">
                            <h3 class="cselect-modal-title">Modal</h3>
                            <button type="button" class="cselect-modal-close" data-dismiss="modal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="cselect-modal-body">
                            ${html}
                        </div>
                    </div>
                `;
            } else {
                // Se encontrou, garantir classes e estrutura
                if (!modalElement.classList.contains('cselect-modal-overlay')) {
                    modalElement.classList.add('cselect-modal-overlay');
                }
                if (!modalElement.classList.contains('cselect-modal-hidden')) {
                    modalElement.classList.add('cselect-modal-hidden');
                }
            }

            // Garantir que tenha ID
            if (!modalElement.id) {
                modalElement.id = modalId;
            }

            log('✅ Modal criado:', modalElement.id);
            return modalElement;
        } catch (error) {
            console.error('❌ Erro ao criar modal:', error);
            return null;
        }
    }

    /**
     * Abrir modal do CSelect
     */
    function openCSelectModal(modalElement, log, selectId) {
        if (!modalElement) {
            console.warn('⚠️ Modal não encontrado');
            return;
        }

        // Definir selectId no modal se fornecido
        if (selectId) {
            modalElement.dataset.selectId = selectId;
        }

        log('📂 Abrindo modal:', modalElement.id, selectId ? `(selectId: ${selectId})` : '');
        modalElement.classList.remove('cselect-modal-hidden');
        modalElement.classList.add('cselect-modal-visible');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Configurar listeners do modal
     */
    function setupModalListeners(modalElement, log) {
        if (!modalElement) return;

        // Fechar ao clicar no backdrop
        modalElement.addEventListener('click', (e) => {
            if (e.target === modalElement || e.target.classList.contains('cselect-modal-backdrop')) {
                closeCSelectModal(modalElement, log);
            }
        });

        // Fechar ao pressionar ESC
        const escHandler = (e) => {
            if (e.key === 'Escape' && modalElement.classList.contains('cselect-modal-visible')) {
                closeCSelectModal(modalElement, log);
            }
        };
        document.addEventListener('keydown', escHandler);

        // Guardar handler para possível remoção futura
        modalElement._escHandler = escHandler;

        // Fechar ao clicar em botões com data-dismiss="modal"
        const closeButtons = modalElement.querySelectorAll('[data-dismiss="modal"], .cselect-modal-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                closeCSelectModal(modalElement, log);
            });
        });
    }

    /**
     * Fechar modal do CSelect
     */
    function closeCSelectModal(modalElement, log) {
        if (!modalElement) {
            return;
        }

        log('📂 Fechando modal:', modalElement.id);
        modalElement.classList.remove('cselect-modal-visible');
        modalElement.classList.add('cselect-modal-hidden');
        document.body.style.overflow = '';

        // Limpar formulário se existir
        const form = modalElement.querySelector('form');
        if (form) {
            form.reset();
            // Limpar erros de validação
            const errors = form.querySelectorAll('.text-red-600, .error-message');
            errors.forEach(error => {
                error.classList.add('hidden');
                if (error.textContent) {
                    error.textContent = '';
                }
            });
        }
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

    /**
     * Função global para selecionar item programaticamente
     * Usada por modais e outras integrações
     */
    window.selectItemProgrammatically = function(selectId, itemData) {
        const input = document.getElementById(selectId);
        if (!input) {
            console.error('Input não encontrado:', selectId);
            return;
        }

        // Verificar se é um CSelect
        if (input._cselectConfig) {
            const config = input._cselectConfig;
            const value = itemData.id ? itemData.id.toString() : itemData.id;
            const label = itemData.nome || itemData.nome_completo || itemData.label || itemData.title || '';

            // Adicionar item à lista se não existir
            const itemValue = config.itemValue || 'value';
            const itemExists = config.items.some(item => {
                const itemVal = item[itemValue];
                return itemVal && itemVal.toString() === value.toString();
            });

            if (!itemExists) {
                // Criar novo item baseado na estrutura esperada
                const newItem = {};
                newItem[itemValue] = value;

                if (config.itemTitle && itemData.nome) {
                    newItem[config.itemTitle] = itemData.nome || itemData.nome_completo;
                } else if (config.itemLabel) {
                    newItem[config.itemLabel] = label;
                }

                if (config.itemSubtitle && itemData.email) {
                    newItem[config.itemSubtitle] = itemData.email;
                }

                // Adicionar outras propriedades do itemData
                Object.keys(itemData).forEach(key => {
                    if (!newItem[key]) {
                        newItem[key] = itemData[key];
                    }
                });

                config.items.push(newItem);
            }

            // Usar a função interna para selecionar
            selectItemProgrammatically(input, value, label);
        } else {
            console.warn('Input não é um CSelect:', selectId);
        }
    };
})();

