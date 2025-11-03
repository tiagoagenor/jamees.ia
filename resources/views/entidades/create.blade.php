@extends('layouts.app')

@section('title', 'Novo ' . ucfirst($tipo))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            @php
                $routeName = match($tipo) {
                    'cliente' => 'clientes',
                    'fornecedor' => 'fornecedores',
                    'funcionario' => 'funcionarios',
                    'transportadora' => 'transportadoras',
                    default => $tipo . 's'
                };
            @endphp
            <a href="{{ route($routeName . '.index') }}"
               class="text-blue-600 hover:text-blue-800 mr-4">
                ← Voltar para {{ ucfirst($tipo) }}s
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">
            @switch($tipo)
                @case('cliente')
                    <i class="fas fa-user-tie text-blue-600 mr-3"></i>Novo Cliente
                    @break
                @case('fornecedor')
                    <i class="fas fa-truck text-green-600 mr-3"></i>Novo Fornecedor
                    @break
                @case('funcionario')
                    <i class="fas fa-user text-purple-600 mr-3"></i>Novo Funcionário
                    @break
                @case('transportadora')
                    <i class="fas fa-shipping-fast text-orange-600 mr-3"></i>Nova Transportadora
                    @break
            @endswitch
        </h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route($routeName . '.store') }}">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <!-- Informações Básicas -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Informações Básicas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de Pessoa -->
                    <div>
                        <label for="tipo_pessoa" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Pessoa</label>
                        <select id="tipo_pessoa" name="tipo_pessoa" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ old('tipo_pessoa') == '1' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="2" {{ old('tipo_pessoa') == '2' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                        @error('tipo_pessoa')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Documento -->
                    <div>
                        <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                        <input type="text" id="documento" name="documento" required
                               placeholder="CPF ou CNPJ"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('documento') }}">
                        @error('documento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                        <input type="text" id="nome" name="nome" required
                               placeholder="Nome completo ou razão social"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('nome') }}">
                        @error('nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome Fantasia (apenas PJ) -->
                    <div id="nome_fantasia_field" style="display: none;">
                        <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia"
                               placeholder="Nome fantasia"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('nome_fantasia') }}">
                        @error('nome_fantasia')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                               placeholder="email@exemplo.com"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefone Comercial -->
                    <div>
                        <label for="telefone_comercial" class="block text-sm font-medium text-gray-700 mb-2">Telefone Comercial</label>
                        <input type="tel" id="telefone_comercial" name="telefone_comercial"
                               placeholder="(11) 3333-4444"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('telefone_comercial') }}">
                        @error('telefone_comercial')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Celular -->
                    <div>
                        <label for="celular" class="block text-sm font-medium text-gray-700 mb-2">Celular</label>
                        <input type="tel" id="celular" name="celular"
                               placeholder="(11) 99999-8888"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('celular') }}">
                        @error('celular')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site -->
                    <div>
                        <label for="site" class="block text-sm font-medium text-gray-700 mb-2">Site</label>
                        <input type="url" id="site" name="site"
                               placeholder="https://www.exemplo.com"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('site') }}">
                        @error('site')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($tipo === 'funcionario')
                        <!-- Vínculo com Usuário -->
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Usuário
                                <span class="text-xs text-gray-500 font-normal">(opcional)</span>
                            </label>
                            <select id="usuario_id"
                                    name="usuario_id"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione um usuário (opcional)</option>
                                @if(isset($usuarios))
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->nome }} - {{ $usuario->email }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Vincule este funcionário a um usuário do sistema</p>
                            @error('usuario_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Observação -->
                <div class="mt-6">
                    <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea id="observacao" name="observacao" rows="3"
                              placeholder="Observações adicionais..."
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('observacao') }}</textarea>
                    @error('observacao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seção de Contatos -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-address-book text-blue-600 mr-2"></i>
                        Contatos
                    </h3>

                    <div id="contatos-container">
                        <!-- Container vazio - contatos serão adicionados dinamicamente -->
                    </div>

                    <button type="button" onclick="adicionarContato()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i>
                        Adicionar Contato
                    </button>
                </div>

                <!-- Seção de Endereços -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                        Endereços
                    </h3>

                    <div id="enderecos-container">
                        <!-- Container vazio - endereços serão adicionados dinamicamente -->
                    </div>

                    <button type="button" onclick="adicionarEndereco()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i>
                        Adicionar Endereço
                    </button>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route($routeName . '.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Salvar {{ ucfirst($tipo) }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoPessoaSelect = document.getElementById('tipo_pessoa');
    const nomeFantasiaField = document.getElementById('nome_fantasia_field');
    const documentoInput = document.getElementById('documento');

    // Máscara para documento
    documentoInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (tipoPessoaSelect.value == '2') { // PJ - CNPJ
            if (value.length > 14) value = value.substring(0, 14);
            if (value.length >= 2) {
                e.target.value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            }
        } else { // PF - CPF
            if (value.length > 11) value = value.substring(0, 11);
            if (value.length >= 3) {
                e.target.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            }
        }
    });

    // Máscara para telefones
    const telefoneInputs = ['telefone_comercial', 'celular'];
    telefoneInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.substring(0, 11);

            if (value.length >= 2) {
                if (value.length <= 10) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                }
            } else if (value.length > 0) {
                e.target.value = `(${value}`;
            }
        });
    });

    // Mostrar/ocultar nome fantasia
    function toggleNomeFantasia() {
        if (tipoPessoaSelect.value == '2') {
            nomeFantasiaField.style.display = 'block';
        } else {
            nomeFantasiaField.style.display = 'none';
        }
    }

    tipoPessoaSelect.addEventListener('change', toggleNomeFantasia);
    toggleNomeFantasia(); // Executar na inicialização

    // Função para mostrar mensagens de sucesso/erro
    @if(session('success'))
        Swal.fire({
            title: 'Sucesso!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Erro!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif

    // Validação do formulário com SweetAlert2
    document.querySelector('form').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const documento = document.getElementById('documento').value.trim();

        if (!nome || !documento) {
            e.preventDefault();
            Swal.fire({
                title: 'Campos obrigatórios!',
                text: 'Por favor, preencha todos os campos obrigatórios.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
    });

    // Máscara para CEP
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('[cep]')) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        }
    });
});

// Funções para gerenciar contatos
let contatoIndex = 0;

function adicionarContato() {
    const container = document.getElementById('contatos-container');
    const contatoItem = document.createElement('div');
    contatoItem.className = 'contato-item border rounded-lg p-4 mb-4';
    contatoItem.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">Contato ${contatoIndex + 1}</h4>
            <button type="button" onclick="removerContato(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text" name="contatos[${contatoIndex}][nome]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Nome do contato">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                <input type="text" name="contatos[${contatoIndex}][contato]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Email ou telefone">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                <input type="text" name="contatos[${contatoIndex}][cargo]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Cargo/função">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observação</label>
                <input type="text" name="contatos[${contatoIndex}][observacao]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Observações">
            </div>
        </div>
    `;
    container.appendChild(contatoItem);
    contatoIndex++;
}

function removerContato(button) {
    const contatoItem = button.closest('.contato-item');
    contatoItem.remove();
}

// Funções para gerenciar endereços
let enderecoIndex = 0;

function adicionarEndereco() {
    const container = document.getElementById('enderecos-container');
    const enderecoItem = document.createElement('div');
    enderecoItem.className = 'endereco-item border rounded-lg p-4 mb-4';
    enderecoItem.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">Endereço ${enderecoIndex + 1}</h4>
            <button type="button" onclick="removerEndereco(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                <input type="text" name="enderecos[${enderecoIndex}][cep]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="00000-000">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                <input type="text" name="enderecos[${enderecoIndex}][logradouro]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="Rua, Avenida, etc.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <input type="text" name="enderecos[${enderecoIndex}][numero]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="123">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                <input type="text" name="enderecos[${enderecoIndex}][complemento]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="Apto, Sala, etc.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                <input type="text" name="enderecos[${enderecoIndex}][bairro]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="Nome do bairro">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                <input type="text" name="enderecos[${enderecoIndex}][cidade]"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="Nome da cidade">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <input type="text" name="enderecos[${enderecoIndex}][estado]" maxlength="2"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="SP">
            </div>
        </div>
    `;
    container.appendChild(enderecoItem);
    enderecoIndex++;
}

function removerEndereco(button) {
    const enderecoItem = button.closest('.endereco-item');
    enderecoItem.remove();
}
</script>
@endsection
