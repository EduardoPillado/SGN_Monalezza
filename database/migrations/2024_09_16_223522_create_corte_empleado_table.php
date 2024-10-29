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
        Schema::create('corte_empleado', function (Blueprint $table) {
            $table->id('corte_empleado_pk')->autoIncrement();
            $table->unsignedBigInteger('corte_caja_fk');
            $table->unsignedBigInteger('empleado_fk');

            $table->foreign('corte_caja_fk')
                ->references('corte_caja_pk')
                ->on('corte_caja');

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
        Schema::dropIfExists('corte_empleado');
    }
};
