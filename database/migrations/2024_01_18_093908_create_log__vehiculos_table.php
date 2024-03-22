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
        Schema::create('log__vehiculos', function (Blueprint $table) {
           // $table->id();
           // $table->smallInteger('idsucursal')->unsigned();
           // $table->string("matricula")->comment("Codigo que es asignado por el sistema");
           // $table->string("razon_social")->comment("Nombre del almacen");
           // $table->string("nombre_comercial")->comment("Nombre comercial del almacen");
           // $table->string("telefono")->comment("Telefonos de contacto del almacen");
           // $table->smallInteger("id_emple")->comment("Telefonos de contacto del almacen");
           // $table->string("tipo")->comment("Direccion del almacen");
           // $table->boolean('activo')->default(1);
           // $table->tinyInteger('estado')->default(1)->comment('1->activo, 0->inactivo');
           // $table->smallInteger('id_user')->unsigned()->nullable()->comment('usuario general');
           // $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
           // $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');
           // $table->timestamps();
           // $table->string('color')->nullable();
           // $table->string('nro_chasis')->nullable();
           $table->id();
            $table->smallInteger('idsucursal')->nullable();
            $table->string('matricula')->comment('Codigo que es asignado por el sistema');
            $table->string('razon_social')->nullable()->comment('Nombre del almacen');
            $table->string('nombre_comercial')->nullable()->comment('Nombre comercial del almacen');
            $table->string('telefono')->nullable()->comment('Telefonos de contacto del almacen');
            $table->smallInteger('id_emple')->comment('Telefonos de contacto del almacen');
            $table->string('tipo')->comment('Direccion del almacen');
            $table->boolean('activo')->default(1);
            $table->tinyInteger('estado')->default(1)->comment('1->activo, 0->inactivo');
            $table->smallInteger('id_user')->comment('usuario general');
            $table->smallInteger('id_usuario_modifica')->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_registra')->nullable()->comment('identificador del usuario que esta registrando el almacen');
            $table->timestamps();
            $table->string('color')->nullable();
            $table->string('nro_chasis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__vehiculos');
    }
};
