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
        Schema::create('pedido', function (Blueprint $table) {
            $table->id('pedido_pk')->autoIncrement();
            $table->unsignedBigInteger('cliente_fk');
            $table->unsignedBigInteger('empleado_fk');
            $table->dateTime('fecha_hora_pedido');
            $table->unsignedBigInteger('medio_pedido_fk');
            $table->decimal('monto_total');
            $table->string('numero_transaccion', 50)->nullable();
            $table->unsignedBigInteger('tipo_pago_fk');
            $table->text('notas_remision');
            $table->smallInteger('estatus_pedido');

            $table->foreign('cliente_fk')
                ->references('cliente_pk')
                ->on('cliente');
            
            $table->foreign('empleado_fk')
                ->references('empleado_pk')
                ->on('empleado');

            $table->foreign('medio_padido_fk')
                ->references('medio_padido_pk')
                ->on(table: 'medio_padido');

            $table->foreign('tipo_pago_fk')
                ->references('tipo_pago_pk')
                ->on('tipo_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
