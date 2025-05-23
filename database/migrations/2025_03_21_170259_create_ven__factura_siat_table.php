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
            $table->bigInteger('id_venta');        
            $table->bigInteger('id_cufd');
            $table->bigInteger('id_cuis');
            $table->text('cuf');
            $table->integer('id_credenciales'); 
            $table->integer('sucursal_siat'); 
            $table->integer('punto_venta');
            $table->text('direccion');
            $table->string('municipio',155);
            $table->bigInteger('numFactura');
            $table->string('fechaEmision',160)->nullable(); 
            $table->text('xml')->nullable();
            $table->integer('id_leyenda')->nullable();
            $table->tinyInteger('estado')->default(1)->nullable();
            $table->text('codRecepcion')->nullable();              
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
