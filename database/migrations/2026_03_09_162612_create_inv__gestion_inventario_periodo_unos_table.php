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
        Schema::create('inv__gestion_inventario_periodo_unos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',255);
            $table->string('motivo',255);
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_tienda');
            $table->smallInteger('id_almacen');
            $table->tinyInteger('estado')->default(1);
            $table->tinyInteger('enproceso')->default(0)->comment('0 sin uso ,1 proceso iniciado,2 procesos en espera,3 terminado');            
            $table->string('tipo_inventario',20)->comment('1= Diario');
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_tabla_dos_momentanio')->nullable();
            $table->smallInteger('id_bloqueo')->nullable();
             $table->tinyInteger('enviado')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__gestion_inventario_periodo_unos');
    }
};
