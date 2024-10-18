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
        Schema::create('domicilio', function (Blueprint $table) {
            $table->id('domicilio_pk')->autoIncrement();
            $table->string('calle', 50);
            $table->integer('numero_externo');
            $table->integer('numero_interno')->nullable();
            $table->text('referencias')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilio');
    }
};
