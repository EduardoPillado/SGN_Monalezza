<?php

namespace App\Http\Controllers;

use App\Models\Tipo_pago;
use Illuminate\Http\Request;

class Tipo_pago_controller extends Controller
{
     public function insertar(Request $req){
        $req->validate([
            'nombre_tipo_pago' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_pago,nombre_tipo_pago'],
        ], [
            'nombre_tipo_pago.required' => 'El nombre del tipo de pago es obligatorio.',
            'nombre_tipo_pago.regex' => 'El nombre del tipo de pago solo puede contener letras, números y espacios.',
            'nombre_tipo_pago.max' => 'El nombre del tipo de pago no puede tener más de :max caracteres.',
            'nombre_tipo_pago.unique' => 'El nombre del tipo de pago ya existe.',
        ]);

        $tipo_pago=new Tipo_pago();

        $tipo_pago->nombre_tipo_pago=$req->nombre_tipo_pago;
        $tipo_pago->estatus_tipo_pago=1;

        $tipo_pago->save();

        if ($tipo_pago->tipo_pago_pk) {
            return back()->with('success', 'Tipo de pago registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosTipo_pago = Tipo_pago::all();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            return view('ventas', compact('datosTipo_pago'));
        } else {
            return redirect('/login');
        }
    }

     public function datosParaEdicion($tipo_pago_pk){
        $datosTipo_pago = Tipo_pago::findOrFail($tipo_pago_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $ROL = session('nombre_rol');
            if ($ROL == 'Administrador') {
                return view('editarTipoPago', compact('datosTipo_pago'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $tipo_pago_pk){
        $tipo_pago = Tipo_pago::findOrFail($tipo_pago_pk);

        $req->validate([
            'nombre_tipo_pago' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:50', 'unique:tipo_pago,nombre_tipo_pago'],
        ], [
            'nombre_tipo_pago.required' => 'El nombre del tipo de pago es obligatorio.',
            'nombre_tipo_pago.regex' => 'El nombre del tipo de pago solo puede contener letras, números y espacios.',
            'nombre_tipo_pago.max' => 'El nombre del tipo de pago no puede tener más de :max caracteres.',
            'nombre_tipo_pago.unique' => 'El nombre del tipo de pago ya existe.',
        ]);

        $tipo_pago->nombre_tipo_pago=$req->nombre_tipo_pago;

        $tipo_pago->save();

        if ($tipo_pago->tipo_pago_pk) {
            return redirect('/ventas')->with('success', 'Tipo de pago actualizado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function baja($tipo_pago_pk){
        $datosTipo_pago = Tipo_pago::findOrFail($tipo_pago_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_pago) {

                $datosTipo_pago->estatus_tipo_pago = 0;
                $datosTipo_pago->save();

                return back()->with('success', 'Tipo de pago dado de baja');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }

    public function alta($tipo_pago_pk){
        $datosTipo_pago = Tipo_pago::findOrFail($tipo_pago_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            if ($datosTipo_pago) {

                $datosTipo_pago->estatus_tipo_pago = 1;
                $datosTipo_pago->save();

                return back()->with('success', 'Tipo de pago dado de alta');
            } else {
                return back()->with('error', 'Hay algún problema con la información');
            }
        } else {
            return redirect('/login');
        }
    }
}
