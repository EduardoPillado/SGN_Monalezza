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
        Schema::create('producto_ingrediente', function (Blueprint $table) {
            $table->id('producto_ingrediente_pk')->autoIncrement();
            $table->unsignedBigInteger('producto_fk');
            $table->unsignedBigInteger('ingrediente_fk');

            $table->foreign('producto_fk')
                ->references('producto_pk')
                ->on('producto');

            $table->foreign('ingrediente_fk')
                ->references('ingrediente_pk')
                ->on('ingrediente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_ingrediente');
    }
};