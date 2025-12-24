@extends('layouts.app')

@section('title', 'Aplicativos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Wizard Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center w-full max-w-4xl">
                <!-- Step 1 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-sm font-medium text-green-600">Selecionar Período</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-sm font-medium text-green-600">Configurar</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                            3
                        </div>
                        <span class="mt-2 text-sm font-medium text-blue-600">Aplicativos</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                </div>
                
                <!-- Step 4 -->
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-sm">
                            4
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-500">Pagamento</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <form method="POST" action="{{ route('planos.configurar', $plano) }}" class="inline">
                @csrf
                <input type="hidden" name="periodo" value="{{ $periodo->value }}">
                <input type="hidden" name="usuarios_extras" value="{{ $usuariosExtras }}">
                <input type="hidden" name="empresas_extras" value="{{ $empresasExtras }}">
                <button type="submit" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Voltar
                </button>
            </form>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Selecionar Aplicativos</h1>
        <p class="mt-2 text-gray-600">Escolha os aplicativos que deseja incluir no seu plano (opcional)</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lista de Aplicativos -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Aplicativos Disponíveis</h2>

                <form method="POST" action="{{ route('planos.pagamento.post', $plano) }}" id="aplicativosForm">
                    @csrf
                    <input type="hidden" name="periodo" value="{{ $periodo->value }}">
                    <input type="hidden" name="usuarios_extras" value="{{ $usuariosExtras }}">
                    <input type="hidden" name="empresas_extras" value="{{ $empresasExtras }}">

                    @if($aplicativos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($aplicativos as $aplicativo)
                                <div class="aplicativo-card relative border-2 rounded-lg p-6 cursor-pointer transition-all hover:shadow-lg
                                    @if(in_array($aplicativo->id, $aplicativosSelecionados ?? [])) border-blue-500 bg-blue-50
                                    @else border-gray-200 bg-white hover:border-blue-300
                                    @endif"
                                    onclick="toggleAplicativo(event, this, '{{ $aplicativo->id }}')">
                                    <input type="checkbox"
                                           name="aplicativos[]"
                                           value="{{ $aplicativo->id }}"
                                           class="absolute top-4 right-4 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                           onchange="atualizarCheckboxAplicativo(this); calcularTotal();"
                                           @if(in_array($aplicativo->id, $aplicativosSelecionados ?? [])) checked @endif>
                                    
                                    <!-- Ícone e Nome -->
                                    <div class="text-center mb-4">
                                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                                            @if($aplicativo->codigo === 'loteamento')
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $aplicativo->nome }}</h3>
                                    </div>

                                    <!-- Preço -->
                                    <div class="text-center mb-4">
                                        <div class="text-2xl font-bold text-gray-900">
                                            R$ {{ number_format($aplicativo->getPrecoPorPeriodo($periodo->value), 2, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            /{{ strtolower($periodo->getLabel()) }}
                                        </div>
                                    </div>

                                    <!-- Descrição (colapsável) -->
                                    @if($aplicativo->descricao)
                                        <div class="aplicativo-descricao hidden mt-4 pt-4 border-t border-gray-200">
                                            <p class="text-sm text-gray-600">{{ $aplicativo->descricao }}</p>
                                        </div>
                                        <button type="button" 
                                                onclick="toggleDescricao(this); event.stopPropagation();"
                                                class="w-full mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center transition-colors">
                                            <span class="descricao-texto">Ver descrição</span>
                                            <svg class="w-4 h-4 ml-1 descricao-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Nenhum aplicativo disponível no momento.</p>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Resumo -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Plano:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $plano->nome }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Período:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $periodo->getLabel() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Preço base:</span>
                        <span class="text-sm font-medium text-gray-900" id="resumo-preco-base">R$ {{ number_format($precoBase, 2, ',', '.') }}</span>
                    </div>
                    @if($usuariosExtras > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $usuariosExtras }} usuário(s) extra(s):</span>
                        <span class="text-sm font-medium text-gray-900">R$ {{ number_format($valorUsuariosExtras, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($empresasExtras > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $empresasExtras }} empresa(s) extra(s):</span>
                        <span class="text-sm font-medium text-gray-900">R$ {{ number_format($valorEmpresasExtras, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center" id="resumo-aplicativos" style="display: none;">
                        <span class="text-sm text-gray-600">Aplicativos:</span>
                        <span class="text-sm font-medium text-gray-900" id="resumo-valor-aplicativos">R$ 0,00</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-blue-600" id="resumo-total">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" 
                        form="aplicativosForm"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium">
                    Continuar para Pagamento
                </button>

                <p class="text-xs text-gray-500 text-center mt-4">
                    * Aplicativos são opcionais
                </p>
            </div>
        </div>
    </div>
</div>

<script>
const precoBase = {{ $precoBase }};
const valorUsuariosExtras = {{ $valorUsuariosExtras ?? 0 }};
const valorEmpresasExtras = {{ $valorEmpresasExtras ?? 0 }};
const periodo = '{{ $periodo->value }}';

const aplicativos = @json($aplicativos->mapWithKeys(function($app) use ($periodo) {
    return [$app->id => $app->getPrecoPorPeriodo($periodo->value)];
}));

function calcularTotal() {
    const checkboxes = document.querySelectorAll('input[name="aplicativos[]"]:checked');
    let valorAplicativos = 0;
    
    checkboxes.forEach(checkbox => {
        const aplicativoId = checkbox.value;
        if (aplicativos[aplicativoId]) {
            valorAplicativos += aplicativos[aplicativoId];
        }
    });

    const valorTotal = precoBase + valorUsuariosExtras + valorEmpresasExtras + valorAplicativos;

    // Atualizar resumo
    document.getElementById('resumo-valor-aplicativos').textContent = 
        formatarMoeda(valorAplicativos);
    document.getElementById('resumo-total').textContent = 
        formatarMoeda(valorTotal);

    // Mostrar/ocultar linha de aplicativos
    document.getElementById('resumo-aplicativos').style.display = 
        valorAplicativos > 0 ? 'flex' : 'none';
}

function formatarMoeda(valor) {
    return 'R$ ' + valor.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Toggle aplicativo selecionado
function toggleAplicativo(event, card, aplicativoId) {
    // Se o clique foi no checkbox, não fazer nada (deixar o comportamento padrão)
    if (event.target.type === 'checkbox' || event.target.closest('input[type="checkbox"]')) {
        return;
    }
    
    // Se o clique foi no botão de descrição, não fazer nada
    if (event.target.closest('button')) {
        return;
    }
    
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    atualizarEstiloCard(card, checkbox.checked);
    calcularTotal();
}

// Atualizar estilo do card baseado no estado do checkbox
function atualizarEstiloCard(card, isChecked) {
    if (isChecked) {
        card.classList.add('border-blue-500', 'bg-blue-50');
        card.classList.remove('border-gray-200', 'bg-white');
    } else {
        card.classList.remove('border-blue-500', 'bg-blue-50');
        card.classList.add('border-gray-200', 'bg-white');
    }
}

// Atualizar checkbox quando clicado diretamente
function atualizarCheckboxAplicativo(checkbox) {
    const card = checkbox.closest('.aplicativo-card');
    atualizarEstiloCard(card, checkbox.checked);
}

// Toggle descrição
function toggleDescricao(button) {
    const card = button.closest('.aplicativo-card');
    const descricao = card.querySelector('.aplicativo-descricao');
    const texto = button.querySelector('.descricao-texto');
    const icon = button.querySelector('.descricao-icon');
    
    if (descricao.classList.contains('hidden')) {
        descricao.classList.remove('hidden');
        texto.textContent = 'Ocultar descrição';
        icon.style.transform = 'rotate(180deg)';
    } else {
        descricao.classList.add('hidden');
        texto.textContent = 'Ver descrição';
        icon.style.transform = 'rotate(0deg)';
    }
}

// Inicializar cálculo
document.addEventListener('DOMContentLoaded', function() {
    calcularTotal();
});
</script>
@endsection

