<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user instanceof \App\Models\Usuario) {
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            if ($empresaPrincipal) {
                $planoAtual = $empresaPrincipal->getPlanoAtual();
                if (!$planoAtual) {
                    return redirect()->route('planos.index');
                }
                if ($planoAtual->isExpirado()) {
                    return redirect()->route('planos.index');
                }
                // Se está em teste ou tem plano ativo, ir para dashboard
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rota de teste sem middleware
Route::get('/test', function () {
    return 'Teste funcionando! Usuário: ' . (Auth::check() ? Auth::user()->nome : 'Não logado');
});

// Rota de login manual para teste
Route::get('/login-manual', function () {
    $user = App\Models\Usuario::where('email', 'maria@teste.com')->first();
    if ($user) {
        Auth::login($user);
        return 'Login realizado com sucesso! Usuário: ' . $user->nome . ' | Admin: ' . ($user->isAdmin() ? 'Sim' : 'Não');
    }
    return 'Usuário não encontrado';
});

// Rota de API para registro (sem CSRF)
Route::post('/api/register', [App\Http\Controllers\Api\RegisterController::class, 'register'])->withoutMiddleware(['web']);

// Rotas básicas autenticadas (perfil, troca de empresa)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota para trocar empresa
    Route::post('/switch-company', [App\Http\Controllers\CompanySwitchController::class, 'switchCompany'])->name('switch.company');

    // Rotas de planos (sempre acessíveis)
    Route::get('/planos', [App\Http\Controllers\PlanoController::class, 'index'])->name('planos.index');
    Route::get('/planos/{plano}', [App\Http\Controllers\PlanoController::class, 'show'])->name('planos.show');
    Route::post('/planos/{plano}/ativar', [App\Http\Controllers\PlanoController::class, 'ativar'])->name('planos.ativar');
    Route::post('/planos/cancelar', [App\Http\Controllers\PlanoController::class, 'cancelar'])->name('planos.cancelar');
    Route::get('/planos/historico', [App\Http\Controllers\PlanoController::class, 'historico'])->name('planos.historico');
    Route::get('/api/plano-info', [App\Http\Controllers\PlanoController::class, 'info'])->name('planos.info');
});

// Rotas protegidas por plano ativo
Route::middleware(['auth', 'plano.ativo'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas de usuários
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class)
        ->middleware('permission:usuarios,listar');

    // Rotas de empresas
    Route::resource('empresas', App\Http\Controllers\EmpresaController::class)
        ->middleware('permission:empresas,listar');

    // Rotas de grupos
    Route::resource('grupos', App\Http\Controllers\GrupoController::class)
        ->middleware('permission:grupos,listar');

    // Rotas de permissões
    Route::resource('permissoes', App\Http\Controllers\PermissaoController::class)
        ->middleware('permission:permissoes,listar');

    // Rotas de clientes
    Route::get('/clientes', [App\Http\Controllers\EntidadeController::class, 'clientes'])->name('clientes.index');
    Route::get('/clientes/create', [App\Http\Controllers\EntidadeController::class, 'createCliente'])->name('clientes.create');
    Route::post('/clientes', [App\Http\Controllers\EntidadeController::class, 'storeCliente'])->name('clientes.store');
    Route::get('/clientes/{entidade}', [App\Http\Controllers\EntidadeController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{entidade}/edit', [App\Http\Controllers\EntidadeController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{entidade}', [App\Http\Controllers\EntidadeController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{entidade}', [App\Http\Controllers\EntidadeController::class, 'destroy'])->name('clientes.destroy');
    Route::patch('/clientes/{entidade}/toggle-status', [App\Http\Controllers\EntidadeController::class, 'toggleStatus'])->name('clientes.toggle-status');

    // Rotas de fornecedores
    Route::get('/fornecedores', [App\Http\Controllers\EntidadeController::class, 'fornecedores'])->name('fornecedores.index');
    Route::get('/fornecedores/create', [App\Http\Controllers\EntidadeController::class, 'createFornecedor'])->name('fornecedores.create');
    Route::post('/fornecedores', [App\Http\Controllers\EntidadeController::class, 'storeFornecedor'])->name('fornecedores.store');
    Route::get('/fornecedores/{entidade}', [App\Http\Controllers\EntidadeController::class, 'show'])->name('fornecedores.show');
    Route::get('/fornecedores/{entidade}/edit', [App\Http\Controllers\EntidadeController::class, 'edit'])->name('fornecedores.edit');
    Route::put('/fornecedores/{entidade}', [App\Http\Controllers\EntidadeController::class, 'update'])->name('fornecedores.update');
    Route::delete('/fornecedores/{entidade}', [App\Http\Controllers\EntidadeController::class, 'destroy'])->name('fornecedores.destroy');
    Route::patch('/fornecedores/{entidade}/toggle-status', [App\Http\Controllers\EntidadeController::class, 'toggleStatus'])->name('fornecedores.toggle-status');

    // Rotas de funcionários
    Route::get('/funcionarios', [App\Http\Controllers\EntidadeController::class, 'funcionarios'])->name('funcionarios.index');
    Route::get('/funcionarios/create', [App\Http\Controllers\EntidadeController::class, 'createFuncionario'])->name('funcionarios.create');
    Route::post('/funcionarios', [App\Http\Controllers\EntidadeController::class, 'storeFuncionario'])->name('funcionarios.store');
    Route::get('/funcionarios/{entidade}', [App\Http\Controllers\EntidadeController::class, 'show'])->name('funcionarios.show');
    Route::get('/funcionarios/{entidade}/edit', [App\Http\Controllers\EntidadeController::class, 'edit'])->name('funcionarios.edit');
    Route::put('/funcionarios/{entidade}', [App\Http\Controllers\EntidadeController::class, 'update'])->name('funcionarios.update');
    Route::delete('/funcionarios/{entidade}', [App\Http\Controllers\EntidadeController::class, 'destroy'])->name('funcionarios.destroy');
    Route::patch('/funcionarios/{entidade}/toggle-status', [App\Http\Controllers\EntidadeController::class, 'toggleStatus'])->name('funcionarios.toggle-status');

    // Rotas de transportadoras
    Route::get('/transportadoras', [App\Http\Controllers\EntidadeController::class, 'transportadoras'])->name('transportadoras.index');
    Route::get('/transportadoras/create', [App\Http\Controllers\EntidadeController::class, 'createTransportadora'])->name('transportadoras.create');
    Route::post('/transportadoras', [App\Http\Controllers\EntidadeController::class, 'storeTransportadora'])->name('transportadoras.store');
    Route::get('/transportadoras/{entidade}', [App\Http\Controllers\EntidadeController::class, 'show'])->name('transportadoras.show');
    Route::get('/transportadoras/{entidade}/edit', [App\Http\Controllers\EntidadeController::class, 'edit'])->name('transportadoras.edit');
    Route::put('/transportadoras/{entidade}', [App\Http\Controllers\EntidadeController::class, 'update'])->name('transportadoras.update');
    Route::delete('/transportadoras/{entidade}', [App\Http\Controllers\EntidadeController::class, 'destroy'])->name('transportadoras.destroy');
    Route::patch('/transportadoras/{entidade}/toggle-status', [App\Http\Controllers\EntidadeController::class, 'toggleStatus'])->name('transportadoras.toggle-status');

    // Rotas para contatos das entidades
    Route::post('/entidades/{entidade}/contatos', [App\Http\Controllers\EntidadeController::class, 'storeContato'])->name('entidades.contatos.store');
    Route::put('/entidades/{entidade}/contatos/{contato}', [App\Http\Controllers\EntidadeController::class, 'updateContato'])->name('entidades.contatos.update');
    Route::delete('/entidades/{entidade}/contatos/{contato}', [App\Http\Controllers\EntidadeController::class, 'destroyContato'])->name('entidades.contatos.destroy');

    // Rotas para endereços das entidades
    Route::post('/entidades/{entidade}/enderecos', [App\Http\Controllers\EntidadeController::class, 'storeEndereco'])->name('entidades.enderecos.store');
    Route::put('/entidades/{entidade}/enderecos/{endereco}', [App\Http\Controllers\EntidadeController::class, 'updateEndereco'])->name('entidades.enderecos.update');
    Route::delete('/entidades/{entidade}/enderecos/{endereco}', [App\Http\Controllers\EntidadeController::class, 'destroyEndereco'])->name('entidades.enderecos.destroy');

    // Rotas de DRE
    Route::get('/dre', [App\Http\Controllers\DreController::class, 'index'])->name('dre.index');
    Route::get('/dre/create', [App\Http\Controllers\DreController::class, 'create'])->name('dre.create');
    Route::post('/dre', [App\Http\Controllers\DreController::class, 'store'])->name('dre.store');
    Route::get('/dre/{dre}', [App\Http\Controllers\DreController::class, 'show'])->name('dre.show');
    Route::get('/dre/{dre}/edit', [App\Http\Controllers\DreController::class, 'edit'])->name('dre.edit');
    Route::put('/dre/{dre}', [App\Http\Controllers\DreController::class, 'update'])->name('dre.update');
    Route::delete('/dre/{dre}', [App\Http\Controllers\DreController::class, 'destroy'])->name('dre.destroy');
    Route::patch('/dre/{dre}/toggle-status', [App\Http\Controllers\DreController::class, 'toggleStatus'])->name('dre.toggle-status');
});

require __DIR__.'/auth.php';
