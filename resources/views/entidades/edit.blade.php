@extends('layouts.app')

@section('title', 'Editar ' . ucfirst($tipo))

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
            <a href="{{ route($routeName . '.show', $entidade) }}"
               class="text-blue-600 hover:text-blue-800 mr-4">
                ← Voltar para detalhes
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">
            @switch($tipo)
                @case('cliente')
                    <i class="fas fa-user-tie text-blue-600 mr-3"></i>Editar Cliente
                    @break
                @case('fornecedor')
                    <i class="fas fa-truck text-green-600 mr-3"></i>Editar Fornecedor
                    @break
                @case('funcionario')
                    <i class="fas fa-user text-purple-600 mr-3"></i>Editar Funcionário
                    @break
                @case('transportadora')
                    <i class="fas fa-shipping-fast text-orange-600 mr-3"></i>Editar Transportadora
                    @break
            @endswitch
        </h1>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route($routeName . '.update', $entidade) }}">
            @csrf
            @method('PUT')

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
                            <option value="1" {{ old('tipo_pessoa', $entidade->tipo_pessoa) == '1' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="2" {{ old('tipo_pessoa', $entidade->tipo_pessoa) == '2' ? 'selected' : '' }}>Pessoa Jurídica</option>
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
                               value="{{ old('documento', $entidade->documento_formatado) }}">
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
                               value="{{ old('nome', $entidade->nome) }}">
                        @error('nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome Fantasia (apenas PJ) -->
                    <div id="nome_fantasia_field" style="display: {{ $entidade->tipo_pessoa == 2 ? 'block' : 'none' }};">
                        <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia"
                               placeholder="Nome fantasia"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('nome_fantasia', $entidade->nome_fantasia) }}">
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
                               value="{{ old('email', $entidade->email) }}">
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
                               value="{{ old('telefone_comercial', $entidade->telefone_comercial_formatado) }}">
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
                               value="{{ old('celular', $entidade->celular_formatado) }}">
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
                               value="{{ old('site', $entidade->site) }}">
                        @error('site')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Observação -->
                <div class="mt-6">
                    <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea id="observacao" name="observacao" rows="3"
                              placeholder="Observações adicionais..."
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('observacao', $entidade->observacao) }}</textarea>
                    @error('observacao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route($routeName . '.show', $entidade) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Salvar Alterações
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
});
</script>
@endsection
