<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empleado;

class Admin_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = new Usuario();

        $usuario->nombre = 'Jesús Méndez';
        $usuario->usuario = 'Méndez';
        
        $pass = 'admin123';
        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);
        $usuario->contraseña = $hash;

        $usuario->rol_fk = 1;
        $usuario->estatus_usuario = 1;

        $usuario->save();

        $empleado = new Empleado();

        $empleado->usuario_fk = 1;
        $empleado->fecha_contratacion = '2024-10-25';
        $empleado->estatus_empleado = 1;

        $empleado->save();
    }
}
