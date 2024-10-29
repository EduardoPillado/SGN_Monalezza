<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Producto_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'producto')->insert([
            // Pizzas --------------------------------------------------------------------------------------------------------------------------------------------------

            ['producto_pk' => 1, 'nombre_producto' => 'Picasso', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 2, 'nombre_producto' => 'Picasso', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 3, 'nombre_producto' => 'Picasso', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 4, 'nombre_producto' => 'Picasso', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            ['producto_pk' => 5, 'nombre_producto' => 'Franconia', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 6, 'nombre_producto' => 'Franconia', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 7, 'nombre_producto' => 'Franconia', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 8, 'nombre_producto' => 'Franconia', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            ['producto_pk' => 9, 'nombre_producto' => 'Da Vinci', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 10, 'nombre_producto' => 'Da Vinci', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 11, 'nombre_producto' => 'Da Vinci', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 12, 'nombre_producto' => 'Da Vinci', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            ['producto_pk' => 13, 'nombre_producto' => 'Bacon', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 14, 'nombre_producto' => 'Bacon', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 15, 'nombre_producto' => 'Bacon', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 16, 'nombre_producto' => 'Bacon', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            ['producto_pk' => 17, 'nombre_producto' => 'Portobello', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 18, 'nombre_producto' => 'Portobello', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 19, 'nombre_producto' => 'Portobello', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 20, 'nombre_producto' => 'Portobello', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            ['producto_pk' => 21, 'nombre_producto' => 'Frida', 'tipo_producto_fk' => 1, 'precio_producto' => 195, 'estatus_producto' => 1],
            ['producto_pk' => 22, 'nombre_producto' => 'Frida', 'tipo_producto_fk' => 2, 'precio_producto' => 225, 'estatus_producto' => 1],
            ['producto_pk' => 23, 'nombre_producto' => 'Frida', 'tipo_producto_fk' => 3, 'precio_producto' => 290, 'estatus_producto' => 1],
            ['producto_pk' => 24, 'nombre_producto' => 'Frida', 'tipo_producto_fk' => 4, 'precio_producto' => 345, 'estatus_producto' => 1],
            
            ['producto_pk' => 25, 'nombre_producto' => 'Go Hawaii', 'tipo_producto_fk' => 1, 'precio_producto' => 195, 'estatus_producto' => 1],
            ['producto_pk' => 26, 'nombre_producto' => 'Go Hawaii', 'tipo_producto_fk' => 2, 'precio_producto' => 225, 'estatus_producto' => 1],
            ['producto_pk' => 27, 'nombre_producto' => 'Go Hawaii', 'tipo_producto_fk' => 3, 'precio_producto' => 290, 'estatus_producto' => 1],
            ['producto_pk' => 28, 'nombre_producto' => 'Go Hawaii', 'tipo_producto_fk' => 4, 'precio_producto' => 345, 'estatus_producto' => 1],

            ['producto_pk' => 29, 'nombre_producto' => 'Vegetariana', 'tipo_producto_fk' => 1, 'precio_producto' => 195, 'estatus_producto' => 1],
            ['producto_pk' => 30, 'nombre_producto' => 'Vegetariana', 'tipo_producto_fk' => 2, 'precio_producto' => 225, 'estatus_producto' => 1],
            ['producto_pk' => 31, 'nombre_producto' => 'Vegetariana', 'tipo_producto_fk' => 3, 'precio_producto' => 290, 'estatus_producto' => 1],
            ['producto_pk' => 32, 'nombre_producto' => 'Vegetariana', 'tipo_producto_fk' => 4, 'precio_producto' => 345, 'estatus_producto' => 1],

            ['producto_pk' => 33, 'nombre_producto' => 'Palomita', 'tipo_producto_fk' => 1, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 34, 'nombre_producto' => 'Palomita', 'tipo_producto_fk' => 2, 'precio_producto' => 230, 'estatus_producto' => 1],
            ['producto_pk' => 35, 'nombre_producto' => 'Palomita', 'tipo_producto_fk' => 3, 'precio_producto' => 295, 'estatus_producto' => 1],
            ['producto_pk' => 36, 'nombre_producto' => 'Palomita', 'tipo_producto_fk' => 4, 'precio_producto' => 350, 'estatus_producto' => 1],
            
            ['producto_pk' => 37, 'nombre_producto' => 'Extravaganza', 'tipo_producto_fk' => 1, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 38, 'nombre_producto' => 'Extravaganza', 'tipo_producto_fk' => 2, 'precio_producto' => 230, 'estatus_producto' => 1],
            ['producto_pk' => 39, 'nombre_producto' => 'Extravaganza', 'tipo_producto_fk' => 3, 'precio_producto' => 295, 'estatus_producto' => 1],
            ['producto_pk' => 40, 'nombre_producto' => 'Extravaganza', 'tipo_producto_fk' => 4, 'precio_producto' => 350, 'estatus_producto' => 1],

            ['producto_pk' => 41, 'nombre_producto' => 'Sinaloense', 'tipo_producto_fk' => 1, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 42, 'nombre_producto' => 'Sinaloense', 'tipo_producto_fk' => 2, 'precio_producto' => 230, 'estatus_producto' => 1],
            ['producto_pk' => 43, 'nombre_producto' => 'Sinaloense', 'tipo_producto_fk' => 3, 'precio_producto' => 295, 'estatus_producto' => 1],
            ['producto_pk' => 44, 'nombre_producto' => 'Sinaloense', 'tipo_producto_fk' => 4, 'precio_producto' => 350, 'estatus_producto' => 1],

            ['producto_pk' => 45, 'nombre_producto' => 'Crustacea', 'tipo_producto_fk' => 1, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 46, 'nombre_producto' => 'Crustacea', 'tipo_producto_fk' => 2, 'precio_producto' => 230, 'estatus_producto' => 1],
            ['producto_pk' => 47, 'nombre_producto' => 'Crustacea', 'tipo_producto_fk' => 3, 'precio_producto' => 295, 'estatus_producto' => 1],
            ['producto_pk' => 48, 'nombre_producto' => 'Crustacea', 'tipo_producto_fk' => 4, 'precio_producto' => 350, 'estatus_producto' => 1],

            ['producto_pk' => 49, 'nombre_producto' => 'Monalezza', 'tipo_producto_fk' => 1, 'precio_producto' => 205, 'estatus_producto' => 1],
            ['producto_pk' => 50, 'nombre_producto' => 'Monalezza', 'tipo_producto_fk' => 2, 'precio_producto' => 250, 'estatus_producto' => 1],
            ['producto_pk' => 51, 'nombre_producto' => 'Monalezza', 'tipo_producto_fk' => 3, 'precio_producto' => 320, 'estatus_producto' => 1],
            ['producto_pk' => 52, 'nombre_producto' => 'Monalezza', 'tipo_producto_fk' => 4, 'precio_producto' => 385, 'estatus_producto' => 1],

            ['producto_pk' => 53, 'nombre_producto' => 'Pizza de Sol', 'tipo_producto_fk' => 1, 'precio_producto' => 205, 'estatus_producto' => 1],
            ['producto_pk' => 54, 'nombre_producto' => 'Pizza de Sol', 'tipo_producto_fk' => 2, 'precio_producto' => 240, 'estatus_producto' => 1],
            ['producto_pk' => 55, 'nombre_producto' => 'Pizza de Sol', 'tipo_producto_fk' => 3, 'precio_producto' => 320, 'estatus_producto' => 1],
            ['producto_pk' => 56, 'nombre_producto' => 'Pizza de Sol', 'tipo_producto_fk' => 4, 'precio_producto' => 385, 'estatus_producto' => 1],

            ['producto_pk' => 57, 'nombre_producto' => 'Media Luna', 'tipo_producto_fk' => 1, 'precio_producto' => 205, 'estatus_producto' => 1],
            ['producto_pk' => 58, 'nombre_producto' => 'Media Luna', 'tipo_producto_fk' => 2, 'precio_producto' => 240, 'estatus_producto' => 1],
            ['producto_pk' => 59, 'nombre_producto' => 'Media Luna', 'tipo_producto_fk' => 3, 'precio_producto' => 320, 'estatus_producto' => 1],
            ['producto_pk' => 60, 'nombre_producto' => 'Media Luna', 'tipo_producto_fk' => 4, 'precio_producto' => 385, 'estatus_producto' => 1],

            ['producto_pk' => 61, 'nombre_producto' => 'Monabritas', 'tipo_producto_fk' => 1, 'precio_producto' => 175, 'estatus_producto' => 1],
            ['producto_pk' => 62, 'nombre_producto' => 'Monabritas', 'tipo_producto_fk' => 2, 'precio_producto' => 200, 'estatus_producto' => 1],
            ['producto_pk' => 63, 'nombre_producto' => 'Monabritas', 'tipo_producto_fk' => 3, 'precio_producto' => 270, 'estatus_producto' => 1],
            ['producto_pk' => 64, 'nombre_producto' => 'Monabritas', 'tipo_producto_fk' => 4, 'precio_producto' => 320, 'estatus_producto' => 1],

            // ---------------------------------------------------------------------------------------------------------------------------------------------------------

            // Bebidas -------------------------------------------------------------------------------------------------------------------------------------------------

            ['producto_pk' => 65, 'nombre_producto' => 'Refresco', 'tipo_producto_fk' => 6, 'precio_producto' => 25, 'estatus_producto' => 1],
            ['producto_pk' => 66, 'nombre_producto' => 'Agua de 1/2', 'tipo_producto_fk' => 6, 'precio_producto' => 20, 'estatus_producto' => 1],
            ['producto_pk' => 67, 'nombre_producto' => 'Agua de 1Lt', 'tipo_producto_fk' => 6, 'precio_producto' => 30, 'estatus_producto' => 1],
            ['producto_pk' => 68, 'nombre_producto' => 'Té de 1/2', 'tipo_producto_fk' => 6, 'precio_producto' => 20, 'estatus_producto' => 1],
            ['producto_pk' => 69, 'nombre_producto' => 'Té de 1Lt', 'tipo_producto_fk' => 6, 'precio_producto' => 30, 'estatus_producto' => 1],
            ['producto_pk' => 70, 'nombre_producto' => 'Agua natural 500ml', 'tipo_producto_fk' => 6, 'precio_producto' => 15, 'estatus_producto' => 1],
            ['producto_pk' => 71, 'nombre_producto' => 'Agua natural 1Lt', 'tipo_producto_fk' => 6, 'precio_producto' => 20, 'estatus_producto' => 1],
            
            // ---------------------------------------------------------------------------------------------------------------------------------------------------------

            // Extras --------------------------------------------------------------------------------------------------------------------------------------------------

            ['producto_pk' => 72, 'nombre_producto' => 'Panzeronni', 'tipo_producto_fk' => 7, 'precio_producto' => 120, 'estatus_producto' => 1],
            ['producto_pk' => 73, 'nombre_producto' => 'Calzone', 'tipo_producto_fk' => 7, 'precio_producto' => 150, 'estatus_producto' => 1],
            ['producto_pk' => 74, 'nombre_producto' => 'Espagueti a la boloñesa', 'tipo_producto_fk' => 7, 'precio_producto' => 90, 'estatus_producto' => 1],
            ['producto_pk' => 75, 'nombre_producto' => 'Dedos de queso', 'tipo_producto_fk' => 7, 'precio_producto' => 85, 'estatus_producto' => 1],

            // ---------------------------------------------------------------------------------------------------------------------------------------------------------

            // Ingredientes extra --------------------------------------------------------------------------------------------------------------------------------------

            ['producto_pk' => 76, 'nombre_producto' => 'Orilla de queso chihuahua Mediana', 'tipo_producto_fk' => 8, 'precio_producto' => 25, 'estatus_producto' => 1],
            ['producto_pk' => 77, 'nombre_producto' => 'Orilla de queso chihuahua Familiar', 'tipo_producto_fk' => 8, 'precio_producto' => 30, 'estatus_producto' => 1],
            ['producto_pk' => 78, 'nombre_producto' => 'Orilla de queso chihuahua Mega', 'tipo_producto_fk' => 8, 'precio_producto' => 40, 'estatus_producto' => 1],
            ['producto_pk' => 79, 'nombre_producto' => 'Orilla de queso chihuahua Cuadrada', 'tipo_producto_fk' => 8, 'precio_producto' => 50, 'estatus_producto' => 1],

            ['producto_pk' => 80, 'nombre_producto' => 'Orilla de queso philadelphia Mediana', 'tipo_producto_fk' => 8, 'precio_producto' => 30, 'estatus_producto' => 1],
            ['producto_pk' => 81, 'nombre_producto' => 'Orilla de queso philadelphia Familiar', 'tipo_producto_fk' => 8, 'precio_producto' => 40, 'estatus_producto' => 1],
            ['producto_pk' => 82, 'nombre_producto' => 'Orilla de queso philadelphia Mega', 'tipo_producto_fk' => 8, 'precio_producto' => 50, 'estatus_producto' => 1],
            ['producto_pk' => 83, 'nombre_producto' => 'Orilla de queso philadelphia Cuadrada', 'tipo_producto_fk' => 8, 'precio_producto' => 65, 'estatus_producto' => 1],

            ['producto_pk' => 84, 'nombre_producto' => 'Orilla de dedos de queso chihuahua o philadelphia Mediana', 'tipo_producto_fk' => 8, 'precio_producto' => 45, 'estatus_producto' => 1],
            ['producto_pk' => 85, 'nombre_producto' => 'Orilla de dedos de queso chihuahua o philadelphia Familiar', 'tipo_producto_fk' => 8, 'precio_producto' => 55, 'estatus_producto' => 1],
            ['producto_pk' => 86, 'nombre_producto' => 'Orilla de dedos de queso chihuahua o philadelphia Mega', 'tipo_producto_fk' => 8, 'precio_producto' => 65, 'estatus_producto' => 1],
            ['producto_pk' => 87, 'nombre_producto' => 'Orilla de dedos de queso chihuahua o philadelphia Cuadrada', 'tipo_producto_fk' => 8, 'precio_producto' => 75, 'estatus_producto' => 1],
            
            // ---------------------------------------------------------------------------------------------------------------------------------------------------------
        ]);
    }
}
