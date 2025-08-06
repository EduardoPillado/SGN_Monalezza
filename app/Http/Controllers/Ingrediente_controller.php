<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;
use App\Models\Tipo_ingrediente;

class Ingrediente_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_ingrediente' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:ingrediente,nombre_ingrediente'],
            'tipo_ingrediente_fk' => ['required', 'exists:tipo_ingrediente,tipo_ingrediente_pk'],
        ], [
            'nombre_ingrediente.required' => 'El nombre del ingrediente es obligatorio.',
            'nombre_ingrediente.regex' => 'El nombre del ingrediente solo puede contener letras, números y espacios.',
            'nombre_ingrediente.max' => 'El nombre del ingrediente no puede tener más de :max caracteres.',
            'nombre_ingrediente.unique' => 'El nombre del ingrediente ya existe.',

            'tipo_ingrediente_fk.required' => 'El tipo de ingrediente es obligatorio.',
            'tipo_ingrediente_fk.exists' => 'El tipo de ingrediente seleccionado no es válido.',
        ]);

        $ingrediente=new Ingrediente();

        $ingrediente->nombre_ingrediente=$req->nombre_ingrediente;
        $ingrediente->tipo_ingrediente_fk=$req->tipo_ingrediente_fk;
        $ingrediente->estatus_ingrediente=1;

        $ingrediente->save();
        
        if ($ingrediente->ingrediente_pk) {
            return back()->with('success', 'Ingrediente registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosIngrediente = Ingrediente::all();
        $datosTipo_ingrediente = Tipo_ingrediente::all();
        $tiposIngrediente = Tipo_ingrediente::where('estatus_tipo_ingrediente', '=', 1)->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('ingredientes', compact('datosIngrediente', 'datosTipo_ingrediente', 'tiposIngrediente'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function baja($ingrediente_pk){
        $datosIngrediente = Ingrediente::findOrFail($ingrediente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosIngrediente) {

                    $datosIngrediente->estatus_ingrediente = 0;
                    $datosIngrediente->save();

                    return back()->with('success', 'Ingrediente dado de baja');
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

    public function alta($ingrediente_pk){
        $datosIngrediente = Ingrediente::findOrFail($ingrediente_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                if ($datosIngrediente) {

                    $datosIngrediente->estatus_ingrediente = 1;
                    $datosIngrediente->save();

                    return back()->with('success', 'Ingrediente dado de alta');
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

    public function datosParaEdicion($ingrediente_pk){
        $datosIngrediente = Ingrediente::findOrFail($ingrediente_pk);
        $datosTipoIngrediente = Tipo_ingrediente::where('estatus_tipo_ingrediente', '=', 1)->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarIngrediente', compact('datosIngrediente', 'datosTipoIngrediente'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $ingrediente_pk){
        $datosIngrediente = Ingrediente::findOrFail($ingrediente_pk);

        $req->validate([
            'nombre_ingrediente' => ['regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:ingrediente,nombre_ingrediente'],
            'tipo_ingrediente_fk' => ['exists:tipo_ingrediente,tipo_ingrediente_pk'],
        ], [
            'nombre_ingrediente.regex' => 'El nombre del ingrediente solo puede contener letras, números y espacios.',
            'nombre_ingrediente.max' => 'El nombre del ingrediente no puede tener más de :max caracteres.',
            'nombre_ingrediente.unique' => 'El nombre del ingrediente ya existe.',

            'tipo_ingrediente_fk.exists' => 'El tipo de ingrediente seleccionado no es válido.',
        ]);

        $datosIngrediente->nombre_ingrediente=$req->nombre_ingrediente;
        $datosIngrediente->tipo_ingrediente_fk=$req->tipo_ingrediente_fk;

        $datosIngrediente->save();
        
        if ($datosIngrediente->ingrediente_pk) {
            return redirect('/ingredientes')->with('success', 'Ingrediente actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
