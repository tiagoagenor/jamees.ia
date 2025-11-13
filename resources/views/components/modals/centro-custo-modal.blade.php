@php
    $modalId = 'centro-custo-modal';
@endphp

<x-modals.base-modal :id="$modalId" title="Novo Centro de Custo" size="2xl">
    <form id="centro-custo-form" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="modal_nome" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Centro de Custo <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nome"
                       id="modal_nome"
                       required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o nome do centro de custo">
                <p id="modal_nome_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Status -->
            <div>
                <label for="modal_status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        id="modal_status"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione o status</option>
                    <option value="1" selected>Ativo</option>
                    <option value="0">Inativo</option>
                </select>
                <p id="modal_status_error" class="mt-1 text-sm text-red-600 hidden"></p>
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
                Salvar Centro de Custo
            </button>
        </div>
    </form>
</x-modals.base-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('centro-custo-form');
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

        fetch('{{ route("centro-custo.store") }}', {
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
            if (data.success || data.redirect) {
                // Fechar modal
                closeModal('{{ $modalId }}');
                
                // Mostrar mensagem de sucesso
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: data.message || 'Centro de custo criado com sucesso!',
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
                        // Buscar o centro de custo criado
                        const centroCusto = data.centro_custo;
                        if (centroCusto) {
                            window.selectItemProgrammatically(selectId, {
                                id: centroCusto.id,
                                nome: centroCusto.nome,
                                status: centroCusto.status
                            });
                        }
                    }, 100);
                }

                // Se houver callback, executar (para atualizar o select)
                if (typeof window.onCentroCustoCreated === 'function') {
                    const centroCusto = data.centro_custo;
                    if (centroCusto) {
                        window.onCentroCustoCreated(centroCusto);
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
                        text: error.message || 'Erro ao salvar centro de custo. Tente novamente.'
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

