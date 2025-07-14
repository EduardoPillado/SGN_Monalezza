<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Ingrediente;
use App\Models\Tipo_gasto;
use App\Models\Tipo_producto;
use App\Models\Proveedor;

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

            'cantidad_paquete.required' => 'La cantidad del paquete es obligatoria.',
            'cantidad_paquete.numeric' => 'La cantidad del paquete debe ser un valor numérico.',
            'cantidad_paquete.min' => 'La cantidad del paquete debe ser mayor o igual a 0.01.',
            'cantidad_paquete.max' => 'La cantidad del paquete no debe exceder 999999.99.',

            'cantidad_inventario_minima.required' => 'La cantidad mínima en inventario es obligatoria.',
            'cantidad_inventario_minima.integer' => 'La cantidad mínima en inventario debe ser un número entero.',
            'cantidad_inventario_minima.min' => 'La cantidad mínima en inventario no puede ser negativa.',
        ]);

        $existeInventario = Inventario::where('ingrediente_fk', $req->ingrediente_fk)
            ->where('producto_fk', $req->producto_fk)
            ->where('proveedor_fk', $req->proveedor_fk)
            ->exists();

        if ($existeInventario) {
            return back()->withErrors(['error' => 'El registro ya existe en el inventario.']);
        }

        $inventario = new Inventario();
        $inventario->ingrediente_fk = $req->ingrediente_fk;
        $inventario->producto_fk = $req->producto_fk;
        $inventario->tipo_gasto_fk = $req->tipo_gasto_fk;
        $inventario->proveedor_fk = $req->proveedor_fk;
        $inventario->precio_proveedor = $req->precio_proveedor;
        $inventario->fecha_inventario = $req->fecha_inventario;
        $inventario->cantidad_inventario = $req->cantidad_inventario;
        $inventario->cantidad_paquete = $req->cantidad_paquete;
        $inventario->cantidad_inventario_minima = $req->cantidad_inventario_minima;
        $inventario->save();

        $servicio = new Servicio();
        $servicio->tipo_gasto_fk = $req->tipo_gasto_fk;
        $servicio->cantidad_pagada_servicio = $req->precio_proveedor * $req->cantidad_inventario;
        $servicio->fecha_pago_servicio = $req->fecha_inventario;
        $servicio->save();

        if ($inventario->inventario_pk && $servicio->servicio_pk) {
            return back()->with('success', 'Agregado a stock');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosInventario = Inventario::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('inventario', compact('datosInventario'));
        } else {
            return redirect('/login');
        }
    }

    public function filtrar(Request $req){
        $query = Inventario::with(['ingrediente', 'producto', 'proveedor', 'tipo_gasto']);

        // Filtro por fecha
        if ($req->filled('fecha')) {
            $query->whereDate('fecha_inventario', $req->fecha);
        }

        // Filtro por tipo de elemento
        if ($req->input('tipo_elemento_filtrar') === 'producto') {
            $query->whereNotNull('producto_fk')->whereNull('ingrediente_fk');
        } elseif ($req->input('tipo_elemento_filtrar') === 'ingrediente') {
            $query->whereNotNull('ingrediente_fk')->whereNull('producto_fk');
        }

        // Filtro por proveedor
        if ($req->filled('proveedor_fk')) {
            $query->where('proveedor_fk', $req->proveedor_fk);
        }

        // Filtro por estado (en riesgo / disponible)
        if ($req->filled('estado')) {
            if ($req->estado === 'riesgo') {
                $query->whereColumn('cantidad_inventario', '<=', 'cantidad_inventario_minima');
            } elseif ($req->estado === 'disponible') {
                $query->whereColumn('cantidad_inventario', '>', 'cantidad_inventario_minima');
            }
        }

        $datosInventario = $query->get();

        $tiposExcluidos = Tipo_producto::where(function($query) {
            $query->where('nombre_tipo_producto', 'like', '%pizza%')
                ->orWhere('nombre_tipo_producto', 'like', '%mediana%')
                ->orWhere('nombre_tipo_producto', 'like', '%familiar%')
                ->orWhere('nombre_tipo_producto', 'like', '%mega%')
                ->orWhere('nombre_tipo_producto', 'like', '%cuadrada%');
        })->pluck('tipo_producto_pk');

        $datosProducto = Producto::where('estatus_producto', 1)
            ->whereNotIn('tipo_producto_fk', $tiposExcluidos)
            ->get();

        $datosIngrediente = Ingrediente::where('estatus_ingrediente', 1)->get();
        $datosTipoGasto = Tipo_gasto::where('estatus_tipo_gasto', 1)->get();
        $datosProveedor = Proveedor::where('estatus_proveedor', 1)->get();

        return view('inventario', compact(
            'datosInventario', 'datosProducto', 'datosIngrediente', 'datosTipoGasto', 'datosProveedor'
        ));
    }

    public function datosParaEdicion($inventario_pk){
        $datosInventario = Inventario::findOrFail($inventario_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('actualizarStock', compact('datosInventario'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $inventario_pk){
        $inventario = Inventario::findOrFail($inventario_pk);

        $req->validate([
            'cantidad_nueva' => ['required', 'integer', 'min:1'],
            'precio_proveedor' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
        ], [
            'cantidad_nueva.required' => 'La cantidad adicional es obligatoria.',
            'cantidad_nueva.integer' => 'La cantidad adicional debe ser un número entero.',
            'cantidad_nueva.min' => 'La cantidad adicional debe ser al menos 1.',

            'precio_proveedor.required' => 'El precio por unidad es obligatorio.',
            'precio_proveedor.numeric' => 'El precio por unidad debe ser un valor numérico.',
            'precio_proveedor.min' => 'El precio por unidad debe ser mayor o igual a 0.01.',
            'precio_proveedor.max' => 'El precio por unidad no debe exceder 999999.99.',
        ]);
        $cantidadNueva = $req->cantidad_nueva;
        $precioPorUnidad = $req->precio_proveedor;
        $gastoAdicional = $cantidadNueva * $precioPorUnidad;

        $inventario->cantidad_inventario += $cantidadNueva;
        $inventario->precio_proveedor = $precioPorUnidad;
        $inventario->fecha_inventario = now();
        $inventario->save();

        $servicio = new Servicio();
        $servicio->tipo_gasto_fk = $inventario->tipo_gasto_fk;
        $servicio->cantidad_pagada_servicio = $gastoAdicional;
        $servicio->fecha_pago_servicio = now();
        $servicio->save();

        if ($inventario->inventario_pk && $servicio->servicio_pk) {
            return redirect('/inventario')->with('success', 'Stock actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
