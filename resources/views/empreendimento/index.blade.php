@extends('layouts.app')

@section('title', 'Empreendimentos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-building text-blue-600 mr-3"></i>
                    Empreendimentos
                </h1>
                <p class="text-gray-600 mt-2">Gerencie os empreendimentos e seus lotes</p>
            </div>
            <a href="{{ route('empreendimentos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Novo Empreendimento
            </a>
        </div>
    </div>

    <!-- Lista de Empreendimentos -->
    @if($empreendimentos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($empreendimentos as $empreendimento)
                @php
                    // Verificar se há lotes vendidos
                    $lotesVendidos = $empreendimento->lotes()
                        ->whereHas('status', function($query) {
                            $query->where('tipo', 2); // Tipo 2 = Vendido
                        })
                        ->count();
                    $temLotesVendidos = $lotesVendidos > 0;
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($empreendimento->imagem)
                        <img src="{{ asset($empreendimento->imagem) }}" alt="{{ $empreendimento->nome }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-building text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $empreendimento->nome }}</h3>
                            @if($empreendimento->status == 1)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inativo</span>
                            @endif
                        </div>
                        <div class="space-y-2 mb-4">
                            @if($empreendimento->valor_m2)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                                    Valor/m²: <span class="font-medium">R$ {{ number_format($empreendimento->valor_m2, 2, ',', '.') }}</span>
                                </p>
                            @endif
                            @if($empreendimento->maximo_parcelas)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-credit-card text-gray-400 mr-2"></i>
                                    Máx. Parcelas: <span class="font-medium">{{ $empreendimento->maximo_parcelas }}x</span>
                                </p>
                            @endif
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-hand-holding-usd text-gray-400 mr-2"></i>
                                Sinal Obrigatório: <span class="font-medium">{{ $empreendimento->sinal == 1 ? 'Sim' : 'Não' }}</span>
                            </p>
                            @if($empreendimento->sinal == 1 && $empreendimento->sinal_tipo)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-tag text-gray-400 mr-2"></i>
                                    Tipo de Sinal: <span class="font-medium">{{ $empreendimento->sinal_tipo == 1 ? 'Fixo' : 'Porcentagem' }}</span>
                                </p>
                            @endif
                            @if($empreendimento->sinal == 1 && $empreendimento->sinal_valor)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-money-bill-wave text-gray-400 mr-2"></i>
                                    Valor do Sinal: <span class="font-medium">
                                        @if($empreendimento->sinal_tipo == 1)
                                            R$ {{ number_format($empreendimento->sinal_valor, 2, ',', '.') }}
                                        @else
                                            {{ number_format($empreendimento->sinal_valor, 2, ',', '.') }}%
                                        @endif
                                    </span>
                                </p>
                            @endif
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-map-marked-alt text-gray-400 mr-2"></i>
                                Lotes: <span class="font-medium">{{ $empreendimento->lotes()->count() }}</span>
                            </p>
                        </div>
                        <!-- Botões de Ação -->
                        <div class="flex flex-col space-y-2">
                            <!-- Primeira linha: Lotes e Mapa (lado esquerdo) + Editar (lado direito) -->
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-2 flex-1">
                                    <a href="{{ route('lotes.index', $empreendimento->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-th-large mr-2"></i>
                                        Lotes
                                    </a>
                                    <a href="{{ route('empreendimentos.mapa', $empreendimento->id) }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-map mr-2"></i>
                                        Mapa
                                    </a>
                                </div>
                                <a href="{{ route('empreendimentos.edit', $empreendimento->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            <!-- Segunda linha: Detalhes e Excluir -->
                            <div class="flex space-x-2">
                                <a href="{{ route('empreendimentos.show', $empreendimento->id) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Detalhes
                                </a>
                                <button onclick="confirmarExclusao('{{ $empreendimento->id }}', '{{ $empreendimento->nome }}', {{ $temLotesVendidos ? 'true' : 'false' }}, {{ $lotesVendidos }})" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-building text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum empreendimento encontrado</h3>
            <p class="text-gray-600 mb-6">Comece criando seu primeiro empreendimento</p>
            <a href="{{ route('empreendimentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Criar Empreendimento
            </a>
        </div>
    @endif
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Confirmar exclusão
function confirmarExclusao(empreendimentoId, nomeEmpreendimento, temLotesVendidos, qtdLotesVendidos) {
    // Se houver lotes vendidos, mostrar mensagem de erro
    if (temLotesVendidos) {
        let mensagem = 'Não é possível excluir este empreendimento pois existem lotes vendidos vinculados a ele.';
        if (qtdLotesVendidos === 1) {
            mensagem = 'Não é possível excluir este empreendimento pois existe 1 lote vendido vinculado a ele.';
        } else {
            mensagem = `Não é possível excluir este empreendimento pois existem ${qtdLotesVendidos} lotes vendidos vinculados a ele.`;
        }

        Swal.fire({
            icon: 'error',
            title: 'Não é possível excluir',
            html: mensagem + '<br><br>Para excluir este empreendimento, é necessário alterar o status dos lotes vendidos primeiro.',
            confirmButtonColor: '#6b7280',
            confirmButtonText: 'Entendi'
        });
        return;
    }

    // Se não houver lotes vendidos, mostrar confirmação normal
    Swal.fire({
        title: 'Confirmar Exclusão',
        html: `Tem certeza que deseja excluir o empreendimento <strong>"${nomeEmpreendimento}"</strong>?<br><br>Esta ação não pode ser desfeita e todos os lotes vinculados serão removidos.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/empreendimentos/${empreendimentoId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Exibir mensagens de sessão
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection

