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
        Schema::create('sis_bitacora_stock', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_producto');
            $table->integer('stock');
            $table->string('fecha_ingreso');        
            $table->smallInteger('id_sucursal')->nullable();
            $table->string('envase')->nullable();
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis_bitacora_stock');
    }
};
