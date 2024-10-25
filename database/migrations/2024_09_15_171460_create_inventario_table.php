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
            $table->datetime('fecha_inventario');
            $table->decimal('cantidad_inventario');
            $table->unsignedBigInteger('gasto_fk')->nullable();

            $table->foreign('ingrediente_fk')
                ->references('ingrediente_pk')
                ->on('ingrediente');

            $table->foreign('producto_fk')
                ->references('producto_pk')
                ->on('producto');

            $table->foreign('gasto_fk')
                ->references('gasto_pk')
                ->on('gasto');
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
