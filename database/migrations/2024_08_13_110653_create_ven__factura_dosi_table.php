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
        Schema::create('ven__factura_dosi', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_venta');
            $table->smallInteger('id_dosificacion');
            $table->integer('numero_factura');
            $table->decimal('total',11,2);
            $table->string('codigo_control');
            $table->tinyInteger('estado_factura')->default(1)->comment('0=anulado, 1=activo');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__factura_dosi');
    }
};
