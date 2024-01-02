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
        Schema::create('inv_traspasos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger("id_origen")->comment("id_ingreso del almacen o tienda origen");
            $table->smallInteger("id_destino")->comment("id_ingreso del almacen o tienda destino");
            $table->smallInteger("id_producto")->comment("id del producto");
            $table->smallInteger("id_ingreso")->comment("id_ingreso del producto");
            $table->string("tipo")->comment("el tipo si es tienda o almacen");
            $table->string("leyenda")->comment("el conjunto de codigo y nombre del producto");
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 -> inactivo');
            $table->string("glosa")->comment("datos o descripción");
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
        Schema::dropIfExists('inv_traspasos');
    }
};
