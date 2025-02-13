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
            $table->tinyInteger('frecuencia')->comment('dia=1, semanal=2, mes=3, bimestral=4, trimestral=5');
            $table->integer('intentos');
            $table->integer('intervalo_min');
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
