<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $modulo, string $acao): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Se o usuário é admin, permite tudo
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Verifica se o usuário tem a permissão específica
        if (!$user->temPermissao($modulo, $acao)) {
            abort(403, 'Você não tem permissão para realizar esta ação.');
        }

        return $next($request);
    }
}
