<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class Mesa_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'numero_mesa' => ['required', 'numeric', 'min:1'],
            'ubicacion' => ['nullable', 'string', 'max:50'],
        ],[
            'numero_mesa.required' => 'El número de mesa es obligatoria.',
            'numero_mesa.numeric' => 'El número de mesa debe ser una cantidad numérica.',
            'numero_mesa.min' => 'El número de mesa debe ser mayor o igual a 1.',

            'ubicacion.string' => 'La ubicación deben ser un texto válido.',
            'ubicacion.max' => 'La ubicación no pueden tener más de :max caracteres.',
        ]);

        $mesa=new Mesa();

        $mesa->numero_mesa=$req->numero_mesa;
        $mesa->ubicacion=$req->ubicacion;
        $mesa->estatus_mesa=1;

        $mesa->save();

        if ($mesa->mesa_pk) {
            return back()->with('success', 'Nueva mesa registrada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosMesa = Mesa::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('reservas', compact('datosMesa'));
        } else {
            return redirect('/login');
        }
    }

     public function datosParaEdicion($mesa_pk){
        $datosMesa = Mesa::findOrFail($mesa_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarMesa', compact('datosMesa'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $mesa_pk){
        $mesa = Mesa::findOrFail($mesa_pk);

         $req->validate([
            'numero_mesa' => ['required', 'numeric', 'min:1'],
            'ubicacion' => ['nullable', 'string', 'max:50'],
        ],[
            'numero_mesa.required' => 'El número de mesa es obligatoria.',
            'numero_mesa.numeric' => 'El número de mesa debe ser una cantidad numérica.',
            'numero_mesa.min' => 'El número de mesa debe ser mayor o igual a 1.',

            'ubicacion.string' => 'La ubicación deben ser un texto válido.',
            'ubicacion.max' => 'La ubicación no pueden tener más de :max caracteres.',
        ]);

        $mesa->numero_mesa=$req->numero_mesa;
        $mesa->ubicacion=$req->ubicacion;

        $mesa->save();

        if ($mesa->mesa_pk) {
            return redirect('/reservas')->with('success', 'Mesa actualizada');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($mesa_pk){
        $datosMesa = Mesa::findOrFail($mesa_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosMesa) {

                $datosMesa->estatus_mesa = 0;
                $datosMesa->save();

                return back()->with('success', 'Mesa dada de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($mesa_pk){
        $datosMesa = Mesa::findOrFail($mesa_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosMesa) {

                $datosMesa->estatus_mesa = 1;
                $datosMesa->save();

                return back()->with('success', 'Mesa dada de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
