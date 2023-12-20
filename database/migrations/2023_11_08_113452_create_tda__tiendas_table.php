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
        Schema::create('tda__tiendas', function (Blueprint $table) {
            $table->id();
            $table->string("codigo",10)->comment("Codigo que identifica la tienda de cada sucursal");
            $table->unsignedSmallInteger("idsucursal")->comment("Identificador unico que hace referencia a la tabla de Sucursales");
            $table->boolean('activo')->default(1)->comment("Estado del registro, 1 -> activo, 0 ->inactivo");
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el registro');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el registro');
            $table->timestamps();
            $table->foreign('idsucursal')
                  ->references('id')->on('adm__sucursals')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tda__tiendas');
    }
};
