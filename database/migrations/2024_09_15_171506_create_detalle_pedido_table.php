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
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('detalle_pedido_pk')->autoIncrement();
            $table->unsignedBigInteger('pedido_fk');
            $table->unsignedBigInteger('producto_fk');
            $table->integer('cantidad_producto');

            $table->foreign('pedido_fk')
                ->references('pedido_pk')
                ->on('pedido');

            $table->foreign('producto_fk')
                ->references('producto_pk')
                ->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
