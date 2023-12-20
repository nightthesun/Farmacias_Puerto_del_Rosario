<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmAccionVentanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__accion_ventanas', function (Blueprint $table) {
            $table->Increments('id');
            $table->smallInteger('idventana')->unsigned();
            $table->string('nombre',100);
            $table->string('metodo_vue')->comment('nombre del metodo javascript dentro del vue');
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idventana')->references('id')->on('adm__ventana_modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm__accion_ventanas');
    }
}
