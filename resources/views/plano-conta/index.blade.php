@extends('layouts.app')

@section('title', 'Plano de Contas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-list-alt text-blue-600 mr-3"></i>
                    Plano de Contas
                </h1>
                <p class="text-gray-600 mt-2">Gerencie o plano de contas da sua empresa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('plano-conta.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Conta
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
                    <span class="font-medium">{{ $planoContas->total() }}</span> conta(s) encontrada(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($planoContas->total() > 0)
                        <span class="text-gray-500">(página {{ $planoContas->currentPage() }} de {{ $planoContas->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('plano-conta.index') }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome da Conta
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome da conta..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Movimentação -->
                    <div class="space-y-2">
                        <label for="movimentacao" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-exchange-alt text-gray-400 mr-1"></i>
                            Movimentação
                        </label>
                        <select name="movimentacao" id="movimentacao" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroMovimentacao === 'todos' ? 'selected' : '' }}>Todas as movimentações</option>
                            <option value="debito" {{ $filtroMovimentacao === 'debito' ? 'selected' : '' }}>Apenas débito</option>
                            <option value="credito" {{ $filtroMovimentacao === 'credito' ? 'selected' : '' }}>Apenas crédito</option>
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
                            <a href="{{ route('plano-conta.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome || $filtroMovimentacao !== 'todos')
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
                            @if($filtroMovimentacao !== 'todos')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-exchange-alt mr-1"></i>
                                    Movimentação: {{ ucfirst($filtroMovimentacao) }}
                                    <button type="button" onclick="limparFiltroMovimentacao()" class="ml-1 text-green-600 hover:text-green-800">
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

    <!-- Lista de Contas -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Plano de Contas</h3>
        </div>

        @if($planoContas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'codigo';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'codigo'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Código</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'movimentacao';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'movimentacao'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Movimentação</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'dre';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'dre'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>DRE</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'tipo';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'tipo'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Tipo</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($planoContas as $conta)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $conta->getCodigoCompleto() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $conta->nome }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($conta->isDebito()) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        <i class="{{ $conta->getMovimentacaoIcon() }} mr-1"></i>
                                        {{ $conta->getMovimentacaoLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $conta->dre ? $conta->dre->nome : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($conta->isRaiz())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-folder mr-1"></i>
                                            Conta Pai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-file mr-1"></i>
                                            Subconta
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- Visualizar -->
                                        <div class="relative group">
                                            <a href="{{ route('plano-conta.show', $conta) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
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
                                            <a href="{{ route('plano-conta.edit', $conta) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="sr-only">Editar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Editar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Excluir -->
                                        <div class="relative group">
                                            <button onclick="confirmarExclusao('{{ $conta->id }}', '{{ $conta->nome }}')" class="text-red-600 hover:text-red-900 flex items-center">
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
                        Mostrando {{ $planoContas->firstItem() }} até {{ $planoContas->lastItem() }} de {{ $planoContas->total() }} resultados
                    </div>
                    <div class="flex space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($planoContas->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $planoContas->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($planoContas->getUrlRange(1, $planoContas->lastPage()) as $page => $url)
                            @if ($page == $planoContas->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($planoContas->hasMorePages())
                            <a href="{{ $planoContas->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
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
                <i class="fas fa-list-alt text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta encontrada</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontradas contas com o nome "{{ $filtroNome }}".
                        @if($filtroMovimentacao !== 'todos')
                            @if($filtroMovimentacao === 'debito')
                                Tente buscar apenas por contas de débito.
                            @else
                                Tente buscar apenas por contas de crédito.
                            @endif
                        @endif
                    </p>
                @elseif($filtroMovimentacao === 'debito')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta de débito encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há contas de débito no momento.</p>
                @elseif($filtroMovimentacao === 'credito')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta de crédito encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há contas de crédito no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta encontrada</h3>
                    <p class="text-gray-600 mb-6">Comece criando sua primeira conta no plano de contas.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('plano-conta.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Conta
                    </a>
                    @if($filtroNome || $filtroMovimentacao !== 'todos')
                        <a href="{{ route('plano-conta.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
function confirmarExclusao(contaId, contaNome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${contaNome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/plano-conta/${contaId}`;

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = '{{ csrf_token() }}';

            form.appendChild(methodField);
            form.appendChild(tokenField);
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

// Limpar filtro de movimentação
function limparFiltroMovimentacao() {
    document.getElementById('movimentacao').value = 'todos';
    document.querySelector('form').submit();
}
</script>
@endsection
