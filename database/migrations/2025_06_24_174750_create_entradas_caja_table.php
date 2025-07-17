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
        Schema::create('entradas_caja', function (Blueprint $table) {
            $table->id('entradas_caja_pk')->autoIncrement();
            $table->decimal('monto_entrada_caja', 10, 2);
            $table->enum('tipo_entrada_caja', ['Inicial', 'Entrada']);
            $table->string('concepto_entrada_caja')->nullable();
            $table->dateTime('fecha_entrada_caja');
            $table->unsignedBigInteger('usuario_fk');

            $table->foreign('usuario_fk')
                ->references('usuario_pk')
                ->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas_caja');
    }
};
