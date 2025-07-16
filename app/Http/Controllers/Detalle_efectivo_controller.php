<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detalle_efectivo;
use Illuminate\Support\Carbon;

class Detalle_efectivo_controller extends Controller
{
    public function verificarRegistro()
    {
        $registroHoy = Detalle_efectivo::whereDate('fecha_actual', Carbon::today())->exists();
        
        return response()->json([
            'registroHoy' => $registroHoy,
            'hoy' => Carbon::today()->toDateString()
        ]);
    }

    public function insertar(Request $req)
    {
        $validated = $req->validate([
            'efectivo_inicial' => 'required|numeric|min:0'
        ], [
            'efectivo_inicial.required' => 'La apertura de caja es obligatoria.',
            'efectivo_inicial.numeric' => 'Debe ser una cantidad numérica.',
            'efectivo_inicial.min' => 'Debe ser mayor o igual a 0.'
        ]);

        $efectivo = Detalle_efectivo::create([
            'fecha_actual' => Carbon::now(),
            'efectivo_inicial' => $validated['efectivo_inicial']
        ]);

        return back()->with(
            $efectivo->exists ? 'success' : 'error',
            $efectivo->exists ? 'Registro exitoso' : 'Error al registrar'
        );
    }
}