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
<body class="min-h-screen flex">
    <!-- Left Column - Registration Form -->
    <div class="w-1/2 bg-white flex flex-col justify-center">
        <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center px-8">
            <!-- Main Title -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Criar Conta</h1>
                <p class="text-gray-500 text-lg">Preencha os dados abaixo para criar sua conta!</p>
            </div>

            <!-- Error Messages -->
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

            <!-- Registration Form -->
            <form class="space-y-5" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Company Name Field -->
                <div>
                    <label for="empresa_nome" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Nome da Empresa<span class="text-red-500">*</span>
                    </label>
                    <input id="empresa_nome" name="empresa_nome" type="text" required
                           class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                           placeholder="Digite o nome da sua empresa" value="{{ old('empresa_nome') }}">
                    @error('empresa_nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Name Field -->
                <div>
                    <label for="nome" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Nome Completo<span class="text-red-500">*</span>
                    </label>
                    <input id="nome" name="nome" type="text" required
                           class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                           placeholder="Digite seu nome completo" value="{{ old('nome') }}">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="telefone" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Telefone<span class="text-red-500">*</span>
                    </label>
                    <input id="telefone" name="telefone" type="tel" required
                           class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                           placeholder="(11) 99999-9999" value="{{ old('telefone') }}">
                    @error('telefone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Email<span class="text-red-500">*</span>
                    </label>
                    <input id="email" name="email" type="email" required
                           class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                           placeholder="Digite seu email" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="senha" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Senha<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="senha" name="senha" type="password" required
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                               placeholder="Mínimo 6 caracteres">
                        <div class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500">
                            <button type="button" onclick="togglePassword('senha')">
                                <i id="senha-icon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @error('senha')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="senha_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Confirmar Senha<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="senha_confirmation" name="senha_confirmation" type="password" required
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-none focus:border-blue-500 focus:ring-blue-500/10"
                               placeholder="Confirme sua senha">
                        <div class="absolute top-1/2 right-4 z-30 -translate-y-1/2 cursor-pointer text-gray-500">
                            <button type="button" onclick="togglePassword('senha_confirmation')">
                                <i id="senha_confirmation-icon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @error('senha_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                        Criar Conta
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-5">
                <p class="text-center text-sm font-normal text-gray-700">
                    Já tem uma conta?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Faça login aqui</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Right Column - Branding -->
    <div class="w-1/2 bg-gradient-to-br from-indigo-900 via-purple-900 to-blue-900 relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-600/20 to-purple-600/20"></div>
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-400/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute top-40 right-32 w-24 h-24 bg-purple-400/10 rounded-full blur-xl animate-pulse delay-1000"></div>
            <div class="absolute bottom-32 left-32 w-28 h-28 bg-indigo-400/10 rounded-full blur-xl animate-pulse delay-2000"></div>
            <div class="absolute bottom-20 right-20 w-20 h-20 bg-blue-400/10 rounded-full blur-xl animate-pulse delay-500"></div>
        </div>

        <!-- Geometric Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-16 left-16 w-4 h-4 bg-white rotate-45"></div>
            <div class="absolute top-32 right-24 w-3 h-3 bg-white rotate-45"></div>
            <div class="absolute top-48 left-40 w-2 h-2 bg-white rotate-45"></div>
            <div class="absolute bottom-40 right-32 w-4 h-4 bg-white rotate-45"></div>
            <div class="absolute bottom-24 left-28 w-3 h-3 bg-white rotate-45"></div>
            <div class="absolute top-64 right-16 w-2 h-2 bg-white rotate-45"></div>
            <div class="absolute bottom-48 left-16 w-3 h-3 bg-white rotate-45"></div>
            <div class="absolute top-80 right-40 w-2 h-2 bg-white rotate-45"></div>
        </div>

        <!-- Logo and Content -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-8">
            <!-- Logo -->
            <div class="mb-12">
                <div class="flex items-center justify-center mb-6">
                    <!-- Logo Text with same font as logged area -->
                    <span style="font-family: 'Roboto'; font-size: 48px; font-weight: bold; font-style: italic; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">JAMEES</span>
                </div>
            </div>

            <!-- Subtitle -->
            <div class="mb-16">
                <p class="text-blue-100 text-xl font-light leading-relaxed">
                    Sistema de Gestão Empresarial<br>
                    <span class="text-blue-200 font-medium">Completo e Gratuito</span>
                </p>
            </div>

            <!-- Features -->
            <div class="space-y-4 text-left">
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Gestão Financeira Completa</span>
                </div>
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Controle de Clientes e Fornecedores</span>
                </div>
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Relatórios e Dashboards</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Máscara de telefone brasileiro
        document.addEventListener('DOMContentLoaded', function() {
            const telefoneInput = document.getElementById('telefone');

            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    // Permitir apagar completamente
                    if (value.length === 0) {
                        e.target.value = '';
                        return;
                    }

                    if (value.length > 11) {
                        value = value.substring(0, 11);
                    }

                    // Aplicar máscara baseada no número de dígitos
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

                // Permitir apenas números
                telefoneInput.addEventListener('keydown', function(e) {
                    // Permitir teclas de controle (Backspace, Delete, Tab, etc.)
                    if ([8, 9, 27, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                        // Permitir Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                        (e.keyCode === 65 && e.ctrlKey === true) ||
                        (e.keyCode === 67 && e.ctrlKey === true) ||
                        (e.keyCode === 86 && e.ctrlKey === true) ||
                        (e.keyCode === 88 && e.ctrlKey === true) ||
                        // Permitir Home, End, Left, Right
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                        return;
                    }
                    // Permitir apenas números
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
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
        });

        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
