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
        Schema::create('caja__cierre', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_apertura');
            $table->smallInteger('id_arqueo');
            $table->decimal('total_venta_caja',16,2);            
            $table->decimal('total_ingreso_caja',16,2);
            $table->decimal('total_salida_caja',16,2);
            $table->decimal('total_caja',16,2);
            $table->decimal('total_arqueo_caja',16,2);
            $table->decimal('diferencia_caja',16,2);
            $table->string('estado_caja',110);
            $table->timestamps();
            $table->smallInteger('id_arqueo_mod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__cierre');
    }
};
