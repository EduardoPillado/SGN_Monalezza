<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Inventario;
use App\Http\Controllers\Entradas_caja_controller;

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

        View::composer('*', function ($view) {
        $resumenCaja = Entradas_caja_controller::calcularResumenCajaHoy();
        $view->with('resumenCajaHoy', $resumenCaja);
    });
    }
}
