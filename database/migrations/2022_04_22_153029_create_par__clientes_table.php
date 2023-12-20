<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__clientes', function (Blueprint $table) {
            $table->id();
            $table->string('papellido',50)->nullable();
            $table->string('sapellido',50)->nullable();
            $table->string('nombre',50);
            $table->string('ci',20)->nullable();
            $table->string('nit',30)->nullable();
            $table->string('telefono',50)->nullable();
            $table->tinyInteger('tipo_cliente')->nullable();
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
        Schema::dropIfExists('par__clientes');
    }
}
