@extends('layouts.app')

@section('title', 'Status de Lotes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-tags text-blue-600 mr-3"></i>
                    Status de Lotes
                </h1>
                <p class="text-gray-600 mt-2">Gerencie os status disponíveis para os lotes</p>
            </div>
            <a href="{{ route('lote-status.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Novo Status
            </a>
        </div>
    </div>

    <!-- Lista de Status -->
    @if($status->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($status as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $item->cor }}"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $item->nome }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->tipo == 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->tipo == 1 ? 'Disponível/Negociação' : 'Vendido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded border border-gray-300" style="background-color: {{ $item->cor }}"></div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $item->cor }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('lote-status.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('lote-status.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar este status?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-tags text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum status encontrado</h3>
            <p class="text-gray-600 mb-6">Os status serão criados automaticamente ao registrar uma empresa</p>
            <a href="{{ route('lote-status.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Criar Status
            </a>
        </div>
    @endif
</div>
@endsection

