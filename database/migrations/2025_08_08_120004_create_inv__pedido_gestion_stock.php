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
        Schema::create('inv__pedido_gestion_stock', function (Blueprint $table) {
          
            $table->bigInteger('id_gestion_stock');
            $table->string('lineas');
            $table->bigInteger('id_producto');
            $table->string('envase');            
            $table->smallInteger('ciclo_pedido');
            $table->smallInteger('consumo_pedido');
            $table->smallInteger('plazo_medio');
            $table->smallInteger('consumo_dia_pedido');
            $table->bigInteger('maximo_pedido');
            $table->smallInteger('actual_pedido');
            $table->bigInteger('stock_pedido');
            $table->bigInteger('cantidad_pedido');
            $table->decimal('precio_pedido',25,2);
                   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__pedido_gestion_stock');
    }
};
