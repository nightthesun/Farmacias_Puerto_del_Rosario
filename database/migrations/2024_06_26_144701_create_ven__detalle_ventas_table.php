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
        Schema::create('ven__detalle_ventas', function (Blueprint $table) {
         
            $table->smallInteger('id_venta');
            $table->smallInteger('id_detalle_descuento')->default(0);
            $table->smallInteger('es_lista')->default(0)->comment('0 es para lista por defecto 1 es para lista');
            $table->smallInteger('id_ges_pre');            
            $table->smallInteger('id_ingreso');
            $table->smallInteger('id_producto');
            $table->smallInteger('id_linea');
            $table->decimal('precio_venta', 11, 2);
            $table->integer('cantidad_venta');
            $table->string('codigo_tienda_almacen',25)->nullable();    
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__detalle_ventas');
    }
};
