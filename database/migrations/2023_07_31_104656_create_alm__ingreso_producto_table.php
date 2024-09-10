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
        Schema::create('alm__ingreso_producto', function (Blueprint $table) {
            $table->id()->comment('Identificar unico de registro');
            $table->unsignedBigInteger('id_prod_producto')->comment('identificador de la tabla prod__productos que relaciona con la tabla de alm__ingreso_producto');
            $table->unsignedBigInteger('idalmacen')->comment('Identificar que relaciona la tabla alamcen con la tabla ingreso de productos');
            $table->integer('cantidad')->comment('Cantidad de productos que esta ingresando al almacen');
            $table->string('tipo_entrada')->comment('Es la manera en que el producto esta ingresando, ya sea por compra, cambio, devolucion, donacion, etc');
            $table->date('fecha_vencimiento')->nullable()->comment('fecha de vencimiento del producto');
            $table->string('lote')->comment('Codigo que hace referecia a un grupo de un productos');
            $table->string('registro_sanitario')->nullable()->comment('Codigo expedido por la autoridad sanitaria');
            $table->string('codigo_internacional')->comment('Codigo internacional o codigo de barras que tiene los productos');
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 ->inactivo');
            $table->timestamps();
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el producto');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el producto');
            $table->string('num_traspaso')->nullable()->comment('identifica el numero de traspaso');
            $table->tinyInteger('prioridad_caducidad')->nullable()->default(0)->comment('0=sin prioridad, 1=baja,2=media,3=alta');
            $table->foreign('idalmacen')->references('id')->on('alm__almacens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alm__ingreso_producto');
    }
};
