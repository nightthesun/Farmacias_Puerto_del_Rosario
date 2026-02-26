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
        Schema::create('inv_gestion_inventario_periodos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_tienda');
            $table->smallInteger('id_almacen');
            $table->smallInteger('id_linea');
            $table->string('estado');
            $table->string('tipo_inventario',20)->comment('1= Diario = D , 7=Semanal = S, 30=Mensual = M, 90=Trimestral = T');
            $table->string('turno'); 
            $table->smallInteger('id_usuario')->nullable();                    
            $table->timestamps();
        });
    }
  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_gestion_inventario_periodos');
    }
};
