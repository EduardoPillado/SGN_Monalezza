<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo_ingrediente;

class Tipo_ingrediente_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_tipo_ingrediente' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_ingrediente,nombre_tipo_ingrediente'],
        ], [
            'nombre_tipo_ingrediente.required' => 'El nombre del tipo de ingrediente es obligatorio.',
            'nombre_tipo_ingrediente.regex' => 'El nombre del tipo de ingrediente solo puede contener letras, números y espacios.',
            'nombre_tipo_ingrediente.max' => 'El nombre del tipo de ingrediente no puede tener más de :max caracteres.',
            'nombre_tipo_ingrediente.unique' => 'El nombre del tipo de ingrediente ya existe.',
        ]);

        $tipo_ingrediente=new Tipo_ingrediente();

        $tipo_ingrediente->nombre_tipo_ingrediente=$req->nombre_tipo_ingrediente;
        $tipo_ingrediente->estatus_tipo_ingrediente=1;

        $tipo_ingrediente->save();

        if ($tipo_ingrediente->tipo_ingrediente_pk) {
            return back()->with('success', 'Tipo de ingrediente registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

     public function datosParaEdicion($tipo_ingrediente_pk){
        $datosTipo_ingrediente = Tipo_ingrediente::findOrFail($tipo_ingrediente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarTipoIngrediente', compact('datosTipo_ingrediente'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $tipo_ingrediente_pk){
        $tipo_ingrediente = Tipo_ingrediente::findOrFail($tipo_ingrediente_pk);

        $req->validate([
            'nombre_tipo_ingrediente' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_ingrediente,nombre_tipo_ingrediente'],
        ], [
            'nombre_tipo_ingrediente.required' => 'El nombre del tipo de ingrediente es obligatorio.',
            'nombre_tipo_ingrediente.regex' => 'El nombre del tipo de ingrediente solo puede contener letras, números y espacios.',
            'nombre_tipo_ingrediente.max' => 'El nombre del tipo de ingrediente no puede tener más de :max caracteres.',
            'nombre_tipo_ingrediente.unique' => 'El nombre del tipo de ingrediente ya existe.',
        ]);

        $tipo_ingrediente->nombre_tipo_ingrediente=$req->nombre_tipo_ingrediente;

        $tipo_ingrediente->save();

        if ($tipo_ingrediente->tipo_ingrediente_pk) {
            return redirect('/ingredientes')->with('success', 'Tipo de ingrediente actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($tipo_ingrediente_pk){
        $datosTipo_ingrediente = Tipo_ingrediente::findOrFail($tipo_ingrediente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_ingrediente) {

                $datosTipo_ingrediente->estatus_tipo_ingrediente = 0;
                $datosTipo_ingrediente->save();

                return back()->with('success', 'Tipo de ingrediente dado de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($tipo_ingrediente_pk){
        $datosTipo_ingrediente = Tipo_ingrediente::findOrFail($tipo_ingrediente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_ingrediente) {

                $datosTipo_ingrediente->estatus_tipo_ingrediente = 1;
                $datosTipo_ingrediente->save();

                return back()->with('success', 'Tipo de ingrediente dado de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
