<?php

namespace App\Http\Controllers;

use App\Models\Medio_pedido;
use Illuminate\Http\Request;

class Medio_pedido_controller extends Controller
{
     public function insertar(Request $req){
        $req->validate([
            'nombre_medio_pedido' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:medio_pedido,nombre_medio_pedido'],
        ], [
            'nombre_medio_pedido.required' => 'El nombre del medio de pedido es obligatorio.',
            'nombre_medio_pedido.regex' => 'El nombre del medio de pedido solo puede contener letras, números y espacios.',
            'nombre_medio_pedido.max' => 'El nombre del medio de pedido no puede tener más de :max caracteres.',
            'nombre_medio_pedido.unique' => 'El nombre del medio de pedido ya existe.',
        ]);

        $medio_pedido=new Medio_pedido();

        $medio_pedido->nombre_medio_pedido=$req->nombre_medio_pedido;
        $medio_pedido->estatus_medio_pedido=1;

        $medio_pedido->save();

        if ($medio_pedido->nombre_medio_pedido) {
            return back()->with('success', 'Medio de pedido registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosMedio_pedido = Medio_pedido::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('ventas', compact('datosMedio_pedido'));
        } else {
            return redirect('/login');
        }
    }

     public function datosParaEdicion($medio_pedido_pk){
        $datosMedio_pedido = Medio_pedido::findOrFail($medio_pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarMedioPedido', compact('datosMedio_pedido'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $medio_pedido_pk){
        $medio_pedido = Medio_pedido::findOrFail($medio_pedido_pk);

        $req->validate([
            'nombre_medio_pedido' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:medio_pedido,nombre_medio_pedido'],
        ], [
            'nombre_medio_pedido.required' => 'El nombre del medio de pedido es obligatorio.',
            'nombre_medio_pedido.regex' => 'El nombre del medio de pedido solo puede contener letras, números y espacios.',
            'nombre_medio_pedido.max' => 'El nombre del medio de pedido no puede tener más de :max caracteres.',
            'nombre_medio_pedido.unique' => 'El nombre del medio de pedido ya existe.',
        ]);

        $medio_pedido->nombre_medio_pedido=$req->nombre_medio_pedido;

        $medio_pedido->save();

        if ($medio_pedido->medio_pedido_pk) {
            return redirect('/ventas')->with('success', 'Medio de pedido actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($medio_pedido_pk){
        $datosMedio_pedido = Medio_pedido::findOrFail($medio_pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosMedio_pedido) {

                $datosMedio_pedido->estatus_medio_pedido = 0;
                $datosMedio_pedido->save();

                return back()->with('success', 'Medio de pedido dada de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($medio_pedido_pk){
        $datosMedio_pedido = Medio_pedido::findOrFail($medio_pedido_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosMedio_pedido) {

                $datosMedio_pedido->estatus_medio_pedido = 1;
                $datosMedio_pedido->save();

                return back()->with('success', 'Medio de pedido dada de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
