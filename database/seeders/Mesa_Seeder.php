<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Mesa_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'mesa')->insert([
            ['mesa_pk' => 1, 'numero_mesa' => 1, 'ubicacion' => 'Primera a la derecha', 'estatus_mesa' => 1],
            ['mesa_pk' => 2, 'numero_mesa' => 2, 'ubicacion' => 'Segunda a la derecha', 'estatus_mesa' => 1],
            ['mesa_pk' => 3, 'numero_mesa' => 3, 'ubicacion' => 'Primera a la izquierda', 'estatus_mesa' => 1],
            ['mesa_pk' => 4, 'numero_mesa' => 4, 'ubicacion' => 'Segunda a la izquierda', 'estatus_mesa' => 1],
            ['mesa_pk' => 5, 'numero_mesa' => 5, 'ubicacion' => 'Barra', 'estatus_mesa' => 1],
        ]);
    }
}
