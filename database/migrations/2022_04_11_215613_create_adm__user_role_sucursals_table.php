<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmUserRoleSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__user_role_sucursals', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('idrole')->unsigned();
            $table->smallInteger('iduser')->unsigned();
            $table->smallInteger('idsucursal')->unsigned();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idrole')->references('id')->on('adm__roles');
            $table->foreign('iduser')->references('id')->on('users');
            $table->foreign('idsucursal')->references('id')->on('adm__sucursals');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm__user_role_sucursals');
    }
}
