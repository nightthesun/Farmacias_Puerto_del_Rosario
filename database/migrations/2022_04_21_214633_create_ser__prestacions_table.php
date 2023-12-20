<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerPrestacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__prestacions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idarea')->unsigned();
            $table->integer('correlativo')->unsigned();
            $table->string('codigo',3);
            $table->string('nombre',100);
            $table->decimal('precio',8,2);
            $table->string('descripcion',200)->nullable();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idarea')->references('id')->on('ser__areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ser__prestacions');
    }
}
