@php
    $modalId = 'cliente-modal';
@endphp

<x-modals.base-modal :id="$modalId" title="Novo Cliente" size="6xl">
    <form id="cliente-form" class="space-y-6">
        @csrf
        
        <!-- Informações Básicas -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                Informações Básicas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Pessoa -->
                <div>
                    <label for="modal_tipo_pessoa" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Pessoa <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_pessoa"
                            id="modal_tipo_pessoa"
                            required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione o tipo</option>
                        <option value="1">Pessoa Física</option>
                        <option value="2">Pessoa Jurídica</option>
                    </select>
                    <p id="modal_tipo_pessoa_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Documento -->
                <div>
                    <label for="modal_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="documento"
                           id="modal_documento"
                           required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="CPF ou CNPJ">
                    <p id="modal_documento_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Nome -->
                <div>
                    <label for="modal_nome" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nome"
                           id="modal_nome"
                           required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome completo ou razão social">
                    <p id="modal_nome_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Nome Fantasia (apenas PJ) -->
                <div id="modal_nome_fantasia_field" style="display: none;">
                    <label for="modal_nome_fantasia" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Fantasia
                    </label>
                    <input type="text"
                           name="nome_fantasia"
                           id="modal_nome_fantasia"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome fantasia">
                    <p id="modal_nome_fantasia_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Razão Social (apenas PJ) -->
                <div id="modal_razao_social_field" style="display: none;">
                    <label for="modal_razao_social" class="block text-sm font-medium text-gray-700 mb-2">
                        Razão Social
                    </label>
                    <input type="text"
                           name="razao_social"
                           id="modal_razao_social"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Razão social">
                    <p id="modal_razao_social_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Email -->
                <div>
                    <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           id="modal_email"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="email@exemplo.com">
                    <p id="modal_email_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Telefone Comercial -->
                <div>
                    <label for="modal_telefone_comercial" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefone Comercial
                    </label>
                    <input type="tel"
                           name="telefone_comercial"
                           id="modal_telefone_comercial"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="(11) 3333-4444">
                    <p id="modal_telefone_comercial_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Celular -->
                <div>
                    <label for="modal_celular" class="block text-sm font-medium text-gray-700 mb-2">
                        Celular
                    </label>
                    <input type="tel"
                           name="celular"
                           id="modal_celular"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="(11) 99999-8888">
                    <p id="modal_celular_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>

                <!-- Site -->
                <div>
                    <label for="modal_site" class="block text-sm font-medium text-gray-700 mb-2">
                        Site
                    </label>
                    <input type="url"
                           name="site"
                           id="modal_site"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://www.exemplo.com">
                    <p id="modal_site_error" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>
            </div>

            <!-- Observação -->
            <div class="mt-6">
                <label for="modal_observacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Observações
                </label>
                <textarea name="observacao"
                          id="modal_observacao"
                          rows="3"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Observações adicionais..."></textarea>
                <p id="modal_observacao_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
        </div>

        <!-- Seção de Contatos -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-address-book text-blue-600 mr-2"></i>
                Contatos
            </h3>

            <div id="modal_contatos-container">
                <!-- Container vazio - contatos serão adicionados dinamicamente -->
            </div>

            <button type="button" onclick="adicionarContatoModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-1"></i>
                Adicionar Contato
            </button>
        </div>

        <!-- Seção de Endereços -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                Endereços
            </h3>

            <div id="modal_enderecos-container">
                <!-- Container vazio - endereços serão adicionados dinamicamente -->
            </div>

            <button type="button" onclick="adicionarEnderecoModal()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-1"></i>
                Adicionar Endereço
            </button>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" 
                onclick="closeModal('{{ $modalId }}')" 
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Cancelar
        </button>
        <button type="button" 
                onclick="saveCliente()" 
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
            <i class="fas fa-save mr-2"></i>
            Salvar Cliente
        </button>
    </x-slot>
</x-modals.base-modal>

@push('scripts')
<script>
// Variáveis globais para contadores (específicas do modal de cliente)
let modalContatoIndex = 0;
let modalEnderecoIndex = 0;

// Função para abrir o modal de cliente
if (typeof window.openClienteModal === 'undefined') {
    window.openClienteModal = function() {
        // Limpar formulário
        const form = document.getElementById('cliente-form');
        if (form) form.reset();
        
        // Limpar erros
        document.querySelectorAll('[id^="modal_"][id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Esconder campos condicionais
        const nomeFantasiaField = document.getElementById('modal_nome_fantasia_field');
        const razaoSocialField = document.getElementById('modal_razao_social_field');
        if (nomeFantasiaField) nomeFantasiaField.style.display = 'none';
        if (razaoSocialField) razaoSocialField.style.display = 'none';
        
        // Limpar contatos e endereços
        const contatosContainer = document.getElementById('modal_contatos-container');
        const enderecosContainer = document.getElementById('modal_enderecos-container');
        if (contatosContainer) contatosContainer.innerHTML = '';
        if (enderecosContainer) enderecosContainer.innerHTML = '';
        
        modalContatoIndex = 0;
        modalEnderecoIndex = 0;
        
        // Abrir modal usando função do CSelect
        if (typeof window.openModal === 'function') {
            window.openModal('{{ $modalId }}');
        }
    };
}

// Inicializar máscaras e eventos quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const tipoPessoaSelect = document.getElementById('modal_tipo_pessoa');
    const nomeFantasiaField = document.getElementById('modal_nome_fantasia_field');
    const razaoSocialField = document.getElementById('modal_razao_social_field');
    const documentoInput = document.getElementById('modal_documento');

    // Mostrar/ocultar campos baseado no tipo de pessoa
    if (tipoPessoaSelect) {
        tipoPessoaSelect.addEventListener('change', function() {
            if (this.value === '2') { // Pessoa Jurídica
                if (nomeFantasiaField) nomeFantasiaField.style.display = 'block';
                if (razaoSocialField) razaoSocialField.style.display = 'block';
            } else {
                if (nomeFantasiaField) nomeFantasiaField.style.display = 'none';
                if (razaoSocialField) razaoSocialField.style.display = 'none';
            }
        });
    }

    // Aplicar máscara de CPF/CNPJ usando função do CSelect
    if (documentoInput && typeof window.applyCpfCnpjMask === 'function') {
        window.applyCpfCnpjMask(documentoInput);
    }

    // Aplicar máscara de telefone usando função do CSelect
    const telefoneInputs = ['modal_telefone_comercial', 'modal_celular'];
    telefoneInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input && typeof window.applyPhoneMask === 'function') {
            window.applyPhoneMask(input);
        }
    });
});

// Funções para gerenciar contatos (usando funções genéricas do CSelect)
function adicionarContatoModal() {
    if (typeof window.addDynamicItem !== 'function') {
        console.error('Função addDynamicItem não encontrada. Certifique-se de que cselect.js está carregado.');
        return;
    }

    window.addDynamicItem({
        containerId: 'modal_contatos-container',
        itemClass: 'contato-item',
        itemIndex: modalContatoIndex,
        template: (index) => `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-900">Contato ${index + 1}</h4>
                <button type="button" onclick="removerContatoModal(this)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" name="contatos[${index}][nome]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Nome do contato">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                    <input type="text" name="contatos[${index}][contato]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Email ou telefone">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                    <input type="text" name="contatos[${index}][cargo]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Cargo/função">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observação</label>
                    <input type="text" name="contatos[${index}][observacao]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Observações">
                </div>
            </div>
        `
    });

    modalContatoIndex++;
}

function removerContatoModal(button) {
    if (typeof window.removeDynamicItem === 'function') {
        window.removeDynamicItem(button, 'contato-item');
    } else {
        const contatoItem = button.closest('.contato-item');
        if (contatoItem) contatoItem.remove();
    }
}

// Funções para gerenciar endereços (usando funções genéricas do CSelect)
function adicionarEnderecoModal() {
    if (typeof window.addDynamicItem !== 'function') {
        console.error('Função addDynamicItem não encontrada. Certifique-se de que cselect.js está carregado.');
        return;
    }

    window.addDynamicItem({
        containerId: 'modal_enderecos-container',
        itemClass: 'endereco-item',
        itemIndex: modalEnderecoIndex,
        template: (index) => `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-900">Endereço ${index + 1}</h4>
                <button type="button" onclick="removerEnderecoModal(this)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                    <input type="text" name="enderecos[${index}][cep]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="00000-000">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                    <input type="text" name="enderecos[${index}][logradouro]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="Rua, Avenida, etc.">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                    <input type="text" name="enderecos[${index}][numero]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="123">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                    <input type="text" name="enderecos[${index}][complemento]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="Apto, Sala, etc.">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                    <input type="text" name="enderecos[${index}][bairro]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="Nome do bairro">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                    <input type="text" name="enderecos[${index}][cidade]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="Nome da cidade">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <input type="text" name="enderecos[${index}][estado]" maxlength="2"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                           placeholder="SP">
                </div>
            </div>
        `
    });

    modalEnderecoIndex++;
}

function removerEnderecoModal(button) {
    if (typeof window.removeDynamicItem === 'function') {
        window.removeDynamicItem(button, 'endereco-item');
    } else {
        const enderecoItem = button.closest('.endereco-item');
        if (enderecoItem) enderecoItem.remove();
    }
}

// Função para salvar cliente (usando função genérica do CSelect)
function saveCliente() {
    if (typeof window.submitFormAjax !== 'function') {
        console.error('Função submitFormAjax não encontrada. Certifique-se de que cselect.js está carregado.');
        return;
    }

    const modal = document.getElementById('{{ $modalId }}');
    const selectId = modal ? modal.dataset.selectId : null;

    window.submitFormAjax({
        formId: 'cliente-form',
        url: '{{ route("clientes.store") }}',
        method: 'POST',
        selectId: selectId,
        selectItemFunction: (selectId, data) => {
            if (selectId && typeof window.selectItemProgrammatically === 'function') {
                window.selectItemProgrammatically(selectId, {
                    id: data.cliente.id,
                    nome: data.cliente.nome || data.cliente.nome_completo,
                    nome_completo: data.cliente.nome_completo || data.cliente.nome,
                    documento: data.cliente.documento || '',
                    email: data.cliente.email || ''
                });
            }
        },
        onSuccess: (data) => {
            // Se houver callback, executar (para atualizar o select)
            if (typeof window.onClienteCreated === 'function') {
                window.onClienteCreated(data.cliente);
            }
        }
    });
}
</script>
@endpush
