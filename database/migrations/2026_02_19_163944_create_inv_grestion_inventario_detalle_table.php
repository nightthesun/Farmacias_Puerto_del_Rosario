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
        Schema::create('inv_grestion_inventario_detalle', function (Blueprint $table) {           
            $table->bigInteger('id_inv_grestion_inventario');
            $table->bigInteger('id_ingreso');
            $table->bigInteger('id_producto');
            $table->string('envase');
            $table->smallInteger('cantidad_sis_detalle_inventario');
            $table->smallInteger('cantidad_reg_detalle_inventario');
            $table->smallInteger('diferencia_detalle_inventario');
            $table->string('estado');  
            $table->string('lote')->nullable();
            $table->string('fecha_v')->nullable();          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_grestion_inventario_detalle');
    }
};
