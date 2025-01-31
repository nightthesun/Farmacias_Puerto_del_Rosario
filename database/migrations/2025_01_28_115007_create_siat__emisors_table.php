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
        Schema::create('siat__emisors', function (Blueprint $table) {
            $table->id();               
            $table->string('nombre');       
            $table->string('descripcion');                  
            $table->smallInteger('id_siat_sucursal');      
            $table->smallInteger('id_caja')->nullable()->comment('id de caja del sistema');                       
            $table->smallInteger('tipo')->comment('el tipo me punto de venta');                 
            $table->smallInteger('id_punto_venta')->comment('lo da impuestos este dato');
            $table->tinyInteger('estado')->default(1);                                      
            $table->smallInteger('id_usuario_registra')->nullable();    
            $table->smallInteger('id_usuario_modifica')->nullable();    
            $table->bigInteger('id_cuis')->nullable();                  
            $table->bigInteger('id_cufd')->nullable();        
            $table->timestamps();                       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__emisors');
    }
};
