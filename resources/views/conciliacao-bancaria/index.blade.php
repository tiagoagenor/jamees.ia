@extends('layouts.app')

@section('title', 'Conciliação Bancária')
@section('page-title', 'Conciliação Bancária')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-balance-scale text-blue-600 mr-3"></i>
                Conciliação Bancária
            </h1>
            <p class="text-gray-600 mt-2">Gerencie as conciliações bancárias da sua empresa</p>
        </div>
        <a href="{{ route('conciliacao-bancaria.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>
            Nova Conciliação
        </a>
    </div>

    <!-- Lista de Conciliações -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transações</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado por</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conciliacoes as $conciliacao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $conciliacao->contaEmpresa->nome }}</div>
                                <div class="text-sm text-gray-500">{{ $conciliacao->contaEmpresa->banco->nome ?? ($conciliacao->contaEmpresa->banco->codigo ?? 'N/A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($conciliacao->data_inicio)->format('d/m/Y') }} até
                                    {{ \Carbon\Carbon::parse($conciliacao->data_fim)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $conciliacao->getTotalConciliadas() }} / {{ $conciliacao->getTotalTransacoes() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($conciliacao->conciliado)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Conciliado
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $conciliacao->usuario->nome }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('conciliacao-bancaria.show', $conciliacao) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nenhuma conciliação encontrada. <a href="{{ route('conciliacao-bancaria.create') }}" class="text-blue-600 hover:text-blue-800">Criar nova conciliação</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($conciliacoes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $conciliacoes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
