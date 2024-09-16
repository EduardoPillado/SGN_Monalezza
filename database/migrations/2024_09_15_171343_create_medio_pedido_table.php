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
        Schema::create('medio_pedido', function (Blueprint $table) {
            $table->id('medio_pedido_pk')->autoIncrement();
            $table->string('nombre_medio_pedido', 50);
            $table->smallInteger('estatus_medio_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medio_pedido');
    }
};
