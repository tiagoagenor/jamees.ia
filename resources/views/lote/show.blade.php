@extends('layouts.app')

@section('title', 'Detalhes do Lote')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-map-marked-alt text-blue-600 mr-3"></i>
                    {{ $lote->nome ?? 'N/A' }}
                </h1>
                <p class="mt-2 text-gray-600">Detalhes do lote</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('lotes.edit', $lote->id) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('lotes.index', $lote->empreendimento_id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar para Lotes
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Informações Básicas
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome do Lote</dt>
                        <dd class="text-sm text-gray-900">{{ $lote->nome ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Empreendimento</dt>
                        <dd class="text-sm text-gray-900">
                            <a href="{{ route('empreendimentos.index') }}" class="text-blue-600 hover:text-blue-800">
                                {{ $lote->empreendimento->nome ?? '-' }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quadra</dt>
                        <dd class="text-sm text-gray-900">{{ $lote->quadra->nome ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm">
                            @if($lote->status)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $lote->status->cor }}">
                                    {{ $lote->status->nome }}
                                </span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Dimensões -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-ruler-combined text-green-600 mr-2"></i>
                    Dimensões
                </h2>
                <dl class="grid grid-cols-2 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Frente</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->frente ? number_format($lote->frente, 2, ',', '.') . ' m' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fundo</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->fundo ? number_format($lote->fundo, 2, ',', '.') . ' m' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lateral Direita</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->lateral_direita ? number_format($lote->lateral_direita, 2, ',', '.') . ' m' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lateral Esquerda</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->lateral_esquerda ? number_format($lote->lateral_esquerda, 2, ',', '.') . ' m' : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Valores -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-dollar-sign text-emerald-600 mr-2"></i>
                    Valores
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Área (m²)</dt>
                        <dd class="text-sm font-semibold text-gray-900">
                            {{ $lote->m2 ? number_format($lote->m2, 2, ',', '.') . ' m²' : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Valor por m²</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->valor_m2 ? 'R$ ' . number_format($lote->valor_m2, 2, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
                        <dd class="text-lg font-bold text-emerald-600">
                            {{ $lote->valor ? 'R$ ' . number_format($lote->valor, 2, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Cálculo de Área</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $lote->m2_tipo == 1 ? 'Calculado Automaticamente' : 'Manual' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Observações -->
            @if($lote->observacao)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-purple-600 mr-2"></i>
                    Observações
                </h2>
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $lote->observacao }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Informações do Empreendimento -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Empreendimento</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nome</p>
                        <p class="text-sm font-medium">
                            <a href="{{ route('empreendimentos.index') }}" class="text-blue-600 hover:text-blue-800">
                                {{ $lote->empreendimento->nome ?? '-' }}
                            </a>
                        </p>
                    </div>
                    @if($lote->empreendimento && $lote->empreendimento->valor_m2)
                    <div>
                        <p class="text-sm text-gray-500">Valor/m² Padrão</p>
                        <p class="text-sm font-medium">R$ {{ number_format($lote->empreendimento->valor_m2, 2, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Adicionais</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Data de Criação</p>
                        <p class="text-sm font-medium">
                            {{ $lote->criado_em ? $lote->criado_em->format('d/m/Y H:i') : '-' }}
                        </p>
                    </div>
                    @if($lote->atualizado_em)
                    <div>
                        <p class="text-sm text-gray-500">Última Atualização</p>
                        <p class="text-sm font-medium">
                            {{ $lote->atualizado_em->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

