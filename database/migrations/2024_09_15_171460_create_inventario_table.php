<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id('inventario_pk')->autoIncrement();
            $table->unsignedBigInteger('ingrediente_fk')->nullable();
            $table->unsignedBigInteger('producto_fk')->nullable();
            $table->unsignedBigInteger('tipo_gasto_fk')->nullable();
            $table->unsignedBigInteger('proveedor_fk');
            $table->decimal('precio_proveedor');
            $table->datetime('fecha_inventario');
            $table->decimal('cantidad_inventario');
            $table->decimal('cantidad_inventario_minima');

            $table->foreign('ingrediente_fk')
                ->references('ingrediente_pk')
                ->on('ingrediente');

            $table->foreign('producto_fk')
                ->references('producto_pk')
                ->on('producto');

            $table->foreign('tipo_gasto_fk')
                ->references('tipo_gasto_pk')
                ->on('tipo_gasto');

            $table->foreign('proveedor_fk')
                ->references('proveedor_pk')
                ->on('proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
