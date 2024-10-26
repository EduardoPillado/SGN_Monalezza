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
        Schema::create('servicio', function (Blueprint $table) {
            $table->id('servicio_pk')->autoIncrement();
            $table->unsignedBigInteger('tipo_gasto_fk');
            $table->decimal('cantidad_pagada_servicio');
            $table->date('fecha_pago_servicio');

            $table->foreign('tipo_gasto_fk')
                ->references('tipo_gasto_pk')
                ->on('tipo_gasto');
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio');
    }
};
