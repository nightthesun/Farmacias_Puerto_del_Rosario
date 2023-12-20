<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRrhCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__cargos', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->smallInteger('idunidadorganizacional')->unsigned();
            $table->string('nombre',50);
            $table->string('descripcion',255)->nullable();
            $table->text('act_especificas')->nullable()->comment('detalla los objetivos especificos del cargo');
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
            $table->foreign('idunidadorganizacional')->references('id')->on('rrh__unidad_organizacionals');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrh__cargos');
    }
}
