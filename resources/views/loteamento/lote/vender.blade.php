@extends('layouts.app')

@section('title', 'Vender Lote - ' . $lote->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Vender Lote</h1>
            <p class="text-gray-600">Empreendimento: <strong>{{ $empreendimento->nome }}</strong></p>
            <p class="text-gray-600">Lote: <strong>{{ $lote->nome }}</strong></p>
        </div>

        <!-- Informações do Lote -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800 font-semibold mb-2">
                <strong>Informações do Lote:</strong>
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-blue-700">
                @if($lote->valor)
                    <div>
                        <span class="text-sm">Valor:</span>
                        <strong class="block text-lg">R$ {{ number_format($lote->valor, 2, ',', '.') }}</strong>
                    </div>
                @endif
                @if($lote->m2)
                    <div>
                        <span class="text-sm">Área:</span>
                        <strong class="block text-lg">{{ number_format($lote->m2, 2, ',', '.') }} m²</strong>
                    </div>
                @endif
                @if($lote->status)
                    <div>
                        <span class="text-sm">Status:</span>
                        <strong class="block text-lg">{{ $lote->status->nome }}</strong>
                    </div>
                @endif
                @if($empreendimento->juros_por_parcela)
                    <div>
                        <span class="text-sm">Juros por Parcela:</span>
                        <strong class="block text-lg">{{ number_format($empreendimento->juros_por_parcela, 2, ',', '.') }}%</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Formulário de Venda -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Dados da Venda</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Select de Clientes -->
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Cliente <span class="text-red-500">*</span>
                    </label>
                    <select name="cliente_id" id="cliente_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione um cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Select de Quantidade de Parcelas -->
                <div>
                    <label for="quantidade_parcelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantidade de Parcelas <span class="text-red-500">*</span>
                    </label>
                    <select name="quantidade_parcelas" id="quantidade_parcelas"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecione a quantidade</option>
                        @for($i = 1; $i <= $maxParcelas; $i++)
                            <option value="{{ $i }}">{{ $i }} parcela{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Data da Primeira Parcela -->
                <div>
                    <label for="data_primeira_parcela" class="block text-sm font-medium text-gray-700 mb-2">
                        Data da Primeira Parcela
                    </label>
                    <input type="date"
                           name="data_primeira_parcela"
                           id="data_primeira_parcela"
                           value="{{ date('Y-m-d', strtotime('+1 month')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <!-- Valor de Entrada -->
                <div>
                    <label for="valor_entrada" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor de Entrada
                    </label>
                    <input type="number"
                           name="valor_entrada"
                           id="valor_entrada"
                           step="0.01"
                           min="{{ $empreendimento->sinal == 1 && $empreendimento->sinal_valor ? ($empreendimento->sinal_tipo == 2 ? ($lote->valor * $empreendimento->sinal_valor / 100) : $empreendimento->sinal_valor) : 0 }}"
                           value="{{ $empreendimento->sinal == 1 && $empreendimento->sinal_valor ? ($empreendimento->sinal_tipo == 2 ? ($lote->valor * $empreendimento->sinal_valor / 100) : $empreendimento->sinal_valor) : 0 }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0,00">
                </div>

                <!-- Checkbox Parcela Anual -->
                <div class="flex items-end">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox"
                               name="parcela_anual"
                               id="parcela_anual"
                               disabled
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="ml-2 text-sm font-medium text-gray-700">
                            Parcela Anual
                        </span>
                    </label>
                </div>
            </div>

            <!-- Campos de Valor da Parcela Anual por Ano (aparece quando checkbox está marcado) -->
            <div id="valorParcelaAnualContainer" class="hidden mb-4">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Valores das Parcelas Anuais
                    </label>

                    <!-- Input para preencher todos os valores de uma vez -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <label for="valor_parcela_anual_todos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preencher todos os valores com:
                                </label>
                                <input type="number"
                                       id="valor_parcela_anual_todos"
                                       step="0.01"
                                       min="0"
                                       value="0"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00">
                            </div>
                            <button type="button"
                                    id="aplicarTodosValoresBtn"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Aplicar a Todos
                            </button>
                        </div>
                    </div>

                    <!-- Container para inputs individuais com scroll -->
                    <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        <div id="parcelasAnuaisInputs" class="space-y-2">
                            <!-- Inputs serão inseridos aqui via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão Gerar Parcelas -->
            <div class="mb-4">
                <button type="button" id="gerarParcelasBtn"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Gerar Parcelas
                </button>
            </div>
        </div>

        <!-- Área de Parcelas Geradas -->
        <div id="parcelasContainer" class="hidden">
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Parcelas Geradas</h2>
                    <div class="flex flex-wrap items-center gap-4">
                        <div id="resumoValores" class="flex flex-wrap items-center gap-4 text-sm">
                            <!-- Valores serão inseridos aqui via JavaScript -->
                        </div>
                        <button id="salvarVendaBtn"
                                class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Venda
                        </button>
                    </div>
                </div>

                <!-- Tabela de Parcelas -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parcela</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor sem Juros</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor com Juros</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                            </tr>
                        </thead>
                        <tbody id="parcelasTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Parcelas serão inseridas aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div id="paginacaoContainer" class="mt-4 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 pt-4 gap-4">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-700">
                            Mostrando <span id="mostrandoDe">0</span> a <span id="mostrandoAte">0</span> de <span id="totalParcelas">0</span> parcelas
                        </p>
                    </div>
                    <div class="flex items-center space-x-1 flex-wrap justify-center">
                        <button id="btnPrimeira"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                title="Primeira página">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button id="btnAnterior"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Anterior
                        </button>
                        <div id="paginacaoNumeros" class="flex items-center space-x-1 flex-wrap justify-center">
                            <!-- Números de página serão inseridos aqui -->
                        </div>
                        <button id="btnProximo"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Próximo
                        </button>
                        <button id="btnUltima"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                title="Última página">
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão Voltar -->
        <div class="mt-6">
            <a href="{{ route('loteamentos.mapa.view', $empreendimento->id) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar ao Mapa
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let todasParcelas = [];
    let paginaAtual = 1;
    const itensPorPagina = 10;

    // Valor do lote
    const valorLote = {{ $lote->valor ?? 0 }};

    // Configurações do empreendimento
    const empreendimentoConfig = {
        juros: {{ $empreendimento->juros ?? 0 }},
        jurosForma: '{{ $empreendimento->juros_forma ?? 'valor' }}',
        multa: {{ $empreendimento->multa ?? 0 }},
        multaForma: '{{ $empreendimento->multa_forma ?? 'valor' }}',
        sinal: {{ $empreendimento->sinal ?? 2 }},
        sinalTipo: {{ $empreendimento->sinal_tipo ?? 1 }},
        sinalValor: {{ $empreendimento->sinal_valor ?? 0 }}
    };

    // Calcular valor mínimo de entrada (valor do sinal configurado no empreendimento)
    let valorMinimoEntrada = 0;
    if (empreendimentoConfig.sinal === 1 && empreendimentoConfig.sinalValor > 0) {
        if (empreendimentoConfig.sinalTipo === 2) {
            // Porcentagem
            valorMinimoEntrada = (valorLote * empreendimentoConfig.sinalValor) / 100;
        } else {
            // Valor fixo
            valorMinimoEntrada = empreendimentoConfig.sinalValor;
        }
    }

    // Preencher campo de entrada com o valor mínimo e atualizar o atributo min
    const inputEntrada = document.getElementById('valor_entrada');
    if (valorMinimoEntrada > 0) {
        inputEntrada.value = valorMinimoEntrada.toFixed(2);
        inputEntrada.setAttribute('min', valorMinimoEntrada.toFixed(2));
    }

    // Habilitar/desabilitar checkbox de parcela anual baseado na quantidade de parcelas
    document.getElementById('quantidade_parcelas').addEventListener('change', function() {
        const checkboxAnual = document.getElementById('parcela_anual');
        const quantidade = parseInt(this.value);

        if (quantidade && quantidade > 0) {
            checkboxAnual.disabled = false;
            // Se já estava marcado, atualizar os inputs
            if (checkboxAnual.checked) {
                atualizarInputsParcelasAnuais(quantidade);
            }
        } else {
            checkboxAnual.disabled = true;
            checkboxAnual.checked = false;
            document.getElementById('valorParcelaAnualContainer').classList.add('hidden');
        }
    });

    // Mostrar/ocultar campos de valores das parcelas anuais
    document.getElementById('parcela_anual').addEventListener('change', function() {
        const container = document.getElementById('valorParcelaAnualContainer');
        const quantidade = parseInt(document.getElementById('quantidade_parcelas').value);

        if (this.checked && quantidade > 0) {
            atualizarInputsParcelasAnuais(quantidade);
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            document.getElementById('parcelasAnuaisInputs').innerHTML = '';
        }
    });

    function atualizarInputsParcelasAnuais(quantidadeMeses) {
        // Calcular quantos anos serão necessários (ex: 36 meses = 3 anos)
        const totalAnos = Math.ceil(quantidadeMeses / 12);
        // O ano inicial não tem parcela anual, então são (totalAnos - 1) inputs
        const anosComParcelaAnual = totalAnos - 1;

        const container = document.getElementById('parcelasAnuaisInputs');
        container.innerHTML = '';

        if (anosComParcelaAnual <= 0) {
            container.innerHTML = '<p class="text-sm text-gray-500">A quantidade de parcelas selecionada não gera anos adicionais para parcela anual.</p>';
            return;
        }

        const hoje = new Date();
        const anoAtual = hoje.getFullYear();

        // Criar tabela mais compacta
        const table = document.createElement('table');
        table.className = 'min-w-full divide-y divide-gray-200';
        table.innerHTML = `
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                        <input type="checkbox"
                               id="selecionarTodosAnuais"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ano</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="parcelasAnuaisTbody">
            </tbody>
        `;
        container.appendChild(table);

        const tbody = document.getElementById('parcelasAnuaisTbody');

        for (let i = 1; i <= anosComParcelaAnual; i++) {
            const anoParcela = anoAtual + i;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            tr.innerHTML = `
                <td class="px-3 py-2 whitespace-nowrap text-center">
                    <input type="checkbox"
                           name="parcela_anual_ativa_ano_${i}"
                           id="parcela_anual_ativa_ano_${i}"
                           data-ano="${anoParcela}"
                           checked
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 checkbox-parcela-anual">
                </td>
                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${anoParcela}
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <input type="number"
                           name="parcela_anual_ano_${i}"
                           id="parcela_anual_ano_${i}"
                           data-ano="${anoParcela}"
                           step="0.01"
                           min="0"
                           value="0"
                           class="w-full max-w-xs border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 input-parcela-anual"
                           placeholder="0,00">
                </td>
            `;
            tbody.appendChild(tr);
        }

        // Adicionar evento para habilitar/desabilitar input baseado no checkbox
        tbody.querySelectorAll('.checkbox-parcela-anual').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.input-parcela-anual');
                if (this.checked) {
                    input.disabled = false;
                    input.classList.remove('bg-gray-100', 'cursor-not-allowed');
                } else {
                    input.disabled = true;
                    input.value = '0';
                    input.classList.add('bg-gray-100', 'cursor-not-allowed');
                }
            });
        });

        // Checkbox "Selecionar Todos"
        const selecionarTodos = document.getElementById('selecionarTodosAnuais');
        selecionarTodos.addEventListener('change', function() {
            const checkboxes = tbody.querySelectorAll('.checkbox-parcela-anual');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    }

    // Botão para aplicar valor a todos os campos
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'aplicarTodosValoresBtn') {
            const valorTodos = parseFloat(document.getElementById('valor_parcela_anual_todos').value) || 0;
            const quantidade = parseInt(document.getElementById('quantidade_parcelas').value);

            if (!quantidade || quantidade <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Por favor, selecione a quantidade de parcelas primeiro.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            const totalAnos = Math.ceil(quantidade / 12);
            const anosComParcelaAnual = totalAnos - 1;

            for (let i = 1; i <= anosComParcelaAnual; i++) {
                const input = document.getElementById(`parcela_anual_ano_${i}`);
                const checkbox = document.getElementById(`parcela_anual_ativa_ano_${i}`);
                if (input && checkbox && checkbox.checked) {
                    input.value = valorTodos.toFixed(2);
                }
            }

            // Feedback visual
            const btn = e.target;
            const originalText = btn.textContent;
            btn.textContent = 'Aplicado!';
            btn.classList.add('bg-green-600', 'hover:bg-green-700');
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');

            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 1500);
        }
    });

    document.getElementById('gerarParcelasBtn').addEventListener('click', function() {
        const clienteId = document.getElementById('cliente_id').value;
        const quantidadeParcelas = parseInt(document.getElementById('quantidade_parcelas').value);
        const temParcelaAnual = document.getElementById('parcela_anual').checked;

        if (!clienteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, selecione um cliente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (!quantidadeParcelas || quantidadeParcelas < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, selecione a quantidade de parcelas.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (valorLote <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'O lote não possui valor definido.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        // Validar parcelas anuais se o checkbox estiver marcado
        // A validação será feita no backend, então removemos a validação aqui
        // para permitir que o backend processe e retorne erro se necessário

        gerarParcelas(quantidadeParcelas);
    });

    function gerarParcelas(quantidade) {
        // Desabilitar botão durante o processamento
        const btnGerar = document.getElementById('gerarParcelasBtn');
        btnGerar.disabled = true;
        btnGerar.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Gerando...';

        // Obter valores do formulário
        const clienteId = document.getElementById('cliente_id').value;
        const valorEntrada = parseFloat(document.getElementById('valor_entrada').value) || 0;
        const temParcelaAnual = document.getElementById('parcela_anual').checked;

        // Coletar parcelas anuais
        const parcelasAnuais = [];
        if (temParcelaAnual) {
            // Buscar todos os checkboxes e inputs de parcelas anuais
            const checkboxesAnuais = document.querySelectorAll('.checkbox-parcela-anual');
            const inputsAnuais = document.querySelectorAll('.input-parcela-anual');

            checkboxesAnuais.forEach((checkbox, index) => {
                const input = inputsAnuais[index];
                if (checkbox && input) {
                    const ano = parseInt(input.getAttribute('data-ano')) || 0;
                    const valor = parseFloat(input.value) || 0;
                    const ativa = checkbox.checked;

                    parcelasAnuais.push({
                        ano: ano,
                        valor: valor,
                        ativa: ativa
                    });
                }
            });
        }

        // Obter data da primeira parcela
        const dataPrimeiraParcela = document.getElementById('data_primeira_parcela').value;

        if (!dataPrimeiraParcela) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, selecione a data da primeira parcela.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        // Preparar dados para enviar ao backend
        const dados = {
            cliente_id: clienteId,
            quantidade_parcelas: quantidade,
            data_primeira_parcela: dataPrimeiraParcela,
            valor_entrada: valorEntrada,
            parcela_anual: temParcelaAnual,
            parcelas_anuais: parcelasAnuais
        };

        // Fazer requisição ao backend
        fetch('{{ route("loteamentos.lote.gerar-parcelas", [$empreendimento->id, $lote->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(dados)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.error,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
                btnGerar.disabled = false;
                btnGerar.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Gerar Parcelas';
                return;
            }

               // Atualizar parcelas com dados do backend
               todasParcelas = data.parcelas;

               // Atualizar resumo de valores
               atualizarResumoValores(data.resumo);

               paginaAtual = 1;
               exibirParcelas();
               document.getElementById('parcelasContainer').classList.remove('hidden');

            // Reabilitar botão
            btnGerar.disabled = false;
            btnGerar.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Gerar Parcelas';
        })
        .catch(error => {
            console.error('Erro ao gerar parcelas:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao gerar parcelas. Por favor, tente novamente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            btnGerar.disabled = false;
            btnGerar.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Gerar Parcelas';
        });
    }

    function exibirParcelas() {
        const tbody = document.getElementById('parcelasTableBody');
        tbody.innerHTML = '';

        const inicio = (paginaAtual - 1) * itensPorPagina;
        const fim = Math.min(inicio + itensPorPagina, todasParcelas.length);
        const parcelasPagina = todasParcelas.slice(inicio, fim);

        parcelasPagina.forEach((parcela) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';

            // Contar total de parcelas do mesmo tipo
            const parcelasMesmoTipo = todasParcelas.filter(p => p.tipo === parcela.tipo);
            const totalMesmoTipo = parcelasMesmoTipo.length;

            // Copiar o array antes de ordenar (para não alterar o original)
            const parcelasOrdenadas = [...parcelasMesmoTipo].sort((a, b) => {
                return new Date(a.vencimento) - new Date(b.vencimento);
            });

            // Encontrar o índice da parcela atual por referência
            let indiceParcela = parcelasOrdenadas.findIndex(p => p === parcela);

            // Caso não encontre (por cópias de objetos), comparar pelos campos
            if (indiceParcela === -1) {
                indiceParcela = parcelasOrdenadas.findIndex(p =>
                    p.numero === parcela.numero &&
                    String(p.vencimento) === String(parcela.vencimento) &&
                    Number(p.valor) === Number(parcela.valor)
                );
            }

            const numeroExibicao = parcela.tipo === 'Mensal'
                ? `${indiceParcela + 1}/${totalMesmoTipo}`
                : `Anual ${indiceParcela + 1}/${totalMesmoTipo}`;

            // Cor diferente para parcela anual
            const tipoClass = parcela.tipo === 'Anual'
                ? 'bg-blue-100 text-blue-800'
                : 'bg-gray-100 text-gray-800';

            // Para parcelas anuais, não há juros por parcela
            const valorSemJuros = parcela.valor_sem_juros !== undefined
                ? parcela.valor_sem_juros
                : parcela.valor;

            const valorComJuros = parcela.valor;

            tr.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${numeroExibicao}
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${tipoClass}">
                        ${parcela.tipo}
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                    R$ ${formatarMoeda(valorSemJuros)}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                    R$ ${formatarMoeda(valorComJuros)}
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                    ${formatarData(parcela.vencimento)}
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Atualizar informações de paginação
        document.getElementById('mostrandoDe').textContent = todasParcelas.length > 0 ? inicio + 1 : 0;
        document.getElementById('mostrandoAte').textContent = fim;
        document.getElementById('totalParcelas').textContent = todasParcelas.length;

        // Atualizar botões de navegação
        const totalPaginas = Math.ceil(todasParcelas.length / itensPorPagina);
        document.getElementById('btnPrimeira').disabled = paginaAtual === 1;
        document.getElementById('btnAnterior').disabled = paginaAtual === 1;
        document.getElementById('btnProximo').disabled = paginaAtual >= totalPaginas;
        document.getElementById('btnUltima').disabled = paginaAtual >= totalPaginas;

        // Atualizar números de página
        atualizarPaginacao();
    }


    function atualizarPaginacao() {
        const totalPaginas = Math.ceil(todasParcelas.length / itensPorPagina);
        const container = document.getElementById('paginacaoNumeros');
        container.innerHTML = '';

        if (totalPaginas <= 1) {
            return;
        }

        // Mostrar no máximo 7 números de página
        let inicioPagina = Math.max(1, paginaAtual - 3);
        let fimPagina = Math.min(totalPaginas, paginaAtual + 3);

        // Ajustar se estiver muito próximo do início ou fim
        if (paginaAtual <= 4) {
            inicioPagina = 1;
            fimPagina = Math.min(7, totalPaginas);
        } else if (paginaAtual >= totalPaginas - 3) {
            inicioPagina = Math.max(1, totalPaginas - 6);
            fimPagina = totalPaginas;
        }

        // Adicionar "..." no início se necessário
        if (inicioPagina > 1) {
            const btnInicio = document.createElement('button');
            btnInicio.className = 'px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50';
            btnInicio.textContent = '1';
            btnInicio.addEventListener('click', () => {
                paginaAtual = 1;
                exibirParcelas();
            });
            container.appendChild(btnInicio);

            if (inicioPagina > 2) {
                const span = document.createElement('span');
                span.className = 'px-2 text-sm text-gray-500';
                span.textContent = '...';
                container.appendChild(span);
            }
        }

        // Adicionar números de página
        for (let i = inicioPagina; i <= fimPagina; i++) {
            const btn = document.createElement('button');
            btn.className = `px-3 py-1 text-sm border rounded-md ${
                i === paginaAtual
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'border-gray-300 hover:bg-gray-50'
            }`;
            btn.textContent = i;
            btn.addEventListener('click', () => {
                paginaAtual = i;
                exibirParcelas();
            });
            container.appendChild(btn);
        }

        // Adicionar "..." no final se necessário
        if (fimPagina < totalPaginas) {
            if (fimPagina < totalPaginas - 1) {
                const span = document.createElement('span');
                span.className = 'px-2 text-sm text-gray-500';
                span.textContent = '...';
                container.appendChild(span);
            }

            const btnFim = document.createElement('button');
            btnFim.className = 'px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50';
            btnFim.textContent = totalPaginas;
            btnFim.addEventListener('click', () => {
                paginaAtual = totalPaginas;
                exibirParcelas();
            });
            container.appendChild(btnFim);
        }
    }

    document.getElementById('btnPrimeira').addEventListener('click', () => {
        paginaAtual = 1;
        exibirParcelas();
    });

    document.getElementById('btnAnterior').addEventListener('click', () => {
        if (paginaAtual > 1) {
            paginaAtual--;
            exibirParcelas();
        }
    });

    document.getElementById('btnProximo').addEventListener('click', () => {
        const totalPaginas = Math.ceil(todasParcelas.length / itensPorPagina);
        if (paginaAtual < totalPaginas) {
            paginaAtual++;
            exibirParcelas();
        }
    });

    document.getElementById('btnUltima').addEventListener('click', () => {
        const totalPaginas = Math.ceil(todasParcelas.length / itensPorPagina);
        paginaAtual = totalPaginas;
        exibirParcelas();
    });

    function formatarMoeda(valor) {
        return new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(valor);
    }

    function formatarData(data) {
        const date = new Date(data + 'T00:00:00');
        return date.toLocaleDateString('pt-BR');
    }

    function atualizarResumoValores(resumo) {
        const container = document.getElementById('resumoValores');

        if (!resumo) {
            container.innerHTML = '';
            return;
        }

        const valorEntrada = resumo.valor_entrada || 0;
        const somaTotalParcelas = resumo.soma_total_parcelas || 0;
        const valorTotal = resumo.valor_total || 0;

        container.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Valor Total:</span>
                <span class="text-lg font-bold text-blue-600">R$ ${formatarMoeda(valorTotal)}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Entrada:</span>
                <span class="text-base font-semibold text-gray-800">R$ ${formatarMoeda(valorEntrada)}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Total Parcelas:</span>
                <span class="text-base font-semibold text-gray-800">R$ ${formatarMoeda(somaTotalParcelas)}</span>
            </div>
        `;
    }

    // Event listener para botão Salvar Venda
    document.getElementById('salvarVendaBtn').addEventListener('click', function() {
        const clienteId = document.getElementById('cliente_id').value;

        if (!clienteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, selecione um cliente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (!todasParcelas || todasParcelas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, gere as parcelas antes de salvar.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        Swal.fire({
            title: 'Confirmar Venda',
            text: 'Deseja realmente salvar esta venda? As parcelas serão criadas em contas-a-receber.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, salvar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                salvarVenda();
            }
        });
    });

    function salvarVenda() {
        const btnSalvar = document.getElementById('salvarVendaBtn');
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';

        const valorEntrada = parseFloat(document.getElementById('valor_entrada').value) || 0;

        const dados = {
            cliente_id: document.getElementById('cliente_id').value,
            parcelas: todasParcelas,
            valor_entrada: valorEntrada
        };

        fetch('{{ route("loteamentos.lote.salvar-venda", [$empreendimento->id, $lote->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(dados)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.error,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
                btnSalvar.disabled = false;
                btnSalvar.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Salvar Venda';
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: data.message || 'Venda salva com sucesso!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            }).then(() => {
                // Redirecionar para a página de vendas
                window.location.href = '{{ route("loteamentos.vendas.index") }}';
            });
        })
        .catch(error => {
            console.error('Erro ao salvar venda:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao salvar venda. Por favor, tente novamente.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Salvar Venda';
        });
    }
</script>
@endpush
@endsection
