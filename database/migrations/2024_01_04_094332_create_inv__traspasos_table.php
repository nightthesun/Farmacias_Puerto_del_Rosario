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
            $table->id();
            $table->smallInteger("id_origen")->comment("id_ingreso del almacen o tienda origen");	           
            $table->smallInteger("id_destino")->comment("id_ingreso del almacen o tienda destino");	            
            $table->smallInteger("id_producto")->comment("id del producto");
            $table->smallInteger("id_ingreso")->comment("id del ingreso de almacen o tienda");
            $table->string("cod_1")->comment("codigo del tienda o almacen");
            $table->string("cod_2")->comment("codigo del tienda o almacen");
            $table->string("leyenda")->comment("conjunto de informacion del producto");
            $table->string("glosa")->comment("descripcion de la informacion de forma como lo requiera la empresa");
            $table->boolean("activo")->defaul(1)->comment("estado del traspaso");
            $table->smallInteger("numero_traspaso")->comment("conjunto de numero y combinaciones de numeros");	            
            $table->smallInteger('id_usuario_modifico');	           
            $table->smallInteger('id_usuario_registro');	           
            $table->boolean('procesado')->default(0)->comment('Estado del registro, 1 -> procesado, 0 -> no procesado');	          

            $table->timestamps();	           
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
