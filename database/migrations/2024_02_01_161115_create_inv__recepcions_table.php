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
        Schema::create('inv__recepcions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_traslado')->comment('id de traslado');
            $table->string('observacion');
         
            $table->boolean('activo')->default(1);            
            $table->smallInteger('id_user')->unsigned()->nullable()->comment('usuario general ');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');
            $table->timestamps();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__recepcions');
    }
};
