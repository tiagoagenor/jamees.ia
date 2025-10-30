@extends('layouts.app')

@section('title', 'Centros de Custo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-building text-blue-600 mr-3"></i>
                    Centros de Custo
                </h1>
                <p class="text-gray-600 mt-2">Gerencie os centros de custo da sua empresa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('centro-custo.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Centro de Custo
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
                    <span class="font-medium">{{ $centroCustos->total() }}</span> centro(s) de custo encontrado(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($centroCustos->total() > 0)
                        <span class="text-gray-500">(página {{ $centroCustos->currentPage() }} de {{ $centroCustos->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('centro-custo.index') }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome do Centro de Custo
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome do centro de custo..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Status
                        </label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroStatus === 'todos' ? 'selected' : '' }}>Todos os centros</option>
                            <option value="ativos" {{ $filtroStatus === 'ativos' ? 'selected' : '' }}>Apenas ativos</option>
                            <option value="inativos" {{ $filtroStatus === 'inativos' ? 'selected' : '' }}>Apenas inativos</option>
                        </select>
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
                            <a href="{{ route('centro-custo.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome || $filtroStatus !== 'todos')
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
                            @if($filtroStatus !== 'todos')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    Status: {{ ucfirst($filtroStatus) }}
                                    <button type="button" onclick="limparFiltroStatus()" class="ml-1 text-green-600 hover:text-green-800">
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

    <!-- Lista de Centros de Custo -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Centros de Custo</h3>
        </div>

        @if($centroCustos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($centroCustos as $centroCusto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $centroCusto->nome }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($centroCusto->isAtivo()) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        <i class="{{ $centroCusto->getStatusIcon() }} mr-1"></i>
                                        {{ $centroCusto->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $centroCusto->criado_em->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- Visualizar -->
                                        <div class="relative group">
                                            <a href="{{ route('centro-custo.show', $centroCusto) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <i class="fas fa-eye"></i>
                                                <span class="sr-only">Visualizar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Visualizar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Editar -->
                                        <div class="relative group">
                                            <a href="{{ route('centro-custo.edit', $centroCusto) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="sr-only">Editar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Editar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Ativar/Inativar -->
                                        <div class="relative group">
                                            <button onclick="toggleStatus('{{ $centroCusto->id }}', '{{ $centroCusto->nome }}', {{ $centroCusto->status }})" class="text-purple-600 hover:text-purple-900 flex items-center">
                                                <i class="fas fa-toggle-{{ $centroCusto->isAtivo() ? 'on' : 'off' }}"></i>
                                                <span class="sr-only">{{ $centroCusto->isAtivo() ? 'Inativar' : 'Ativar' }}</span>
                                            </button>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                {{ $centroCusto->isAtivo() ? 'Inativar' : 'Ativar' }}
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Excluir -->
                                        <div class="relative group">
                                            <button onclick="confirmarExclusaoSweetAlert('{{ $centroCusto->id }}', '{{ $centroCusto->nome }}')" class="text-red-600 hover:text-red-900 flex items-center">
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
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando {{ $centroCustos->firstItem() }} até {{ $centroCustos->lastItem() }} de {{ $centroCustos->total() }} resultados
                    </div>
                    <div class="flex space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($centroCustos->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $centroCustos->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($centroCustos->getUrlRange(1, $centroCustos->lastPage()) as $page => $url)
                            @if ($page == $centroCustos->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($centroCustos->hasMorePages())
                            <a href="{{ $centroCustos->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-building text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum centro de custo encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontrados centros de custo com o nome "{{ $filtroNome }}".
                        @if($filtroStatus !== 'todos')
                            @if($filtroStatus === 'ativos')
                                Tente buscar apenas por centros ativos.
                            @else
                                Tente buscar apenas por centros inativos.
                            @endif
                        @endif
                    </p>
                @elseif($filtroStatus === 'ativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum centro de custo ativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há centros de custo ativos no momento.</p>
                @elseif($filtroStatus === 'inativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum centro de custo inativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há centros de custo inativos no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum centro de custo encontrado</h3>
                    <p class="text-gray-600 mb-6">Comece criando seu primeiro centro de custo.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('centro-custo.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Centro de Custo
                    </a>
                    @if($filtroNome || $filtroStatus !== 'todos')
                        <a href="{{ route('centro-custo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Limpar Filtros
                        </a>
                    @endif
                </div>
            </div>
        @endif
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

// Confirmar exclusão
function confirmarExclusaoSweetAlert(id, nome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${nome}"?`,
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
            form.action = `/centro-custo/${id}`;

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

// Toggle status
function toggleStatus(id, nome, statusAtual) {
    const action = statusAtual === 1 ? 'inativar' : 'ativar';
    const icon = statusAtual === 1 ? 'warning' : 'success';
    const confirmColor = statusAtual === 1 ? '#d33' : '#28a745';

    Swal.fire({
        title: `Confirmar ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Tem certeza que deseja ${action} "${nome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/centro-custo/${id}/toggle-status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Limpar filtro de nome
function limparFiltroNome() {
    document.getElementById('nome').value = '';
    document.querySelector('form').submit();
}

// Limpar filtro de status
function limparFiltroStatus() {
    document.getElementById('status').value = 'todos';
    document.querySelector('form').submit();
}
</script>
@endsection
