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
        Schema::create('log__sincro_ges_stock', function (Blueprint $table) {
           $table->id();
            $table->time('hora')->default('00:00:00'); // Guarda solo la hora (HH:MM:SS)
            $table->tinyInteger('frecuencia')->default(1)->comment('en días');
            $table->tinyInteger('activo')->default(0)->comment('0 = inactivo, 1 = activo');
            $table->string('id_sucursales')->nullable();
            $table->tinyInteger('error')->default(0)->comment('0= sin errores, 1= con errores');
            $table->string('informe',255)->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__sincro_ges_stock');
    }
};
