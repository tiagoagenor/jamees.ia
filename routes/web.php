<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rota de teste sem middleware
Route::get('/test', function () {
    return 'Teste funcionando! Usuário: ' . (Auth::check() ? Auth::user()->nome : 'Não logado');
});

// Rota de login manual para teste
Route::get('/login-manual', function () {
    $user = App\Models\Usuario::where('email', 'jose@jose.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/dashboard');
    }
    return 'Usuário não encontrado';
});

// Rota de API para registro (sem CSRF)
Route::post('/api/register', [App\Http\Controllers\Api\RegisterController::class, 'register'])->withoutMiddleware(['web']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Rotas de usuários
        Route::resource('usuarios', App\Http\Controllers\UsuarioController::class);

        // Rotas de empresas
        Route::resource('empresas', App\Http\Controllers\EmpresaController::class);
});

require __DIR__.'/auth.php';
