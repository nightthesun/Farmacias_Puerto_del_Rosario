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
        Schema::create('inv__ajuste_negativos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger("id_usuario")->comment("id del usuario que realizo la asignacion");
            $table->smallInteger("id_tipo")->comment("id tipo de ingreso");
            $table->smallInteger("id_producto_linea")->comment("id de la linea de producto");
            $table->smallInteger("id_sucursal")->comment("id de la sucursal");
            $table->string("usuario")->comment("nombre de usuario que hizo la operacion");
            $table->string("codigo")->comment("Codigo del producto asignado por el sistema");
            $table->string("linea")->comment("tipo de linea del producto");
            $table->string("producto")->comment("nombre del producto");
            $table->integer("cantidad")->comment("stock del producto que se está ingresando por el input");
            $table->string("descripcion")->nullable()->comment("son datos de entrada del formulario");
            $table->string("fecha")->comment("fecha que se realizó el registro");
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que está modificando el almacen');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que está registrando el almacen');
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 -> inactivo');
            $table->string("cod")->comment("codigo");
            $table->string("leyenda")->comment("es la union de descripcion de prodycto");
            $table->smallInteger("id_ingreso");
            $table->timestamps();
            $table->string('id_traspaso')->nullable()->comment('solo es para traspasos');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__ajuste_negativos');
    }
};
