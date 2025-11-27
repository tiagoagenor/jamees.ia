@extends('layouts.app')

@section('title', 'Aplicativos')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-mobile-alt text-blue-600 mr-3"></i>
            Aplicativos
        </h1>
    </div>

    <!-- Filtros de Categoria -->
    <div class="mb-6 overflow-x-auto">
        <div class="flex space-x-3 pb-2">
            <a href="{{ route('aplicativos.index') }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ !request('categoria') || request('categoria') === 'todas' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="none">
                    <rect x="2" y="2" width="6" height="6" fill="currentColor"/>
                    <rect x="12" y="2" width="6" height="6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="1.5 1.5"/>
                    <rect x="2" y="12" width="6" height="6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="1.5 1.5"/>
                    <rect x="12" y="12" width="6" height="6" fill="currentColor"/>
                </svg>
                Todas
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'em-alta']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'em-alta' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-bolt mr-2"></i>
                Em Alta
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'financeiro']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'financeiro' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-dollar-sign mr-2"></i>
                Financeiro
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'e-commerce']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'e-commerce' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-shopping-cart mr-2"></i>
                E-commerce
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'marketing']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'marketing' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-comments mr-2"></i>
                Marketing
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'vendas']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'vendas' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-shopping-basket mr-2"></i>
                Vendas
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'logistica']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'logistica' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-truck mr-2"></i>
                Logística
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'api']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'api' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-laptop-code mr-2"></i>
                API
            </a>
            <a href="{{ route('aplicativos.index', ['categoria' => 'gestao']) }}" 
               class="flex items-center px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors duration-200 {{ request('categoria') === 'gestao' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                <i class="fas fa-chart-bar mr-2"></i>
                Gestão
            </a>
        </div>
    </div>

    <!-- Aplicativos -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @forelse($aplicativos as $aplicativo)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <!-- Imagem do Aplicativo -->
                <div class="h-32 bg-gray-100 flex items-center justify-center relative overflow-hidden border-b border-gray-200">
                    @if($aplicativo->imagem)
                        <img src="{{ $aplicativo->imagem }}" alt="{{ $aplicativo->nome }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-gray-400 text-center">
                            <i class="fas fa-mobile-alt text-4xl opacity-50"></i>
                        </div>
                    @endif
                    @if(in_array($aplicativo->codigo, $aplicativosContratados ?? []))
                        <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            Contratado
                        </span>
                    @endif
                </div>

                <!-- Conteúdo do Card -->
                <div class="p-4">
                    <div class="mb-2">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $aplicativo->nome }}</h3>
                            @if($aplicativo->categoria)
                                <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-0.5 rounded">{{ $aplicativo->categoria }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $aplicativo->codigo }}</p>
                    </div>

                    <p class="text-gray-600 text-xs mb-3 line-clamp-2">
                        {{ $aplicativo->descricao ?? 'Sem descrição disponível.' }}
                    </p>

                    <!-- Preços -->
                    <div class="mb-3 pb-3 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">Mensal:</span>
                            <span class="text-sm font-bold text-blue-600">R$ {{ number_format($aplicativo->preco_mensal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Anual:</span>
                            <span class="text-sm font-bold text-green-600">R$ {{ number_format($aplicativo->preco_anual, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Botão Ver Detalhes -->
                    <a href="{{ route('aplicativos.show', $aplicativo) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-eye mr-1"></i>
                        Ver Detalhes
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white shadow rounded-lg p-12 text-center border border-gray-200">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Nenhum aplicativo encontrado</p>
                <p class="text-gray-400 text-sm mt-2">
                    Não há aplicativos cadastrados no sistema.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($aplicativos->hasPages())
        <div class="mt-8">
            {{ $aplicativos->links() }}
        </div>
    @endif
</div>

<!-- Modal de Detalhes -->
<div id="modalDetalhes" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-gray-200">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
                <h3 class="text-2xl font-bold text-gray-900" id="modalTitulo">Detalhes do Aplicativo</h3>
                <button onclick="fecharModalDetalhes()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="p-6" id="modalConteudo">
                <!-- Conteúdo será carregado via JavaScript -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                    <p class="text-gray-600">Carregando detalhes...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModalDetalhes(aplicativo) {
    if (!aplicativo) return;

    const modal = document.getElementById('modalDetalhes');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalConteudo = document.getElementById('modalConteudo');

    modalTitulo.textContent = aplicativo.nome;
    
    modalConteudo.innerHTML = `
        <div class="space-y-6">
            <!-- Imagem -->
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative border border-gray-200">
                ${aplicativo.imagem ? 
                    `<img src="${aplicativo.imagem.startsWith('http') ? aplicativo.imagem : '/storage/' + aplicativo.imagem}" alt="${aplicativo.nome}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                     <div class="text-gray-400 text-center hidden absolute inset-0 items-center justify-center">
                         <i class="fas fa-mobile-alt text-8xl opacity-50"></i>
                     </div>` :
                    `<i class="fas fa-mobile-alt text-gray-400 text-8xl opacity-50"></i>`
                }
            </div>

            <!-- Informações Básicas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <p class="text-gray-900">${aplicativo.codigo}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <p class="text-gray-900">${aplicativo.categoria || 'Sem categoria'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${aplicativo.ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        <i class="fas fa-${aplicativo.ativo ? 'check' : 'times'}-circle mr-2"></i>
                        ${aplicativo.ativo ? 'Ativo' : 'Inativo'}
                    </span>
                </div>
            </div>

            <!-- Descrição -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <p class="text-gray-600">${aplicativo.descricao || 'Sem descrição disponível.'}</p>
            </div>

            <!-- Detalhes -->
            ${aplicativo.detalhes ? `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Detalhes</label>
                <div class="text-gray-600 whitespace-pre-line">${aplicativo.detalhes}</div>
            </div>
            ` : ''}

            <!-- Preços -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Preços</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Mensal</p>
                        <p class="text-xl font-bold text-blue-600">R$ ${parseFloat(aplicativo.preco_mensal).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Trimestral</p>
                        <p class="text-xl font-bold text-blue-600">R$ ${parseFloat(aplicativo.preco_trimestral).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Semestral</p>
                        <p class="text-xl font-bold text-blue-600">R$ ${parseFloat(aplicativo.preco_semestral).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Anual</p>
                        <p class="text-xl font-bold text-green-600">R$ ${parseFloat(aplicativo.preco_anual).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('planos.index') }}?aplicativo=${aplicativo.id}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium text-center transition-colors duration-200">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Contratar Agora
                </a>
                <button onclick="fecharModalDetalhes()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Fechar
                </button>
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function fecharModalDetalhes() {
    const modal = document.getElementById('modalDetalhes');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Fechar modal ao clicar fora
document.getElementById('modalDetalhes').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalDetalhes();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fecharModalDetalhes();
    }
});
</script>

<style>
.slider-container {
    position: relative;
}

.slider-track {
    display: flex;
    width: 100%;
}

.slider-slide {
    flex-shrink: 0;
    width: 100%;
}

.slider-dot {
    transition: all 0.3s;
    cursor: pointer;
}

.slider-dot:hover {
    transform: scale(1.2);
}
</style>
@endsection
