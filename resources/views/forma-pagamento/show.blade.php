@extends('layouts.app')

@section('title', 'Forma de Pagamento - ' . $formaPagamento->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    {{ $formaPagamento->nome }}
                </h1>
                <p class="text-gray-600 mt-2">Detalhes da forma de pagamento</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('forma-pagamento.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
                <a href="{{ route('forma-pagamento.edit', $formaPagamento) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <!-- Detalhes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações Principais</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->nome }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Modalidade</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($formaPagamento->modalidade->getColor() === 'green') bg-green-100 text-green-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'blue') bg-blue-100 text-blue-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'purple') bg-purple-100 text-purple-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'orange') bg-orange-100 text-orange-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'indigo') bg-indigo-100 text-indigo-800
                                    @elseif($formaPagamento->modalidade->getColor() === 'pink') bg-pink-100 text-pink-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="{{ $formaPagamento->getModalidadeIcon() }} mr-1"></i>
                                    {{ $formaPagamento->getModalidadeLabel() }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número de Parcelas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->numero_parcelas }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Intervalo entre Parcelas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->intercalo_parcelas }} dias</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Primeira Parcela</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->primeira_parcela }} dias</dd>
                        </div>
                        @if($formaPagamento->contaEmpresa)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Conta Bancária</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $formaPagamento->contaEmpresa->nome }}
                                @if($formaPagamento->contaEmpresa->banco)
                                    ({{ $formaPagamento->contaEmpresa->banco->nome_normalizado }})
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Taxas -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Taxas e Custos</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Taxa do Banco</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->getTaxaBancoFormatada() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Taxa da Operadora</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->getTaxaOperadoraFormatada() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Juros/Multa</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->getJurosMultaFormatado() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Juros/Mora</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->getJurosMoraFormatado() }}</dd>
                        </div>
                        <div class="md:col-span-2 border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Total de Taxas</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $formaPagamento->getTotalTaxasFormatado() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Disponível</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $formaPagamento->isDisponivel() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $formaPagamento->isDisponivel() ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Confirmação Automática</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $formaPagamento->isConfirmacaoAutomatica() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $formaPagamento->isConfirmacaoAutomatica() ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Gerar Boleto</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $formaPagamento->isGerarBoleto() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $formaPagamento->isGerarBoleto() ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Permitir Exclusão</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $formaPagamento->isPermiteDeletar() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $formaPagamento->isPermiteDeletar() ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ações</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button onclick="confirmarToggleDisponibilidade('{{ $formaPagamento->id }}', '{{ $formaPagamento->nome }}', {{ $formaPagamento->isDisponivel() ? 'true' : 'false' }})"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-toggle-{{ $formaPagamento->isDisponivel() ? 'on' : 'off' }} mr-2"></i>
                            {{ $formaPagamento->isDisponivel() ? 'Desativar' : 'Ativar' }}
                        </button>

                        @if($formaPagamento->isPermiteDeletar())
                            <button onclick="confirmarExclusao('{{ $formaPagamento->id }}', '{{ $formaPagamento->nome }}')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>
                                Excluir
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações do Sistema</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->criado_em->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $formaPagamento->atualizado_em->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
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
</script>
@endsection
