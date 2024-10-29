<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_Gasto_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'tipo_gasto')->insert([
            ['tipo_gasto_pk' => 1, 'nombre_tipo_gasto' => 'Gas', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 2, 'nombre_tipo_gasto' => 'Agua', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 3, 'nombre_tipo_gasto' => 'Electricidad', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 4, 'nombre_tipo_gasto' => 'Línea de teléfono e internet', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 5, 'nombre_tipo_gasto' => 'Mantenimiento o reparaciones', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 6, 'nombre_tipo_gasto' => 'Decoraciones y adornos', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 7, 'nombre_tipo_gasto' => 'Productos de limpieza', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 8, 'nombre_tipo_gasto' => 'Impuestos', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 9, 'nombre_tipo_gasto' => 'Seguro del negocio', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 10, 'nombre_tipo_gasto' => 'Alquiler', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 11, 'nombre_tipo_gasto' => 'Publicidad y diseño', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 12, 'nombre_tipo_gasto' => 'Membresías', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 13, 'nombre_tipo_gasto' => 'Desechables', 'estatus_tipo_gasto' => 1],
            ['tipo_gasto_pk' => 14, 'nombre_tipo_gasto' => 'Ingredientes', 'estatus_tipo_gasto' => 1],
        ]);
    }
}
