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
        // Validação completa do formulário de registro
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos do formulário
            const form = document.querySelector('form');
            const empresaNomeInput = document.getElementById('empresa_nome');
            const nomeInput = document.getElementById('nome');
            const telefoneInput = document.getElementById('telefone');
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            const senhaConfirmationInput = document.getElementById('senha_confirmation');
            const submitButton = document.querySelector('button[type="submit"]');

            // Estados de validação
            let validationState = {
                empresa_nome: false,
                nome: false,
                telefone: false,
                email: false,
                senha: false,
                senha_confirmation: false
            };

            // Máscara de telefone brasileiro
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    // Permitir apagar completamente
                    if (value.length === 0) {
                        e.target.value = '';
                        clearFieldError('telefone');
                        validationState.telefone = false;
                        updateSubmitButton();
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

                    // Limpar erro enquanto digita
                    clearFieldError('telefone');

                    // Só validar se o campo estiver completo (10 ou 11 dígitos)
                    const digitsOnly = e.target.value.replace(/\D/g, '');
                    if (digitsOnly.length === 10 || digitsOnly.length === 11) {
                        validateTelefone(e.target);
                    }
                });

                telefoneInput.addEventListener('blur', function(e) {
                    validateTelefone(e.target);
                });

                // Permitir teclas especiais (Backspace, Delete, etc.)
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

            // Validação de empresa nome
            if (empresaNomeInput) {
                empresaNomeInput.addEventListener('blur', function(e) {
                    validateEmpresaNome(e.target);
                });

                empresaNomeInput.addEventListener('input', function(e) {
                    clearFieldError('empresa_nome');
                    if (e.target.value.trim().length >= 2) {
                        validationState.empresa_nome = true;
                        updateSubmitButton();
                    }
                });
            }

            // Validação de nome
            if (nomeInput) {
                nomeInput.addEventListener('blur', function(e) {
                    validateNome(e.target);
                });

                nomeInput.addEventListener('input', function(e) {
                    clearFieldError('nome');
                    if (e.target.value.trim().length >= 2) {
                        validationState.nome = true;
                        updateSubmitButton();
                    }
                });
            }

            // Validação de email
            if (emailInput) {
                emailInput.addEventListener('blur', function(e) {
                    validateEmail(e.target);
                });

                emailInput.addEventListener('input', function(e) {
                    clearFieldError('email');

                    // Limpar erro do backend se existir
                    const backendError = emailInput.parentNode.parentNode.querySelector('.text-red-600');
                    if (backendError && backendError.textContent.includes('já está sendo usado')) {
                        backendError.remove();
                    }

                    if (validateEmailFormat(e.target.value)) {
                        validationState.email = true;
                        updateSubmitButton();
                    } else {
                        validationState.email = false;
                        updateSubmitButton();
                    }
                });
            }

            // Validação de senha
            if (senhaInput) {
                senhaInput.addEventListener('blur', function(e) {
                    validateSenha(e.target);
                });

                senhaInput.addEventListener('input', function(e) {
                    clearFieldError('senha');
                    if (e.target.value.length >= 6) {
                        validationState.senha = true;
                        updateSubmitButton();
                        // Validar confirmação de senha se já foi preenchida
                        if (senhaConfirmationInput.value) {
                            validateSenhaConfirmation(senhaConfirmationInput);
                        }
                    }
                });
            }

            // Validação de confirmação de senha
            if (senhaConfirmationInput) {
                senhaConfirmationInput.addEventListener('blur', function(e) {
                    validateSenhaConfirmation(e.target);
                });

                senhaConfirmationInput.addEventListener('input', function(e) {
                    clearFieldError('senha_confirmation');
                    if (e.target.value === senhaInput.value && e.target.value.length >= 6) {
                        validationState.senha_confirmation = true;
                        updateSubmitButton();
                    }
                });
            }

            // Prevenir envio se houver erros
            form.addEventListener('submit', function(e) {
                if (!isFormValid()) {
                    e.preventDefault();
                    showFormError('Por favor, corrija os erros antes de continuar.');
                }
            });

            // Funções de validação
            function validateEmpresaNome(input) {
                const value = input.value.trim();
                if (value.length < 2) {
                    showFieldError('empresa_nome', 'Nome da empresa deve ter pelo menos 2 caracteres.');
                    validationState.empresa_nome = false;
                    updateSubmitButton();
                    return false;
                }
                validationState.empresa_nome = true;
                updateSubmitButton();
                return true;
            }

            function validateNome(input) {
                const value = input.value.trim();
                if (value.length < 2) {
                    showFieldError('nome', 'Nome deve ter pelo menos 2 caracteres.');
                    validationState.nome = false;
                    updateSubmitButton();
                    return false;
                }
                validationState.nome = true;
                updateSubmitButton();
                return true;
            }

            function validateTelefone(input) {
                const value = input.value.replace(/\D/g, '');

                console.log('Validando telefone:', input.value, '→', value, 'dígitos:', value.length);

                // Verificar se tem o número correto de dígitos
                if (value.length < 10 || value.length > 11) {
                    showFieldError('telefone', 'Telefone deve ter 10 dígitos (fixo) ou 11 dígitos (celular).');
                    validationState.telefone = false;
                    updateSubmitButton();
                    return false;
                }

                // Verificar se o DDD é válido (11 a 99)
                const ddd = parseInt(value.substring(0, 2));
                if (ddd < 11 || ddd > 99) {
                    showFieldError('telefone', 'DDD inválido. Use um DDD válido do Brasil.');
                    validationState.telefone = false;
                    updateSubmitButton();
                    return false;
                }

                // Para celular (11 dígitos), verificar se o 9º dígito é 9
                if (value.length === 11) {
                    const nonoDigito = parseInt(value.substring(2, 3));
                    if (nonoDigito !== 9) {
                        showFieldError('telefone', 'Celular deve começar com 9 após o DDD.');
                        validationState.telefone = false;
                        updateSubmitButton();
                        return false;
                    }
                }

                console.log('Telefone válido!');
                validationState.telefone = true;
                updateSubmitButton();
                return true;
            }

            function validateEmail(input) {
                const email = input.value.trim();
                if (!email) {
                    showFieldError('email', 'Email é obrigatório.');
                    validationState.email = false;
                    updateSubmitButton();
                    return false;
                }
                if (!validateEmailFormat(email)) {
                    showFieldError('email', 'Por favor, insira um email válido.');
                    validationState.email = false;
                    updateSubmitButton();
                    return false;
                }
                validationState.email = true;
                updateSubmitButton();
                return true;
            }

            function validateEmailFormat(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function validateSenha(input) {
                const senha = input.value;
                if (senha.length < 6) {
                    showFieldError('senha', 'Senha deve ter pelo menos 6 caracteres.');
                    validationState.senha = false;
                    updateSubmitButton();
                    return false;
                }
                validationState.senha = true;
                updateSubmitButton();
                return true;
            }

            function validateSenhaConfirmation(input) {
                const senha = senhaInput.value;
                const confirmacao = input.value;
                if (confirmacao !== senha) {
                    showFieldError('senha_confirmation', 'As senhas não coincidem.');
                    validationState.senha_confirmation = false;
                    updateSubmitButton();
                    return false;
                }
                validationState.senha_confirmation = true;
                updateSubmitButton();
                return true;
            }

            // Funções auxiliares
            function showFieldError(fieldName, message) {
                clearFieldError(fieldName);

                const input = document.getElementById(fieldName);
                const errorDiv = document.createElement('div');
                errorDiv.id = `${fieldName}-error-custom`;
                errorDiv.className = 'mt-2 text-sm text-red-600';
                errorDiv.textContent = message;

                // Inserir erro após o container do input (não dentro dele)
                const inputContainer = input.parentNode; // div com classe "relative"
                const fieldContainer = inputContainer.parentNode; // div que contém label + input + erro
                fieldContainer.appendChild(errorDiv);

                input.classList.add('border-red-500');
                input.classList.remove('border-gray-300');
            }

            function clearFieldError(fieldName) {
                const existingError = document.getElementById(`${fieldName}-error-custom`);
                if (existingError) {
                    existingError.remove();
                }

                const input = document.getElementById(fieldName);
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');
            }

            function showFormError(message) {
                // Criar ou atualizar mensagem de erro geral
                let errorDiv = document.getElementById('form-error-general');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.id = 'form-error-general';
                    errorDiv.className = 'mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md';
                    form.insertBefore(errorDiv, form.firstChild);
                }

                errorDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erro no formulário</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>${message}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            function isFormValid() {
                return Object.values(validationState).every(valid => valid);
            }

            function updateSubmitButton() {
                if (isFormValid()) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-indigo-700');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-indigo-700');
                }
            }

            // Inicializar estado do botão
            updateSubmitButton();

            // Detectar erros do backend e permitir revalidação
            const backendErrors = document.querySelectorAll('.text-red-600');
            if (backendErrors.length > 0) {
                console.log('Erros do backend detectados, permitindo revalidação...');

                // Limpar estado de validação para permitir revalidação
                Object.keys(validationState).forEach(key => {
                    validationState[key] = false;
                });

                // Revalidar todos os campos que têm valores
                if (empresaNomeInput && empresaNomeInput.value.trim()) {
                    validateEmpresaNome(empresaNomeInput);
                }
                if (nomeInput && nomeInput.value.trim()) {
                    validateNome(nomeInput);
                }
                if (telefoneInput && telefoneInput.value.replace(/\D/g, '').length >= 10) {
                    validateTelefone(telefoneInput);
                }
                if (emailInput && emailInput.value.trim()) {
                    validateEmail(emailInput);
                }
                if (senhaInput && senhaInput.value.length >= 6) {
                    validateSenha(senhaInput);
                }
                if (senhaConfirmationInput && senhaConfirmationInput.value === senhaInput.value) {
                    validateSenhaConfirmation(senhaConfirmationInput);
                }

                updateSubmitButton();
            }
        });
    </script>
</body>
</html>
