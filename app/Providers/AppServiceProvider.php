<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Inventario;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cantidadCritico = Inventario::whereColumn('cantidad_inventario', '<=', 'cantidad_inventario_minima')->count();
            $view->with('cantidadCritico', $cantidadCritico);
        });
    }
}
