<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmVentanaModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__ventana_modulos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('idmodulo')->unsigned();
            $table->smallInteger('codventana')->unsigned();
            $table->string('nombre',100);
            $table->string('template');
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idmodulo')->references('id')->on('adm__modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm__ventana_modulos');
    }
}
