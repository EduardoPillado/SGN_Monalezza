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
        Schema::create('empleado', function (Blueprint $table) {
            $table->id('empleado_pk')->autoIncrement();
            $table->unsignedBigInteger('usuario_fk');
            $table->date('fecha_contratacion');
            $table->smallInteger('estatus_empleado');

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
        Schema::dropIfExists('empleado');
    }
};
