@extends('layouts.app')

@section('title', 'Formas de Pagamento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    Formas de Pagamento
                </h1>
                <p class="text-gray-600 mt-2">Gerencie as formas de pagamento da sua empresa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('forma-pagamento.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Forma de Pagamento
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
                    <span class="font-medium">{{ $formasPagamento->count() }}</span> forma(s) de pagamento encontrada(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('forma-pagamento.index') }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome da Forma de Pagamento
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome da forma de pagamento..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Disponibilidade
                        </label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroStatus === 'todos' ? 'selected' : '' }}>Todas as formas</option>
                            <option value="disponiveis" {{ $filtroStatus === 'disponiveis' ? 'selected' : '' }}>Apenas disponíveis</option>
                            <option value="indisponiveis" {{ $filtroStatus === 'indisponiveis' ? 'selected' : '' }}>Apenas indisponíveis</option>
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
                            <a href="{{ route('forma-pagamento.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
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

    <!-- Formas de Pagamento List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Formas de Pagamento</h3>
        </div>

        @if($formasPagamento->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($formasPagamento as $formaPagamento)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                        @if($formaPagamento->modalidade->getColor() === 'green') bg-green-100 text-green-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'blue') bg-blue-100 text-blue-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'purple') bg-purple-100 text-purple-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'yellow') bg-yellow-100 text-yellow-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'orange') bg-orange-100 text-orange-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'indigo') bg-indigo-100 text-indigo-600
                                        @elseif($formaPagamento->modalidade->getColor() === 'pink') bg-pink-100 text-pink-600
                                        @else bg-gray-100 text-gray-600 @endif">
                                        <i class="{{ $formaPagamento->getModalidadeIcon() }}"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $formaPagamento->nome }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($formaPagamento->modalidade->getColor() === 'green') bg-green-100 text-green-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'blue') bg-blue-100 text-blue-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'purple') bg-purple-100 text-purple-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'yellow') bg-yellow-100 text-yellow-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'orange') bg-orange-100 text-orange-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'indigo') bg-indigo-100 text-indigo-800
                                            @elseif($formaPagamento->modalidade->getColor() === 'pink') bg-pink-100 text-pink-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $formaPagamento->getModalidadeLabel() }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $formaPagamento->isDisponivel() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $formaPagamento->isDisponivel() ? 'Disponível' : 'Indisponível' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $formaPagamento->numero_parcelas }} parcela(s)
                                        @if($formaPagamento->numero_parcelas > 1)
                                            a cada {{ $formaPagamento->intercalo_parcelas }} dias
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Total de taxas: {{ $formaPagamento->getTotalTaxasFormatado() }}
                                    </p>
                                    @if($formaPagamento->contaEmpresa)
                                        <p class="text-sm text-gray-600">
                                            Conta: {{ $formaPagamento->contaEmpresa->nome }}
                                            @if($formaPagamento->contaEmpresa->banco)
                                                ({{ $formaPagamento->contaEmpresa->banco->nome_normalizado }})
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Visualizar -->
                                <div class="relative group">
                                    <a href="{{ route('forma-pagamento.show', $formaPagamento) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
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
                                    <a href="{{ route('forma-pagamento.edit', $formaPagamento) }}" class="text-yellow-600 hover:text-yellow-900 flex items-center">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Editar</span>
                                    </a>
                                    <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                        Editar
                                        <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                    </div>
                                </div>

                                <!-- Ativar/Desativar -->
                                <div class="relative group">
                                    <button onclick="confirmarToggleDisponibilidade('{{ $formaPagamento->id }}', '{{ $formaPagamento->nome }}', {{ $formaPagamento->isDisponivel() ? 'true' : 'false' }})"
                                            class="text-purple-600 hover:text-purple-900 flex items-center">
                                        <i class="fas fa-toggle-{{ $formaPagamento->isDisponivel() ? 'on' : 'off' }}"></i>
                                        <span class="sr-only">{{ $formaPagamento->isDisponivel() ? 'Desativar' : 'Ativar' }}</span>
                                    </button>
                                    <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                        {{ $formaPagamento->isDisponivel() ? 'Desativar' : 'Ativar' }}
                                        <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                    </div>
                                </div>

                                <!-- Excluir -->
                                @if($formaPagamento->isPermiteDeletar())
                                <div class="relative group">
                                    <button onclick="confirmarExclusao('{{ $formaPagamento->id }}', '{{ $formaPagamento->nome }}')"
                                            class="text-red-600 hover:text-red-900 flex items-center">
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
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-credit-card text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma forma de pagamento encontrada</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontradas formas de pagamento com o nome "{{ $filtroNome }}".
                        @if($filtroStatus !== 'todos')
                            @if($filtroStatus === 'disponiveis')
                                Tente buscar apenas por formas disponíveis.
                            @else
                                Tente buscar apenas por formas indisponíveis.
                            @endif
                        @endif
                    </p>
                @elseif($filtroStatus === 'disponiveis')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma forma de pagamento disponível encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há formas de pagamento disponíveis no momento.</p>
                @elseif($filtroStatus === 'indisponiveis')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma forma de pagamento indisponível encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há formas de pagamento indisponíveis no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma forma de pagamento encontrada</h3>
                    <p class="text-gray-600 mb-6">Comece criando uma nova forma de pagamento para sua empresa.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('forma-pagamento.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Forma de Pagamento
                    </a>
                    @if($filtroNome || $filtroStatus !== 'todos')
                        <a href="{{ route('forma-pagamento.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
function confirmarExclusao(formaPagamentoId, formaPagamentoNome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${formaPagamentoNome}"?`,
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
            form.action = `/formas-pagamento/${formaPagamentoId}`;

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

// Confirmar toggle de disponibilidade
function confirmarToggleDisponibilidade(formaPagamentoId, formaPagamentoNome, isDisponivel) {
    const action = isDisponivel ? 'desativar' : 'ativar';
    const icon = isDisponivel ? 'warning' : 'success';

    Swal.fire({
        title: `Confirmar ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Tem certeza que deseja ${action} "${formaPagamentoNome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: isDisponivel ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para toggle disponibilidade
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/formas-pagamento/${formaPagamentoId}/toggle-disponibilidade`;

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

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

// Limpar filtro de status
function limparFiltroStatus() {
    document.getElementById('status').value = 'todos';
    document.querySelector('form').submit();
}
</script>
@endsection
