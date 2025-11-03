@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Detalhes do Centro de Custo</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('centro-custo.edit', $centroCusto) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                        <a href="{{ route('centro-custo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informações Básicas -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informações Básicas
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="text-sm text-gray-900">{{ $centroCusto->nome }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($centroCusto->isAtivo()) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        <i class="{{ $centroCusto->getStatusIcon() }} mr-1"></i>
                                        {{ $centroCusto->getStatusLabel() }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Informações de Sistema -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-cog mr-2"></i>
                            Informações de Sistema
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $centroCusto->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                                <dd class="text-sm text-gray-900">{{ $centroCusto->empresa->nome_fantasia ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="text-sm text-gray-900">{{ $centroCusto->criado_em->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                                <dd class="text-sm text-gray-900">{{ $centroCusto->atualizado_em->format('d/m/Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Ações -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <button onclick="toggleStatus('{{ $centroCusto->id }}', '{{ $centroCusto->nome }}', {{ $centroCusto->status }})"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-toggle-{{ $centroCusto->isAtivo() ? 'on' : 'off' }} mr-2"></i>
                                {{ $centroCusto->isAtivo() ? 'Inativar' : 'Ativar' }}
                            </button>
                            <button onclick="confirmarExclusaoSweetAlert('{{ $centroCusto->id }}', '{{ $centroCusto->nome }}')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>
                                Excluir
                            </button>
                        </div>
                        <div class="text-sm text-gray-500">
                            Última atualização: {{ $centroCusto->atualizado_em->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
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
</script>
@endsection
