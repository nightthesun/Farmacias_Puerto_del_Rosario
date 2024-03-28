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
        Schema::create('ges_pre__venta2s', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->unsignedBigInteger('id_table_ingreso_tienda_almacen')->comment('identificador unico que hace referencia al id de la tabla alm__ingreso_producto, especificamente al producto ingresado');
            $table->tinyInteger('tienda')->default(0)->comment('Esta columna determina si el registro en la tabla ges_pre__ventas pertenece a la tienda');
            $table->tinyInteger('almacen')->default(0)->comment('Esta columna determina si el registro en la tabla ges_pre__ventas pertenece a almacen');
            $table->decimal('precio_lista_gespreventa', 8, 2);
            $table->decimal('precio_venta_gespreventa', 8, 2)->comment('costo_compra_gespreventa');
            $table->decimal('cantidad_envase_gespreventa', 8, 2);
            $table->decimal('costo_compra_gespreventa', 8, 2);
            $table->decimal('margen_20p_gespreventa', 8, 2);
            $table->decimal('margen_30p_gespreventa', 8, 2);
            $table->decimal('utilidad_bruta_gespreventa', 8, 2);
            $table->decimal('utilidad_neto_gespreventa', 8, 2);
            $table->integer('idusuario')->comment('identificador del usuario que registro dicha gestion de precio de un producto');
            $table->tinyInteger('listo_venta')->default(0)->comment('Columna que hace referencia a que si un prodicto esta listo para la venta, esto significa que se llenaron los datos en la tabla ges_pre__ventas');
            $table->timestamps();
            $table->string('id_lista')->nullable()->default(0);
            $table->foreign('id_table_ingreso_tienda_almacen')->references('id')->on('pivot__modulo_tienda_almacens');       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ges_pre__venta2s');
    }
};
