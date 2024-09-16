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
        Schema::create('nomina', function (Blueprint $table) {
            $table->id('nomina_pk')->autoIncrement();
            $table->unsignedBigInteger('empleado_fk');
            $table->date('fecha_pago');
            $table->decimal('salario_base');
            $table->decimal('horas_extra');
            $table->decimal('deducciones');
            $table->decimal('salario_neto');

            $table->foreign('empleado_fk')
                ->references('empleado_pk')
                ->on('empleado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina');
    }
};
