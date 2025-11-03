@extends('layouts.app')

@section('title', 'Editar Lote')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-map-marked-alt text-blue-600 mr-2"></i>
                Editar Lote - {{ $lote->empreendimento->nome }}
            </h1>
            <p class="text-gray-600 mt-1">Atualize os dados do lote</p>
        </div>

        <form method="POST" action="{{ route('lotes.update', $lote->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Informações Básicas -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                                Nome do Lote <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $lote->nome) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ex: Lote 001">
                            @error('nome')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="quadra_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Quadra <span class="text-red-500">*</span>
                            </label>
                            <select id="quadra_id" name="quadra_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecione uma quadra</option>
                                @foreach($quadras as $quadra)
                                    <option value="{{ $quadra->id }}" {{ old('quadra_id', $lote->quadra_id) == $quadra->id ? 'selected' : '' }}>{{ $quadra->nome }}</option>
                                @endforeach
                            </select>
                            @error('quadra_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="lote_status_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="lote_status_id" name="lote_status_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @foreach($status as $s)
                                    <option value="{{ $s->id }}" {{ old('lote_status_id', $lote->lote_status_id) == $s->id ? 'selected' : '' }}>{{ $s->nome }}</option>
                                @endforeach
                            </select>
                            @error('lote_status_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Dimensões -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Dimensões (em metros)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="frente" class="block text-sm font-medium text-gray-700 mb-1">Frente</label>
                            <input type="number" id="frente" name="frente" value="{{ old('frente', $lote->frente) }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '2' ? 'disabled' : '' }}>
                            @error('frente')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="fundo" class="block text-sm font-medium text-gray-700 mb-1">Fundo</label>
                            <input type="number" id="fundo" name="fundo" value="{{ old('fundo', $lote->fundo) }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '2' ? 'disabled' : '' }}>
                            @error('fundo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="lateral_direita" class="block text-sm font-medium text-gray-700 mb-1">Lateral Direita</label>
                            <input type="number" id="lateral_direita" name="lateral_direita" value="{{ old('lateral_direita', $lote->lateral_direita) }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '2' ? 'disabled' : '' }}>
                            @error('lateral_direita')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="lateral_esquerda" class="block text-sm font-medium text-gray-700 mb-1">Lateral Esquerda</label>
                            <input type="number" id="lateral_esquerda" name="lateral_esquerda" value="{{ old('lateral_esquerda', $lote->lateral_esquerda) }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '2' ? 'disabled' : '' }}>
                            @error('lateral_esquerda')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Valores -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Valores</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="m2_tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cálculo de Área *</label>
                            <select id="m2_tipo" name="m2_tipo" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '1' ? 'selected' : '' }}>Calculado Automaticamente</option>
                                <option value="2" {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '2' ? 'selected' : '' }}>Manual</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Escolha se a área será calculada automaticamente ou inserida manualmente</p>
                            @error('m2_tipo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="m2" class="block text-sm font-medium text-gray-700 mb-1">
                                Área (m²) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="m2" name="m2" value="{{ old('m2', $lote->m2) }}" step="0.01" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '1' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                placeholder="Calculado automaticamente (frente × fundo + laterais)"
                                {{ old('m2_tipo', $lote->m2_tipo ?? '1') == '1' ? 'readonly' : '' }}>
                            <p class="text-xs text-gray-500 mt-1" id="m2-hint">
                                @if(old('m2_tipo', $lote->m2_tipo ?? '1') == '1')
                                    Preencha as dimensões para calcular automaticamente
                                @else
                                    Digite o valor da área manualmente
                                @endif
                            </p>
                            @error('m2')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="valor_m2" class="block text-sm font-medium text-gray-700 mb-1">
                                Valor por m² <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="valor_m2" name="valor_m2" value="{{ old('valor_m2', $lote->valor_m2 ?? $lote->empreendimento->valor_m2) }}" step="0.01" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="{{ $lote->empreendimento->valor_m2 ? 'Padrão: R$ ' . number_format($lote->empreendimento->valor_m2, 2, ',', '.') : 'Digite o valor' }}">
                            @error('valor_m2')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor Total</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 min-h-[42px] flex items-center">
                                <span id="valor-display" class="text-gray-900 font-medium">
                                    @if(old('valor', $lote->valor))
                                        R$ {{ number_format(old('valor', $lote->valor), 2, ',', '.') }}
                                    @else
                                        R$ 0,00
                                    @endif
                                </span>
                            </div>
                            <input type="hidden" id="valor" name="valor" value="{{ old('valor', $lote->valor) }}">
                            <p class="text-xs text-gray-500 mt-1">Calculado automaticamente (Área × Valor/m²)</p>
                            @error('valor')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label for="observacao" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea id="observacao" name="observacao" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('observacao', $lote->observacao) }}</textarea>
                    @error('observacao')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('lotes.index', $lote->empreendimento_id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Gerenciar habilitação/desabilitação dos campos baseado no tipo
document.getElementById('m2_tipo')?.addEventListener('change', function() {
    const m2Input = document.getElementById('m2');
    const m2Hint = document.getElementById('m2-hint');
    const frenteInput = document.getElementById('frente');
    const fundoInput = document.getElementById('fundo');
    const lateralDireitaInput = document.getElementById('lateral_direita');
    const lateralEsquerdaInput = document.getElementById('lateral_esquerda');

    if (this.value === '1') {
        // Calculado - usar readonly ao invés de disabled para manter o valor no form, habilitar campos de dimensões
        m2Input.disabled = false;
        m2Input.setAttribute('readonly', 'readonly');
        m2Input.classList.add('bg-gray-100', 'cursor-not-allowed');
        m2Hint.textContent = 'Preencha as dimensões para calcular automaticamente';

        // Habilitar campos de dimensões
        frenteInput.disabled = false;
        frenteInput.removeAttribute('readonly');
        frenteInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        fundoInput.disabled = false;
        fundoInput.removeAttribute('readonly');
        fundoInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        lateralDireitaInput.disabled = false;
        lateralDireitaInput.removeAttribute('readonly');
        lateralDireitaInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        lateralEsquerdaInput.disabled = false;
        lateralEsquerdaInput.removeAttribute('readonly');
        lateralEsquerdaInput.classList.remove('bg-gray-100', 'cursor-not-allowed');

        // Recalcular ao mudar para calculado
        calcularM2();
    } else {
        // Manual - habilitar campo m², desabilitar campos de dimensões
        m2Input.disabled = false;
        m2Input.removeAttribute('readonly');
        m2Input.classList.remove('bg-gray-100', 'cursor-not-allowed');
        m2Hint.textContent = 'Digite o valor da área manualmente';

        // Desabilitar campos de dimensões
        frenteInput.disabled = true;
        frenteInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        fundoInput.disabled = true;
        fundoInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        lateralDireitaInput.disabled = true;
        lateralDireitaInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        lateralEsquerdaInput.disabled = true;
        lateralEsquerdaInput.classList.add('bg-gray-100', 'cursor-not-allowed');
    }
});

// Calcular m² automaticamente (apenas se tipo for calculado)
document.getElementById('frente')?.addEventListener('input', calcularM2);
document.getElementById('fundo')?.addEventListener('input', calcularM2);
document.getElementById('lateral_direita')?.addEventListener('input', calcularM2);
document.getElementById('lateral_esquerda')?.addEventListener('input', calcularM2);

function calcularM2() {
    const m2Tipo = document.getElementById('m2_tipo')?.value;

    // Só calcular se o tipo for calculado
    if (m2Tipo !== '1') {
        return;
    }

    const frente = parseFloat(document.getElementById('frente')?.value || 0);
    const fundo = parseFloat(document.getElementById('fundo')?.value || 0);
    const lateralDireita = parseFloat(document.getElementById('lateral_direita')?.value || 0);
    const lateralEsquerda = parseFloat(document.getElementById('lateral_esquerda')?.value || 0);
    const m2Input = document.getElementById('m2');

    // Calcular área base (frente × fundo)
    let area = 0;

    if (frente > 0 && fundo > 0) {
        area = frente * fundo;
    }

    // Adicionar área das laterais (se preenchidas)
    // Lateral direita: considerada como retângulo adicional (lateral × fundo)
    if (lateralDireita > 0 && fundo > 0) {
        area += lateralDireita * fundo;
    }

    // Lateral esquerda: considerada como retângulo adicional (lateral × fundo)
    if (lateralEsquerda > 0 && fundo > 0) {
        area += lateralEsquerda * fundo;
    }

    // Sempre atualizar o campo m² se houver área calculada
    if (area > 0) {
        m2Input.value = area.toFixed(2);
        calcularValor();
    } else {
        // Se todos os campos foram limpos, limpar também o m²
        m2Input.value = '';
    }
}

// Calcular valor automaticamente
document.getElementById('m2')?.addEventListener('input', calcularValor);
document.getElementById('valor_m2')?.addEventListener('input', calcularValor);

function calcularValor() {
    const m2 = parseFloat(document.getElementById('m2')?.value || 0);
    const valorM2 = parseFloat(document.getElementById('valor_m2')?.value || 0);
    const valorInput = document.getElementById('valor');
    const valorDisplay = document.getElementById('valor-display');

    if (m2 > 0 && valorM2 > 0) {
        const valor = m2 * valorM2;
        valorInput.value = valor.toFixed(2);
        // Formatar para Real brasileiro
        valorDisplay.textContent = formatarReal(valor);
    } else {
        valorInput.value = '';
        valorDisplay.textContent = 'R$ 0,00';
    }
}

function formatarReal(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

// Inicializar estado ao carregar
document.addEventListener('DOMContentLoaded', function() {
    const m2TipoSelect = document.getElementById('m2_tipo');
    if (m2TipoSelect) {
        m2TipoSelect.dispatchEvent(new Event('change'));
    }
    // Calcular valor inicial se houver dados
    calcularValor();
});
</script>
@endsection

