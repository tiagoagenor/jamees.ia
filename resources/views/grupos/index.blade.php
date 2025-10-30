@extends('layouts.app')

@section('title', 'Grupos de Usuários')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    Grupos de Usuários
                </h1>
                <p class="text-gray-600 mt-2">Gerencie os grupos e suas permissões</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('permissoes.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-key mr-2"></i>
                    Ver Permissões
                </a>
                <a href="{{ route('grupos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Grupo
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
                    <span class="font-medium">{{ $grupos->total() }}</span> grupo(s) encontrado(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($grupos->total() > 0)
                        <span class="text-gray-500">(página {{ $grupos->currentPage() }} de {{ $grupos->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('grupos.index') }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome do Grupo
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome do grupo..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Tipo -->
                    <div class="space-y-2">
                        <label for="tipo" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-tag text-gray-400 mr-1"></i>
                            Tipo
                        </label>
                        <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroTipo === 'todos' ? 'selected' : '' }}>Todos os tipos</option>
                            <option value="administrativo" {{ $filtroTipo === 'administrativo' ? 'selected' : '' }}>Apenas administrativos</option>
                            <option value="usuario" {{ $filtroTipo === 'usuario' ? 'selected' : '' }}>Apenas usuários</option>
                        </select>
                    </div>

                    <!-- Filtro por Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Status
                        </label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroStatus === 'todos' ? 'selected' : '' }}>Todos os status</option>
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
                            <a href="{{ route('grupos.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome || $filtroTipo !== 'todos' || $filtroStatus !== 'todos')
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
                            @if($filtroTipo !== 'todos')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-tag mr-1"></i>
                                    Tipo: {{ ucfirst($filtroTipo) }}
                                    <button type="button" onclick="limparFiltroTipo()" class="ml-1 text-purple-600 hover:text-purple-800">
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

    <!-- Lista de Grupos -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Grupos de Usuários</h3>
        </div>

        @if($grupos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissões</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($grupos as $grupo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-users text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $grupo->nome }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $grupo->descricao ?? 'Sem descrição' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupo->administrativo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-crown mr-1"></i>
                                            Administrativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user mr-1"></i>
                                            Usuário
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $grupo->permissoes->count() }} permissões
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($grupo->ativo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- Visualizar -->
                                        <div class="relative group">
                                            <a href="{{ route('grupos.show', $grupo) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
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
                                            <a href="{{ route('grupos.edit', $grupo) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="sr-only">Editar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Editar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Excluir -->
                                        @if(!$grupo->administrativo)
                                        <div class="relative group">
                                            <button onclick="confirmarExclusao('{{ $grupo->id }}', '{{ $grupo->nome }}')" class="text-red-600 hover:text-red-900 flex items-center">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Excluir</span>
                                            </button>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Excluir
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>
                                        @endif
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
                        Mostrando {{ $grupos->firstItem() }} até {{ $grupos->lastItem() }} de {{ $grupos->total() }} resultados
                    </div>
                    <div class="flex space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($grupos->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $grupos->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($grupos->getUrlRange(1, $grupos->lastPage()) as $page => $url)
                            @if ($page == $grupos->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($grupos->hasMorePages())
                            <a href="{{ $grupos->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
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
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontrados grupos com o nome "{{ $filtroNome }}".
                        @if($filtroTipo !== 'todos')
                            @if($filtroTipo === 'administrativo')
                                Tente buscar apenas por grupos administrativos.
                            @else
                                Tente buscar apenas por grupos de usuário.
                            @endif
                        @endif
                        @if($filtroStatus !== 'todos')
                            @if($filtroStatus === 'ativos')
                                Tente buscar apenas por grupos ativos.
                            @else
                                Tente buscar apenas por grupos inativos.
                            @endif
                        @endif
                    </p>
                @elseif($filtroTipo === 'administrativo')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo administrativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há grupos administrativos no momento.</p>
                @elseif($filtroTipo === 'usuario')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo de usuário encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há grupos de usuário no momento.</p>
                @elseif($filtroStatus === 'ativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo ativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há grupos ativos no momento.</p>
                @elseif($filtroStatus === 'inativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo inativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há grupos inativos no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo encontrado</h3>
                    <p class="text-gray-600 mb-6">Comece criando seu primeiro grupo de usuários.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('grupos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Grupo
                    </a>
                    @if($filtroNome || $filtroTipo !== 'todos' || $filtroStatus !== 'todos')
                        <a href="{{ route('grupos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
function confirmarExclusao(grupoId, grupoNome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir o grupo "${grupoNome}"?`,
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
            form.action = `/grupos/${grupoId}`;

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

// Limpar filtro de nome
function limparFiltroNome() {
    document.getElementById('nome').value = '';
    document.querySelector('form').submit();
}

// Limpar filtro de tipo
function limparFiltroTipo() {
    document.getElementById('tipo').value = 'todos';
    document.querySelector('form').submit();
}

// Limpar filtro de status
function limparFiltroStatus() {
    document.getElementById('status').value = 'todos';
    document.querySelector('form').submit();
}
</script>
@endsection
