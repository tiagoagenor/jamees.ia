@extends('layouts.app')

@section('content')
<div>
    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $titulo }}</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.edit' : 'contas-a-receber.edit', $movimentacao) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
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
                                <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->descricao }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($movimentacao->tipo->value === 1) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        <i class="{{ $movimentacao->getTipoIcon() }} mr-1"></i>
                                        {{ $movimentacao->getTipoLabel() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Situação</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($movimentacao->situacao->value === 1) bg-yellow-100 text-yellow-800
                                        @elseif($movimentacao->situacao->value === 2) bg-green-100 text-green-800
                                        @elseif($movimentacao->situacao->value === 3) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="{{ $movimentacao->getSituacaoIcon() }} mr-1"></i>
                                        {{ $movimentacao->getSituacaoLabel() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data de Vencimento</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $movimentacao->vencimento->format('d/m/Y') }}
                                    @if($movimentacao->isVencidaPorData())
                                        <span class="ml-2 text-red-600 text-xs font-medium">VENCIDA</span>
                                    @endif
                                </dd>
                            </div>
                            @if($movimentacao->data_compensacao)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data de Compensação</dt>
                                    <dd class="text-sm text-gray-900">{{ $movimentacao->data_compensacao->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Informações Financeiras -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Informações Financeiras
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor</dt>
                                <dd class="text-sm text-gray-900">R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</dd>
                            </div>
                            @if($movimentacao->juros)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Juros</dt>
                                    <dd class="text-sm text-gray-900">R$ {{ number_format($movimentacao->juros, 2, ',', '.') }}</dd>
                                </div>
                            @endif
                            @if($movimentacao->desconto)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Desconto</dt>
                                    <dd class="text-sm text-gray-900">R$ {{ number_format($movimentacao->desconto, 2, ',', '.') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
                                <dd class="text-lg font-bold text-gray-900">R$ {{ number_format($movimentacao->valor_total, 2, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Informações de Conta -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-chart-line mr-2"></i>
                            Informações de Conta
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plano de Conta</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->planoConta->nome }}</dd>
                            </div>
                            @if($movimentacao->centroCusto)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Centro de Custo</dt>
                                    <dd class="text-sm text-gray-900">{{ $movimentacao->centroCusto->nome }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Forma de Pagamento</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->formaPagamento->nome }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Conta Empresa</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->contaEmpresa->nome }} - {{ $movimentacao->contaEmpresa->banco->nome_normalizado ?? 'Banco não encontrado' }}</dd>
                            </div>
                            @if($movimentacao->entidade)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ $tipo == 1 ? 'Fornecedor' : 'Cliente' }}</dt>
                                    <dd class="text-sm text-gray-900">{{ $movimentacao->entidade->nome }}</dd>
                                </div>
                            @endif
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
                                <dd class="text-sm text-gray-900 font-mono">{{ $movimentacao->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->empresa->nome_fantasia ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->criado_em->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                                <dd class="text-sm text-gray-900">{{ $movimentacao->atualizado_em->format('d/m/Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Parcelas (se houver parcelamento) -->
                @if($movimentacao->parcela_codigo && $parcelas->count() > 0)
                    <div class="mt-6 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-list-ol mr-2"></i>
                            Parcelas ({{ $movimentacao->numero_parcela ?? 1 }}/{{ $totalParcelas }})
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Parcela</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Descrição</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Data de Vencimento</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Valor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Situação</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($parcelas as $parcela)
                                        <tr class="hover:bg-gray-50 {{ $parcela->id === $movimentacao->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                <span class="font-medium">{{ $parcela->numero_parcela ?? 1 }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $parcela->descricao }}
                                                @if($parcela->id === $movimentacao->id)
                                                    <span class="ml-2 text-xs text-blue-600 font-medium">(Atual)</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $parcela->vencimento->format('d/m/Y') }}
                                                @if($parcela->isVencidaPorData())
                                                    <span class="ml-2 text-red-600 text-xs font-medium">VENCIDA</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                R$ {{ number_format($parcela->valor_total, 2, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($parcela->situacao->value === 1) bg-yellow-100 text-yellow-800
                                                    @elseif($parcela->situacao->value === 2) bg-green-100 text-green-800
                                                    @elseif($parcela->situacao->value === 3) bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    <i class="{{ $parcela->getSituacaoIcon() }} mr-1"></i>
                                                    {{ $parcela->getSituacaoLabel() }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route($tipo == 1 ? 'contas-a-pagar.show' : 'contas-a-receber.show', $parcela) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i>
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Observações -->
                @if($movimentacao->observacao || $movimentacao->informacao_complementar)
                    <div class="mt-6 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-sticky-note mr-2"></i>
                            Observações
                        </h3>
                        @if($movimentacao->observacao)
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Observação</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $movimentacao->observacao }}</dd>
                            </div>
                        @endif
                        @if($movimentacao->informacao_complementar)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">Informação Complementar</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $movimentacao->informacao_complementar }}</dd>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Ações -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <button onclick="toggleStatus('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}', {{ $movimentacao->situacao->value }})"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-toggle-{{ $movimentacao->isPendente() ? 'on' : 'off' }} mr-2"></i>
                                {{ $movimentacao->isPendente() ? 'Marcar como Paga' : 'Marcar como Pendente' }}
                            </button>
                            @if($movimentacao->estaConciliado())
                                <button disabled
                                        class="bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded-md text-sm font-medium"
                                        title="Não é possível excluir uma movimentação conciliada">
                                    <i class="fas fa-trash mr-2"></i>
                                    Excluir (conciliado)
                                </button>
                            @else
                                <button onclick="confirmarExclusaoSweetAlert('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i>
                                    Excluir
                                </button>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            Última atualização: {{ $movimentacao->atualizado_em->diffForHumans() }}
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
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}`;

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
    const action = statusAtual === 1 ? 'pagar' : 'marcar como pendente';
    const icon = statusAtual === 1 ? 'warning' : 'success';
    const confirmColor = statusAtual === 1 ? '#d33' : '#28a745';

    Swal.fire({
        title: `Confirmar ${statusAtual === 1 ? 'Pagamento' : 'Marcar como Pendente'}`,
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
            form.action = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}/toggle-status`;

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
