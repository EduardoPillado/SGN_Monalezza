<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Cliente_controller extends Controller
{
    public function insertar(Request $req){
        $req->validate([
            'nombre' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:255', 'unique:usuario,nombre'],
            'usuario' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:255', 'unique:usuario,usuario'],
            'contraseña' => ['required', 'min:8', 'max:255'],
        ], [
            'nombre.required' => 'El nombre del empleado es obligatorio.',
            'nombre.regex' => 'El nombre del empleado solo puede contener letras, números y espacios.',
            'nombre.max' => 'El nombre del empleado no puede tener más de :max caracteres.',
            'nombre.unique' => 'El nombre del empleado ya existe.',

            'usuario.required' => 'El usuario es obligatorio.',
            'usuario.regex' => 'El usuario solo puede contener letras, números y espacios.',
            'usuario.max' => 'El usuario no puede tener más de :max caracteres.',
            'usuario.unique' => 'El usuario ya existe.',

            'contraseña.required' => 'La contraseña es obligatoria.',
            'contraseña.max' => 'La contraseña no puede tener más de :max caracteres.',
            'contraseña.min' => 'La contraseña no puede tener menos de :min caracteres.',
        ]);

        $usuario=new Usuario();

        $usuario->nombre=$req->nombre;
        $usuario->rol_fk=$req->rol_fk;
        $usuario->usuario=$req->usuario;
        $hash = Hash::make($req->input('contraseña'));
        $usuario->contraseña=$hash;
        $usuario->estatus_usuario=1;
        $usuario->save();

        $usuario->refresh();

        $empleado=new Empleado();
        
        $empleado->usuario_fk=$usuario->usuario_pk;
        $empleado->fecha_contratacion=$req->fecha_contratacion;
        $empleado->estatus_empleado=1;

        $empleado->save();
        
        if ($empleado->empleado_pk) {
            return back()->with('success', 'Empleado registrado');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }

    public function mostrar(){
        $datosEmpleado = Empleado::where('estatus_empleado', '=', 1)->get();
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $rol = session('nombre_rol');
            if ($rol == 'Administrador') {
                return view('empleados', compact('datosEmpleado'));
            } else {
                return back()->with('message', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function baja($empleado_pk){
        $datosEmpleado = Empleado::findOrFail($empleado_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $rol = session('nombre_rol');
            if ($rol == 'Administrador') {
                if ($datosEmpleado) {
                    $datosEmpleado->estatus_empleado = 0;
                    $datosEmpleado->save();

                    if ($datosEmpleado->usuario_fk) {
                        $usuario = Usuario::findOrFail($datosEmpleado->usuario_fk);
                        $usuario->estatus_usuario = 0;
                        $usuario->save();
                    }
                    return back()->with('success', 'Empleado dado de baja');
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

    public function datosParaEdicion($empleado_pk){
        $datosEmpleado = Empleado::findOrFail($empleado_pk);
        $USUARIO_PK = session('usuario_pk');
        if ($USUARIO_PK) {
            $rol = session('nombre_rol');
            if ($rol == 'Administrador') {
                return view('empleados', compact('datosEmpleado'));
            } else {
                return back()->with('warning', 'No puedes acceder');
            }
        } else {
            return redirect('/login');
        }
    }

    public function actualizar(Request $req, $empleado_pk){
        $req->validate([
            'nombre' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:255', 'unique:usuario,nombre,' . $empleado_pk . ',empleado_pk'],
            'usuario' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', 'max:255', 'unique:usuario,usuario,' . $empleado_pk . ',empleado_pk'],
            'contraseña' => ['required', 'min:8', 'max:255', 'confirmed'],
        ], [
            'nombre.required' => 'El nombre del empleado es obligatorio.',
            'nombre.regex' => 'El nombre del empleado solo puede contener letras, números y espacios.',
            'nombre.max' => 'El nombre del empleado no puede tener más de :max caracteres.',
            'nombre.unique' => 'El nombre del empleado ya existe.',

            'usuario.required' => 'El usuario es obligatorio.',
            'usuario.regex' => 'El usuario solo puede contener letras, números y espacios.',
            'usuario.max' => 'El usuario no puede tener más de :max caracteres.',
            'usuario.unique' => 'El usuario ya existe.',

            'contraseña.required' => 'La contraseña es obligatoria.',
            'contraseña.max' => 'La contraseña no puede tener más de :max caracteres.',
            'contraseña.min' => 'La contraseña no puede tener menos de :min caracteres.',
            'contraseña.confirmed' => 'Las contraseñas ingresadas deben coincidir.',
        ]);

        $datosEmpleado = Empleado::findOrFail($empleado_pk);

        $datosEmpleado->usuario->nombre=$req->nombre;
        $datosEmpleado->usuario->rol_fk=$req->rol_fk;
        $datosEmpleado->usuario->usuario=$req->usuario;
        
        $rules = [
            'contraseña' => 'required',
            'confirmar_contraseña' => 'required|same:contraseña',
        ];
        $validacion = Validator::make($req->all(), $rules);
        if ($validacion->fails()) {
            return back()->with('error', 'Las contraseñas no coinciden');
        }

        $pass = $req->input('contraseña');
        $hash = Hash::make($pass);
        $datosEmpleado->usuario->contraseña=$hash;

        $datosEmpleado->save();
        
        $datosEmpleado->usuario_fk=$datosEmpleado->usuario->usuario_pk;
        $datosEmpleado->fecha_contratacion=$req->fecha_contratacion;

        $datosEmpleado->save();
        
        if ($datosEmpleado->empleado_pk) {
            return back()->with('success', 'Datos de empleado actualizados');
        } else {
            return back()->with('error', 'Hay algún problema con la información');
        }
    }
}
