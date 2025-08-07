<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_ingrediente_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'tipo_ingrediente')->insert([
            ['tipo_ingrediente_pk' => 1, 'nombre_tipo_ingrediente' => 'Ingrediente Base', 'estatus_tipo_ingrediente' => 1],
            ['tipo_ingrediente_pk' => 2, 'nombre_tipo_ingrediente' => 'Topping', 'estatus_tipo_ingrediente' => 1],
        ]);
    }
}
