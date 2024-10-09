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
        Schema::create('egr__inversions', function (Blueprint $table) {
            $table->id();           
            $table->smallInteger('id_distribuidor');          
            $table->tinyInteger('tipo_persona_empresa')->nullable();
            $table->tinyInteger('tipo_comprabante')->comment('1= recibo, 2 = factura');
            $table->bigInteger('nro_comprobante');
            $table->decimal('total',11,2);
            $table->tinyText('simbolo')->nullable();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
            $table->string('descripcion');
            $table->smallInteger('id_apertura')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egr__inversions');
    }
};
