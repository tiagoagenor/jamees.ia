@php
    $modalId = 'forma-pagamento-modal';
@endphp

<x-modals.base-modal :id="$modalId" title="Nova Forma de Pagamento" size="6xl">
    <form id="forma-pagamento-form" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div class="md:col-span-2">
                <label for="modal_nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nome"
                       id="modal_nome"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Ex: Cartão de Crédito - 3x">
                <p id="modal_nome_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Modalidade -->
            <div>
                <label for="modal_modalidade" class="block text-sm font-medium text-gray-700 mb-2">
                    Modalidade <span class="text-red-500">*</span>
                </label>
                <select name="modalidade"
                        id="modal_modalidade"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione uma modalidade</option>
                    @foreach(\App\Enums\FormaPagamentoModalidadeEnum::cases() as $modalidade)
                        <option value="{{ $modalidade->value }}">
                            {{ $modalidade->getLabel() }}
                        </option>
                    @endforeach
                </select>
                <p id="modal_modalidade_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Conta Bancária -->
            <div>
                <label for="modal_conta_empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Conta Bancária
                </label>
                {{-- <select name="conta_empresa_id"
                        id="modal_conta_empresa_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione uma conta (opcional)</option>
                    <!-- Opções serão carregadas via AJAX -->
                </select> --}}
                <p id="modal_conta_empresa_id_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Número de Parcelas -->
            <div>
                <label for="modal_numero_parcelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Parcelas <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="numero_parcelas"
                       id="modal_numero_parcelas"
                       value="1"
                       min="1"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_numero_parcelas_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Intervalo entre Parcelas -->
            <div>
                <label for="modal_intercalo_parcelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Intervalo entre Parcelas (dias) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="intercalo_parcelas"
                       id="modal_intercalo_parcelas"
                       value="30"
                       min="1"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_intercalo_parcelas_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Primeira Parcela -->
            <div>
                <label for="modal_primeira_parcela" class="block text-sm font-medium text-gray-700 mb-2">
                    Primeira Parcela (dias) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="primeira_parcela"
                       id="modal_primeira_parcela"
                       value="0"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_primeira_parcela_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Taxa do Banco -->
            <div>
                <label for="modal_taxa_banco" class="block text-sm font-medium text-gray-700 mb-2">
                    Taxa do Banco (R$) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="taxa_banco"
                       id="modal_taxa_banco"
                       value="0.00"
                       step="0.01"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_taxa_banco_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Taxa da Operadora -->
            <div>
                <label for="modal_taxa_operadora" class="block text-sm font-medium text-gray-700 mb-2">
                    Taxa da Operadora (R$) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="taxa_operadora"
                       id="modal_taxa_operadora"
                       value="0.00"
                       step="0.01"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_taxa_operadora_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Juros/Multa -->
            <div>
                <label for="modal_juros_multa" class="block text-sm font-medium text-gray-700 mb-2">
                    Juros/Multa (R$) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="juros_multa"
                       id="modal_juros_multa"
                       value="0.00"
                       step="0.01"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_juros_multa_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Juros/Mora -->
            <div>
                <label for="modal_juros_mora" class="block text-sm font-medium text-gray-700 mb-2">
                    Juros/Mora (R$) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="juros_mora"
                       id="modal_juros_mora"
                       value="0.00"
                       step="0.01"
                       min="0"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="modal_juros_mora_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>
        </div>

        <!-- Opções -->
        <div class="mt-6 border-t border-gray-200 pt-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Opções</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox"
                           name="disponivel"
                           id="modal_disponivel"
                           value="1"
                           checked
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="modal_disponivel" class="ml-2 block text-sm text-gray-900">
                        Disponível para uso
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="confirmacao_automatica"
                           id="modal_confirmacao_automatica"
                           value="1"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="modal_confirmacao_automatica" class="ml-2 block text-sm text-gray-900">
                        Confirmação automática
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="gerar_boleto"
                           id="modal_gerar_boleto"
                           value="1"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="modal_gerar_boleto" class="ml-2 block text-sm text-gray-900">
                        Gerar boleto automaticamente
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           name="permite_deletar"
                           id="modal_permite_deletar"
                           value="1"
                           checked
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="modal_permite_deletar" class="ml-2 block text-sm text-gray-900">
                        Permitir exclusão
                    </label>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <button type="button"
                    onclick="closeModal('{{ $modalId }}')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Cancelar
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-save mr-2"></i>
                Salvar Forma de Pagamento
            </button>
        </div>
    </form>
</x-modals.base-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carregar contas bancárias (opcional - pode ser implementado depois)
    // Por enquanto, deixar vazio

    const form = document.getElementById('forma-pagamento-form');
    if (!form) return;

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Limpar erros anteriores
        form.querySelectorAll('[id^="modal_"][id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        const formData = new FormData(form);

        // Mostrar loading
        const saveButton = form.querySelector('button[type="submit"]');
        const originalText = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

        // Função para fazer a requisição com JWT
        const makeRequest = async () => {
            // Obter token JWT
            let jwtToken = null;
            if (typeof window.getJwtToken === 'function') {
                jwtToken = await window.getJwtToken();
            }

            const headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            };

            // Adicionar JWT token se disponível
            if (jwtToken) {
                headers['Authorization'] = `Bearer ${jwtToken}`;
            }

            return fetch('{{ route("forma-pagamento.store") }}', {
                method: 'POST',
                body: formData,
                headers: headers
            });
        };

        makeRequest()
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
                        text: data.message || 'Forma de pagamento criada com sucesso!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                // Selecionar automaticamente no select que abriu o modal
                const modal = document.getElementById('{{ $modalId }}');
                const selectId = modal ? modal.dataset.selectId : null;

                if (selectId) {
                    // Tentar usar CSelect primeiro
                    if (window.cSelectInstances && window.cSelectInstances[selectId]) {
                        const cSelectInstance = window.cSelectInstances[selectId];
                        const formaPagamento = data.forma_pagamento;
                        if (formaPagamento) {
                            const nome = formaPagamento.nome || '';
                            cSelectInstance.setValue(formaPagamento.id, nome);
                        }
                    }
                    // Fallback para custom-select antigo
                    else if (typeof window.selectItemProgrammatically === 'function') {
                        setTimeout(() => {
                            const formaPagamento = data.forma_pagamento;
                            if (formaPagamento) {
                                window.selectItemProgrammatically(selectId, {
                                    id: formaPagamento.id,
                                    nome: formaPagamento.nome,
                                    modalidade: formaPagamento.modalidade
                                });
                            }
                        }, 100);
                    }
                }

                // Se houver callback, executar (para atualizar o select)
                if (typeof window.onFormaPagamentoCreated === 'function') {
                    const formaPagamento = data.forma_pagamento;
                    if (formaPagamento) {
                        window.onFormaPagamentoCreated(formaPagamento);
                    }
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
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: error.message || 'Erro ao salvar forma de pagamento. Tente novamente.'
                    });
                }
            }
        })
        .finally(() => {
            // Restaurar botão
            saveButton.disabled = false;
            saveButton.innerHTML = originalText;
        });
    });
});
</script>
@endpush

