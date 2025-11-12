@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $grupo->nome }}</h2>
                        <p class="text-gray-600">{{ $grupo->descricao ?? 'Sem descrição' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('grupos.edit', $grupo) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                        <a href="{{ route('grupos.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <!-- Group Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Tipo</h3>
                                @if($grupo->administrativo)
                                    <p class="text-sm text-red-600 font-medium">Administrativo</p>
                                @else
                                    <p class="text-sm text-blue-600 font-medium">Usuário</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Status</h3>
                                @if($grupo->ativo)
                                    <p class="text-sm text-green-600 font-medium">Ativo</p>
                                @else
                                    <p class="text-sm text-red-600 font-medium">Inativo</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-key text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Permissões</h3>
                                <p class="text-sm text-gray-600">{{ $grupo->permissoes->count() }} permissões</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissões do Grupo</h3>

                    @if($grupo->administrativo)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-crown text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Acesso Administrativo
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Este grupo possui acesso total a todas as funcionalidades do sistema.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @if($grupo->permissoes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($grupo->permissoes->groupBy('modulo') as $modulo => $permissoesModulo)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <h4 class="font-medium text-gray-900 mb-3 capitalize">
                                            {{ str_replace('_', ' ', $modulo) }}
                                        </h4>
                                        <div class="space-y-2">
                                            @foreach($permissoesModulo as $permissao)
                                                <div class="flex items-center space-x-2 p-2 bg-green-50 rounded">
                                                    <i class="fas fa-check text-green-600"></i>
                                                    <span class="text-sm text-gray-700">{{ $permissao->nome }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-lock text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma Permissão</h3>
                                <p class="text-gray-600">Este grupo não possui permissões configuradas.</p>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Users in Group -->
                <div class="bg-gray-50 p-6 rounded-lg mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Usuários no Grupo</h3>

                    @if($grupo->usuarios->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nome
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($grupo->usuarios as $usuario)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $usuario->nome }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $usuario->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($usuario->status->value === 1)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Ativo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Inativo
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum Usuário</h3>
                            <p class="text-gray-600">Este grupo não possui usuários vinculados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection







