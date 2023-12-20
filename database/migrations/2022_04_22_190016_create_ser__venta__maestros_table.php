<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerVentaMaestrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__venta__maestros', function (Blueprint $table) {
            $table->id();
            $table->string('num_documento',20);
            $table->tinyinteger('tipodocumento')->unsigned()->comment('1->recibo,2->factura');
            $table->bigInteger('idcliente');
            $table->decimal('total');
            $table->decimal('efectivo');
            $table->decimal('cambio');
            $table->boolean('activo')->default(1);
            $table->smallInteger('idsucursal');
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
        Schema::dropIfExists('ser__venta__maestros');
    }
}
