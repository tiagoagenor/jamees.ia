@extends('layouts.app')

@section('title', $ideia->titulo . ' - Portal de Ideias')

@section('content')
<style>
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.25rem;
        color: white;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-em-aberto { background: #dbeafe; color: #1e40af; }
    .status-em-analise { background: #fef3c7; color: #92400e; }
    .status-em-desenvolvimento { background: #e9d5ff; color: #6b21a8; }
    .status-concluido { background: #d1fae5; color: #065f46; }
    .status-concluído { background: #d1fae5; color: #065f46; }
    .status-sem-previsao { background: #fee2e2; color: #991b1b; }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li><a href="{{ route('ideias.index') }}" class="hover:text-blue-600">Portal de Ideias</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-900 font-medium">{{ $ideia->titulo }}</li>
        </ol>
    </nav>

    <!-- Botão Voltar -->
    <div class="mb-6">
        <a href="{{ route('ideias.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar para Portal de Ideias
        </a>
    </div>

    <!-- Card Principal -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
        <!-- Header -->
        <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-200">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($ideia->usuario->imagem)
                    <div class="avatar-circle">
                        <img src="{{ asset('storage/' . $ideia->usuario->imagem) }}" alt="{{ $ideia->usuario->nome }}">
                    </div>
                @else
                    <div class="avatar-circle">
                        {{ strtoupper(substr($ideia->usuario->nome, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- Informações -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $ideia->titulo }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                    <span class="font-medium">{{ $ideia->usuario->nome }}</span>
                    <span>-</span>
                    <span>{{ $ideia->categoria }}</span>
                    <span>-</span>
                    <span>{{ $ideia->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="status-badge status-{{ strtolower(str_replace([' ', 'é'], ['-', 'e'], $ideia->status_label)) }}">
                        {{ $ideia->status_label }}
                    </span>
                    <button onclick="votarIdeia('{{ $ideia->id }}')" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white hover:opacity-80 transition-colors"
                            id="voto-btn-{{ $ideia->id }}">
                        <i class="fas fa-thumbs-up mr-2"></i>
                        @if($ideia->votos > 0)
                            <span id="votos-count-{{ $ideia->id }}">{{ $ideia->votos }}</span>
                        @else
                            <span id="votos-count-{{ $ideia->id }}" class="hidden">0</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Descrição -->
        <div class="prose max-w-none mb-6">
            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ideia->descricao }}</p>
        </div>
    </div>

    <!-- Comentários -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Comentários ({{ $ideia->comentarios->count() }})</h2>

        <!-- Formulário de Comentário -->
        <form action="{{ route('ideias.comentar', $ideia) }}" method="POST" class="mb-8">
            @csrf
            <div class="flex gap-4">
                <div class="flex-1">
                    <textarea name="comentario" 
                              rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Escreva seu comentário..."
                              required></textarea>
                </div>
                <div class="flex-shrink-0">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 h-full">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Comentar
                    </button>
                </div>
            </div>
        </form>

        <!-- Lista de Comentários -->
        <div class="space-y-6">
            @forelse($ideia->comentarios as $comentario)
                <div class="flex gap-4 pb-6 border-b border-gray-200 last:border-0">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($comentario->usuario->imagem)
                            <div class="avatar-circle">
                                <img src="{{ asset('storage/' . $comentario->usuario->imagem) }}" alt="{{ $comentario->usuario->nome }}">
                            </div>
                        @else
                            <div class="avatar-circle">
                                {{ strtoupper(substr($comentario->usuario->nome, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Conteúdo -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-semibold text-gray-900">{{ $comentario->usuario->nome }}</span>
                            <span class="text-sm text-gray-500">{{ $comentario->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $comentario->comentario }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
            @endforelse
        </div>
    </div>
</div>

<script>
function votarIdeia(ideiaId) {
    fetch(`/ideias/${ideiaId}/votar`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const count = document.getElementById(`votos-count-${ideiaId}`);
            
            if (data.votos > 0) {
                count.textContent = data.votos;
                count.classList.remove('hidden');
            } else {
                count.classList.add('hidden');
            }
        }
    })
    .catch(error => {
        console.error('Erro ao votar:', error);
    });
}
</script>
@endsection

