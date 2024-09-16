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
        Schema::create('reserva', function (Blueprint $table) {
            $table->id('reserva_pk')->autoIncrement();
            $table->unsignedBigInteger('cliente_fk');
            $table->dateTime('fecha_hora_reserva');
            $table->text('notas')->nullable();
            $table->smallInteger('estatus_reserva');

            $table->foreign('cliente_fk')
                ->references('cliente_pk')
                ->on('cliente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
