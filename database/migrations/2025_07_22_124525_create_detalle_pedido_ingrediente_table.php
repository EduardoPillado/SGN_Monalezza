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
        Schema::create('detalle_pedido_ingrediente', function (Blueprint $table) {
            $table->id('detalle_pedido_ingrediente_pk')->autoIncrement();
            $table->unsignedBigInteger('detalle_pedido_fk');
            $table->unsignedBigInteger('ingrediente_fk');
            $table->decimal('cantidad_usada');

            $table->foreign('detalle_pedido_fk')
                ->references('detalle_pedido_pk')
                ->on('detalle_pedido');
            
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
        Schema::dropIfExists('detalle_pedido_ingrediente');
    }
};
