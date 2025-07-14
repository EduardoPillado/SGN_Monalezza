<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo_gasto;

class Tipo_gasto_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_tipo_gasto' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_gasto,nombre_tipo_gasto'],
        ], [
            'nombre_tipo_gasto.required' => 'El nombre del tipo de producto es obligatorio.',
            'nombre_tipo_gasto.regex' => 'El nombre del tipo de producto solo puede contener letras, números y espacios.',
            'nombre_tipo_gasto.max' => 'El nombre del tipo de producto no puede tener más de :max caracteres.',
            'nombre_tipo_gasto.unique' => 'El nombre del tipo de producto ya existe.',
        ]);

        $tipo_gasto=new Tipo_gasto();

        $tipo_gasto->nombre_tipo_gasto=$req->nombre_tipo_gasto;
        $tipo_gasto->estatus_tipo_gasto=1;

        $tipo_gasto->save();

        if ($tipo_gasto->nombre_tipo_gasto) {
            return back()->with('success', 'Tipo de producto registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

     public function mostrar(){
        $datosTipo_gasto = Tipo_gasto::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('gastos', compact('datosTipo_gasto'));
        } else {
            return redirect('/login');
        }
    }

     public function datosParaEdicion($tipo_gasto_pk){
        $datosTipo_gasto = Tipo_gasto::findOrFail($tipo_gasto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarTipoGasto', compact('datosTipo_gasto'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $tipo_gasto_pk){
        $tipo_gasto = Tipo_gasto::findOrFail($tipo_gasto_pk);

        $req->validate([
            'nombre_tipo_gasto' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_gasto,nombre_tipo_gasto'],
        ], [
            'nombre_tipo_gasto.required' => 'El nombre de tipo de gasto es obligatorio.',
            'nombre_tipo_gasto.regex' => 'El nombre de tipo de gasto solo puede contener letras, números y espacios.',
            'nombre_tipo_gasto.max' => 'El nombre de tipo de gasto no puede tener más de :max caracteres.',
            'nombre_tipo_gasto.unique' => 'El nombre de tipo de gasto ya existe.',
        ]);

        $tipo_gasto->nombre_medio_pedido=$req->nombre_medio_pedido;

        $tipo_gasto->save();

        if ($tipo_gasto->medio_pedido_pk) {
            return redirect('/gastos')->with('success', 'Tipo de gasto actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($tipo_gasto_pk){
        $datosTipo_gasto = Tipo_gasto::findOrFail($tipo_gasto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_gasto) {

                $datosTipo_gasto->estatus_tipo_gasto = 0;
                $datosTipo_gasto->save();

                return back()->with('success', 'Tipo de gasto dado de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($tipo_gasto_pk){
        $datosTipo_gasto = Tipo_gasto::findOrFail($tipo_gasto_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_gasto) {

                $datosTipo_gasto->estatus_tipo_gasto = 1;
                $datosTipo_gasto->save();

                return back()->with('success', 'Tipo de gasto dado de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
