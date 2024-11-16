<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corte_caja;
use App\Models\Pedido;

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
    
        $ventas = Pedido::whereBetween('fecha_hora_pedido', [$corte->fecha_corte_inicio, $corte->fecha_corte_fin])->get();

        $corte->cantidad_ventas = $ventas->count();
        $corte->ganancia_total = $ventas->sum('monto_total');
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
        return view('cortesDeCaja', compact('datosCorteCaja'));
    }
}
