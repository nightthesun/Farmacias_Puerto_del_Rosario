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
        Schema::create('tda__ingreso_productos', function (Blueprint $table) {
            $table->id()->comment('Identificar unico de registro de la tabla tda__ingreso_productos');

            $table->unsignedBigInteger('idtienda')->comment('Identificar que relaciona la tabla tienda con la tabla ingreso de productos');
            $table->unsignedBigInteger('id_prod_producto')->comment('identificador de la tabla prod__productos que relaciona con la tabla de tda__ingreso_productos');
            $table->string("envase",50)->comment("Envase con el que se esta registrando un producto en tda__ingreso_productos");
            $table->unsignedBigInteger('id_tipoentrada')->comment('Identificador unico que hace referencia a la tabla de prod__tipo_entradas');
            $table->integer('cantidad')->comment('Cantidad de productos que esta ingresando a la tienda');
            $table->integer('stock_ingreso')->comment('Stock de productos que en la tienda');
            $table->date('fecha_vencimiento')->nullable()->comment('fecha de vencimiento del producto');
            $table->string('lote')->comment('Codigo que hace referecia a un grupo de un productos');
            $table->string('registro_sanitario')->nullable()->comment('Codigo expedido por la autoridad sanitaria');
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 ->inactivo');
            $table->timestamps();
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el registo');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el registro');
            $table->string('num_traspaso')->nullable()->comment('identifica el numero de traspaso');

            $table->foreign('idtienda')->references('id')->on('tda__tiendas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('id_tipoentrada')->references('id')->on('prod__tipo_entradas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('id_prod_producto')->references('id')->on('prod__productos')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tda__ingreso_productos');
    }
};
