<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corte_caja;
use App\Models\Detalle_efectivo;
use App\Models\Pedido;
use App\Models\Servicio;

class Corte_caja_controller extends Controller
{
    public function generarCorte(Request $req){

        $req->validate([
            'fecha_corte_inicio' => 'required|date_format:Y-m-d\TH:i',
            'fecha_corte_fin' => 'required|date_format:Y-m-d\TH:i|after_or_equal:fecha_corte_inicio',
        ],[
            'fecha_corte_inicio.required' => 'Debe ingresar una fecha de inicio.',
            'fecha_corte_inicio.date_format' => 'Debe ingresar una fecha válida en formato YYYY-MM-DD HH:mm para la fecha inicial.',
            
            'fecha_corte_fin.required' => 'Debe ingresar una fecha de fin.',
            'fecha_corte_fin.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha de inicio.',
            'fecha_corte_fin.date_format' => 'Debe ingresar una fecha válida en formato YYYY-MM-DD HH:mm para la fecha final.',
        ]);
    
        $corte = new Corte_caja();
        $corte->fecha_corte_inicio = $req->input('fecha_corte_inicio');
        $corte->fecha_corte_fin = $req->input('fecha_corte_fin');

        $suma = Detalle_efectivo::whereBetween('fecha_actual', [$corte->fecha_corte_inicio, $corte->fecha_corte_fin])->get();
        $corte->suma_efectivo_inicial = $suma->sum('efectivo_inicial');
    
        $ventas = Pedido::whereBetween('fecha_hora_pedido', [$corte->fecha_corte_inicio, $corte->fecha_corte_fin])->get();

        $corte->cantidad_ventas = $ventas->count();
        $corte->ganancia_total = $ventas->sum('monto_total');

        $suma_gastos = Servicio::whereBetween('fecha_pago_servicio', [$corte->fecha_corte_inicio, $corte->fecha_corte_fin])->get();
        $corte->suma_gasto_servicios = $suma_gastos->sum('cantidad_pagada_servicio');

        $diferencia = ($corte->suma_efectivo_inicial + $corte->ganancia_total) - $corte->suma_gasto_servicios;

        $corte->utilidad_neta = $diferencia;
        
        $corte->save();
    
        $corte->empleados()->sync($ventas->pluck('empleado_fk')->unique()->toArray());
    
        if ($corte->corte_caja_pk) {
            return back()->with('success', 'Corte generado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosCorteCaja = Corte_caja::with('empleados.usuario')->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('cortesDeCaja', compact('datosCorteCaja'));
        } else {
            return redirect('/login');
        }
    }
}
