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
        Schema::create('auto__sincronizacion', function (Blueprint $table) {
            $table->id();
            $table->time('hora'); // Guarda solo la hora (HH:MM:SS)
            $table->tinyInteger('frecuencia')->comment('en dias');
            $table->integer('intentos');
            $table->integer('intervalo_min');
            $table->tinyInteger('activo')->nullable()->default(0)->comment('1=automatico 0=manual'); // Campo para activar/desactivar
            $table->string('fecha_ini')->nullable();
            $table->string('fecha_siguiente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto__sincronizacion');
    }
};
