@extends('layouts.app')

@section('title', 'Entidades')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    @switch($tipo)
                        @case('cliente')
                            <i class="fas fa-user-tie text-blue-600 mr-3"></i>Clientes
                            @break
                        @case('fornecedor')
                            <i class="fas fa-truck text-green-600 mr-3"></i>Fornecedores
                            @break
                        @case('funcionario')
                            <i class="fas fa-user text-purple-600 mr-3"></i>Funcionários
                            @break
                        @case('transportadora')
                            <i class="fas fa-shipping-fast text-orange-600 mr-3"></i>Transportadoras
                            @break
                    @endswitch
                </h1>
                <p class="mt-2 text-gray-600">
                    @switch($tipo)
                        @case('cliente')
                            Gerencie seus clientes
                            @break
                        @case('fornecedor')
                            Gerencie seus fornecedores
                            @break
                        @case('funcionario')
                            Gerencie seus funcionários
                            @break
                        @case('transportadora')
                            Gerencie suas transportadoras
                            @break
                    @endswitch
                </p>
            </div>
            <div class="flex space-x-3">
                @php
                    $routeName = match($tipo) {
                        'cliente' => 'clientes',
                        'fornecedor' => 'fornecedores',
                        'funcionario' => 'funcionarios',
                        'transportadora' => 'transportadoras',
                        default => $tipo . 's'
                    };
                @endphp
                <a href="{{ route($routeName . '.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Novo {{ ucfirst($tipo) }}
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
                    <span class="font-medium">{{ $entidades->total() }}</span> {{ $tipo }}(s) encontrado(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($entidades->total() > 0)
                        <span class="text-gray-500">(página {{ $entidades->currentPage() }} de {{ $entidades->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route($routeName . '.index') }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome/Razão Social
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome ou razão social..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Documento -->
                    <div class="space-y-2">
                        <label for="documento" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-gray-400 mr-1"></i>
                            Documento
                        </label>
                        <input type="text"
                               name="documento"
                               id="documento"
                               value="{{ $filtroDocumento }}"
                               placeholder="Digite o documento..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Email
                        </label>
                        <input type="text"
                               name="email"
                               id="email"
                               value="{{ $filtroEmail }}"
                               placeholder="Digite o email..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            <a href="{{ route($routeName . '.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome || $filtroDocumento || $filtroEmail || $filtroStatus !== 'todos')
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
                            @if($filtroDocumento)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-id-card mr-1"></i>
                                    Documento: "{{ $filtroDocumento }}"
                                    <button type="button" onclick="limparFiltroDocumento()" class="ml-1 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroEmail)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-envelope mr-1"></i>
                                    Email: "{{ $filtroEmail }}"
                                    <button type="button" onclick="limparFiltroEmail()" class="ml-1 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroStatus !== 'todos')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    Status: {{ ucfirst($filtroStatus) }}
                                    <button type="button" onclick="limparFiltroStatus()" class="ml-1 text-orange-600 hover:text-orange-800">
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

    <!-- Lista de Entidades -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-8 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                @switch($tipo)
                    @case('cliente')
                        Clientes
                        @break
                    @case('fornecedor')
                        Fornecedores
                        @break
                    @case('funcionario')
                        Funcionários
                        @break
                    @case('transportadora')
                        Transportadoras
                        @break
                @endswitch
            </h3>
        </div>

        @if($entidades->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($entidades as $entidade)
                            <tr class="hover:bg-gray-50">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="{{ $entidade->getTipoRelacionamentoIcon() }} text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $entidade->nome_completo }}</div>
                                            @if($entidade->nome_fantasia && $entidade->nome_fantasia !== $entidade->nome_completo)
                                                <div class="text-sm text-gray-500">{{ $entidade->nome_fantasia }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->documento_formatado }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->email }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->telefone_comercial_formatado ?: $entidade->celular_formatado }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $entidade->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $entidade->isAtivo() ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route($routeName . '.show', $entidade) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route($routeName . '.edit', $entidade) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="toggleStatus('{{ $entidade->id }}', '{{ $entidade->nome_completo }}', {{ $entidade->status ? 'true' : 'false' }}, '{{ $routeName }}')" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-{{ $entidade->isAtivo() ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button onclick="confirmarExclusao('{{ $entidade->id }}', '{{ $entidade->nome_completo }}', '{{ $routeName }}')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                    <div class="text-sm text-gray-700">
                        Mostrando {{ $entidades->firstItem() }} até {{ $entidades->lastItem() }} de {{ $entidades->total() }} resultados
                    </div>
                    <div class="flex space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($entidades->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $entidades->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($entidades->getUrlRange(1, $entidades->lastPage()) as $page => $url)
                            @if ($page == $entidades->currentPage())
                                <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($entidades->hasMorePages())
                            <a href="{{ $entidades->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
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
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} encontrado</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontrados {{ $tipo }}s com o nome "{{ $filtroNome }}".
                        @if($filtroDocumento)
                            Tente buscar apenas por documento.
                        @endif
                        @if($filtroEmail)
                            Tente buscar apenas por email.
                        @endif
                        @if($filtroStatus !== 'todos')
                            @if($filtroStatus === 'ativos')
                                Tente buscar apenas por {{ $tipo }}s ativos.
                            @else
                                Tente buscar apenas por {{ $tipo }}s inativos.
                            @endif
                        @endif
                    </p>
                @elseif($filtroDocumento)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} encontrado</h3>
                    <p class="text-gray-600 mb-6">Não foram encontrados {{ $tipo }}s com o documento "{{ $filtroDocumento }}".</p>
                @elseif($filtroEmail)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} encontrado</h3>
                    <p class="text-gray-600 mb-6">Não foram encontrados {{ $tipo }}s com o email "{{ $filtroEmail }}".</p>
                @elseif($filtroStatus === 'ativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} ativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há {{ $tipo }}s ativos no momento.</p>
                @elseif($filtroStatus === 'inativos')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} inativo encontrado</h3>
                    <p class="text-gray-600 mb-6">Não há {{ $tipo }}s inativos no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} encontrado</h3>
                    <p class="text-gray-600 mb-6">Comece criando seu primeiro {{ $tipo }}.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route($routeName . '.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Novo {{ ucfirst($tipo) }}
                    </a>
                    @if($filtroNome || $filtroDocumento || $filtroEmail || $filtroStatus !== 'todos')
                        <a href="{{ route($routeName . '.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
function confirmarExclusao(entidadeId, nomeEntidade, routeName) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${nomeEntidade}"?`,
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
            form.action = `/${routeName}/${entidadeId}`;

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
function toggleStatus(entidadeId, nomeEntidade, statusAtual, routeName) {
    const action = statusAtual ? 'inativar' : 'ativar';
    const icon = statusAtual ? 'warning' : 'success';
    const confirmColor = statusAtual ? '#d33' : '#28a745';

    Swal.fire({
        title: `Confirmar ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Tem certeza que deseja ${action} "${nomeEntidade}"?`,
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
            form.action = `/${routeName}/${entidadeId}/toggle-status`;

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

// Limpar filtro de documento
function limparFiltroDocumento() {
    document.getElementById('documento').value = '';
    document.querySelector('form').submit();
}

// Limpar filtro de email
function limparFiltroEmail() {
    document.getElementById('email').value = '';
    document.querySelector('form').submit();
}

// Limpar filtro de status
function limparFiltroStatus() {
    document.getElementById('status').value = 'todos';
    document.querySelector('form').submit();
}
</script>
@endsection
