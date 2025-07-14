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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('asistencia_pk')->autoIncrement();
            $table->unsignedBigInteger('empleado_fk');
            $table->date('fecha_asistencia');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();

            $table->foreign('empleado_fk')
                ->references('empleado_pk')
                ->on(table: 'empleado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
