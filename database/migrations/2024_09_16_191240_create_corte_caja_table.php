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
        Schema::create('corte_caja', function (Blueprint $table) {
            $table->id('corte_caja_pk')->autoIncrement();
            $table->dateTime('fecha_corte_inicio');
            $table->dateTime('fecha_corte_fin');
            $table->decimal('suma_efectivo_inicial');
            $table->integer('cantidad_ventas');
            $table->decimal('ganancia_total');
            $table->decimal('utilidad_neta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_caja');
    }
};
