<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class Servicio_controller extends Controller
{
    public function mostrar() {
        $datosServicio = Servicio::with('tipo_gasto')->get()->map(function ($servicio) {
            $nombreTipoGasto = $servicio->tipo_gasto->nombre_tipo_gasto ?? 'Desconocido';
    
            // Clasificación de origen
            if (in_array($nombreTipoGasto, ['Ingredientes', 'Bebidas'])) {
                $origen = $nombreTipoGasto;
            } else {
                $origen = 'Servicio'; // Cualquier otro caso es 'Servicio'
            }
    
            return [
                'tipo_gasto' => $nombreTipoGasto,
                'cantidad_pagada' => $servicio->cantidad_pagada_servicio,
                'fecha_pago' => $servicio->fecha_pago_servicio,
                'origen' => $origen,
                'pk' => $servicio->servicio_pk,
            ];
        });
    
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('gastos', compact('datosServicio'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function insertar(Request $req){
        $req->validate([
            'tipo_gasto_fk' => ['required', 'exists:tipo_gasto,tipo_gasto_pk'],
            'cantidad_pagada_servicio' => ['required', 'numeric','min:1'],
            'fecha_pago_servicio' => ['required', 'date_format:Y-m-d'],
        ], [
            'tipo_gasto_fk.required' => 'Debe seleccionar un tipo de gasto.',
            'tipo_gasto_fk.exists' => 'El tipo de gasto seleccionado no es válido.',

            'cantidad_pagada_servicio.required' => 'Debe ingresar la cantidad pagada.',
            'cantidad_pagada_servicio.numeric' => 'La cantidad pagada debe ser un número.',
            'cantidad_pagada_servicio.min' => 'La cantidad pagada debe ser mayor o igual a 1.',

            'fecha_pago_servicio.required' => 'La fecha de pago es requerida.',
            'fecha_pago_servicio.date_format' => 'La fecha de pago debe tener el formato YYYY-MM-DD.'
        ]);

        $servicio=new Servicio();

        $servicio->tipo_gasto_fk = $req->tipo_gasto_fk;
        $servicio->cantidad_pagada_servicio = $req->cantidad_pagada_servicio;
        $servicio->fecha_pago_servicio = $req->fecha_pago_servicio;

        $servicio->save();
        
        if ($servicio->servicio_pk) {
            return back()->with('success', 'Gasto de servicio registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function datosParaEdicion($servicio_pk){
        $datosServicio = Servicio::findOrFail($servicio_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarGasto', compact('datosServicio'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $servicio_pk){
        $datosServicio = Servicio::findOrFail($servicio_pk);

        $req->validate([
            'tipo_gasto_fk' => ['exists:tipo_gasto,tipo_gasto_pk'],
            'cantidad_pagada_servicio' => ['numeric', 'min:0.01', 'max:999999.99'],
            'fecha_pago_servicio' => ['date_format:Y-m-d'],
        ], [
            'tipo_gasto_fk.exists' => 'El tipo de gasto seleccionado no es válido.',

            'cantidad_pagada_servicio.numeric' => 'La cantidad pagada debe ser numérica.',
            'cantidad_pagada_servicio.min' => 'La cantidad pagada debe ser mayor a 0.',

            'fecha_pago_servicio.date_format' => 'La fecha de pago debe tener un formato válido.',
        ]);

        $datosServicio->tipo_gasto_fk=$req->tipo_gasto_fk;
        $datosServicio->cantidad_pagada_servicio=$req->cantidad_pagada_servicio;
        $datosServicio->fecha_pago_servicio=$req->fecha_pago_servicio;

        $datosServicio->save();
        
        if ($datosServicio->servicio_pk) {
            return redirect('/gastos')->with('success', 'Datos de gasto actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
