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
        Schema::create('ven__factura_siat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_venta');        
            $table->integer('id_cufd');
            $table->integer('id_cuis');
            $table->text('cuf');
            $table->smallInteger('sucursal_siat'); 
            $table->smallInteger('id_emisor'); 
            $table->integer('numero_factura');
            $table->decimal('total', 11, 2);
            $table->text('codigo_control')->comment('del codigo cufd');
            $table->text('codigo_Recepcion')->comment('lo dal en recepcion si todoe sta bien lo da impuestos');
            $table->smallInteger('id_tipo_pago'); 
            $table->tinyInteger('estado_factura')->default(0)->comment('0=activo, 1=anulado');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__factura_siat');
    }
};
