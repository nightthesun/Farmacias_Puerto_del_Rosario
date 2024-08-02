<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dos__dosificacion', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->string('nro_autorizacion');
            $table->string('identificacion');
            $table->string('dosificacion');
            $table->string('fecha_a',25);
            $table->string('fecha_e',25);
            $table->integer('n_ini_facturacion');
            $table->integer('n_fin_facturacion');
            $table->integer('n_act_facturacion');
            $table->string('nit');
            $table->tinyInteger('estado')->default(0)->comment('1->activo, 0->inactivo 2->expirado');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');        
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dos__dosificacion');
    }
};
