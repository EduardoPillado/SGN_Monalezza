<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;

class Inventario_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'ingrediente_fk' => ['nullable', 'exists:ingrediente,ingrediente_pk'],
            'producto_fk' => ['nullable', 'exists:producto,producto_pk'],
            'tipo_gasto_fk' => ['nullable', 'exists:tipo_gasto,tipo_gasto_pk'],
            'proveedor_fk' => ['required', 'exists:proveedor,proveedor_pk'],
            'precio_proveedor' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'fecha_inventario' => ['required', 'date_format:Y-m-d\TH:i'],
            'cantidad_inventario' => ['required', 'integer', 'min:0'],
            'cantidad_paquete' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'cantidad_inventario_minima' => ['required', 'integer', 'min:0'],
        ], [
            'ingrediente_fk.exists' => 'El ingrediente seleccionado no es válido.',

            'producto_fk.exists' => 'El producto seleccionado no es válido.',
            
            'tipo_gasto_fk.exists' => 'El tipo de gasto seleccionado no es válido.',
        
            'proveedor_fk.required' => 'El proveedor es obligatorio.',
            'proveedor_fk.exists' => 'El proveedor seleccionado no es válido.',
        
            'precio_proveedor.required' => 'El precio del proveedor es obligatorio.',
            'precio_proveedor.numeric' => 'El precio del proveedor debe ser un valor numérico.',
            'precio_proveedor.min' => 'El precio del proveedor debe ser mayor o igual a 0.01.',
            'precio_proveedor.max' => 'El precio del proveedor no debe exceder 999999.99.',
        
            'fecha_inventario.required' => 'La fecha de inventario es obligatoria.',
            'fecha_inventario.date_format' => 'La fecha de inventario debe estar en el formato válido (Y-m-d\TH:i).',
        
            'cantidad_inventario.required' => 'La cantidad en stock es obligatoria.',
            'cantidad_inventario.integer' => 'La cantidad en stock debe ser un número entero.',
            'cantidad_inventario.min' => 'La cantidad en stock no puede ser negativa.',

            'cantidad_paquete.required' => 'La cantidad del paquete es obligatorio.',
            'cantidad_paquete.numeric' => 'La cantidad del paquete debe ser un valor numérico.',
            'cantidad_paquete.min' => 'La cantidad del paquete debe ser mayor o igual a 0.01.',
            'cantidad_paquete.max' => 'La cantidad del paquete no debe exceder 999999.99.',
        
            'cantidad_inventario_minima.required' => 'La cantidad mínima en inventario es obligatoria.',
            'cantidad_inventario_minima.integer' => 'La cantidad mínima en inventario debe ser un número entero.',
            'cantidad_inventario_minima.min' => 'La cantidad mínima en inventario no puede ser negativa.',
        ]);

        $inventario=new Inventario();

        $inventario->ingrediente_fk=$req->ingrediente_fk;
        $inventario->producto_fk=$req->producto_fk;
        $inventario->tipo_gasto_fk=$req->tipo_gasto_fk;
        $inventario->proveedor_fk=$req->proveedor_fk;
        $inventario->precio_proveedor=$req->precio_proveedor;
        $inventario->fecha_inventario=$req->fecha_inventario;
        $inventario->cantidad_inventario=$req->cantidad_inventario;
        $inventario->cantidad_paquete=$req->cantidad_paquete;
        $inventario->cantidad_inventario_minima=$req->cantidad_inventario_minima;

        $inventario->save();
        
        if ($inventario->inventario_pk) {
            return back()->with('success', 'Agregado a stock');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosInventario = Inventario::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('inventario', compact('datosInventario'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }
}
