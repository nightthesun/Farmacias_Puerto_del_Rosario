<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('idmodulos')->comment('separados por comas los modulos a los que tiene acceso el rol');
            $table->string('idventanas',300)->comment('separados por comas las ventanas a los que tiene acceso el rol');
            $table->string('idacciones',300)->comment('separados por comas los ids de las acciones a las que tiene permiso el rol')->nullable();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm__roles');
    }
}
