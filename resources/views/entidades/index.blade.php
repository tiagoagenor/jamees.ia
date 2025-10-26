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
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                       placeholder="Nome, documento, email..."
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="min-w-32">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route($routeName . '.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i>Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Entidades -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($entidades->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($entidades as $entidade)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->documento_formatado }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $entidade->telefone_comercial_formatado ?: $entidade->celular_formatado }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $entidade->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $entidade->isAtivo() ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route($routeName . '.show', $entidade) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route($routeName . '.edit', $entidade) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route($routeName . '.toggle-status', $entidade) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-{{ $entidade->isAtivo() ? 'red' : 'green' }}-600 hover:text-{{ $entidade->isAtivo() ? 'red' : 'green' }}-900">
                                                <i class="fas fa-{{ $entidade->isAtivo() ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" onclick="confirmarExclusao('{{ $entidade->id }}', '{{ $entidade->nome_completo }}', '{{ $routeName }}')" class="text-red-600 hover:text-red-900">
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
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $entidades->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum {{ $tipo }} encontrado</h3>
                <p class="text-gray-500 mb-6">Comece criando seu primeiro {{ $tipo }}.</p>
                <a href="{{ route($routeName . '.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Novo {{ ucfirst($tipo) }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Função para confirmar exclusão com SweetAlert2
function confirmarExclusao(entidadeId, nomeEntidade, routeName) {
    Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja realmente excluir "${nomeEntidade}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/${routeName}/${entidadeId}`;

            // Adicionar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Adicionar método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Adicionar ao DOM e submeter
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Função para mostrar mensagens de sucesso/erro
@if(session('success'))
    Swal.fire({
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
@endif

@if(session('error'))
    Swal.fire({
        title: 'Erro!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
@endif
</script>
@endsection
