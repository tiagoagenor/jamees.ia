@extends('layouts.app')

@section('title', 'Contas Bancárias')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-university text-blue-600 mr-3"></i>
                    Contas Bancárias
                </h1>
                <p class="text-gray-600 mt-2">Gerencie as contas bancárias da sua empresa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('conta-empresa.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
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
                    <span class="font-medium">{{ $contas->count() }}</span> conta(s) encontrada(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                </div>
            </div>

            <form method="GET" action="{{ route('conta-empresa.index') }}" class="space-y-4">
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

                    <!-- Filtro por Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Status
                        </label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="todos" {{ $filtroStatus === 'todos' ? 'selected' : '' }}>Todas as contas</option>
                            <option value="ativas" {{ $filtroStatus === 'ativas' ? 'selected' : '' }}>Apenas ativas</option>
                            <option value="inativas" {{ $filtroStatus === 'inativas' ? 'selected' : '' }}>Apenas inativas</option>
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
                            <a href="{{ route('conta-empresa.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
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

    <!-- Contas List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Contas Bancárias</h3>
        </div>

        @if($contas->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($contas as $conta)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($conta->banco && $conta->banco->imagem)
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <img src="{{ asset('img/bancos/' . $conta->banco->imagem) }}"
                                                 alt="{{ $conta->banco->nome_normalizado }}"
                                                 class="w-full h-full object-contain"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400" style="display: none;">
                                                <i class="fas fa-university text-xl"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                            @if($conta->isCorrente()) bg-blue-100 text-blue-600
                                            @elseif($conta->isPoupanca()) bg-green-100 text-green-600
                                            @elseif($conta->isInvestimento()) bg-purple-100 text-purple-600
                                            @elseif($conta->isCartaoCredito()) bg-red-100 text-red-600
                                            @else bg-orange-100 text-orange-600 @endif">
                                            <i class="{{ $conta->getTipoIcon() }}"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $conta->nome }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($conta->isCorrente()) bg-blue-100 text-blue-800
                                            @elseif($conta->isPoupanca()) bg-green-100 text-green-800
                                            @elseif($conta->isInvestimento()) bg-purple-100 text-purple-800
                                            @elseif($conta->isCartaoCredito()) bg-red-100 text-red-800
                                            @else bg-orange-100 text-orange-800 @endif">
                                            {{ $conta->getTipoLabel() }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $conta->isAtiva() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $conta->isAtiva() ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @if($conta->banco)
                                            {{ $conta->banco->nome_normalizado }} ({{ $conta->banco->numero_banco }})
                                        @else
                                            Banco não encontrado
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Saldo inicial: {{ $conta->getSaldoInicialFormatado() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('conta-empresa.show', $conta) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>
                                <a href="{{ route('conta-empresa.edit', $conta) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </a>
                                <button onclick="confirmarToggleStatus('{{ $conta->id }}', '{{ $conta->nome }}', {{ $conta->isAtiva() ? 'true' : 'false' }})"
                                        class="text-purple-600 hover:text-purple-900 text-sm font-medium">
                                    <i class="fas fa-toggle-{{ $conta->isAtiva() ? 'on' : 'off' }} mr-1"></i>
                                    {{ $conta->isAtiva() ? 'Desativar' : 'Ativar' }}
                                </button>
                                <button onclick="confirmarExclusao('{{ $conta->id }}', '{{ $conta->nome }}')"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-university text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta encontrada</h3>
                    <p class="text-gray-600 mb-6">
                        Não foram encontradas contas com o nome "{{ $filtroNome }}".
                        @if($filtroStatus !== 'todos')
                            @if($filtroStatus === 'ativas')
                                Tente buscar apenas por contas ativas.
                            @else
                                Tente buscar apenas por contas inativas.
                            @endif
                        @endif
                    </p>
                @elseif($filtroStatus === 'ativas')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta ativa encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há contas bancárias ativas no momento.</p>
                @elseif($filtroStatus === 'inativas')
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta inativa encontrada</h3>
                    <p class="text-gray-600 mb-6">Não há contas bancárias inativas no momento.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma conta bancária encontrada</h3>
                    <p class="text-gray-600 mb-6">Comece criando uma nova conta bancária para sua empresa.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('conta-empresa.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Conta
                    </a>
                    @if($filtroNome || $filtroStatus !== 'todos')
                        <a href="{{ route('conta-empresa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
            form.action = `/contas-bancarias/${contaId}`;

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

// Confirmar toggle de status
function confirmarToggleStatus(contaId, contaNome, isAtiva) {
    const action = isAtiva ? 'desativar' : 'ativar';
    const icon = isAtiva ? 'warning' : 'success';

    Swal.fire({
        title: `Confirmar ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Tem certeza que deseja ${action} "${contaNome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: isAtiva ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para toggle status
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/contas-bancarias/${contaId}/toggle-status`;

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
