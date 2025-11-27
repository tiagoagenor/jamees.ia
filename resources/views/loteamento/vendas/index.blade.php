@extends('layouts.app')

@section('title', 'Vendas de Lotes')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-shopping-cart text-green-600 mr-3"></i>
                    Vendas de Lotes
                </h1>
                <p class="text-gray-600 mt-2">Visualize todas as vendas de lotes realizadas</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('loteamentos.vendas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="empreendimento_id" class="block text-sm font-medium text-gray-700 mb-2">Empreendimento</label>
                <select name="empreendimento_id" id="empreendimento_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos</option>
                    @foreach($empreendimentos as $empreendimento)
                        <option value="{{ $empreendimento->id }}" {{ request('empreendimento_id') == $empreendimento->id ? 'selected' : '' }}>
                            {{ $empreendimento->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Vendas -->
    @if($vendas->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empreendimento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quadra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Venda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vendas as $venda)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $venda->nome ?? 'Lote #' . substr($venda->id, 0, 8) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $venda->empreendimento->nome ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $venda->quadra->nome ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $venda->cliente->nome ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    R$ {{ number_format($venda->valor ?? 0, 2, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $venda->criado_em ? $venda->criado_em->format('d/m/Y') : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('loteamentos.mapa.view', $venda->empreendimento_id) }}" 
                                   class="text-green-600 hover:text-green-900 mr-4">
                                    <i class="fas fa-map mr-1"></i>Ver Mapa
                                </a>
                                <a href="{{ route('contas-a-receber.index') }}?entidade_tipo=5&entidade_id={{ $venda->id }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-4">
                                    <i class="fas fa-receipt mr-1"></i>Ver Parcelas
                                </a>
                                <a href="{{ route('loteamentos.vendas.parcelas', $venda->id) }}" 
                                   class="text-purple-600 hover:text-purple-900">
                                    <i class="fas fa-list-alt mr-1"></i>Parcelas
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $vendas->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhuma venda encontrada</h3>
            <p class="text-gray-600">Não há vendas de lotes registradas no momento.</p>
        </div>
    @endif
</div>
@endsection

