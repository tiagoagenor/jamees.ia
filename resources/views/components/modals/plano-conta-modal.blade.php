@php
    $modalId = 'plano-conta-modal';
@endphp

<x-modals.base-modal :id="$modalId" title="Nova Conta" size="2xl">
    <form id="plano-conta-form" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="modal_nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome da Conta <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nome"
                       id="modal_nome"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o nome da conta">
                <p id="modal_nome_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Movimentação -->
            <div>
                <label for="modal_movimentacao" class="block text-sm font-medium text-gray-700 mb-2">
                    Movimentação <span class="text-red-500">*</span>
                </label>
                <select name="movimentacao"
                        id="modal_movimentacao"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione a movimentação</option>
                    <option value="1">Débito</option>
                    <option value="2">Crédito</option>
                </select>
                <p id="modal_movimentacao_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Ordem Pai -->
            <div>
                <label for="modal_ordem_pai" class="block text-sm font-medium text-gray-700 mb-2">
                    Ordem Pai <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="ordem_pai"
                       id="modal_ordem_pai"
                       required
                       min="1"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ex: 1, 2, 3...">
                <p id="modal_ordem_pai_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Ordem Filho -->
            <div>
                <label for="modal_ordem_filho" class="block text-sm font-medium text-gray-700 mb-2">
                    Ordem Filho
                </label>
                <input type="number"
                       name="ordem_filho"
                       id="modal_ordem_filho"
                       min="1"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ex: 1, 2, 3... (opcional)">
                <p class="mt-1 text-xs text-gray-500">Deixe vazio para criar uma conta pai</p>
                <p id="modal_ordem_filho_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Conta Pai -->
            <div>
                <label for="modal_plano_conta_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Conta Pai
                </label>
                <select name="plano_conta_id"
                        id="modal_plano_conta_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione uma conta pai (opcional)</option>
                    <!-- Opções serão carregadas via AJAX -->
                </select>
                <p id="modal_plano_conta_id_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- DRE -->
            <div>
                <label for="modal_dre_id" class="block text-sm font-medium text-gray-700 mb-2">
                    DRE
                </label>
                <select name="dre_id"
                        id="modal_dre_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione um item DRE (opcional)</option>
                    <!-- Opções serão carregadas via AJAX -->
                </select>
                <p id="modal_dre_id_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" 
                onclick="closeModal('{{ $modalId }}')" 
                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Cancelar
        </button>
        <button type="button" 
                onclick="savePlanoConta()" 
                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
            <i class="fas fa-save mr-2"></i>
            Salvar Conta
        </button>
    </x-slot>
</x-modals.base-modal>

@push('scripts')
<script>
// Carregar dados ao abrir o modal
function loadPlanoContaModalData() {
    // Carregar DREs
    fetch('{{ route("api.plano-conta.dres") }}')
        .then(response => response.json())
        .then(data => {
            const dreSelect = document.getElementById('modal_dre_id');
            if (dreSelect && data.dres) {
                dreSelect.innerHTML = '<option value="">Selecione um item DRE (opcional)</option>';
                data.dres.forEach(dre => {
                    const option = document.createElement('option');
                    option.value = dre.id;
                    option.textContent = dre.nome;
                    dreSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar DREs:', error);
        });

    // Carregar Planos de Conta Pai
    fetch('{{ route("api.plano-conta.parents") }}')
        .then(response => response.json())
        .then(data => {
            const planoContaSelect = document.getElementById('modal_plano_conta_id');
            if (planoContaSelect && data.plano_contas) {
                planoContaSelect.innerHTML = '<option value="">Selecione uma conta pai (opcional)</option>';
                data.plano_contas.forEach(conta => {
                    const option = document.createElement('option');
                    option.value = conta.id;
                    option.textContent = conta.codigo + ' - ' + conta.nome;
                    planoContaSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar planos de conta pai:', error);
        });
}

// Função para abrir o modal de plano de conta
if (typeof window.openPlanoContaModal === 'undefined') {
    window.openPlanoContaModal = function() {
        // Limpar formulário
        document.getElementById('plano-conta-form').reset();
        // Limpar erros
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        // Carregar dados
        loadPlanoContaModalData();
        // Abrir modal
        openModal('{{ $modalId }}');
    };
}

// Função para salvar plano de conta
function savePlanoConta() {
    const form = document.getElementById('plano-conta-form');
    const formData = new FormData(form);
    
    // Limpar erros anteriores
    document.querySelectorAll('[id$="_error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });

    // Validar se conta pai foi selecionada, ordem_filho deve ser obrigatória
    const planoContaId = formData.get('plano_conta_id');
    const ordemFilho = formData.get('ordem_filho');
    if (planoContaId && !ordemFilho) {
        const errorEl = document.getElementById('modal_ordem_filho_error');
        errorEl.textContent = 'Ordem filho é obrigatória quando uma conta pai é selecionada.';
        errorEl.classList.remove('hidden');
        return;
    }

    // Mostrar loading
    const saveButton = event.target;
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

    fetch('{{ route("plano-conta.store") }}', {
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
                    text: data.message || 'Plano de conta criado com sucesso!',
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
                    // Selecionar o plano de conta criado no select
                    window.selectItemProgrammatically(selectId, {
                        id: data.plano_conta.id,
                        nome: data.plano_conta.nome,
                        codigo: data.plano_conta.codigo,
                        categoria: data.plano_conta.categoria || ''
                    });
                }, 100);
            }

            // Se houver callback, executar (para atualizar o select)
            if (typeof window.onPlanoContaCreated === 'function') {
                window.onPlanoContaCreated(data.plano_conta);
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
                    text: error.message || 'Erro ao salvar plano de conta. Tente novamente.'
                });
            }
        }
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });
}

// JavaScript para melhorar a experiência do usuário
document.addEventListener('DOMContentLoaded', function() {
    const ordemFilhoInput = document.getElementById('modal_ordem_filho');
    const contaPaiSelect = document.getElementById('modal_plano_conta_id');

    if (ordemFilhoInput && contaPaiSelect) {
        contaPaiSelect.addEventListener('change', function() {
            if (this.value) {
                ordemFilhoInput.placeholder = 'Ex: 1, 2, 3... (obrigatório para subconta)';
                ordemFilhoInput.required = true;
            } else {
                ordemFilhoInput.placeholder = 'Ex: 1, 2, 3... (opcional)';
                ordemFilhoInput.required = false;
            }
        });
    }
});
</script>
@endpush

