<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha - Jamees</title>
    @include('components.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex">
    <!-- Left Column - Reset Password Form -->
    <div class="w-1/2 bg-white flex flex-col justify-center">
        <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center px-8">
        <!-- Main Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Recuperar Senha</h1>
            <p class="text-gray-500 text-lg">Digite seu email para receber instruções de recuperação</p>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Sucesso!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erro!</h3>
                        <div class="mt-2 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erro na validação</h3>
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

        <!-- Form -->
        <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="{{ old('email') }}"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                           placeholder="Digite seu email cadastrado">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Recuperar Senha
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-700 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-1"></i>
                Voltar para o Login
            </a>
        </div>

        <!-- Information Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Informações Importantes</h3>
                    <div class="text-sm text-blue-700 space-y-1">
                        <div class="flex items-start">
                            <i class="fas fa-envelope-open text-blue-500 mt-1 mr-2 text-xs"></i>
                            <span>Verifique sua caixa de entrada e pasta de spam</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-blue-500 mt-1 mr-2 text-xs"></i>
                            <span>O link só pode ser usado uma vez</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Right Column - Branding -->
    <div class="w-1/2 bg-gradient-to-br from-indigo-900 via-purple-900 to-blue-900 relative overflow-hidden">
        <!-- Animated Background Pattern -->
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

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-12">
            <!-- Logo -->
            <div class="mb-8">
                <div class="flex items-center justify-center mb-6">
                    <!-- Logo Text with same font as logged area -->
                    <span style="font-family: 'Roboto'; font-size: 48px; font-weight: bold; font-style: italic; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">JAMEES</span>
                </div>
            </div>

            <!-- Subtitle -->
            <div class="mb-16">
                <p class="text-blue-100 text-xl font-light leading-relaxed">
                    Sistema de Gestão Empresarial<br>
                    <span class="text-blue-200 font-medium">Recuperação Segura de Senha</span>
                </p>
            </div>

            <!-- Features -->
            <div class="space-y-4 text-left">
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Recuperação Segura</span>
                </div>
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Token com Expiração</span>
                </div>
                <div class="flex items-center text-blue-100">
                    <div class="w-2 h-2 bg-blue-300 rounded-full mr-3"></div>
                    <span class="text-sm">Email Automático</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Validação em tempo real
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');

        emailInput.addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('border-red-300');
            } else {
                this.classList.remove('border-red-300');
            }
        });
    });
    </script>
</body>
</html>
