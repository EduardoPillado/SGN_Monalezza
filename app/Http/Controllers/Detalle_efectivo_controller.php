<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detalle_efectivo;
use Illuminate\Support\Carbon;

class Detalle_efectivo_controller extends Controller
{
     public function insertar(Request $req){

        $req->validate([
            'efectivo_inicial' => ['required', 'numeric', 'min:0'],
        ],[
            'efectivo_inicial.required' => 'La apertura de caja es obligatoria.',
            'efectivo_inicial.numeric' => 'La apertura de caja debe ser una cantidad numérica.',
            'efectivo_inicial.min' => 'La apertura de caja debe ser mayor o igual a 0.',
        ]);
    
        $efectivo = new Detalle_efectivo();
        $efectivo->fecha_actual = Carbon::now(); 
        $efectivo->efectivo_inicial = $req->efectivo_inicial;
        $efectivo->save();
    
        if ($efectivo->detalle_efectivo_pk) {
            return back()->with('success', 'Efectivo inicial registrado'); 
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
