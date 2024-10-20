<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Medio_Pedido_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'medio_pedido')->insert([
            ['medio_pedido_pk' => 1, 'nombre_medio_pedido' => 'WhatsApp', 'estatus_medio_pedido' => '1'],
            ['medio_pedido_pk' => 2, 'nombre_medio_pedido' => 'Messenger', 'estatus_medio_pedido' => '1'],
            ['medio_pedido_pk' => 3, 'nombre_medio_pedido' => 'Teléfono', 'estatus_medio_pedido' => '1'],
            ['medio_pedido_pk' => 4, 'nombre_medio_pedido' => 'Local', 'estatus_medio_pedido' => '1'],
        ]);
    }
}
