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
        Schema::create('inv__gestion_inventario_periodo_linea_dos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_gestion_inv_periodo_uno');           
            $table->smallInteger('id_linea');  
            $table->string('turno'); 
            $table->date('fecha_ini');                   
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__gestion_inventario_periodo_linea_dos');
    }
};
