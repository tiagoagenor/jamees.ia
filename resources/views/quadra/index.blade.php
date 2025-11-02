@extends('layouts.app')

@section('title', 'Quadras - ' . $empreendimento->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-th text-blue-600 mr-3"></i>
                    Quadras - {{ $empreendimento->nome }}
                </h1>
                <p class="mt-2 text-gray-600">Gerencie as quadras do empreendimento</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showCreateQuadraModal()"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Quadra
                </button>
                <a href="{{ route('lotes.index', $empreendimento->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar para Lotes
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtros
                </h3>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $quadras->total() }}</span> quadra(s) encontrada(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($quadras->total() > 0)
                        <span class="text-gray-500">(página {{ $quadras->currentPage() }} de {{ $quadras->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('quadras.index', $empreendimento->id) }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome da Quadra
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome da quadra..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Botões de Ação -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-cogs text-gray-400 mr-1"></i>
                            Ações
                        </label>
                        <div class="flex space-x-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('quadras.index', $empreendimento->id) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome)
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Filtros ativos:</span>
                            @if($filtroNome)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-search mr-1"></i>
                                    Nome: "{{ $filtroNome }}"
                                    <button type="button" onclick="limparFiltroNome()" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Lista de Quadras -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-8 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quadras</h3>
        </div>

        @if($quadras->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'nome';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'nome'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Nome</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade de Lotes</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quadras as $quadra)
                            <tr class="hover:bg-gray-50">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $quadra->nome }}</div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $quadra->lotes()->count() }} lote(s)
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- Excluir -->
                                        <div class="relative group">
                                            <button onclick="confirmarExclusao('{{ $quadra->id }}', '{{ $quadra->nome }}')" class="text-red-600 hover:text-red-900 flex items-center">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Excluir</span>
                                            </button>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Excluir
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($quadras->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <a href="{{ $quadras->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </a>
                        @endif

                        @if($quadras->hasMorePages())
                            <a href="{{ $quadras->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Próximo
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed">
                                Próximo
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium">{{ $quadras->firstItem() }}</span>
                                até
                                <span class="font-medium">{{ $quadras->lastItem() }}</span>
                                de
                                <span class="font-medium">{{ $quadras->total() }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($quadras->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $quadras->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach($quadras->getUrlRange(1, $quadras->lastPage()) as $page => $url)
                                    @if($page == $quadras->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-indigo-50 text-sm font-medium text-indigo-600">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if($quadras->hasMorePages())
                                    <a href="{{ $quadras->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma quadra encontrada</h3>
                    <p class="text-gray-600 mb-6">Não foram encontradas quadras com o nome "{{ $filtroNome }}".</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma quadra cadastrada</h3>
                    <p class="text-gray-600 mb-6">Clique no botão "Nova Quadra" acima para criar a primeira quadra.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <button onclick="showCreateQuadraModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Primeira Quadra
                    </button>
                    @if($filtroNome)
                        <a href="{{ route('quadras.index', $empreendimento->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Limpar Filtros
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Criar Quadra -->
<div id="quadra-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Nova Quadra</h3>
                <button onclick="closeCreateQuadraModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('quadras.store', $empreendimento->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        As quadras serão criadas automaticamente com nomes sequenciais.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                        <p class="text-sm text-blue-900">
                            <strong>Tipo de numeração:</strong>
                            @if(($empreendimento->quadra_numeracao_tipo ?? 1) == 2)
                                Alfanumérica (A, B, C, D...)
                            @else
                                Numérica (1, 2, 3, 4...)
                            @endif
                        </p>
                    </div>
                    <div>
                        <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantidade de Quadras *
                        </label>
                        <input type="number"
                               id="quantidade"
                               name="quantidade"
                               value="1"
                               min="1"
                               max="100"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Digite quantas quadras deseja criar</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateQuadraModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Quadra(s)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Exibir mensagens de sessão
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

// Modal de criar quadra
function showCreateQuadraModal() {
    document.getElementById('quadra-modal').classList.remove('hidden');
}

function closeCreateQuadraModal() {
    document.getElementById('quadra-modal').classList.add('hidden');
    document.getElementById('quantidade').value = '1';
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(event) {
    const modal = document.getElementById('quadra-modal');
    if (event.target === modal) {
        closeCreateQuadraModal();
    }
});

// Confirmar exclusão
function confirmarExclusao(quadraId, nomeQuadra) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir a quadra "${nomeQuadra}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/quadras/${quadraId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Limpar filtros
function limparFiltroNome() {
    document.getElementById('nome').value = '';
    document.querySelector('form').submit();
}
</script>
@endsection
