<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRrhProfesionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__profesions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre',50);
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
        Schema::dropIfExists('rrh__profesions');
    }
}
