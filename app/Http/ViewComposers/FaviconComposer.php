<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class FaviconComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Garantir que o favicon esteja sempre disponível
        $view->with('favicon', [
            'svg' => asset('favicon.svg'),
            'ico' => asset('favicon.ico'),
        ]);
    }
}

