<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Debug: Verificar se o usuário está autenticado
            \Log::info('User authenticated', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email ?? 'null'
            ]);

            return redirect('/dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Login failed', [
                'errors' => $e->errors(),
                'email' => $request->input('email')
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->only('email', 'remember'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
