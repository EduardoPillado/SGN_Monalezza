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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('usuario_pk')->autoIncrement();
            $table->string('nombre', 100);
            $table->unsignedBigInteger('rol_fk');
            $table->string('usuario', 50);
            $table->string('contraseña', 255);
            $table->smallInteger('estatus_usuario');

            $table->foreign('rol_fk')
                ->references('rol_pk')
                ->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
