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
        Schema::create('detalle_efectivo', function (Blueprint $table) {
            $table->id('detalle_efectivo_pk')->autoIncrement();
            $table->dateTime('fecha_actual');
            $table->decimal('efectivo_inicial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_efectivo');
    }
};