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
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('cliente_pk')->autoIncrement();
            $table->string('nombre_cliente', 50);
            $table->unsignedBigInteger('domicilio_fk');
            $table->unsignedBigInteger('telefono_fk');

            $table->foreign('domicilio_fk')
                ->references('domicilio_pk')
                ->on('domicilio');

            $table->foreign('telefono_fk')
                ->references('telefono_pk')
                ->on('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
