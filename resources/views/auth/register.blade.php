<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Jamees</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto">
                <span style="font-family: 'Roboto'; font-size: 55px; font-weight: bold; font-style: italic; color: rgb(79 70 229 / var(--tw-bg-opacity, 1));">JAMEES</span>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Criar nova conta
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Preencha os dados abaixo para criar sua conta
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erro no formulário</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Company Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-building text-indigo-600 mr-2"></i>
                        Dados da Empresa
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Informações sobre sua empresa</p>

                    <div class="mt-4">
                        <label for="empresa_nome" class="block text-sm font-medium text-gray-700">Nome da Empresa</label>
                        <div class="mt-1 relative">
                            <input id="empresa_nome" name="empresa_nome" type="text" required
                                   class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Digite o nome da sua empresa" value="{{ old('empresa_nome') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                        </div>
                        @error('empresa_nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Personal Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-user text-indigo-600 mr-2"></i>
                        Dados Pessoais
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Suas informações pessoais</p>

                    <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                            <div class="mt-1 relative">
                                <input id="nome" name="nome" type="text" required
                                       class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Seu nome completo" value="{{ old('nome') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                            @error('nome')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <div class="mt-1 relative">
                                <input id="telefone" name="telefone" type="tel" required
                                       class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="(11) 99999-9999" value="{{ old('telefone') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                            </div>
                            @error('telefone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Section -->
                <div class="pb-6">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-lock text-indigo-600 mr-2"></i>
                        Dados de Acesso
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Crie suas credenciais de acesso</p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1 relative">
                                <input id="email" name="email" type="email" required
                                       class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="seu@email.com" value="{{ old('email') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                                <div class="mt-1 relative">
                                    <input id="senha" name="senha" type="password" required
                                           class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Mínimo 6 caracteres">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                </div>
                                @error('senha')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="senha_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                                <div class="mt-1 relative">
                                    <input id="senha_confirmation" name="senha_confirmation" type="password" required
                                           class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Confirme sua senha">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Criar Conta
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Já tem uma conta?
                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                            Faça login aqui
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Máscara para telefone brasileiro
        document.addEventListener('DOMContentLoaded', function() {
            const telefoneInput = document.getElementById('telefone');
            const emailInput = document.getElementById('email');

            // Máscara de telefone
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    if (value.length > 11) {
                        value = value.substring(0, 11);
                    }

                    if (value.length >= 2) {
                        if (value.length <= 6) {
                            e.target.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                        } else if (value.length <= 10) {
                            e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                        } else if (value.length <= 11) {
                            e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                        }
                    } else if (value.length > 0) {
                        e.target.value = `(${value}`;
                    }
                });

                // Aplicar máscara ao valor existente (se houver)
                if (telefoneInput.value) {
                    let value = telefoneInput.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        if (value.length <= 6) {
                            telefoneInput.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                        } else if (value.length <= 10) {
                            telefoneInput.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                        } else if (value.length <= 11) {
                            telefoneInput.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                        }
                    }
                }
            }

            // Validação de email em tempo real
            if (emailInput) {
                emailInput.addEventListener('blur', function(e) {
                    validateEmail(e.target);
                });

                emailInput.addEventListener('input', function(e) {
                    // Limpar mensagem de erro se o usuário estiver digitando
                    clearEmailError();
                });
            }
        });

        // Função para validar email
        function validateEmail(input) {
            const email = input.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                showEmailError('Por favor, insira um email válido.');
                input.classList.add('border-red-500');
                input.classList.remove('border-gray-300');
                return false;
            } else {
                clearEmailError();
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');
                return true;
            }
        }

        // Função para mostrar erro de email
        function showEmailError(message) {
            clearEmailError();

            const emailInput = document.getElementById('email');
            const errorDiv = document.createElement('div');
            errorDiv.id = 'email-error-custom';
            errorDiv.className = 'mt-2 text-sm text-red-600';
            errorDiv.textContent = message;

            emailInput.parentNode.appendChild(errorDiv);
        }

        // Função para limpar erro de email
        function clearEmailError() {
            const existingError = document.getElementById('email-error-custom');
            if (existingError) {
                existingError.remove();
            }
        }
    </script>
</body>
</html>
