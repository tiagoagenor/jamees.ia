@extends('layouts.app')

@section('title', 'Portal de Ideias')

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

    .filter-item {
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: background 0.2s;
        border-radius: 0.375rem;
    }

    .filter-item:hover {
        background: #f3f4f6;
    }

    .filter-item.active {
        background: #e5e7eb;
        font-weight: 600;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-lightbulb text-blue-600 mr-3"></i>
                Portal de Ideias
            </h1>
            <p class="text-gray-600 mt-2">Compartilhe suas ideias, comente e vote nas que achar mais interessantes</p>
        </div>
        <div class="text-sm text-gray-600">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Início</a>
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <span class="text-gray-900 font-medium">Portal de Ideias</span>
        </div>
    </div>

    <!-- Banner Informativo -->
    <div class="bg-blue-600 text-white p-6 mb-6">
        <h2 class="text-xl font-bold mb-2">Fique à vontade!</h2>
        <p class="text-blue-100">
            O Portal de Ideias é um ambiente colaborativo, em que a comunidade pode ajudar a elaborar novas funcionalidades, 
            adicionando ideias, comentando e votando naquelas que achar mais interessante.
        </p>
    </div>

    <div class="flex gap-6">
        <!-- Sidebar de Filtros -->
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="font-bold text-gray-900 mb-4">SITUAÇÕES</h3>
                <nav class="space-y-1">
                    <a href="{{ route('ideias.index', ['filtro' => 'todas']) }}" 
                       class="filter-item {{ $filtro === 'todas' ? 'active' : '' }}">
                        <i class="fas fa-list text-gray-600"></i>
                        <span>Todas</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'mais-votadas']) }}" 
                       class="filter-item {{ $filtro === 'mais-votadas' ? 'active' : '' }}">
                        <i class="fas fa-trophy text-gray-600"></i>
                        <span>Mais votadas</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'novas']) }}" 
                       class="filter-item {{ $filtro === 'novas' ? 'active' : '' }}">
                        <i class="far fa-calendar text-gray-600"></i>
                        <span>Novas</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'em-aberto']) }}" 
                       class="filter-item {{ $filtro === 'em-aberto' ? 'active' : '' }}">
                        <i class="fas fa-lock-open text-gray-600"></i>
                        <span>Em aberto</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'em-analise']) }}" 
                       class="filter-item {{ $filtro === 'em-analise' ? 'active' : '' }}">
                        <i class="fas fa-filter text-gray-600"></i>
                        <span>Em análise</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'em-desenvolvimento']) }}" 
                       class="filter-item {{ $filtro === 'em-desenvolvimento' ? 'active' : '' }}">
                        <i class="fas fa-code text-gray-600"></i>
                        <span>Em desenvolvimento</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'concluido']) }}" 
                       class="filter-item {{ $filtro === 'concluido' ? 'active' : '' }}">
                        <i class="fas fa-check text-gray-600"></i>
                        <span>Concluído</span>
                    </a>
                    <a href="{{ route('ideias.index', ['filtro' => 'sem-previsao']) }}" 
                       class="filter-item {{ $filtro === 'sem-previsao' ? 'active' : '' }}">
                        <i class="fas fa-ban text-gray-600"></i>
                        <span>Sem previsão</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <div class="flex-1">
            <!-- Barra de Ações -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6 flex items-center justify-between gap-4">
                <a href="{{ route('ideias.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white hover:bg-green-700 transition-colors">
                    <i class="fas fa-pencil-alt mr-2"></i>
                    Compartilhar ideia
                </a>
                <form method="GET" action="{{ route('ideias.index') }}" class="flex-1 max-w-md">
                    <input type="hidden" name="filtro" value="{{ $filtro }}">
                    <div class="flex gap-2">
                        <input type="text" 
                               name="busca" 
                               value="{{ $busca }}"
                               placeholder="Pesquisar ideia" 
                               class="flex-1 px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Ideias -->
            <div class="space-y-4">
                @forelse($ideias as $ideia)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
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

                            <!-- Conteúdo -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div class="flex-1">
                                        <a href="{{ route('ideias.show', $ideia) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600 underline">
                                            {{ $ideia->titulo }}
                                        </a>
                                        <div class="mt-1 text-sm text-gray-600">
                                            <span class="font-medium">{{ $ideia->usuario->nome }}</span>
                                            <span class="mx-2">-</span>
                                            <a href="{{ route('ideias.index', ['categoria' => $ideia->categoria]) }}" class="underline hover:text-blue-600">
                                                {{ $ideia->categoria }}
                                            </a>
                                            <span class="mx-2">-</span>
                                            <span>{{ $ideia->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status e Votos -->
                            <div class="flex-shrink-0 flex items-center gap-3">
                                <span class="status-badge status-{{ strtolower(str_replace([' ', 'é'], ['-', 'e'], $ideia->status_label)) }}">
                                    {{ $ideia->status_label }}
                                </span>
                                <button onclick="votarIdeia('{{ $ideia->id }}')" 
                                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white hover:opacity-80 transition-colors"
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
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhuma ideia encontrada</h3>
                        <p class="text-gray-500">Seja o primeiro a compartilhar uma ideia!</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginação -->
            @if($ideias->hasPages())
                <div class="mt-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ $ideias->firstItem() }} até {{ $ideias->lastItem() }} de {{ $ideias->total() }} resultados
                        </div>
                        <div class="flex items-center space-x-1 flex-wrap justify-center">
                            @php
                                $currentPage = $ideias->currentPage();
                                $lastPage = $ideias->lastPage();
                                $onEachSide = 2;
                                $start = max(1, $currentPage - $onEachSide);
                                $end = min($lastPage, $currentPage + $onEachSide);
                                if ($start == 1) {
                                    $end = min($lastPage, $start + ($onEachSide * 2) + 1);
                                }
                                if ($end == $lastPage) {
                                    $start = max(1, $end - ($onEachSide * 2) - 1);
                                }
                            @endphp
                            
                            @if ($ideias->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed" title="Primeira página">
                                    <i class="fas fa-angle-double-left"></i>
                                </span>
                            @else
                                <a href="{{ $ideias->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50" title="Primeira página">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            @endif

                            @if ($ideias->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $ideias->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            @if ($start > 1)
                                <a href="{{ $ideias->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">1</a>
                                @if ($start > 2)
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $ideias->url($page) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $lastPage)
                                @if ($end < $lastPage - 1)
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                @endif
                                <a href="{{ $ideias->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">{{ $lastPage }}</a>
                            @endif

                            @if ($ideias->hasMorePages())
                                <a href="{{ $ideias->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif

                            @if ($ideias->currentPage() == $lastPage)
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 cursor-not-allowed" title="Última página">
                                    <i class="fas fa-angle-double-right"></i>
                                </span>
                            @else
                                <a href="{{ $ideias->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50" title="Última página">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function votarIdeia(ideiaId) {
    const btn = document.getElementById(`voto-btn-${ideiaId}`);
    const count = document.getElementById(`votos-count-${ideiaId}`);
    
    if (!btn || !count) {
        console.error('Elementos não encontrados para ideia:', ideiaId);
        return;
    }

    // Desabilitar botão durante a requisição
    btn.disabled = true;

    fetch(`/ideias/${ideiaId}/votar`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.votos > 0) {
                count.textContent = data.votos;
                count.classList.remove('hidden');
            } else {
                count.classList.add('hidden');
            }
        } else {
            alert(data.message || 'Erro ao votar na ideia.');
        }
    })
    .catch(error => {
        console.error('Erro ao votar:', error);
        alert(error.message || 'Erro ao votar na ideia. Tente novamente.');
    })
    .finally(() => {
        btn.disabled = false;
    });
}
</script>
@endsection

