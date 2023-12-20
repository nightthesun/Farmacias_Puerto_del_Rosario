<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmRoleAccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__role_accions', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('idrole')->unsigned();
            $table->Integer('idaccionventana')->unsigned();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idrole')->references('id')->on('adm__roles');
            $table->foreign('idaccionventana')->references('id')->on('adm__accion_ventanas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm__role_accions');
    }
}
