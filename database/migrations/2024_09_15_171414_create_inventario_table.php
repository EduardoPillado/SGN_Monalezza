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
            $table->unsignedBigInteger('ingrediente_fk');
            $table->datetime('fecha_inventario');
            $table->decimal('cantidad_inventario');

            $table->foreign('ingrediente_fk')
                ->references('ingrediente_pk')
                ->on('ingrediente');
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
