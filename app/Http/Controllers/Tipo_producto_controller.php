<?php

namespace App\Http\Controllers;

use App\Models\Tipo_producto;
use Illuminate\Http\Request;

class Tipo_producto_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_tipo_producto' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_producto,nombre_tipo_producto'],
        ], [
            'nombre_tipo_producto.required' => 'El nombre del tipo de producto es obligatorio.',
            'nombre_tipo_producto.regex' => 'El nombre del tipo de producto solo puede contener letras, números y espacios.',
            'nombre_tipo_producto.max' => 'El nombre del tipo de producto no puede tener más de :max caracteres.',
            'nombre_tipo_producto.unique' => 'El nombre del tipo de producto ya existe.',
        ]);

        $tipo_producto=new Tipo_producto();

        $tipo_producto->nombre_tipo_producto=$req->nombre_tipo_producto;
        $tipo_producto->estatus_tipo_producto=1;

        $tipo_producto->save();

        if ($tipo_producto->tipo_producto_pk) {
            return back()->with('success', 'Tipo de producto registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

     public function datosParaEdicion($tipo_producto_pk){
        $datosTipo_producto = Tipo_Producto::findOrFail($tipo_producto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarTipoProducto', compact('datosTipo_producto'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $tipo_producto_pk){
        $tipo_producto = Tipo_producto::findOrFail($tipo_producto_pk);

        $req->validate([
            'nombre_tipo_producto' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_producto,nombre_tipo_producto'],
        ], [
            'nombre_tipo_producto.required' => 'El nombre del tipo de producto es obligatorio.',
            'nombre_tipo_producto.regex' => 'El nombre del tipo de producto solo puede contener letras, números y espacios.',
            'nombre_tipo_producto.max' => 'El nombre del tipo de producto no puede tener más de :max caracteres.',
            'nombre_tipo_producto.unique' => 'El nombre del tipo de producto ya existe.',
        ]);

        $tipo_producto->nombre_tipo_producto=$req->nombre_tipo_producto;

        $tipo_producto->save();

        if ($tipo_producto->tipo_producto_pk) {
            return redirect('/productos')->with('success', 'Tipo de producto actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($tipo_producto_pk){
        $datosTipo_producto = Tipo_producto::findOrFail($tipo_producto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_producto) {

                $datosTipo_producto->estatus_tipo_producto = 0;
                $datosTipo_producto->save();

                return back()->with('success', 'Tipo de producto dado de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($tipo_producto_pk){
        $datosTipo_producto = Tipo_producto::findOrFail($tipo_producto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_producto) {

                $datosTipo_producto->estatus_tipo_producto = 1;
                $datosTipo_producto->save();

                return back()->with('success', 'Tipo de producto dado de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}