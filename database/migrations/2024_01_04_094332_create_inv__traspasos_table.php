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
        Schema::create('inv__traspasos', function (Blueprint $table) {
            //ingreso--
            $table->id()->comment("Identificar unico registro de la tabla inv__traspasos");
            $table->unsignedBigInteger('id_almacen_tienda')->comment("identificador de la tabla tienda destino o almacen destino");
            $table->unsignedBigInteger('id_prod_producto')->comment("identificador de la tabla producto");
            $table->string("envase",50)->comment("Envase con el que se esta registrando un producto en tda__ingreso_productos");
            $table->unsignedBigInteger('id_tipoentrada')->comment('Identificador unico que hace referencia a la tabla de prod__tipo_entradas');
            $table->integer('cantidad__stock_ingreso')->comment('Cantidad de productos que esta ingresando a la tienda y ttambien el stock actual de ese producto');
            $table->string('fecha_vencimiento')->nullable()->comment('fecha de vencimiento del producto');
            $table->string('lote')->comment('Codigo que hace referecia a un grupo de un productos');
            $table->string('registro_sanitario')->nullable()->comment('Codigo expedido por la autoridad sanitaria');
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 ->inactivo');
           //traspaso---
            $table->unsignedBigInteger("id_origen")->comment("id_ingreso del almacen o tienda origen");	           
            $table->unsignedBigInteger("id_destino")->comment("id_ingreso del almacen o tienda destino");	            
            $table->unsignedBigInteger("id_ingreso")->comment("id del ingreso de almacen o tienda");
            $table->string("cod_1")->comment("codigo del tienda o almacen");
            $table->string("cod_2")->comment("codigo del tienda o almacen");
            $table->string("leyenda")->comment("conjunto de informacion del producto");
            $table->string("glosa")->comment("descripcion de la informacion de forma como lo requiera la empresa");
            $table->smallInteger("numero_traspaso")->comment("conjunto de numero y combinaciones de numeros");	            
                  
            $table->smallInteger('id_usuario_modifico')->nullable();	           
            $table->smallInteger('id_usuario_registro');	           
            $table->boolean('procesado')->default(0)->comment('Estado del registro, 1 -> procesado, 0 -> no procesado');	          
            $table->timestamps();
            $table->smallInteger('user_id')->comment('Registra segun la accion');
            $table->string('name_des')->nullable();
            $table->string('name_ori')->nullable();
            $table->smallInteger('correlativo')->nullable();
            $table->integer('cantidad_old')->nullable()->comment('Cantidad antes de la modificacion');
    
           	           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__traspasos');
    }
};
