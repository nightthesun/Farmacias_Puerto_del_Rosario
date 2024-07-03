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
        Schema::create('ven__detalle_descuentos', function (Blueprint $table) {
      
            $table->smallInteger('id_venta');
            $table->smallInteger('id_tabla');
            $table->smallInteger('id_descuento');            
            $table->decimal('cantidad_descuento', 11, 2);   
            $table->string('tipo_num_des',20);        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__detalle_descuentos');
    }
};
