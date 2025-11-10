@extends('layouts.app')

@section('title', 'Reservar Lote - ' . $lote->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Reservar Lote</h1>
            <p class="text-gray-600">Empreendimento: <strong>{{ $empreendimento->nome }}</strong></p>
            <p class="text-gray-600">Lote: <strong>{{ $lote->nome }}</strong></p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Informações Principais -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações Básicas -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
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
                            <dd class="text-sm text-gray-900">{{ $empreendimento->nome ?? '-' }}</dd>
                        </div>
                        @if($lote->quadra)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Quadra</dt>
                            <dd class="text-sm text-gray-900">{{ $lote->quadra->nome ?? '-' }}</dd>
                        </div>
                        @endif
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
                        @if($lote->cliente)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cliente Atual</dt>
                            <dd class="text-sm text-gray-900">{{ $lote->cliente->nome }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Dimensões -->
                @if($lote->frente || $lote->fundo || $lote->lateral_direita || $lote->lateral_esquerda)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-ruler-combined text-green-600 mr-2"></i>
                        Dimensões
                    </h2>
                    <dl class="grid grid-cols-2 gap-3">
                        @if($lote->frente)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Frente</dt>
                            <dd class="text-sm text-gray-900">
                                {{ number_format($lote->frente, 2, ',', '.') }} m
                            </dd>
                        </div>
                        @endif
                        @if($lote->fundo)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fundo</dt>
                            <dd class="text-sm text-gray-900">
                                {{ number_format($lote->fundo, 2, ',', '.') }} m
                            </dd>
                        </div>
                        @endif
                        @if($lote->lateral_direita)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Lateral Direita</dt>
                            <dd class="text-sm text-gray-900">
                                {{ number_format($lote->lateral_direita, 2, ',', '.') }} m
                            </dd>
                        </div>
                        @endif
                        @if($lote->lateral_esquerda)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Lateral Esquerda</dt>
                            <dd class="text-sm text-gray-900">
                                {{ number_format($lote->lateral_esquerda, 2, ',', '.') }} m
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
                @endif

                <!-- Valores -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-dollar-sign text-emerald-600 mr-2"></i>
                        Valores
                    </h2>
                    <dl class="space-y-3">
                        @if($lote->m2)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Área (m²)</dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ number_format($lote->m2, 2, ',', '.') }} m²
                            </dd>
                        </div>
                        @endif
                        @if($lote->valor_m2)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Valor por m²</dt>
                            <dd class="text-sm text-gray-900">
                                R$ {{ number_format($lote->valor_m2, 2, ',', '.') }}
                            </dd>
                        </div>
                        @endif
                        @if($lote->valor)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
                            <dd class="text-lg font-bold text-emerald-600">
                                R$ {{ number_format($lote->valor, 2, ',', '.') }}
                            </dd>
                        </div>
                        @endif
                        @if($lote->m2_tipo)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Cálculo de Área</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $lote->m2_tipo == 1 ? 'Calculado Automaticamente' : 'Manual' }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Observações -->
                @if($lote->observacao)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-purple-600 mr-2"></i>
                        Observações
                    </h2>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $lote->observacao }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar - Formulário de Reserva -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Reservar Lote</h2>
                    
                    <form action="{{ route('loteamentos.lote.salvar-reserva', [$empreendimento->id, $lote->id]) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Cliente <span class="text-red-500">*</span>
                            </label>
                            <select name="cliente_id" id="cliente_id" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cliente_id') border-red-500 @enderror">
                                <option value="">Selecione um cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $lote->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                        @if($cliente->documento)
                                            - {{ $cliente->documento_formatado }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">
                                Comentário
                            </label>
                            <textarea name="comentario" id="comentario" rows="10"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('comentario') border-red-500 @enderror"
                                      style="min-height: 200px; resize: vertical;"
                                      placeholder="Adicione um comentário sobre a reserva...">{{ old('comentario') }}</textarea>
                            @error('comentario')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Reservar Lote
                            </button>
                            <a href="{{ route('loteamentos.mapa.view', $empreendimento->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Voltar ao Mapa
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($comentarios->count() > 0)
            <div class="mt-8 border-t pt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Comentários do Lote</h2>
                <div class="space-y-4">
                    @foreach($comentarios as $comentario)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        @if($comentario->usuario)
                                            {{ $comentario->usuario->nome }}
                                        @else
                                            Sistema
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $comentario->criado_em->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comentario->comentario }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
