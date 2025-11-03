@extends('layouts.app')

@section('title', 'Plano Necessário')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Ícone -->
            <div class="mx-auto h-24 w-24 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <!-- Título -->
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Acesso Restrito
            </h2>

            <!-- Descrição -->
            <p class="mt-2 text-sm text-gray-600">
                Você precisa ativar um plano para acessar esta funcionalidade.
            </p>
        </div>

        <!-- Informações do Plano -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Escolha seu plano</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Plano Base</h4>
                        <p class="text-xs text-gray-500">Para pequenas empresas</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">R$ 29,90/mês</div>
                        <div class="text-xs text-gray-500">5 usuários</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Plano Premium</h4>
                        <p class="text-xs text-gray-500">Para empresas em crescimento</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">R$ 59,90/mês</div>
                        <div class="text-xs text-gray-500">15 usuários</div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Plano Master</h4>
                        <p class="text-xs text-gray-500">Para grandes empresas</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">R$ 99,90/mês</div>
                        <div class="text-xs text-gray-500">50 usuários</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="space-y-3">
            <a href="{{ route('planos.index') }}"
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Ver Todos os Planos
            </a>

            <a href="{{ route('profile.edit') }}"
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Meu Perfil
            </a>
        </div>

        <!-- Informações Adicionais -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                Precisa de ajuda? Entre em contato conosco.
            </p>
        </div>
    </div>
</div>
@endsection
