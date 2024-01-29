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
        
        Schema::create('log__traslados', function (Blueprint $table) {
             // Cambiados a unsignedBigInteger si se necesita más rango
             $table->id();
             $table->smallInteger('id_traspaso');
             $table->smallInteger('id_empleado');
             $table->smallInteger('id_vehiculo');
            $table->timestamp('tiempo');
            $table->string('observacion')->nullable()->comment('si se encotro alguna observacion');
            $table->boolean('activo')->default(1);
            $table->tinyInteger('estado')->default(1)->comment('1->activo, 0->inactivo');
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
        Schema::dropIfExists('log__traslados');
    }
};
