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
// Variáveis globais para contadores
let modalContatoIndex = 0;
let modalEnderecoIndex = 0;

// Função para abrir o modal de cliente
if (typeof window.openClienteModal === 'undefined') {
    window.openClienteModal = function() {
        // Limpar formulário
        document.getElementById('cliente-form').reset();
        // Limpar erros
        document.querySelectorAll('[id^="modal_"][id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        // Esconder campos condicionais
        document.getElementById('modal_nome_fantasia_field').style.display = 'none';
        document.getElementById('modal_razao_social_field').style.display = 'none';
        // Limpar contatos e endereços
        document.getElementById('modal_contatos-container').innerHTML = '';
        document.getElementById('modal_enderecos-container').innerHTML = '';
        modalContatoIndex = 0;
        modalEnderecoIndex = 0;
        // Abrir modal
        openModal('{{ $modalId }}');
    };
}

// Mostrar/ocultar campos baseado no tipo de pessoa
document.addEventListener('DOMContentLoaded', function() {
    const tipoPessoaSelect = document.getElementById('modal_tipo_pessoa');
    const nomeFantasiaField = document.getElementById('modal_nome_fantasia_field');
    const razaoSocialField = document.getElementById('modal_razao_social_field');
    const documentoInput = document.getElementById('modal_documento');

    if (tipoPessoaSelect) {
        tipoPessoaSelect.addEventListener('change', function() {
            if (this.value === '2') { // Pessoa Jurídica
                nomeFantasiaField.style.display = 'block';
                razaoSocialField.style.display = 'block';
            } else {
                nomeFantasiaField.style.display = 'none';
                razaoSocialField.style.display = 'none';
            }
        });
    }

    // Máscara para documento
    if (documentoInput) {
        documentoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            const tipoPessoa = document.getElementById('modal_tipo_pessoa').value;

            if (tipoPessoa == '2') { // PJ - CNPJ
                if (value.length > 14) value = value.substring(0, 14);
                if (value.length >= 2) {
                    e.target.value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                }
            } else { // PF - CPF
                if (value.length > 11) value = value.substring(0, 11);
                if (value.length >= 3) {
                    e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                }
            }
        });
    }

    // Máscara para telefones
    const telefoneInputs = ['modal_telefone_comercial', 'modal_celular'];
    telefoneInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
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
                }
            });
        }
    });
});

// Funções para gerenciar contatos
function adicionarContatoModal() {
    const container = document.getElementById('modal_contatos-container');
    const contatoItem = document.createElement('div');
    contatoItem.className = 'contato-item border rounded-lg p-4 mb-4 bg-gray-50';
    contatoItem.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">Contato ${modalContatoIndex + 1}</h4>
            <button type="button" onclick="removerContatoModal(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text" name="contatos[${modalContatoIndex}][nome]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Nome do contato">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                <input type="text" name="contatos[${modalContatoIndex}][contato]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Email ou telefone">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                <input type="text" name="contatos[${modalContatoIndex}][cargo]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Cargo/função">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observação</label>
                <input type="text" name="contatos[${modalContatoIndex}][observacao]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                       placeholder="Observações">
            </div>
        </div>
    `;
    container.appendChild(contatoItem);
    modalContatoIndex++;
}

function removerContatoModal(button) {
    const contatoItem = button.closest('.contato-item');
    contatoItem.remove();
}

// Funções para gerenciar endereços
function adicionarEnderecoModal() {
    const container = document.getElementById('modal_enderecos-container');
    const enderecoItem = document.createElement('div');
    enderecoItem.className = 'endereco-item border rounded-lg p-4 mb-4 bg-gray-50';
    enderecoItem.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">Endereço ${modalEnderecoIndex + 1}</h4>
            <button type="button" onclick="removerEnderecoModal(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][cep]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="00000-000">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][logradouro]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="Rua, Avenida, etc.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][numero]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="123">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][complemento]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="Apto, Sala, etc.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][bairro]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="Nome do bairro">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][cidade]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="Nome da cidade">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <input type="text" name="enderecos[${modalEnderecoIndex}][estado]" maxlength="2"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"
                       placeholder="SP">
            </div>
        </div>
    `;
    container.appendChild(enderecoItem);
    
    // Adicionar máscara de CEP
    const cepInput = enderecoItem.querySelector('input[name*="[cep]"]');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });
    }
    
    modalEnderecoIndex++;
}

function removerEnderecoModal(button) {
    const enderecoItem = button.closest('.endereco-item');
    enderecoItem.remove();
}

// Função para salvar cliente
function saveCliente() {
    const form = document.getElementById('cliente-form');
    const formData = new FormData(form);
    
    // Limpar erros anteriores
    document.querySelectorAll('[id^="modal_"][id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });

    // Mostrar loading
    const saveButton = event.target;
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

    fetch('{{ route("clientes.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            // Fechar modal
            closeModal('{{ $modalId }}');
            
            // Mostrar mensagem de sucesso
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message || 'Cliente criado com sucesso!',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Selecionar automaticamente no custom-select que abriu o modal
            const modal = document.getElementById('{{ $modalId }}');
            const selectId = modal ? modal.dataset.selectId : null;
            
            if (selectId && typeof window.selectItemProgrammatically === 'function') {
                // Pequeno delay para garantir que o DOM esteja atualizado
                setTimeout(() => {
                    // Selecionar o cliente criado no select
                    window.selectItemProgrammatically(selectId, {
                        id: data.cliente.id,
                        nome: data.cliente.nome || data.cliente.nome_completo,
                        nome_completo: data.cliente.nome_completo || data.cliente.nome,
                        documento: data.cliente.documento || '',
                        email: data.cliente.email || ''
                    });
                }, 100);
            }

            // Se houver callback, executar (para atualizar o select)
            if (typeof window.onClienteCreated === 'function') {
                window.onClienteCreated(data.cliente);
            }

            // Não recarregar a página, apenas fechar o modal
            // A seleção já foi feita acima
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
                        const errorEl = document.getElementById('modal_' + field + '_error');
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
                    text: error.message || 'Erro ao salvar cliente. Tente novamente.'
                });
            }
        }
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });
}
</script>
@endpush
