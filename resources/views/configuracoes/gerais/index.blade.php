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
                        <button onclick="switchTab('dashboard')"
                                id="tab-dashboard"
                                class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 whitespace-nowrap">
                            Dashboard
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

                        <div class="space-y-6">
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
                                        <option value="{{ $i }}" {{ old('limite_registros', isset($configuracoes['geral']['limite_registros']) ? $configuracoes['geral']['limite_registros'] : 20) == $i ? 'selected' : '' }}>
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

                <!-- Tab Dashboard -->
                <div id="tab-content-dashboard" class="tab-content">
                    <!-- Mensagem de Sucesso -->
                    <div id="success-message-dashboard" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span id="success-text-dashboard"></span>
                        </div>
                    </div>

                    <!-- Mensagem de Erro -->
                    <div id="error-message-dashboard" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="error-text-dashboard"></span>
                        </div>
                    </div>

                    <form id="dashboard-form" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Logo da Empresa -->
                            <div>
                                <label for="logo_empresa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Logo da Empresa
                                </label>
                                @if(isset($configuracoes['dashboard']['logo_empresa']) && $configuracoes['dashboard']['logo_empresa'])
                                    <div class="mb-3 relative inline-block">
                                        <img src="{{ asset('storage/' . $configuracoes['dashboard']['logo_empresa']) }}" alt="Logo atual" class="max-w-xs h-20 object-contain border border-gray-300 rounded p-2" id="logo-preview">
                                        <button type="button"
                                                onclick="deletarLogo()"
                                                class="mt-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                            <i class="fas fa-trash mr-2"></i>
                                            Deletar Logo
                                        </button>
                                    </div>
                                @endif
                                <input type="file"
                                       id="logo_empresa"
                                       name="logo_empresa"
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">Faça upload da logo da empresa. Formatos aceitos: JPG, PNG, GIF, SVG (máx. 2MB)</p>
                            </div>

                            <!-- Texto de Boas-vindas -->
                            <div>
                                <label for="texto_boas_vindas_dashboard" class="block text-sm font-medium text-gray-700 mb-2">
                                    Texto de Boas-vindas
                                </label>
                                <textarea id="texto_boas_vindas_dashboard"
                                          name="texto_boas_vindas"
                                          rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                          placeholder="Ex: Tenha um excelente dia!">{{ old('texto_boas_vindas', $configuracoes['dashboard']['texto_boas_vindas'] ?? '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Este texto aparecerá abaixo de "Olá, empresa!" no dashboard</p>
                            </div>

                            <!-- Frase da Empresa -->
                            <div>
                                <label for="frase_empresa_dashboard" class="block text-sm font-medium text-gray-700 mb-2">
                                    Frase da Empresa
                                </label>
                                <textarea id="frase_empresa_dashboard"
                                          name="frase_empresa"
                                          rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                          placeholder="Ex: Transformando desafios em oportunidades">{{ old('frase_empresa', $configuracoes['dashboard']['frase_empresa'] ?? '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Esta frase aparecerá na seção "Mensagem da Empresa" no dashboard</p>
                            </div>

                            <!-- Vídeo Institucional -->
                            <div>
                                <div class="flex items-start gap-6">
                                    <div class="flex-1">
                                        <label for="titulo_video_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                            Título do Vídeo Institucional
                                        </label>
                                        <input type="text"
                                               id="titulo_video_institucional"
                                               name="titulo_video_institucional"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 mb-4"
                                               placeholder="Ex: Vídeo Institucional"
                                               value="{{ old('titulo_video_institucional', $configuracoes['dashboard']['titulo_video_institucional'] ?? 'Vídeo Institucional') }}">
                                        
                                        <label for="video_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                            URL do YouTube
                                        </label>
                                        <input type="text"
                                               id="video_institucional"
                                               name="video_institucional"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                               placeholder="Ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ ou dQw4w9WgXcQ"
                                               value="{{ old('video_institucional', $configuracoes['dashboard']['video_institucional'] ?? '') }}">
                                        <p class="mt-1 text-xs text-gray-500">Cole a URL completa do YouTube ou apenas o ID do vídeo. Este vídeo aparecerá no dashboard.</p>
                                    </div>
                                    
                                    <!-- Mostrar Vídeo Padrão -->
                                    <div class="relative flex-shrink-0">
                                        <label for="mostrar_video_default" class="block text-sm font-medium text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                Mostrar vídeo padrão
                                                <button data-tooltip-target="tooltip-video-default"
                                                        data-tooltip-placement="top"
                                                        type="button"
                                                        class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </span>
                                        </label>
                                        <!-- Tooltip -->
                                        <div id="tooltip-video-default"
                                             role="tooltip"
                                             class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Quando ativado, um vídeo padrão será exibido caso não haja vídeo institucional configurado.
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <select id="mostrar_video_default"
                                                name="mostrar_video_default"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                            <option value="1" {{ old('mostrar_video_default', $configuracoes['dashboard']['mostrar_video_default'] ?? '1') == '1' ? 'selected' : '' }}>Sim</option>
                                            <option value="0" {{ old('mostrar_video_default', $configuracoes['dashboard']['mostrar_video_default'] ?? '1') == '0' ? 'selected' : '' }}>Não</option>
                                        </select>
                                    </div>
                                </div>
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

        // Form submit via AJAX - Dados Gerais
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

        // Form submit via AJAX - Dashboard
        const dashboardForm = document.getElementById('dashboard-form');
        if (dashboardForm) {
            dashboardForm.addEventListener('submit', async function(e) {
                e.preventDefault(); // Prevenir submit padrão

                const formData = new FormData(dashboardForm);
                const submitButton = dashboardForm.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                // Desabilitar botão e mostrar loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

                // Esconder mensagens anteriores
                document.getElementById('success-message-dashboard').classList.add('hidden');
                document.getElementById('error-message-dashboard').classList.add('hidden');

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
                        document.getElementById('success-text-dashboard').textContent = data.message || 'Configurações salvas com sucesso!';
                        document.getElementById('success-message-dashboard').classList.remove('hidden');

                        // Recarregar página após 1 segundo para atualizar a imagem
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Erro
                        document.getElementById('error-text-dashboard').textContent = data.message || 'Erro ao salvar configurações. Tente novamente.';
                        document.getElementById('error-message-dashboard').classList.remove('hidden');
                    }
                } catch (error) {
                    // Erro de rede
                    document.getElementById('error-text-dashboard').textContent = 'Erro ao salvar configurações. Verifique sua conexão e tente novamente.';
                    document.getElementById('error-message-dashboard').classList.remove('hidden');
                } finally {
                    // Reabilitar botão
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });
        }

        // Função para deletar logo
        window.deletarLogo = async function() {
            if (!confirm('Tem certeza que deseja deletar a logo da empresa?')) {
                return;
            }

            // Esconder mensagens anteriores
            document.getElementById('success-message-dashboard').classList.add('hidden');
            document.getElementById('error-message-dashboard').classList.add('hidden');

            try {
                const response = await fetch('{{ route("configuracoes.gerais.deletar-logo") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Sucesso
                    document.getElementById('success-text-dashboard').textContent = data.message || 'Logo deletada com sucesso!';
                    document.getElementById('success-message-dashboard').classList.remove('hidden');

                    // Recarregar página após 1 segundo para atualizar a interface
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Erro
                    document.getElementById('error-text-dashboard').textContent = data.message || 'Erro ao deletar logo. Tente novamente.';
                    document.getElementById('error-message-dashboard').classList.remove('hidden');
                }
            } catch (error) {
                // Erro de rede
                document.getElementById('error-text-dashboard').textContent = 'Erro ao deletar logo. Verifique sua conexão e tente novamente.';
                document.getElementById('error-message-dashboard').classList.remove('hidden');
            }
        };
    });
</script>

<!-- Flowbite Tooltip Script -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
@endsection
