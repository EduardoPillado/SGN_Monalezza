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
        Schema::create('ingrediente', function (Blueprint $table) {
            $table->id('ingrediente_pk')->autoIncrement();
            $table->string('nombre_ingrediente', 50);
            $table->integer('cantidad_actual');
            $table->string('um');
            $table->integer('um_minima');
            $table->smallInteger('estatus_ingrediente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingrediente');
    }
};
