@extends('layouts.app')

@section('title', $aplicativo->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('aplicativos.index') }}" class="hover:text-blue-600">Aplicativos</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-900">{{ $aplicativo->nome }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Banner Principal -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                <div class="relative h-64 bg-gradient-to-br from-blue-500 to-blue-600">
                    @if($aplicativo->imagem)
                        <img src="{{ $aplicativo->imagem }}" alt="{{ $aplicativo->nome }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-white text-center flex items-center justify-center h-full">
                            <i class="fas fa-mobile-alt text-8xl opacity-50"></i>
                        </div>
                    @endif
                    @if($aplicativo->ativo)
                        <span class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            Ativo
                        </span>
                    @endif
                </div>
                
                <!-- Informações do Banner -->
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $aplicativo->nome }}</h1>
                            @if($aplicativo->categoria)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $aplicativo->categoria }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <p class="text-gray-600 text-lg mb-4">
                        {{ $aplicativo->descricao ?? 'Sem descrição disponível.' }}
                    </p>

                    @if($aplicativo->detalhes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Detalhes</h2>
                            <div class="text-gray-600 whitespace-pre-line">
                                {{ $aplicativo->detalhes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Características/Recursos -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Características</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-gray-900">Código</h3>
                            <p class="text-gray-600 text-sm">{{ $aplicativo->codigo }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-gray-900">Status</h3>
                            <p class="text-gray-600 text-sm">{{ $aplicativo->ativo ? 'Ativo e disponível' : 'Indisponível' }}</p>
                        </div>
                    </div>
                    @if($aplicativo->categoria)
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-gray-900">Categoria</h3>
                            <p class="text-gray-600 text-sm">{{ $aplicativo->categoria }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar de Contratação (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 sticky top-8">
                <!-- Preços -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Preços</h3>
                    @php
                        $periodosDisponiveis = [];
                        if ($isTeste || !$periodoPermitido) {
                            // Se for teste ou não tem plano, mostra todos os períodos
                            $periodosDisponiveis = [
                                'mensal' => ['label' => 'Mensal', 'preco' => $aplicativo->preco_mensal, 'destaque' => false],
                                'trimestral' => ['label' => 'Trimestral', 'preco' => $aplicativo->preco_trimestral, 'destaque' => false],
                                'semestral' => ['label' => 'Semestral', 'preco' => $aplicativo->preco_semestral, 'destaque' => false],
                                'anual' => ['label' => 'Anual', 'preco' => $aplicativo->preco_anual, 'destaque' => true],
                            ];
                        } else {
                            // Se não for teste, mostra apenas o período do plano atual
                            $periodosDisponiveis[$periodoPermitido] = [
                                'label' => ucfirst($periodoPermitido),
                                'preco' => $aplicativo->getPrecoPorPeriodo($periodoPermitido),
                                'destaque' => $periodoPermitido === 'anual'
                            ];
                        }
                    @endphp
                    
                    @if(count($periodosDisponiveis) > 0)
                        <div class="space-y-3">
                            @foreach($periodosDisponiveis as $periodo => $info)
                                <div class="flex justify-between items-center p-3 {{ $info['destaque'] ? 'bg-green-50 rounded-lg border-2 border-green-200' : 'bg-gray-50 rounded-lg' }}">
                                    <span class="text-sm {{ $info['destaque'] ? 'font-semibold text-gray-700' : 'text-gray-600' }}">{{ $info['label'] }}</span>
                                    <div class="text-right">
                                        <span class="text-lg font-bold {{ $info['destaque'] ? 'text-green-600' : 'text-blue-600' }}">R$ {{ number_format($info['preco'], 2, ',', '.') }}</span>
                                        @if($info['destaque'])
                                            <p class="text-xs text-green-600">Melhor valor</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(!$isTeste && $periodoPermitido)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Seu plano atual é <strong>{{ ucfirst($periodoPermitido) }}</strong>. O aplicativo será contratado no mesmo período.
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Você precisa ter um plano ativo para contratar este aplicativo.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Botão de Contratação -->
                @if($jaContratado)
                    <button disabled
                            class="w-full bg-gray-400 text-white px-6 py-4 rounded-lg font-bold text-lg text-center flex items-center justify-center mb-3 shadow-lg cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i>
                        Contratado
                    </button>
                    <form method="POST" action="{{ route('aplicativos.cancelar', $aplicativo) }}" 
                          onsubmit="return confirm('Tem certeza que deseja cancelar este aplicativo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200 flex items-center justify-center mb-4 shadow-lg">
                            <i class="fas fa-times-circle mr-2"></i>
                            Cancelar Aplicativo
                        </button>
                    </form>
                @elseif(count($periodosDisponiveis) > 0 && $planoAtual)
                    @php
                        $periodoContratacao = $periodoPermitido ?? 'anual';
                        if (!$periodoPermitido && !$isTeste) {
                            $periodoContratacao = 'anual'; // Default
                        }
                    @endphp
                    <a href="{{ route('aplicativos.pagamento', ['aplicativo' => $aplicativo, 'periodo' => $periodoContratacao]) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-bold text-lg text-center transition-colors duration-200 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas {{ $isTeste ? 'fa-play-circle' : 'fa-shopping-cart' }} mr-2"></i>
                        {{ $isTeste ? 'Ativar Agora' : 'Contratar Agora' }}
                    </a>
                @else
                    <a href="{{ route('planos.index') }}" 
                       class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-4 rounded-lg font-bold text-lg text-center transition-colors duration-200 flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Ver Planos
                    </a>
                @endif

                <!-- Informações Adicionais -->
                <div class="space-y-4 pt-4 border-t border-gray-200">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Ativação Imediata</p>
                            <p class="text-xs text-gray-600">Acesso liberado após confirmação do pagamento</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Suporte Incluído</p>
                            <p class="text-xs text-gray-600">Suporte técnico durante todo o período</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Cancelamento Flexível</p>
                            <p class="text-xs text-gray-600">Cancele quando quiser, sem multas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes (reutilizando o mesmo modal) -->
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
@endsection

