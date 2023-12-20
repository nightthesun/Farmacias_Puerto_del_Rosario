<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__ventas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idprestacion')->unsigned();
            $table->bigInteger('iddescuento')->unsigned()->nullable();
            $table->decimal('monto_a_cancelar');
            $table->bigInteger('idventamaestro')->unsigned()->nullable();
            $table->tinyInteger('estado')->default(0)->comment('0->en proceso,1->venta correcta,2->venta cancelada eliminada');
            $table->smallInteger('idsucursal')->unsigned();
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            
            $table->timestamps();
            $table->foreign('idprestacion')->references('id')->on('ser__prestacions');
            //$table->foreign('idprestacion')->references('id')->on('prestacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ser__ventas');
    }
}
