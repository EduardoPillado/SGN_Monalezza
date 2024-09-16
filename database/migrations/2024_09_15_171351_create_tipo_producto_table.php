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
        Schema::create('tipo_producto', function (Blueprint $table) {
            $table->id('tipo_producto_pk')->autoIncrement();
            $table->string('nombre_tipo_producto', 50);
            $table->smallInteger('estatus_tipo_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_producto');
    }
};
