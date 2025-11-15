@extends('layouts.app')

@section('title', 'Contratação Realizada com Sucesso')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Card de Sucesso -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Ícone de Sucesso -->
            <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Contratação Realizada com Sucesso!
            </h1>

            <!-- Mensagem -->
            <p class="text-lg text-gray-600 mb-2">
                Parabéns! Seu plano <strong>{{ $plano->nome }}</strong> foi ativado com sucesso.
            </p>
            <p class="text-gray-600 mb-8">
                Você já pode começar a usar todas as funcionalidades disponíveis no seu plano.
            </p>

            <!-- Informações do Plano -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Contratação</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Plano:</span>
                        <span class="font-medium text-gray-900">{{ $plano->nome }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Período:</span>
                        <span class="font-medium text-gray-900">{{ $periodo->getLabel() }}</span>
                    </div>
                    @if(isset($usuariosExtras) && $usuariosExtras > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Usuários Extras:</span>
                        <span class="font-medium text-gray-900">{{ $usuariosExtras }}</span>
                    </div>
                    @endif
                    @if(isset($empresasExtras) && $empresasExtras > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Empresas Extras:</span>
                        <span class="font-medium text-gray-900">{{ $empresasExtras }}</span>
                    </div>
                    @endif
                    @if(isset($aplicativos) && $aplicativos->count() > 0)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600">Aplicativos:</span>
                        <div class="text-right">
                            @foreach($aplicativos as $aplicativo)
                                <div class="font-medium text-gray-900">{{ $aplicativo->nome }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(isset($valorTotal))
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-lg font-semibold text-gray-900">Valor Total:</span>
                        <span class="text-2xl font-bold text-green-600">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir para o Dashboard
                </a>
                <a href="{{ route('planos.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Ver Meus Planos
                </a>
            </div>

            <!-- Mensagem Adicional -->
            <p class="text-sm text-gray-500 mt-8">
                Um email de confirmação foi enviado para você com todos os detalhes da sua contratação.
            </p>
        </div>
    </div>
</div>
@endsection

