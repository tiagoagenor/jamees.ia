@php
    $modalId = 'conta-empresa-modal';
@endphp

<x-modals.base-modal :id="$modalId" title="Nova Conta Bancária" size="6xl">
    <form id="conta-empresa-form" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Banco -->
            <div>
                <label for="modal_banco_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Banco <span class="text-red-500">*</span>
                </label>
                <select name="banco_id"
                        id="modal_banco_id"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione o banco</option>
                    <!-- Opções serão carregadas via AJAX -->
                </select>
                <p id="modal_banco_id_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Tipo -->
            <div>
                <label for="modal_tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo <span class="text-red-500">*</span>
                </label>
                <select name="tipo"
                        id="modal_tipo"
                        required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecione o tipo</option>
                    @foreach(\App\Enums\ContaTipoEnum::cases() as $tipo)
                        <option value="{{ $tipo->value }}">
                            {{ $tipo->getLabel() }}
                        </option>
                    @endforeach
                </select>
                <p id="modal_tipo_error" class="mt-1 text-sm text-red-600 hidden"></p>
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
                       placeholder="Ex: Conta Corrente Principal">
                <p id="modal_nome_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Saldo Inicial -->
            <div>
                <label for="modal_saldo_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                    Saldo Inicial
                </label>
                <input type="number"
                       name="saldo_inicial"
                       id="modal_saldo_inicial"
                       value="0"
                       step="0.01"
                       min="0"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="0,00">
                <p id="modal_saldo_inicial_error" class="mt-1 text-sm text-red-600 hidden"></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio"
                               name="status"
                               value="1"
                               checked
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Ativa</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio"
                               name="status"
                               value="0"
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Inativa</span>
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
                Salvar Conta Bancária
            </button>
        </div>
    </form>
</x-modals.base-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carregar bancos
    fetch('{{ route("api.bancos.ativos") }}')
        .then(response => response.json())
        .then(data => {
            console.log('📊 Resposta da API de bancos:', data);
            console.log('🏦 Total de bancos recebidos:', data.total || data.bancos.length);
            console.log('🏦 Lista completa de bancos:', data.bancos);

            const bancoSelect = document.getElementById('modal_banco_id');
            if (bancoSelect && data.bancos) {
                bancoSelect.innerHTML = '<option value="">Selecione o banco</option>';
                data.bancos.forEach((banco, index) => {
                    const option = document.createElement('option');
                    option.value = banco.id;
                    option.textContent = banco.nome_normalizado + ' (' + banco.numero_banco + ')';
                    bancoSelect.appendChild(option);
                    console.log(`  ${index + 1}. ${banco.nome_normalizado} (${banco.numero_banco})`);
                });
                console.log('✅ Total de opções adicionadas ao select:', bancoSelect.options.length - 1);
            }
        })
        .catch(error => {
            console.error('❌ Erro ao carregar bancos:', error);
        });

    const form = document.getElementById('conta-empresa-form');
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

        fetch('{{ route("conta-empresa.store") }}', {
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
                        text: data.message || 'Conta bancária criada com sucesso!',
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
                        // Buscar a conta bancária criada
                        const contaEmpresa = data.conta_empresa;
                        if (contaEmpresa) {
                            window.selectItemProgrammatically(selectId, {
                                id: contaEmpresa.id,
                                nome: contaEmpresa.nome,
                                banco: contaEmpresa.banco,
                                tipo: contaEmpresa.tipo
                            });
                        }
                    }, 100);
                }

                // Se houver callback, executar (para atualizar o select)
                if (typeof window.onContaEmpresaCreated === 'function') {
                    const contaEmpresa = data.conta_empresa;
                    if (contaEmpresa) {
                        window.onContaEmpresaCreated(contaEmpresa);
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
                        text: error.message || 'Erro ao salvar conta bancária. Tente novamente.'
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

