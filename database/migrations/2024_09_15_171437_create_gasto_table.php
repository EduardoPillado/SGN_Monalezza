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
        Schema::create('gasto', function (Blueprint $table) {
            $table->id('gasto_pk')->autoIncrement();
            $table->date('fecha_gasto');
            $table->unsignedBigInteger('tipo_gasto_fk');
            $table->decimal('monto_gasto');
            $table->unsignedBigInteger('proveedor_fk');
            $table->text('descripcion');

            $table->foreign('tipo_gasto_fk')
                ->references('tipo_gasto_pk')
                ->on('tipo_gasto');

            $table->foreign('proveedor_fk')
                ->references('proveedor_pk')
                ->on('proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto');
    }
};
