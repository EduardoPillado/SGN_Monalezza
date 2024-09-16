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
        Schema::create('producto', function (Blueprint $table) {
            $table->id('producto_pk')->autoIncrement();
            $table->string('nombre_producto', 50);
            $table->unsignedBigInteger('tipo_producto_fk');
            $table->decimal('precio_producto');
            $table->unsignedBigInteger('proveedor_fk');
            $table->smallInteger('estatus_producto');

            $table->foreign('tipo_producto_fk')
                ->references('tipo_producto_pk')
                ->on('tipo_producto');

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
        Schema::dropIfExists('producto');
    }
};
