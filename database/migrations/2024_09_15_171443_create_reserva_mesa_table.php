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
        Schema::create('reserva_mesa', function (Blueprint $table) {
            $table->id('reserva_mesa_pk')->autoIncrement();
            $table->unsignedBigInteger('mesa_fk');
            $table->unsignedBigInteger('reserva_fk');

            $table->foreign('mesa_fk')
                ->references('mesa_pk')
                ->on('mesa');

            $table->foreign('reserva_fk')
                ->references('reserva_pk')
                ->on('reserva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_mesa');
    }
};
