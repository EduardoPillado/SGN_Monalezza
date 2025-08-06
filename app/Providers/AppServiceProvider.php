<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Inventario;
use App\Models\Reserva;
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
            // Indicador de cuanto hay de poco stock
            $cantidadCritico = Inventario::whereColumn('cantidad_inventario', '<=', 'cantidad_inventario_minima')->count();

            // Indicador de cuantas reservas hay para el día en curso
            $hoy = Carbon::now()->toDateString();
            $cantidadReservasHoy = Reserva::whereDate('fecha_hora_reserva', $hoy)
                ->where('estatus_reserva', '=', 1)
                ->count();

            $view->with([
                'cantidadCritico' => $cantidadCritico,
                'cantidadReservasHoy' => $cantidadReservasHoy
            ]);
        });

        View::composer('*', function ($view) {
            // Cantidad de dinero caja (mostrado en header)
            $resumenCaja = Entradas_caja_controller::calcularResumenCajaHoy();

            $view->with('resumenCajaHoy', $resumenCaja);
        });
    }
}
