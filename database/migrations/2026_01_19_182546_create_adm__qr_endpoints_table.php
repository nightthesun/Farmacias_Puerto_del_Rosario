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
        Schema::create('adm__qr_endpoints', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_qr_simple');
            $table->text('descripcion');
            $table->text('url');
            $table->string('version');
            $table->tinyInteger('tipo')->comment('1= produccion 2 =para pruebas');  
            $table->integer('id_servicio')->nullable()->comment('1=Generar Token,2=Actualizar Credencial,3=Generar QR,4=Obtener lista de QRs generados,5=Obtener estado QR,6=Cancelar QR');          
           $table->tinyInteger('activar')->nullable()->default(0)->comment('0 desactivado, 1 activado');             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__qr_endpoints');
    }
};
