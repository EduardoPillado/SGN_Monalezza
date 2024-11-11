<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;

class Ingrediente_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre_ingrediente' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:ingrediente,nombre_ingrediente'],
        ], [
            'nombre_ingrediente.required' => 'El nombre del ingrediente es obligatorio.',
            'nombre_ingrediente.regex' => 'El nombre del ingrediente solo puede contener letras, números y espacios.',
            'nombre_ingrediente.max' => 'El nombre del ingrediente no puede tener más de :max caracteres.',
            'nombre_ingrediente.unique' => 'El nombre del ingrediente ya existe.',
        ]);

        $ingrediente=new Ingrediente();

        $ingrediente->nombre_ingrediente=$req->nombre_ingrediente;
        $ingrediente->estatus_ingrediente=1;

        $ingrediente->save();
        
        if ($ingrediente->nombre_ingrediente) {
            return back()->with('success', 'Ingrediente registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosIngrediente = Ingrediente::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('ingredientes', compact('datosIngrediente'));
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
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarIngrediente', compact('datosIngrediente'));
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
        ], [
            'nombre_ingrediente.regex' => 'El nombre del ingrediente solo puede contener letras, números y espacios.',
            'nombre_ingrediente.max' => 'El nombre del ingrediente no puede tener más de :max caracteres.',
            'nombre_ingrediente.unique' => 'El nombre del ingrediente ya existe.',
        ]);

        $datosIngrediente->nombre_ingrediente=$req->nombre_ingrediente;

        $datosIngrediente->save();
        
        if ($datosIngrediente->ingrediente_pk) {
            return redirect('/ingredientes')->with('success', 'Datos de ingrediente actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
