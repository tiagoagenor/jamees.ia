@extends('layouts.app')

@section('title', 'Criar Empreendimento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-building text-blue-600 mr-2"></i>
                Novo Empreendimento
            </h1>
            <p class="text-gray-600 mt-1">Preencha os dados do empreendimento</p>
        </div>

        <form method="POST" action="{{ route('empreendimentos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Informações Básicas -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informações Básicas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('nome')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="imagem" class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
                            <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                onchange="validateFileSize(this, 10)">
                            <p class="text-xs text-gray-500 mt-1">Tamanho máximo: 10MB. Formatos aceitos: JPG, PNG, GIF, WEBP</p>
                            <p id="imagem-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('imagem')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="imagem_mapa" class="block text-sm font-medium text-gray-700 mb-1">Imagem do Mapa</label>
                            <input type="file" id="imagem_mapa" name="imagem_mapa" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                onchange="validateFileSize(this, 10)">
                            <p class="text-xs text-gray-500 mt-1">Mapa do empreendimento para visualização dos lotes. Tamanho máximo: 10MB. Formatos aceitos: JPG, PNG, GIF, WEBP</p>
                            <p id="imagem_mapa-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            @error('imagem_mapa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="zoom_default" class="block text-sm font-medium text-gray-700 mb-1">Zoom Padrão do Mapa (%)</label>
                            <input type="number" id="zoom_default" name="zoom_default" value="{{ old('zoom_default', 180) }}" min="50" max="300"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="180">
                            <p class="text-xs text-gray-500 mt-1">Valor padrão: 180%. Mínimo: 50%, Máximo: 300%</p>
                            @error('zoom_default')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="quadra_numeracao_tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Numeração das Quadras *</label>
                            <select id="quadra_numeracao_tipo" name="quadra_numeracao_tipo" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('quadra_numeracao_tipo', '1') == '1' ? 'selected' : '' }}>Numérica (1, 2, 3, 4...)</option>
                                <option value="2" {{ old('quadra_numeracao_tipo') == '2' ? 'selected' : '' }}>Alfanumérica (A, B, C, D...)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">O sistema gerará automaticamente o nome das quadras sequencialmente</p>
                            @error('quadra_numeracao_tipo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Venda -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Configurações de Venda</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="valor_m2" class="block text-sm font-medium text-gray-700 mb-1">Valor por m² (padrão)</label>
                            <input type="number" id="valor_m2" name="valor_m2" value="{{ old('valor_m2') }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00">
                            @error('valor_m2')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="maximo_parcelas" class="block text-sm font-medium text-gray-700 mb-1">Máximo de Parcelas</label>
                            <input type="number" id="maximo_parcelas" name="maximo_parcelas" value="{{ old('maximo_parcelas') }}" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ex: 60">
                            @error('maximo_parcelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Sinal -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Configurações de Sinal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sinal" class="block text-sm font-medium text-gray-700 mb-1">Sinal Obrigatório *</label>
                            <select id="sinal" name="sinal" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('sinal', '1') == '1' ? 'selected' : '' }}>Sim</option>
                                <option value="2" {{ old('sinal') == '2' ? 'selected' : '' }}>Não</option>
                            </select>
                            @error('sinal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div id="sinal-tipo-container" class="hidden">
                            <label for="sinal_tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Sinal</label>
                            <select id="sinal_tipo" name="sinal_tipo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ old('sinal_tipo') == '1' ? 'selected' : '' }}>Fixo</option>
                                <option value="2" {{ old('sinal_tipo') == '2' ? 'selected' : '' }}>Porcentagem</option>
                            </select>
                            @error('sinal_tipo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div id="sinal-valor-forma-container" class="hidden">
                            <label for="sinal_valor_forma" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Forma de Valor do Sinal
                                    <div class="relative group ml-2">
                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                            <strong>Valor:</strong> O sinal será aplicado como valor fixo (ex: R$ 10.000,00).<br><br>
                                            <strong>Porcentagem:</strong> O sinal será aplicado como percentual do valor total do lote (ex: 10% sobre R$ 100.000,00 = R$ 10.000,00).
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </label>
                            <select id="sinal_valor_forma" name="sinal_valor_forma"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="valor" {{ old('sinal_valor_forma', 'porcentagem') == 'valor' ? 'selected' : '' }}>Valor</option>
                                <option value="porcentagem" {{ old('sinal_valor_forma', 'porcentagem') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                            </select>
                            @error('sinal_valor_forma')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div id="sinal-valor-container" class="hidden">
                            <label for="sinal_valor" class="block text-sm font-medium text-gray-700 mb-1">Valor do Sinal</label>
                            <input type="number" id="sinal_valor" name="sinal_valor" value="{{ old('sinal_valor') }}" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00 ou %">
                            <p class="text-xs text-gray-500 mt-1" id="sinal-valor-hint"></p>
                            @error('sinal_valor')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Juros e Multa -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Configurações de Juros e Multa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Forma de Juros -->
                        <div>
                            <label for="juros_forma" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Forma de Juros
                                    <div class="relative group ml-2">
                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                            <strong>Valor:</strong> Juros aplicado como valor fixo por dia (ex: R$ 5,00/dia).<br><br>
                                            <strong>Porcentagem:</strong> Juros aplicado como percentual do valor principal por dia (ex: 0,5% ao dia sobre R$ 1.000,00 = R$ 5,00/dia).<br><br>
                                            <em>O juros será calculado por dia de atraso.</em>
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </label>
                            <select id="juros_forma" name="juros_forma"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="valor" {{ old('juros_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                <option value="porcentagem" {{ old('juros_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                            </select>
                            @error('juros_forma')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Juros -->
                        <div>
                            <label for="juros" class="block text-sm font-medium text-gray-700 mb-1">Juros</label>
                            <input type="number" id="juros" name="juros" value="{{ old('juros', 0) }}" step="any"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00">
                            @error('juros')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Forma de Multa -->
                        <div>
                            <label for="multa_forma" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Forma de Multa
                                    <div class="relative group ml-2">
                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                            <strong>Valor:</strong> Multa aplicada como valor fixo único quando o pagamento estiver atrasado (ex: R$ 30,00).<br><br>
                                            <strong>Porcentagem:</strong> Multa aplicada como percentual único do valor principal quando o pagamento estiver atrasado (ex: 2% sobre R$ 1.000,00 = R$ 20,00).<br><br>
                                            <em>A multa é aplicada uma única vez quando houver atraso no pagamento.</em>
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </label>
                            <select id="multa_forma" name="multa_forma"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="valor" {{ old('multa_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                <option value="porcentagem" {{ old('multa_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                            </select>
                            @error('multa_forma')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Multa -->
                        <div>
                            <label for="multa" class="block text-sm font-medium text-gray-700 mb-1">Multa</label>
                            <input type="number" id="multa" name="multa" value="{{ old('multa', 0) }}" step="any"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00">
                            @error('multa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Juros por Parcela -->
                        <div>
                            <label for="juros_por_parcela" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Juros por Parcela
                                    <div class="relative group ml-2">
                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                            Este campo define o percentual de juros que será aplicado sobre o valor de cada parcela na hora da venda do lote (ex: 2% sobre R$ 100,00 = R$ 2,00 de juros).
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </label>
                            <input type="number" id="juros_por_parcela" name="juros_por_parcela" value="{{ old('juros_por_parcela', 0) }}" step="any"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0.00">
                            <p class="text-xs text-gray-500 mt-1">Valor em porcentagem (ex: 2 para 2%)</p>
                            @error('juros_por_parcela')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('empreendimentos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Empreendimento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validação de tamanho de arquivo antes do envio
function validateFileSize(input, maxSizeMB) {
    const errorId = input.id + '-error';
    const errorElement = document.getElementById(errorId);
    const file = input.files[0];

    if (!file) {
        errorElement.classList.add('hidden');
        return true;
    }

    const fileSizeMB = file.size / (1024 * 1024);

    if (fileSizeMB > maxSizeMB) {
        errorElement.textContent = `O arquivo "${file.name}" tem ${fileSizeMB.toFixed(2)}MB, mas o tamanho máximo permitido é ${maxSizeMB}MB.`;
        errorElement.classList.remove('hidden');
        input.value = ''; // Limpar o input
        return false;
    }

    errorElement.classList.add('hidden');
    return true;
}

// Validar antes de enviar o formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const imagemInput = document.getElementById('imagem');
    const imagemMapaInput = document.getElementById('imagem_mapa');

    if (imagemInput.files.length > 0 && !validateFileSize(imagemInput, 10)) {
        e.preventDefault();
        return false;
    }

    if (imagemMapaInput.files.length > 0 && !validateFileSize(imagemMapaInput, 10)) {
        e.preventDefault();
        return false;
    }
});

document.getElementById('sinal').addEventListener('change', function() {
    const sinalTipoContainer = document.getElementById('sinal-tipo-container');
    const sinalValorFormaContainer = document.getElementById('sinal-valor-forma-container');
    const sinalValorContainer = document.getElementById('sinal-valor-container');

    if (this.value === '1') {
        sinalTipoContainer.classList.remove('hidden');
        sinalValorFormaContainer.classList.remove('hidden');
        sinalValorContainer.classList.remove('hidden');
    } else {
        sinalTipoContainer.classList.add('hidden');
        sinalValorFormaContainer.classList.add('hidden');
        sinalValorContainer.classList.add('hidden');
    }
});

document.getElementById('sinal_tipo')?.addEventListener('change', function() {
    const hint = document.getElementById('sinal-valor-hint');
    if (this.value === '1') {
        hint.textContent = 'Digite o valor fixo (ex: 10000.00)';
    } else {
        hint.textContent = 'Digite a porcentagem (ex: 10 para 10%)';
    }
});

document.getElementById('sinal_valor_forma')?.addEventListener('change', function() {
    const hint = document.getElementById('sinal-valor-hint');
    if (this.value === 'valor') {
        hint.textContent = 'Digite o valor fixo do sinal (ex: 10000.00)';
    } else {
        hint.textContent = 'Digite a porcentagem do sinal (ex: 10 para 10%)';
    }
});

// Inicializar estado
if (document.getElementById('sinal').value === '1') {
    document.getElementById('sinal').dispatchEvent(new Event('change'));
}
</script>
@endsection

