@extends('layouts.app')

@section('title', 'Configurações Gerais')
@section('page-title', 'Configurações Gerais')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Configurações Gerais</h2>
                    <p class="text-gray-600 mt-1">Gerencie as configurações gerais do sistema</p>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button onclick="switchTab('dados-gerais')"
                                id="tab-dados-gerais"
                                class="tab-button active border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600 whitespace-nowrap">
                            Dados Gerais
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div id="tab-content-dados-gerais" class="tab-content">
                    <!-- Mensagem de Sucesso -->
                    <div id="success-message" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span id="success-text"></span>
                        </div>
                    </div>

                    <!-- Mensagem de Erro -->
                    <div id="error-message" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="error-text"></span>
                        </div>
                    </div>

                    <form id="configuracoes-form" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Limite de Registros por Página -->
                            <div class="relative">
                                <label for="limite_registros" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Limite de Registros por Página
                                        <button data-tooltip-target="tooltip-limite-registros"
                                                data-tooltip-placement="top"
                                                type="button"
                                                class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </span>
                                </label>
                                <!-- Tooltip -->
                                <div id="tooltip-limite-registros"
                                     role="tooltip"
                                     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Define quantos registros serão exibidos por página na paginação
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <select id="limite_registros"
                                        name="limite_registros"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    @for($i = 20; $i <= 100; $i += 10)
                                        <option value="{{ $i }}" {{ old('limite_registros', 20) == $i ? 'selected' : '' }}>
                                            {{ $i }} registros
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button"
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-button {
        transition: all 0.2s ease-in-out;
    }

    .tab-button.active {
        border-bottom-color: #3b82f6;
        color: #2563eb;
    }

    .tab-button:not(.active) {
        border-bottom-color: transparent;
        color: #6b7280;
    }

    .tab-button:not(.active):hover {
        color: #374151;
        border-bottom-color: #d1d5db;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>

<script>
    function switchTab(tabName) {
        // Esconder todas as tabs
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        // Remover active de todos os botões
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active');
            button.classList.remove('border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Mostrar tab selecionada
        const selectedContent = document.getElementById('tab-content-' + tabName);
        if (selectedContent) {
            selectedContent.classList.add('active');
        }

        // Ativar botão selecionado
        const selectedButton = document.getElementById('tab-' + tabName);
        if (selectedButton) {
            selectedButton.classList.add('active');
            selectedButton.classList.remove('border-transparent', 'text-gray-500');
            selectedButton.classList.add('border-blue-500', 'text-blue-600');
        }
    }

    // Inicializar primeira tab
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('dados-gerais');

        // Form submit via AJAX
        const form = document.getElementById('configuracoes-form');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // Prevenir submit padrão

                const formData = new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                // Desabilitar botão e mostrar loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

                // Esconder mensagens anteriores
                document.getElementById('success-message').classList.add('hidden');
                document.getElementById('error-message').classList.add('hidden');

                try {
                    const response = await fetch('{{ route("configuracoes.gerais.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Sucesso
                        document.getElementById('success-text').textContent = data.message || 'Configurações salvas com sucesso!';
                        document.getElementById('success-message').classList.remove('hidden');

                        // Scroll para o topo
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        // Erro
                        document.getElementById('error-text').textContent = data.message || 'Erro ao salvar configurações. Tente novamente.';
                        document.getElementById('error-message').classList.remove('hidden');
                    }
                } catch (error) {
                    // Erro de rede
                    document.getElementById('error-text').textContent = 'Erro ao salvar configurações. Verifique sua conexão e tente novamente.';
                    document.getElementById('error-message').classList.remove('hidden');
                } finally {
                    // Reabilitar botão
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });
        }
    });
</script>

<!-- Flowbite Tooltip Script -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
@endsection
