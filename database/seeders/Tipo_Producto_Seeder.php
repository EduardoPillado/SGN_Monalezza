<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_Producto_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'tipo_producto')->insert([
            ['tipo_producto_pk' => 1, 'nombre_tipo_producto' => 'Pizza mediana', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 2, 'nombre_tipo_producto' => 'Pizza familiar', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 3, 'nombre_tipo_producto' => 'Pizza mega', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 4, 'nombre_tipo_producto' => 'Pizza cuadrada', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 5, 'nombre_tipo_producto' => 'Aderezo', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 6, 'nombre_tipo_producto' => 'Bebida', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 7, 'nombre_tipo_producto' => 'Extra', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 8, 'nombre_tipo_producto' => 'Ingrediente extra', 'estatus_tipo_producto' => 1],
            ['tipo_producto_pk' => 9, 'nombre_tipo_producto' => 'Postre', 'estatus_tipo_producto' => 1],
        ]);
    }
}
