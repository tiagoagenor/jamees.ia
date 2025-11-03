@extends('layouts.app')

@section('title', 'Meus Dados')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Meus Dados</h2>
                        <p class="text-gray-600 mt-1">Gerencie suas informações pessoais</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">{{ strtoupper(substr($usuario->nome ?: 'Usuário', 0, 1)) }}</span>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('meus-dados.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nome"
                                   name="nome"
                                   value="{{ old('nome', $usuario->nome) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('nome') border-red-300 ring-red-500 @enderror"
                                   placeholder="Digite seu nome completo">
                            @error('nome')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   value="{{ $usuario->email }}"
                                   readonly
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                                   placeholder="Email não pode ser alterado">
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                O email não pode ser alterado por questões de segurança
                            </p>
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                                Telefone
                            </label>
                            <input type="text"
                                   id="telefone"
                                   name="telefone"
                                   value="{{ old('telefone', $telefoneCompleto) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('telefone') border-red-300 ring-red-500 @enderror"
                                   placeholder="(00) 00000-0000"
                                   maxlength="15">
                            @error('telefone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Seção de Alteração de Senha -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Alterar Senha</h3>
                        <p class="text-sm text-gray-600 mb-4">Deixe em branco se não quiser alterar sua senha</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Senha Atual -->
                            <div>
                                <label for="senha_atual" class="block text-sm font-medium text-gray-700 mb-2">
                                    Senha Atual
                                </label>
                                <input type="password"
                                       id="senha_atual"
                                       name="senha_atual"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('senha_atual') border-red-300 ring-red-500 @enderror"
                                       placeholder="Digite sua senha atual">
                                @error('senha_atual')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nova Senha -->
                            <div>
                                <label for="nova_senha" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nova Senha
                                </label>
                                <input type="password"
                                       id="nova_senha"
                                       name="nova_senha"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('nova_senha') border-red-300 ring-red-500 @enderror"
                                       placeholder="Digite a nova senha">
                                @error('nova_senha')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmar Nova Senha -->
                            <div>
                                <label for="nova_senha_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmar Nova Senha
                                </label>
                                <input type="password"
                                       id="nova_senha_confirmation"
                                       name="nova_senha_confirmation"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="Confirme a nova senha">
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefoneInput = document.getElementById('telefone');
    const form = telefoneInput.closest('form');

    // Função para aplicar máscara de telefone
    function aplicarMascaraTelefone(valor, posicaoCursor) {
        // Remove tudo que não é dígito
        const apenasDigitos = valor.replace(/\D/g, '');

        let valorComMascara = '';

        // Aplica a máscara baseado no número de dígitos
        if (apenasDigitos.length === 0) {
            valorComMascara = '';
        } else if (apenasDigitos.length <= 2) {
            valorComMascara = '(' + apenasDigitos;
        } else if (apenasDigitos.length <= 7) {
            valorComMascara = '(' + apenasDigitos.substring(0, 2) + ') ' + apenasDigitos.substring(2);
        } else if (apenasDigitos.length <= 10) {
            // Telefone fixo: (00) 0000-0000
            valorComMascara = '(' + apenasDigitos.substring(0, 2) + ') ' +
                             apenasDigitos.substring(2, 6) + '-' +
                             apenasDigitos.substring(6);
        } else {
            // Celular: (00) 00000-0000
            valorComMascara = '(' + apenasDigitos.substring(0, 2) + ') ' +
                             apenasDigitos.substring(2, 7) + '-' +
                             apenasDigitos.substring(7, 11);
        }

        // Ajusta posição do cursor após aplicar máscara
        if (posicaoCursor !== undefined && posicaoCursor !== null) {
            // Calcula quantos caracteres não-dígitos existem antes da posição do cursor
            const digitosAntes = valor.substring(0, posicaoCursor).replace(/\D/g, '').length;
            let novaPosicao = 0;
            let digitosContados = 0;

            for (let i = 0; i < valorComMascara.length && digitosContados < digitosAntes; i++) {
                if (/\d/.test(valorComMascara[i])) {
                    digitosContados++;
                }
                novaPosicao = i + 1;
            }

            // Se está apagando (backspace), ajusta para não pular caracteres especiais
            setTimeout(() => {
                telefoneInput.setSelectionRange(novaPosicao, novaPosicao);
            }, 0);
        }

        return valorComMascara;
    }

    // Função para remover máscara (apenas dígitos)
    function removerMascaraTelefone(valor) {
        return valor.replace(/\D/g, '');
    }

    // Aplica máscara enquanto o usuário digita ou apaga
    telefoneInput.addEventListener('input', function(e) {
        const posicaoCursor = e.target.selectionStart;
        const valorComMascara = aplicarMascaraTelefone(e.target.value, posicaoCursor);
        e.target.value = valorComMascara;
    });

    // Permite navegar e apagar caracteres especiais
    telefoneInput.addEventListener('keydown', function(e) {
        // Se for backspace ou delete, permite apagar mesmo que esteja em caracteres especiais
        if (e.key === 'Backspace' || e.key === 'Delete') {
            const posicaoCursor = e.target.selectionStart;
            const valor = e.target.value;

            // Se está apagando um caractere especial, remove o dígito anterior/posterior
            if (valor[posicaoCursor - 1] && !/\d/.test(valor[posicaoCursor - 1])) {
                e.preventDefault();
                const apenasDigitos = valor.replace(/\D/g, '');
                const digitosAteCursor = valor.substring(0, posicaoCursor).replace(/\D/g, '').length;

                // Remove o último dígito antes da posição do cursor
                if (digitosAteCursor > 0) {
                    const novosDigitos = apenasDigitos.substring(0, digitosAteCursor - 1) + apenasDigitos.substring(digitosAteCursor);
                    e.target.value = aplicarMascaraTelefone(novosDigitos);
                }
            }
        }
    });

    // Aplica máscara quando o campo ganha foco (se já tiver valor)
    telefoneInput.addEventListener('focus', function(e) {
        if (e.target.value && !e.target.value.includes('(')) {
            e.target.value = aplicarMascaraTelefone(e.target.value);
        }
    });

    // Remove máscara antes de enviar o formulário
    form.addEventListener('submit', function(e) {
        const valorSemMascara = removerMascaraTelefone(telefoneInput.value);
        telefoneInput.value = valorSemMascara;
    });

    // Aplica máscara no valor inicial se houver
    if (telefoneInput.value) {
        telefoneInput.value = aplicarMascaraTelefone(telefoneInput.value);
    }
});
</script>
@endpush
@endsection
