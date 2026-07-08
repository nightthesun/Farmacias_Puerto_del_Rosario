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
            $table->string('codDescripcion',160)->nullable();              
            $table->smallInteger('codEstado')->nullable();
            $table->smallInteger('codSector')->nullable()->comment('1 FACTURA COMPRA-VENTA, codigoDocumentoSector'); 
            $table->tinyInteger('tipo_contigencia')->default(0)->nullable()->comment('0 cero sin accion 1 segun se selecciona');   
            $table->text('zip_factura')->nullable();  
            $table->tinyInteger('tipo_emision')->default(0)->nullable()->comment('1 en linea ,2 fuera de linea');
            $table->tinyInteger('modalidad')->default(0)->nullable()->comment('1 Electrónica en Línea, 2 Computarizada en Línea');  
            $table->smallInteger('tipoFacturaDoc')->default(0)->nullable()->comment('1 FACTURA CON DERECHO A CREDITO FISCAL , tipoFacturaDocumento');  
            $table->tinyInteger('ambiente')->default(0)->nullable()->comment('1 produccion , 2 piloto'); 
            $table->tinyInteger('enviado')->default(1)->nullable()->comment('1 enviado 0 no');  
                            
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
