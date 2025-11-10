@extends('layouts.app')

@section('title', $empreendimento->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-building text-blue-600 mr-3"></i>
                    {{ $empreendimento->nome }}
                </h1>
                <p class="text-gray-600 mt-2">Detalhes do empreendimento</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('empreendimentos.edit', $empreendimento->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('empreendimentos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Empreendimento -->
        <div class="lg:col-span-1">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informações Básicas
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-sm font-medium">
                            @if($empreendimento->status == 1)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inativo</span>
                            @endif
                        </p>
                    </div>
                    @if($empreendimento->zoom_default)
                        <div>
                            <p class="text-sm text-gray-500">Zoom Padrão do Mapa</p>
                            <p class="text-sm font-medium">{{ $empreendimento->zoom_default }}%</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Tipo de Numeração das Quadras</p>
                        <p class="text-sm font-medium">
                            @if(($empreendimento->quadra_numeracao_tipo ?? 1) == 2)
                                Alfanumérica (A, B, C, D...)
                            @else
                                Numérica (1, 2, 3, 4...)
                            @endif
                        </p>
                    </div>
                    @if($empreendimento->criado_em)
                        <div>
                            <p class="text-sm text-gray-500">Data de Criação</p>
                            <p class="text-sm font-medium">{{ $empreendimento->criado_em->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    @if($empreendimento->atualizado_em)
                        <div>
                            <p class="text-sm text-gray-500">Última Atualização</p>
                            <p class="text-sm font-medium">{{ $empreendimento->atualizado_em->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Configurações de Venda -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                    Configurações de Venda
                </h3>
                <div class="space-y-3">
                    @if($empreendimento->valor_m2)
                        <div>
                            <p class="text-sm text-gray-500">Valor/m² Padrão</p>
                            <p class="text-sm font-medium text-green-600">R$ {{ number_format($empreendimento->valor_m2, 2, ',', '.') }}</p>
                        </div>
                    @endif
                    @if($empreendimento->maximo_parcelas)
                        <div>
                            <p class="text-sm text-gray-500">Máximo de Parcelas</p>
                            <p class="text-sm font-medium">{{ $empreendimento->maximo_parcelas }}x</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Configurações de Sinal -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-hand-holding-usd text-yellow-600 mr-2"></i>
                    Configurações de Sinal
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Sinal Obrigatório</p>
                        <p class="text-sm font-medium">
                            @if($empreendimento->sinal == 1)
                                <span class="text-green-600">Sim</span>
                            @else
                                <span class="text-gray-600">Não</span>
                            @endif
                        </p>
                    </div>
                    @if($empreendimento->sinal == 1 && $empreendimento->sinal_valor)
                        <div>
                            <p class="text-sm text-gray-500">Valor do Sinal</p>
                            <p class="text-sm font-medium">
                                @if($empreendimento->sinal_tipo == 1)
                                    Fixo: R$ {{ number_format($empreendimento->sinal_valor, 2, ',', '.') }}
                                @else
                                    Porcentagem: {{ number_format($empreendimento->sinal_valor, 2, ',', '.') }}%
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Configurações de Juros e Multa -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-percentage text-purple-600 mr-2"></i>
                    Configurações de Juros e Multa
                </h3>
                <div class="space-y-3">
                    @if($empreendimento->juros !== null)
                        <div>
                            <p class="text-sm text-gray-500">Juros</p>
                            <p class="text-sm font-medium">
                                @if($empreendimento->juros_forma == 'porcentagem')
                                    {{ number_format($empreendimento->juros, 6, ',', '.') }}% ao dia
                                @else
                                    R$ {{ number_format($empreendimento->juros, 6, ',', '.') }}/dia
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Forma: {{ $empreendimento->juros_forma == 'porcentagem' ? 'Porcentagem' : 'Valor' }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-sm text-gray-500">Juros</p>
                            <p class="text-sm font-medium text-gray-400">Não configurado</p>
                        </div>
                    @endif
                    @if($empreendimento->multa !== null)
                        <div>
                            <p class="text-sm text-gray-500">Multa</p>
                            <p class="text-sm font-medium">
                                @if($empreendimento->multa_forma == 'porcentagem')
                                    {{ number_format($empreendimento->multa, 6, ',', '.') }}% do valor
                                @else
                                    R$ {{ number_format($empreendimento->multa, 6, ',', '.') }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Forma: {{ $empreendimento->multa_forma == 'porcentagem' ? 'Porcentagem' : 'Valor' }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-sm text-gray-500">Multa</p>
                            <p class="text-sm font-medium text-gray-400">Não configurado</p>
                        </div>
                    @endif
                    @if($empreendimento->juros_por_parcela !== null)
                        <div>
                            <p class="text-sm text-gray-500">Juros por Parcela</p>
                            <p class="text-sm font-medium">
                                {{ number_format($empreendimento->juros_por_parcela, 6, ',', '.') }}%
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Aplicado sobre o valor de cada parcela na venda</p>
                        </div>
                    @else
                        <div>
                            <p class="text-sm text-gray-500">Juros por Parcela</p>
                            <p class="text-sm font-medium text-gray-400">Não configurado</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Imagens -->
            @if($empreendimento->imagem || $empreendimento->imagem_mapa)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images text-indigo-600 mr-2"></i>
                        Imagens
                    </h3>
                    <div class="space-y-4">
                        @if($empreendimento->imagem)
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Imagem de Capa</p>
                                <div class="rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset($empreendimento->imagem) }}" alt="{{ $empreendimento->nome }}" class="w-full h-auto">
                                </div>
                            </div>
                        @endif
                        @if($empreendimento->imagem_mapa)
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Imagem do Mapa</p>
                                <div class="rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset($empreendimento->imagem_mapa) }}" alt="Mapa do {{ $empreendimento->nome }}" class="w-full h-auto">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Lotes e Quadras -->
        <div class="lg:col-span-2">
            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="showTab('lotes')" id="tab-lotes" class="tab-button active px-6 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            Lotes ({{ $empreendimento->lotes->count() }})
                        </button>
                        <button onclick="showTab('quadras')" id="tab-quadras" class="tab-button px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300">
                            <i class="fas fa-th mr-2"></i>
                            Quadras
                        </button>
                    </nav>
                </div>

                <!-- Conteúdo: Lotes -->
                <div id="content-lotes" class="tab-content p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Lotes</h3>
                        <a href="{{ route('lotes.create', $empreendimento->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Lote
                        </a>
                    </div>

                    @if($empreendimento->lotes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quadra</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">m²</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($empreendimento->lotes as $lote)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $lote->nome ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $lote->quadra->nome ?? '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($lote->status)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $lote->status->cor }}">
                                                        {{ $lote->status->nome }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $lote->m2 ? number_format($lote->m2, 2, ',', '.') : '-' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                {{ $lote->valor ? 'R$ ' . number_format($lote->valor, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('lotes.show', $lote->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('lotes.edit', $lote->id) }}" class="text-gray-600 hover:text-gray-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600 mb-4">Nenhum lote cadastrado ainda</p>
                            <a href="{{ route('lotes.create', $empreendimento->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Primeiro Lote
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Conteúdo: Quadras -->
                <div id="content-quadras" class="tab-content p-6 hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Quadras</h3>
                        <button onclick="showCreateQuadraModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Nova Quadra
                        </button>
                    </div>

                    @php
                        $quadras = $empreendimento->quadras()->orderBy('nome')->get();
                    @endphp

                    @if($quadras->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($quadras as $quadra)
                                <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $quadra->nome }}</h4>
                                        <p class="text-sm text-gray-600">{{ $quadra->lotes()->count() }} lotes</p>
                                    </div>
                                    <form action="{{ route('quadras.destroy', $quadra->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-th text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">Nenhuma quadra cadastrada ainda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Criar Quadra -->
<div id="quadra-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Nova Quadra</h3>
                <button onclick="closeCreateQuadraModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('quadras.store', $empreendimento->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-700 mb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        As quadras serão criadas automaticamente com nomes sequenciais.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                        <p class="text-sm text-blue-900">
                            <strong>Tipo de numeração:</strong>
                            @if(($empreendimento->quadra_numeracao_tipo ?? 1) == 2)
                                Alfanumérica (A, B, C, D...)
                            @else
                                Numérica (1, 2, 3, 4...)
                            @endif
                        </p>
                    </div>
                    <div>
                        <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantidade de Quadras *
                        </label>
                        <input type="number"
                               id="quantidade"
                               name="quantidade"
                               value="1"
                               min="1"
                               max="100"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Digite quantas quadras deseja criar</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateQuadraModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Quadra(s)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    // Esconder todos os conteúdos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remover active de todos os botões
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'text-blue-600', 'border-blue-600');
        button.classList.add('text-gray-500', 'border-transparent');
    });

    // Mostrar conteúdo selecionado
    document.getElementById('content-' + tab).classList.remove('hidden');

    // Ativar botão selecionado
    const button = document.getElementById('tab-' + tab);
    button.classList.add('active', 'text-blue-600', 'border-blue-600');
    button.classList.remove('text-gray-500', 'border-transparent');
}

function showCreateQuadraModal() {
    document.getElementById('quadra-modal').classList.remove('hidden');
}

function closeCreateQuadraModal() {
    document.getElementById('quadra-modal').classList.add('hidden');
    document.getElementById('quantidade').value = '1';
}

// Verificar se deve abrir a aba de quadras após criar uma nova quadra
document.addEventListener('DOMContentLoaded', function() {
    // Verificar parâmetro na URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    if (tab === 'quadras') {
        showTab('quadras');
    }

    // Verificar se há mensagem de sucesso de criação de quadra (abre aba de quadras automaticamente)
    @if(session('success') && strpos(session('success'), 'quadra') !== false)
        showTab('quadras');
    @endif
});
</script>
@endsection

