@extends('layouts.app')

@section('title', 'Editar Empresa - Jamees')
@section('page-title', 'Editar Empresa')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Editar Empresa</h3>
            <p class="text-sm text-gray-500">{{ $empresa->nome_fantasia }}</p>
        </div>

        <form method="POST" action="{{ route('empresas.update', $empresa) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações Básicas -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Informações Básicas</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                        <select id="tipo"
                                name="tipo"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Selecione o tipo</option>
                            @foreach(\App\Enums\EmpresaTipoEnum::options() as $value => $label)
                                <option value="{{ $value }}" {{ old('tipo', $empresa->tipo?->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome Fantasia -->
                    <div>
                        <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-1">Nome Fantasia *</label>
                        <input type="text"
                               id="nome_fantasia"
                               name="nome_fantasia"
                               value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nome_fantasia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Razão Social -->
                    <div>
                        <label for="razao_social" class="block text-sm font-medium text-gray-700 mb-1">Razão Social</label>
                        <input type="text"
                               id="razao_social"
                               name="razao_social"
                               value="{{ old('razao_social', $empresa->razao_social) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('razao_social')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNPJ -->
                    <div>
                        <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-1">CNPJ/CPF</label>
                        <input type="text"
                               id="cnpj"
                               name="cnpj"
                               value="{{ old('cnpj', $empresa->cnpj) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('cnpj')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome Referência -->
                    <div>
                        <label for="nome_referencia" class="block text-sm font-medium text-gray-700 mb-1">Nome Referência</label>
                        <input type="text"
                               id="nome_referencia"
                               name="nome_referencia"
                               value="{{ old('nome_referencia', $empresa->nome_referencia) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nome_referencia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informações Tributárias -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Informações Tributárias</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Inscrição Estadual -->
                    <div>
                        <label for="inscricao_estadual" class="block text-sm font-medium text-gray-700 mb-1">Inscrição Estadual</label>
                        <input type="text"
                               id="inscricao_estadual"
                               name="inscricao_estadual"
                               value="{{ old('inscricao_estadual', $empresa->inscricao_estadual) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('inscricao_estadual')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inscrição Estadual Isenta -->
                    <div>
                        <label for="inscricao_estadual_isenta" class="block text-sm font-medium text-gray-700 mb-1">Inscrição Estadual Isenta</label>
                        <input type="text"
                               id="inscricao_estadual_isenta"
                               name="inscricao_estadual_isenta"
                               value="{{ old('inscricao_estadual_isenta', $empresa->inscricao_estadual_isenta) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('inscricao_estadual_isenta')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inscrição Municipal -->
                    <div>
                        <label for="inscricao_municipal" class="block text-sm font-medium text-gray-700 mb-1">Inscrição Municipal</label>
                        <input type="text"
                               id="inscricao_municipal"
                               name="inscricao_municipal"
                               value="{{ old('inscricao_municipal', $empresa->inscricao_municipal) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('inscricao_municipal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNAE -->
                    <div>
                        <label for="cnae" class="block text-sm font-medium text-gray-700 mb-1">CNAE</label>
                        <input type="text"
                               id="cnae"
                               name="cnae"
                               value="{{ old('cnae', $empresa->cnae) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('cnae')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Regime Tributário -->
                    <div>
                        <label for="regime_tributario" class="block text-sm font-medium text-gray-700 mb-1">Regime Tributário</label>
                        <input type="text"
                               id="regime_tributario"
                               name="regime_tributario"
                               value="{{ old('regime_tributario', $empresa->regime_tributario) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('regime_tributario')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Regime Especial -->
                    <div>
                        <label for="regime_especial" class="block text-sm font-medium text-gray-700 mb-1">Regime Especial</label>
                        <input type="text"
                               id="regime_especial"
                               name="regime_especial"
                               value="{{ old('regime_especial', $empresa->regime_especial) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('regime_especial')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informações Pessoais (para PF) -->
            <div class="space-y-6" id="pessoa-fisica-section" style="display: none;">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Informações Pessoais</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <input type="text"
                               id="nome"
                               name="nome"
                               value="{{ old('nome', $empresa->nome) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nome')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CPF -->
                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                        <input type="text"
                               id="cpf"
                               name="cpf"
                               value="{{ old('cpf', $empresa->cpf) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('cpf')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RG -->
                    <div>
                        <label for="rg" class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                        <input type="text"
                               id="rg"
                               name="rg"
                               value="{{ old('rg', $empresa->rg) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('rg')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contatos -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Contatos</h4>
                    <button type="button"
                            id="add-contato"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-1"></i>
                        Adicionar Contato
                    </button>
                </div>

                <div id="contatos-container">
                    @foreach($empresa->contatos as $index => $contato)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-sm font-medium text-gray-900">Contato {{ $index + 1 }}</h5>
                            <button type="button" class="remove-contato text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                <select name="contatos[{{ $index }}][tipo]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="telefone" {{ $contato->tipo == 'telefone' ? 'selected' : '' }}>Telefone</option>
                                    <option value="email" {{ $contato->tipo == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="site" {{ $contato->tipo == 'site' ? 'selected' : '' }}>Site</option>
                                    <option value="whatsapp" {{ $contato->tipo == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dado</label>
                                <input type="text" name="contatos[{{ $index }}][dado]" value="{{ $contato->dado }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite o contato">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Endereços -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Endereços</h4>
                    <button type="button"
                            id="add-endereco"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-1"></i>
                        Adicionar Endereço
                    </button>
                </div>

                <div id="enderecos-container">
                    @foreach($empresa->enderecos as $index => $endereco)
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-sm font-medium text-gray-900">Endereço {{ $index + 1 }}</h5>
                            <button type="button" class="remove-endereco text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                                <input type="text" name="enderecos[{{ $index }}][cep]" value="{{ $endereco->cep }}" class="cep-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="00000-000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                                <input type="text" name="enderecos[{{ $index }}][logradouro]" value="{{ $endereco->logradouro }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rua, Avenida, etc.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                                <input type="text" name="enderecos[{{ $index }}][numero]" value="{{ $endereco->numero }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="123">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                                <input type="text" name="enderecos[{{ $index }}][complemento]" value="{{ $endereco->complemento }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apto, Sala, etc.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                                <input type="text" name="enderecos[{{ $index }}][bairro]" value="{{ $endereco->bairro }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome do bairro">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                                <input type="text" name="enderecos[{{ $index }}][uf]" value="{{ $endereco->uf }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="MG" maxlength="2">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Configurações</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status"
                                name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(\App\Enums\EmpresaStatusEnum::options() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $empresa->status->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('empresas.show', $empresa) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const pessoaFisicaSection = document.getElementById('pessoa-fisica-section');

    // Controles de contatos
    const contatosContainer = document.getElementById('contatos-container');
    const addContatoBtn = document.getElementById('add-contato');
    let contatoIndex = {{ $empresa->contatos->count() }};

    // Controles de endereços
    const enderecosContainer = document.getElementById('enderecos-container');
    const addEnderecoBtn = document.getElementById('add-endereco');
    let enderecoIndex = {{ $empresa->enderecos->count() }};

    // Toggle seção pessoa física
    function togglePessoaFisica() {
        if (tipoSelect.value === '2') { // 2 = PF
            pessoaFisicaSection.style.display = 'block';
        } else {
            pessoaFisicaSection.style.display = 'none';
        }
    }

    tipoSelect.addEventListener('change', togglePessoaFisica);
    togglePessoaFisica(); // Executar na inicialização

    // Adicionar contato
    function addContato() {
        const contatoDiv = document.createElement('div');
        contatoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
        contatoDiv.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-medium text-gray-900">Contato ${contatoIndex + 1}</h5>
                <button type="button" class="remove-contato text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="contatos[${contatoIndex}][tipo]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="telefone">Telefone</option>
                        <option value="email">Email</option>
                        <option value="site">Site</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dado</label>
                    <input type="text" name="contatos[${contatoIndex}][dado]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite o contato">
                </div>
            </div>
        `;

        contatosContainer.appendChild(contatoDiv);
        contatoIndex++;

        // Adicionar evento de remoção
        contatoDiv.querySelector('.remove-contato').addEventListener('click', function() {
            contatoDiv.remove();
        });
    }

    // Adicionar endereço
    function addEndereco() {
        const enderecoDiv = document.createElement('div');
        enderecoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
        enderecoDiv.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-medium text-gray-900">Endereço ${enderecoIndex + 1}</h5>
                <button type="button" class="remove-endereco text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                    <input type="text" name="enderecos[${enderecoIndex}][cep]" class="cep-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="00000-000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                    <input type="text" name="enderecos[${enderecoIndex}][logradouro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rua, Avenida, etc.">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                    <input type="text" name="enderecos[${enderecoIndex}][numero]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="123">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                    <input type="text" name="enderecos[${enderecoIndex}][complemento]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apto, Sala, etc.">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                    <input type="text" name="enderecos[${enderecoIndex}][bairro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome do bairro">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                    <input type="text" name="enderecos[${enderecoIndex}][uf]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="MG" maxlength="2">
                </div>
            </div>
        `;

        enderecosContainer.appendChild(enderecoDiv);
        enderecoIndex++;

        // Adicionar evento de remoção
        enderecoDiv.querySelector('.remove-endereco').addEventListener('click', function() {
            enderecoDiv.remove();
        });

        // Aplicar máscara de CEP
        const cepInput = enderecoDiv.querySelector('.cep-input');
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
            } else {
                e.target.value = value;
            }
        });
    }

    // Event listeners
    addContatoBtn.addEventListener('click', addContato);
    addEnderecoBtn.addEventListener('click', addEndereco);

    // Adicionar eventos de remoção para contatos e endereços existentes
    document.querySelectorAll('.remove-contato').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.border').remove();
        });
    });

    document.querySelectorAll('.remove-endereco').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.border').remove();
        });
    });

    // Aplicar máscara de CEP aos campos existentes
    document.querySelectorAll('.cep-input').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
            } else {
                e.target.value = value;
            }
        });
    });
});
</script>
@endsection
