@extends('layouts.app')

@section('title', 'Importar Lotes via CSV')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-upload text-blue-600 mr-3"></i>
                    Importar Lotes via CSV
                </h1>
                <p class="mt-2 text-gray-600">Importe múltiplos lotes de uma vez usando arquivo CSV</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('lotes.download-exemplo-csv', $empreendimento->id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Baixar Exemplo CSV
                </a>
                <a href="{{ route('lotes.index', $empreendimento->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar para Lotes
                </a>
            </div>
        </div>
    </div>

    <!-- Card de Importação -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-csv text-blue-600 mr-2"></i>
            Selecionar Arquivo CSV
        </h2>

        <form id="import-form" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Arquivo CSV
                </label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Selecione um arquivo CSV com os lotes a serem importados. Tamanho máximo: 2MB.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('lotes.index', $empreendimento->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="button" onclick="validarArquivo()"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Validar Arquivo
                </button>
            </div>
        </form>
    </div>

    <!-- Resultados da Validação -->
    <div id="validation-results" class="hidden">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-clipboard-check text-indigo-600 mr-2"></i>
                Resultado da Validação
            </h2>

            <!-- Mensagens de Sucesso/Erro -->
            <div id="validation-messages" class="mb-4"></div>

            <!-- Quadras a serem criadas -->
            <div id="quadras-info" class="hidden mb-4"></div>

            <!-- Estatísticas -->
            <div id="validation-stats" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6"></div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('validas')" id="tab-validas" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        Válidas (<span id="count-validas">0</span>)
                    </button>
                    <button onclick="showTab('erros')" id="tab-erros" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Com Erros (<span id="count-erros">0</span>)
                    </button>
                </nav>
            </div>

            <!-- Conteúdo: Válidas -->
            <div id="content-validas" class="tab-content">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Linha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Nome do Lote
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Quadra
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Área (m²)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Valor por m²
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">
                                    Valor Total
                                </th>
                            </tr>
                        </thead>
                        <tbody id="validas-tbody" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Conteúdo: Erros -->
            <div id="content-erros" class="tab-content hidden">
                <div class="space-y-4">
                    <div id="erros-list">
                        <!-- Erros serão inseridos aqui via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Botão para Importar (apenas se não houver erros) -->
            <div id="import-button-container" class="hidden mt-6 flex justify-end">
                <button type="button" onclick="confirmarImportacao()"
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-upload mr-2"></i>
                    Confirmar e Importar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let validationData = null;

// Validar arquivo
function validarArquivo() {
    const fileInput = document.getElementById('csv_file');
    const file = fileInput.files[0];

    if (!file) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Por favor, selecione um arquivo CSV.'
        });
        return;
    }

    if (!file.name.endsWith('.csv')) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Por favor, selecione um arquivo CSV válido.'
        });
        return;
    }

    const formData = new FormData();
    formData.append('csv_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('validar_only', 'true');

    Swal.fire({
        title: 'Validando arquivo...',
        text: 'Aguarde enquanto validamos o arquivo CSV.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route('lotes.import-csv', $empreendimento->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        validationData = data;
        exibirResultadosValidacao(data);
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Ocorreu um erro ao processar o arquivo. Tente novamente.'
        });
        console.error('Error:', error);
    });
}

// Exibir resultados da validação
function exibirResultadosValidacao(data) {
    const resultsDiv = document.getElementById('validation-results');
    resultsDiv.classList.remove('hidden');

    // Mensagens
    const messagesDiv = document.getElementById('validation-messages');
    messagesDiv.innerHTML = '';

    if (data.erros && data.erros.length > 0) {
        messagesDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-600 mr-2"></i>
                    <p class="text-red-800 font-medium">O arquivo possui erros e não pode ser importado.</p>
                </div>
            </div>
        `;
    } else {
        messagesDiv.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <p class="text-green-800 font-medium">Arquivo validado com sucesso! Todas as linhas estão corretas.</p>
                </div>
            </div>
        `;
    }

    // Informações sobre quadras a serem criadas
    const quadrasInfoDiv = document.getElementById('quadras-info');
    if (data.quadras_a_criar && data.quadras_a_criar.length > 0) {
        quadrasInfoDiv.classList.remove('hidden');
        quadrasInfoDiv.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-2 mt-1"></i>
                    <div>
                        <p class="text-blue-800 font-medium mb-1">Quadras serão criadas automaticamente:</p>
                        <p class="text-blue-700 text-sm">${data.quadras_a_criar.join(', ')}</p>
                    </div>
                </div>
            </div>
        `;
    } else {
        quadrasInfoDiv.classList.add('hidden');
    }

    // Estatísticas
    const statsDiv = document.getElementById('validation-stats');
    statsDiv.innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <p class="text-sm text-blue-600 font-medium">Total de Linhas</p>
            <p class="text-2xl font-bold text-blue-900">${data.total_linhas || 0}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-600 font-medium">Válidas</p>
            <p class="text-2xl font-bold text-green-900">${data.validas || 0}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-600 font-medium">Com Erros</p>
            <p class="text-2xl font-bold text-red-900">${data.erros ? data.erros.length : 0}</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-md p-4">
            <p class="text-sm text-purple-600 font-medium">A Importar</p>
            <p class="text-2xl font-bold text-purple-900">${data.total_linhas - (data.erros ? data.erros.length : 0) || 0}</p>
        </div>
    `;

    // Atualizar contadores nas tabs
    document.getElementById('count-validas').textContent = data.validas || 0;
    document.getElementById('count-erros').textContent = data.erros ? data.erros.length : 0;

    // Tabela de Válidas
    const validasTbody = document.getElementById('validas-tbody');
    validasTbody.innerHTML = '';

    if (data.dados_validos && data.dados_validos.length > 0) {
        data.dados_validos.forEach(item => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-green-50';

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${item.linha || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.nome || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.quadra_nome || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.status_nome || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.m2 ? parseFloat(item.m2).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' m²' : '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.valor_m2 ? 'R$ ' + parseFloat(item.valor_m2).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                    ${item.valor ? 'R$ ' + parseFloat(item.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-'}
                </td>
            `;

            validasTbody.appendChild(row);
        });
    } else {
        validasTbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2 block"></i>
                    Nenhuma linha válida encontrada.
                </td>
            </tr>
        `;
    }

    // Lista de Erros (formato melhorado)
    const errosList = document.getElementById('erros-list');
    errosList.innerHTML = '';

    if (data.erros && data.erros.length > 0) {
        data.erros.forEach((erro, index) => {
            const errorCard = document.createElement('div');
            errorCard.className = 'bg-white border border-red-200 rounded-lg p-4 hover:shadow-md transition-shadow';

            // Título do erro
            let erroContent = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-semibold text-red-900 mb-2">
                            Erro na Linha ${erro.linha || '-'}
                        </h4>
            `;

            // Dados formatados se disponíveis
            if (erro.dados_formatados) {
                erroContent += `
                    <div class="bg-gray-50 rounded-md p-3 mb-3 border border-gray-200">
                        <p class="text-xs font-medium text-gray-700 mb-2">Dados informados na linha:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                `;

                Object.entries(erro.dados_formatados).forEach(([campo, valor]) => {
                    const valorClass = valor === '(vazio)' ? 'text-orange-600 font-medium' : 'text-gray-700';
                    erroContent += `
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">${campo}:</span>
                            <span class="${valorClass}">${valor}</span>
                        </div>
                    `;
                });

                erroContent += `
                        </div>
                    </div>
                `;
            } else {
                // Fallback para dados não formatados
                erroContent += `
                    <div class="bg-gray-50 rounded-md p-3 mb-3 border border-gray-200">
                        <p class="text-xs text-gray-600">${erro.dados || 'Dados não disponíveis'}</p>
                    </div>
                `;
            }

            // Mensagem de erro
            erroContent += `
                        <div class="bg-red-50 border border-red-200 rounded-md p-3">
                            <p class="text-sm text-red-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Problema:</strong> ${erro.mensagem || 'Erro desconhecido'}
                            </p>
                        </div>
                    </div>
                </div>
            `;

            errorCard.innerHTML = erroContent;
            errosList.appendChild(errorCard);

            // Adicionar separador entre erros (exceto o último)
            if (index < data.erros.length - 1) {
                const separator = document.createElement('div');
                separator.className = 'h-2';
                errosList.appendChild(separator);
            }
        });
    } else {
        errosList.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center">
                <i class="fas fa-check-circle text-green-400 text-4xl mb-4 block"></i>
                <p class="text-green-800 font-medium">Nenhum erro encontrado!</p>
            </div>
        `;
    }

    // Botão de importar (apenas se não houver erros)
    const importButtonContainer = document.getElementById('import-button-container');
    if (!data.erros || data.erros.length === 0) {
        importButtonContainer.classList.remove('hidden');
    } else {
        importButtonContainer.classList.add('hidden');
    }

    // Ativar primeira tab
    showTab('validas');
}

// Função para alternar entre tabs
function showTab(tabName) {
    // Esconder todos os conteúdos
    document.getElementById('content-validas').classList.add('hidden');
    document.getElementById('content-erros').classList.add('hidden');

    // Remover active de todas as tabs
    document.getElementById('tab-validas').classList.remove('active', 'border-blue-500', 'text-blue-600');
    document.getElementById('tab-validas').classList.add('border-transparent', 'text-gray-500');
    document.getElementById('tab-erros').classList.remove('active', 'border-red-500', 'text-red-600');
    document.getElementById('tab-erros').classList.add('border-transparent', 'text-gray-500');

    // Mostrar conteúdo selecionado
    if (tabName === 'validas') {
        document.getElementById('content-validas').classList.remove('hidden');
        document.getElementById('tab-validas').classList.add('active', 'border-blue-500', 'text-blue-600');
        document.getElementById('tab-validas').classList.remove('border-transparent', 'text-gray-500');
    } else if (tabName === 'erros') {
        document.getElementById('content-erros').classList.remove('hidden');
        document.getElementById('tab-erros').classList.add('active', 'border-red-500', 'text-red-600');
        document.getElementById('tab-erros').classList.remove('border-transparent', 'text-gray-500');
    }
}

// Confirmar e importar
function confirmarImportacao() {
    Swal.fire({
        title: 'Confirmar Importação',
        text: `Deseja realmente importar ${validationData.validas || validationData.total_linhas} lote(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, importar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            importarArquivo();
        }
    });
}

// Importar arquivo
function importarArquivo() {
    const fileInput = document.getElementById('csv_file');
    const file = fileInput.files[0];

    const formData = new FormData();
    formData.append('csv_file', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('importar', 'true');

    Swal.fire({
        title: 'Importando...',
        text: 'Aguarde enquanto importamos os lotes.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route('lotes.import-csv', $empreendimento->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Importação Concluída!',
                html: `<p><strong>${data.importados}</strong> lote(s) importado(s) com sucesso.</p>`,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '{{ route('lotes.index', $empreendimento->id) }}';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro na Importação',
                text: data.message || 'Ocorreu um erro ao importar o CSV.'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Ocorreu um erro ao processar o arquivo. Tente novamente.'
        });
        console.error('Error:', error);
    });
}
</script>
@endsection

