@extends('layouts.app')

@section('title', 'Meu Plano')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Meu Plano</h1>
        <p class="mt-2 text-gray-600">Gerencie seu plano e acompanhe o uso do sistema</p>
    </div>

    <!-- Plano Atual -->
    @if($planoAtual)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Plano Atual</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($planoAtual->status->value === 'teste') bg-blue-100 text-blue-800
                    @elseif($planoAtual->status->value === 'ativo') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $planoAtual->status->getLabel() }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $planoAtual->plano->nome }}</h3>
                    <p class="text-gray-600">{{ $planoAtual->plano->descricao }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Período</h4>
                    <p class="text-lg text-gray-900">{{ $planoAtual->periodo->getLabel() }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Vencimento</h4>
                    <p class="text-lg text-gray-900">{{ $planoAtual->data_fim->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $planoAtual->diasRestantes() }} dias restantes</p>
                </div>
            </div>

            @if($planoAtual->valor_pago)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Valor pago:</span>
                        <span class="text-lg font-semibold text-gray-900">
                            R$ {{ number_format($planoAtual->valor_pago, 2, ',', '.') }}
                        </span>
                    </div>
                    @if($planoAtual->desconto_aplicado > 0)
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-sm text-gray-500">Desconto aplicado:</span>
                            <span class="text-sm text-green-600">{{ $planoAtual->desconto_aplicado }}%</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-6 flex space-x-4">
                @if($planoAtual->status->value === 'teste')
                    <a href="{{ route('planos.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Escolher Plano
                    </a>
                @else
                    <a href="{{ route('planos.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Alterar Plano
                    </a>
                    <form method="POST" action="{{ route('planos.cancelar') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                onclick="return confirm('Tem certeza que deseja cancelar seu plano?')">
                            Cancelar Plano
                        </button>
                    </form>
                @endif
                <a href="{{ route('planos.historico') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Ver Histórico
                </a>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-yellow-800">Nenhum plano ativo</h3>
                    <p class="text-yellow-700">Você precisa ativar um plano para continuar usando o sistema.</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('planos.index') }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Escolher Plano
                </a>
            </div>
        </div>
    @endif

    <!-- Planos Disponíveis -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Planos Disponíveis</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($planos as $plano)
                <div class="bg-white rounded-lg shadow-md p-6 border-2
                    @if($planoAtual && $planoAtual->plano_id === $plano->id) border-blue-500
                    @else border-gray-200
                    @endif">

                    @if($planoAtual && $planoAtual->plano_id === $plano->id)
                        <div class="bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full inline-block mb-4">
                            Plano Atual
                        </div>
                    @endif

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $plano->nome }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $plano->descricao }}</p>

                    <!-- Preços -->
                    <div class="mb-4">
                        <div class="text-2xl font-bold text-gray-900">
                            R$ {{ number_format($plano->preco_mensal, 2, ',', '.') }}
                            <span class="text-sm font-normal text-gray-500">/mês</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Anual: R$ {{ number_format($plano->preco_anual, 2, ',', '.') }}
                            <span class="text-green-600">(20% desconto)</span>
                        </div>
                    </div>

                    <!-- Limites -->
                    <div class="mb-4 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Usuários:</span>
                            <span>{{ $plano->limite_usuarios ?? 'Ilimitado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Empresas:</span>
                            <span>{{ $plano->limite_empresas ?? 'Ilimitado' }}</span>
                        </div>
                    </div>

                    <!-- Funcionalidades -->
                    @if($plano->funcionalidades)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Funcionalidades:</h4>
                            <ul class="text-xs text-gray-600 space-y-1">
                                @foreach(array_slice($plano->funcionalidades, 0, 3) as $funcionalidade)
                                    <li class="flex items-center">
                                        <svg class="h-3 w-3 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $funcionalidade }}
                                    </li>
                                @endforeach
                                @if(count($plano->funcionalidades) > 3)
                                    <li class="text-gray-500">+{{ count($plano->funcionalidades) - 3 }} mais...</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <!-- Botão de ação -->
                    <div class="mt-4">
                        @if($planoAtual && $planoAtual->plano_id === $plano->id)
                            <button disabled
                                    class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                Plano Atual
                            </button>
                        @else
                            <a href="{{ route('planos.show', $plano) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                                @if($planoAtual && $planoAtual->status->value === 'teste')
                                    Ativar Plano
                                @else
                                    Escolher Plano
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Importantes</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Período de Teste</h4>
                <p>Novos clientes recebem 15 dias gratuitos para testar o sistema com o Plano Base.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Descontos</h4>
                <p>Planos anuais têm 20% de desconto, semestrais 10% e trimestrais 5%.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Alteração de Plano</h4>
                <p>Você pode alterar seu plano a qualquer momento sem custos adicionais.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Suporte</h4>
                <p>Entre em contato conosco para dúvidas sobre planos ou funcionalidades.</p>
            </div>
        </div>
    </div>
</div>
@endsection
