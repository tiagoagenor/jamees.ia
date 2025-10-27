@extends('layouts.app')

@section('title', 'Detalhes da Conta Bancária')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-university text-blue-600 mr-3"></i>
                    {{ $contaEmpresa->nome }}
                </h1>
                <p class="text-gray-600 mt-2">Detalhes da conta bancária</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('conta-empresa.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
                <a href="{{ route('conta-empresa.edit', $contaEmpresa) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <button onclick="confirmarExclusao('{{ $contaEmpresa->id }}', '{{ $contaEmpresa->nome }}')"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-trash mr-2"></i>
                    Excluir
                </button>
            </div>
        </div>
    </div>

    <!-- Detalhes da Conta -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informações Principais -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Informações Principais
                </h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome da Conta</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contaEmpresa->nome }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Banco</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($contaEmpresa->banco)
                                {{ $contaEmpresa->banco->nome_normalizado }} ({{ $contaEmpresa->banco->numero_banco }})
                            @else
                                Banco não encontrado
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($contaEmpresa->isCorrente()) bg-blue-100 text-blue-800
                                @elseif($contaEmpresa->isPoupanca()) bg-green-100 text-green-800
                                @elseif($contaEmpresa->isInvestimento()) bg-purple-100 text-purple-800
                                @elseif($contaEmpresa->isCartaoCredito()) bg-red-100 text-red-800
                                @else bg-orange-100 text-orange-800 @endif">
                                <i class="{{ $contaEmpresa->getTipoIcon() }} mr-1"></i>
                                {{ $contaEmpresa->getTipoLabel() }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $contaEmpresa->isAtiva() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ $contaEmpresa->isAtiva() ? 'Ativa' : 'Inativa' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Informações Financeiras -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                    Informações Financeiras
                </h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Saldo Inicial</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $contaEmpresa->getSaldoInicialFormatado() }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Criação</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $contaEmpresa->criado_em ? \Carbon\Carbon::parse($contaEmpresa->criado_em)->format('d/m/Y H:i') : 'Não informado' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Atualização</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $contaEmpresa->atualizado_em ? \Carbon\Carbon::parse($contaEmpresa->atualizado_em)->format('d/m/Y H:i') : 'Não informado' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Ações -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <button onclick="confirmarToggleStatus('{{ $contaEmpresa->id }}', '{{ $contaEmpresa->nome }}', {{ $contaEmpresa->isAtiva() ? 'true' : 'false' }})"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-toggle-{{ $contaEmpresa->isAtiva() ? 'on' : 'off' }} mr-2"></i>
                        {{ $contaEmpresa->isAtiva() ? 'Desativar' : 'Ativar' }}
                    </button>
                </div>

                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    ID: {{ $contaEmpresa->id }}
                </div>
            </div>
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
</script>
@endsection
