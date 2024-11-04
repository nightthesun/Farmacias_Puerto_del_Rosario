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
        Schema::create('egr__tesorerias', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');    
            $table->smallInteger('id_arqueo_abrir');                       
            $table->decimal('total_arqueo_caja_abrir',11,2);        
            $table->decimal('monto_actual_abrir',11,2)->default(0);
            $table->string('comentario_abrir',100)->nullable();                                 
           
            $table->tinyInteger('abrir_cerrar')->default(0)->comment('0 = abrir , 9 = cerrar');   
            $table->smallInteger('id_arqueo_cerrar')->nullable();              
            $table->decimal('total_arqueo_caja_cerrar',11,2)->nullable();
            $table->string('comentario_cerrar',100)->nullable();            
       
            $table->timestamps();          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egr__tesorerias');
    }
};
