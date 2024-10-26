<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class Proveedor_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_proveedor' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:255', 'unique:proveedor,nombre_proveedor'],
        ], [
            'nombre_proveedor.required' => 'El nombre del proveedor es obligatorio.',
            'nombre_proveedor.regex' => 'El nombre del proveedor solo puede contener letras, números y espacios.',
            'nombre_proveedor.max' => 'El nombre del proveedor no puede tener más de :max caracteres.',
            'nombre_proveedor.unique' => 'El nombre del proveedor ya existe.',
        ]);

        $proveedor=new Proveedor();

        $proveedor->nombre_proveedor=$req->nombre_proveedor;
        $proveedor->estatus_proveedor=1;

        $proveedor->save();
        
        if ($proveedor->proveedor_pk) {
            return back()->with('success', 'Proveedor registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosProveedor = Proveedor::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('proveedores', compact('datosProveedor'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function baja($proveedor_pk){
        $datosProveedor = Proveedor::findOrFail($proveedor_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosProveedor) {

                    $datosProveedor->estatus_proveedor = 0;
                    $datosProveedor->save();

                    return back()->with('success', 'Proveedor dado de baja');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($proveedor_pk){
        $datosProveedor = Proveedor::findOrFail($proveedor_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosProveedor) {

                    $datosProveedor->estatus_proveedor = 1;
                    $datosProveedor->save();

                    return back()->with('success', 'Proveedor dado de alta');
                } else {
                    return back()->with('error', 'Hay algún problema con la información');
                }
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function datosParaEdicion($proveedor_pk){
        $datosProveedor = Proveedor::findOrFail($proveedor_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarProveedor', compact('datosProveedor'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $proveedor_pk){
        $datosProveedor = Proveedor::findOrFail($proveedor_pk);

        $req->validate([
            'nombre_proveedor' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:100', 'unique:proveedor,nombre_proveedor,' . $proveedor_pk . ',proveedor_pk'],
        ], [
            'nombre_proveedor.regex' => 'El nombre del proveedor solo puede contener letras, números y espacios.',
            'nombre_proveedor.max' => 'El nombre del proveedor no puede tener más de :max caracteres.',
            'nombre_proveedor.unique' => 'El nombre del proveedor ya existe.',
        ]);

        $datosProveedor->nombre_proveedor=$req->nombre_proveedor;
        $datosProveedor->save();
        
        if ($datosProveedor->proveedor_pk) {
            return redirect('/proveedores')->with('success', 'Datos de proveedor actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
