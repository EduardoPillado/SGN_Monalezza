<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Usuario_controller extends Controller
{
    use ValidatesRequests;
    
    public function login(Request $req){
        $this->validate($req, [
            'usuario' => 'required',
            'contraseña' => 'required',
        ]);

        $credentials = $req->only('usuario', 'contraseña');

        $usuario = $this->obtenerUsuarioPorNombre($credentials['usuario']);

        if ($usuario && password_verify($credentials['contraseña'], $usuario->contraseña)) {
            if ($usuario->estatus_usuario == 1) {
                session(['usuario_pk' => $usuario->usuario_pk, 'usuario' => $usuario->usuario]);
                session(['rol_pk' => $usuario->rol->rol_pk, 'nombre_rol' => $usuario->rol->nombre_rol]);
                if ($usuario->rol_fk == 1) {
                    return redirect('/')->with('success', 'Bienvenido');
                } else {
                    return redirect('/asistencia/entrada')->with('success', 'Bienvenido');
                }
            } else {
                return redirect('/login')->with('error', 'El usuario no es válido');
            }
        } else {
            return redirect('/login')->with('error', 'Datos incorrectos');
        }
    }

    private function obtenerUsuarioPorNombre($usuario){
        $usuario = Usuario::where('usuario', $usuario)->first();
        return $usuario;
    }

    public function logout() {
        session()->forget(['usuario_pk', 'usuario', 'rol_pk', 'nombre_rol']);
        return redirect('/login')->with('success', 'Sesión cerrada');
    }
}
