<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class CompanyComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $currentCompany = null;

        if (Auth::check()) {
            $user = Auth::user();

            // Buscar empresa atual da sessão
            $empresaAtualId = session('empresa_atual_id');
            if ($empresaAtualId) {
                // Verificar se o usuário tem acesso à empresa da sessão
                $currentCompany = $user->empresas()->where('empresa.id', $empresaAtualId)->first();
            }

            // Se não há empresa selecionada, selecionar a primeira disponível
            if (!$currentCompany && $user->empresas->count() > 0) {
                // Primeiro, tentar encontrar empresa principal
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

                if ($empresaPrincipal) {
                    $currentCompany = $empresaPrincipal;
                    session(['empresa_atual_id' => $empresaPrincipal->id]);
                    session(['whitelabel_atual_id' => $empresaPrincipal->whitelabel_id]);
                    session()->save(); // Force save
                } else {
                    // Se não existe empresa principal, pegar a primeira empresa da lista
                    $currentCompany = $user->empresas->first();
                    session(['empresa_atual_id' => $currentCompany->id]);
                    session(['whitelabel_atual_id' => $currentCompany->whitelabel_id]);
                    session()->save(); // Force save
                }
            }

            // Passar também a empresa principal para a view
            $empresaPrincipal = null;
            if ($user->empresas->count() > 0) {
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            }
        }

        $view->with([
            'currentCompany' => $currentCompany,
            'empresaPrincipal' => $empresaPrincipal ?? null
        ]);
    }
}
