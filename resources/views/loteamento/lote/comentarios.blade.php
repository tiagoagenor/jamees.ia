@extends('layouts.app')

@section('title', 'Comentários - ' . $lote->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Comentários do Lote</h1>
            <p class="text-gray-600">Empreendimento: <strong>{{ $empreendimento->nome }}</strong></p>
            <p class="text-gray-600">Lote: <strong>{{ $lote->nome }}</strong></p>
            @if(isset($cliente))
                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-blue-800">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrando comentários do cliente: <strong>{{ $cliente->nome }}</strong>
                    </p>
                </div>
            @elseif($lote->cliente)
                <p class="text-gray-600">Cliente Atual: <strong>{{ $lote->cliente->nome }}</strong></p>
            @endif
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
            <!-- Informações do Lote -->
            <div class="lg:col-span-1">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Informações do Lote</h2>
                    <dl class="space-y-2 text-sm">
                        @if($lote->valor)
                            <div>
                                <dt class="text-gray-500">Valor</dt>
                                <dd class="font-semibold text-gray-900">R$ {{ number_format($lote->valor, 2, ',', '.') }}</dd>
                            </div>
                        @endif
                        @if($lote->m2)
                            <div>
                                <dt class="text-gray-500">Área</dt>
                                <dd class="font-semibold text-gray-900">{{ number_format($lote->m2, 2, ',', '.') }} m²</dd>
                            </div>
                        @endif
                        @if($lote->status)
                            <div>
                                <dt class="text-gray-500">Status</dt>
                                <dd>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $lote->status->cor }}">
                                        {{ $lote->status->nome }}
                                    </span>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Formulário e Comentários -->
            <div class="lg:col-span-2">
                <!-- Formulário para adicionar comentário -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Comentário</h2>
                    <form action="{{ route('loteamentos.lote.salvar-comentario', [$empreendimento->id, $lote->id]) }}" method="POST">
                        @csrf
                        @if(isset($cliente))
                            <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                        @endif
                        <div class="mb-4">
                            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">
                                Comentário <span class="text-red-500">*</span>
                            </label>
                            <textarea name="comentario" id="comentario" rows="8" required
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('comentario') border-red-500 @enderror"
                                      style="min-height: 200px; resize: vertical;"
                                      placeholder="Digite seu comentário...">{{ old('comentario') }}</textarea>
                            @error('comentario')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Adicionar Comentário
                        </button>
                    </form>
                </div>

                <!-- Lista de Comentários -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Comentários ({{ $comentarios->count() }})
                    </h2>
                    
                    @if($comentarios->count() > 0)
                        <div class="space-y-4">
                            @foreach($comentarios as $comentario)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="text-sm font-medium text-gray-800">
                                                    @if($comentario->usuario)
                                                        <span class="text-blue-600">{{ $comentario->usuario->nome }}</span>
                                                    @else
                                                        <span class="text-gray-500">Sistema</span>
                                                    @endif
                                                </p>
                                                @if($comentario->cliente)
                                                    <span class="text-xs text-gray-400">•</span>
                                                    <span class="text-xs text-gray-600">Cliente: {{ $comentario->cliente->nome }}</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ $comentario->criado_em->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comentario->comentario }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            @if(isset($cliente))
                <a href="{{ route('lotes.show', $lote->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar ao Lote
                </a>
            @endif
            <a href="{{ route('loteamentos.mapa.view', $empreendimento->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar ao Mapa
            </a>
        </div>
    </div>
</div>
@endsection

