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
        Schema::create('egr__gastos', function (Blueprint $table) {
            $table->id();                                      
            $table->smallInteger('id_proveedor');                              
            $table->tinyInteger('tipo_persona_empresa')->nullable();
            $table->tinyInteger('tipo_comprabante')->comment('1= recibo, 2 = factura');
            $table->smallInteger('nro_comprobante');
            $table->decimal('total',11,2);
            $table->tinyText('simbolo')->nullable();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
            $table->string('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egr__gastos');
    }
};
