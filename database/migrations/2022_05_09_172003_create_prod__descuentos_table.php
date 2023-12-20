<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod__descuentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('monto_descuento')->unsigned();
            $table->tinyInteger('idtipodescuento')->unsigned();
            $table->string('regla',255)->comment('es la regla que se aplicara para el descuento, estara registrada en cadena de texto segun parametros');
            $table->tinyInteger('aplica_a')->comment('1->todos los productos, 2->producto individual','3->categoria, 4->metodo ABC');
            $table->boolean('activo')->default(1);
            $table->tinyInteger('estado')->default(1)->comment('1->');
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
        Schema::dropIfExists('prod__descuentos');
    }
}
