<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rol_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'rol')->insert([
            ['nombre_rol' => 'Administrador', 'permisos' => 'Agregar, modificar, y dar de baja cualquier dato del sistema, ademas de las mismas funciones que el empleado'],
            ['nombre_rol' => 'Empleado', 'permisos' => 'Realizar ventas, cortes de caja, y revisar el inventario actual, y productos del negocio'],
        ]);
    }
}
