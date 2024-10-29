<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_Pago_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'tipo_pago')->insert([
            ['tipo_pago_pk' => 1, 'nombre_tipo_pago' => 'Transferencia', 'estatus_tipo_pago' => 1],
            ['tipo_pago_pk' => 2, 'nombre_tipo_pago' => 'Tarjeta de crédito', 'estatus_tipo_pago' => 1],
            ['tipo_pago_pk' => 3, 'nombre_tipo_pago' => 'Efectivo', 'estatus_tipo_pago' => 1],
        ]);
    }
}
