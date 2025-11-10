<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\CompanyComposer;
use App\Http\ViewComposers\FaviconComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registrar o composer para o layout principal
        View::composer('layouts.app', CompanyComposer::class);

        // Registrar o composer de favicon para todas as views
        View::composer('*', FaviconComposer::class);
    }
}
