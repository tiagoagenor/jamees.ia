@extends('layouts.app')

@section('title', 'Nova Conciliação Bancária')
@section('page-title', 'Nova Conciliação Bancária')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-balance-scale text-blue-600 mr-3"></i>
            Nova Conciliação Bancária
        </h1>
        <p class="text-gray-600 mt-2">Crie uma nova conciliação bancária importando um arquivo OFX</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('conciliacao-bancaria.store') }}" method="POST" enctype="multipart/form-data" id="conciliacao-form">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
            <!-- Seleção de Conta -->
            <div>
                <label for="conta_empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Conta Bancária <span class="text-red-500">*</span>
                </label>
                <select name="conta_empresa_id" id="conta_empresa_id" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('conta_empresa_id') }}">
                    <option value="">Selecione uma conta</option>
                    @foreach($contas as $conta)
                        <option value="{{ $conta->id }}" {{ old('conta_empresa_id') == $conta->id ? 'selected' : '' }}>
                            {{ $conta->nome }} - {{ $conta->banco->nome ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Upload de Arquivo OFX -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Arquivo OFX <span class="text-red-500">*</span>
                </label>
                
                <!-- Área de Drag and Drop -->
                <div id="drop-zone" 
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                    <input type="file" name="arquivo_ofx" id="arquivo_ofx" accept=".ofx" required class="hidden">
                    <div id="drop-content">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">
                            Arraste o arquivo OFX aqui ou clique para selecionar
                        </p>
                        <p class="text-sm text-gray-500">
                            Formatos aceitos: .ofx (máx. 10MB)
                        </p>
                    </div>
                    <div id="file-info" class="hidden mt-4">
                        <i class="fas fa-file text-2xl text-blue-600 mb-2"></i>
                        <p class="text-sm font-medium text-gray-700" id="file-name"></p>
                        <button type="button" id="remove-file" class="mt-2 text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-times mr-1"></i> Remover
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('conciliacao-bancaria.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        id="submit-btn"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-upload mr-2"></i>
                    <span id="submit-text">Importar e Criar Conciliação</span>
                    <span id="submit-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    #drop-zone.drag-over {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('arquivo_ofx');
    const dropContent = document.getElementById('drop-content');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const removeFile = document.getElementById('remove-file');

    // Click para selecionar arquivo
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // Seleção de arquivo
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Remover arquivo
    removeFile.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        dropContent.classList.remove('hidden');
        fileInfo.classList.add('hidden');
    });

    function handleFile(file) {
        if (!file.name.toLowerCase().endsWith('.ofx')) {
            alert('Por favor, selecione um arquivo OFX válido.');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('O arquivo é muito grande. Tamanho máximo: 10MB.');
            return;
        }

        // Criar DataTransfer para atualizar o input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        fileName.textContent = file.name;
        dropContent.classList.add('hidden');
        fileInfo.classList.remove('hidden');
    }

    // Adicionar listener para o submit do formulário
    const form = document.getElementById('conciliacao-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');

    form.addEventListener('submit', function(e) {
        // Validar se conta foi selecionada
        const contaSelect = document.getElementById('conta_empresa_id');
        if (!contaSelect.value) {
            e.preventDefault();
            alert('Por favor, selecione uma conta bancária.');
            return false;
        }

        // Validar se arquivo foi selecionado
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            alert('Por favor, selecione um arquivo OFX.');
            return false;
        }

        // Mostrar loading
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
    });
});
</script>
@endsection

