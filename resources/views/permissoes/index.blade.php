@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Permissões do Sistema</h2>
                        <p class="text-gray-600">Gerencie as permissões disponíveis no sistema</p>
                    </div>
                    <a href="{{ route('grupos.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar para Grupos
                    </a>
                </div>

                <!-- Permissions by Module -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($permissoes as $modulo => $permissoesModulo)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 capitalize">
                                <i class="fas fa-folder mr-2"></i>
                                {{ str_replace('_', ' ', $modulo) }}
                            </h3>
                            <div class="space-y-2">
                                @foreach($permissoesModulo as $permissao)
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex items-center">
                                            <i class="fas fa-key text-blue-500 mr-3"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $permissao->nome }}
                                                </div>
                                                @if($permissao->descricao)
                                                    <div class="text-xs text-gray-500">
                                                        {{ $permissao->descricao }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $permissao->acao }}
                                            </span>
                                            @if($permissao->ativo)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Ativa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Inativa
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












