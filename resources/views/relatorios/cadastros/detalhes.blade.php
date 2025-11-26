@extends('layouts.app')

@section('title', 'Relatórios - Cadastros')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center mb-2">
                    <a href="{{ route('relatorios.cadastros') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                        Relatórios - {{ ucfirst($tipo) }}
                    </h1>
                </div>
                <p class="text-gray-600 mt-2">Visualize e exporte relatórios dos seus cadastros</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('relatorios.cadastros.detalhes', $tipo) }}" class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtros
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($tipo === 'clientes' || $tipo === 'fornecedores' || $tipo === 'transportadoras')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>Pessoa Física</option>
                        <option value="2" {{ request('tipo') == '2' ? 'selected' : '' }}>Pessoa Jurídica</option>
                    </select>
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                <input type="text" name="nome" value="{{ request('nome') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o nome">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                <input type="email" name="email" value="{{ request('email') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o e-mail">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                <input type="text" name="telefone" value="{{ request('telefone') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o telefone">
            </div>
            @if($tipo === 'funcionarios')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                    <input type="text" name="cidade" value="{{ request('cidade') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite a cidade">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <input type="text" name="estado" value="{{ request('estado') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o estado">
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @if($tipo === 'clientes' || $tipo === 'fornecedores' || $tipo === 'transportadoras')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Situação</label>
                    <select name="situacao" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="1" {{ request('situacao') == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ request('situacao') == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
            @endif
        </div>
        <div class="mt-4 flex justify-end space-x-3">
            <a href="{{ route('relatorios.cadastros.detalhes', $tipo) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Limpar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>
                Filtrar
            </button>
        </div>
    </form>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total de Registros</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalRegistros ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ativos</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">{{ $totalAtivos ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inativos</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">{{ $totalInativos ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Resultados -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Resultados
            </h2>
            <div class="flex space-x-2">
                @if(($tipo === 'clientes' && isset($clientes) && $clientes->count() > 0) || ($tipo === 'funcionarios' && isset($funcionarios) && $funcionarios->count() > 0) || ($tipo === 'fornecedores' && isset($fornecedores) && $fornecedores->count() > 0) || ($tipo === 'transportadoras' && isset($transportadoras) && $transportadoras->count() > 0))
                    <a href="{{ route('relatorios.cadastros.exportar-csv', array_merge(['tipo' => $tipo], request()->query())) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm inline-flex items-center">
                        <i class="fas fa-file-excel mr-2"></i>
                        Exportar Excel
                    </a>
                @endif
            </div>
        </div>
        @if($tipo === 'clientes' && isset($clientes) && $clientes->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando {{ $clientes->firstItem() }} até {{ $clientes->lastItem() }} de {{ $clientes->total() }} resultados
                </p>
            </div>
        @elseif($tipo === 'funcionarios' && isset($funcionarios) && $funcionarios->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando {{ $funcionarios->firstItem() }} até {{ $funcionarios->lastItem() }} de {{ $funcionarios->total() }} resultados
                </p>
            </div>
        @elseif($tipo === 'fornecedores' && isset($fornecedores) && $fornecedores->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando {{ $fornecedores->firstItem() }} até {{ $fornecedores->lastItem() }} de {{ $fornecedores->total() }} resultados
                </p>
            </div>
        @elseif($tipo === 'transportadoras' && isset($transportadoras) && $transportadoras->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm text-gray-600">
                    Mostrando {{ $transportadoras->firstItem() }} até {{ $transportadoras->lastItem() }} de {{ $transportadoras->total() }} resultados
                </p>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                        @if($tipo === 'funcionarios')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Situação</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Cadastro</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($tipo === 'clientes' && isset($clientes) && $clientes->count() > 0)
                        @foreach($clientes as $cliente)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $cliente->nome_completo }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cliente->documento_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cliente->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cliente->telefone_comercial_formatado ?? $cliente->celular_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cliente->status == 1)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @elseif($tipo === 'fornecedores' && isset($fornecedores) && $fornecedores->count() > 0)
                        @foreach($fornecedores as $fornecedor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $fornecedor->nome_completo }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $fornecedor->documento_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $fornecedor->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $fornecedor->telefone_comercial_formatado ?? $fornecedor->celular_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($fornecedor->status == 1)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $fornecedor->created_at ? $fornecedor->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @elseif($tipo === 'transportadoras' && isset($transportadoras) && $transportadoras->count() > 0)
                        @foreach($transportadoras as $transportadora)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $transportadora->nome_completo }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $transportadora->documento_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $transportadora->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $transportadora->telefone_comercial_formatado ?? $transportadora->celular_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transportadora->status == 1)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $transportadora->created_at ? $transportadora->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @elseif($tipo === 'funcionarios' && isset($funcionarios) && $funcionarios->count() > 0)
                        @foreach($funcionarios as $funcionario)
                            @php
                                $endereco = $funcionario->enderecos->first();
                                $cidade = $endereco ? ($endereco->cidade ?? 'N/A') : 'N/A';
                                $estado = $endereco ? ($endereco->estado ?? 'N/A') : 'N/A';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $funcionario->nome_completo }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $funcionario->documento_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $funcionario->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $funcionario->telefone_comercial_formatado ?? $funcionario->celular_formatado ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $cidade }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $estado }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($funcionario->status == 1)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $funcionario->created_at ? $funcionario->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ $tipo === 'funcionarios' ? '8' : '6' }}" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Nenhum resultado encontrado</p>
                                <p class="text-sm mt-2">Aplique os filtros para visualizar os relatórios</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if($tipo === 'clientes' && isset($clientes) && $clientes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $clientes->links() }}
            </div>
        @elseif($tipo === 'funcionarios' && isset($funcionarios) && $funcionarios->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $funcionarios->links() }}
            </div>
        @elseif($tipo === 'fornecedores' && isset($fornecedores) && $fornecedores->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $fornecedores->links() }}
            </div>
        @elseif($tipo === 'transportadoras' && isset($transportadoras) && $transportadoras->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transportadoras->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

