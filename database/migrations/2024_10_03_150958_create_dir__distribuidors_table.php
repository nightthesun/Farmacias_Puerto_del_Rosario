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
        Schema::create('dir__distribuidors', function (Blueprint $table) {
            $table->id();    
            $table->string('contacto')->default("N/S");
            $table->smallInteger('id_cliente');
            $table->string('id_linea_array');
            $table->string('nom_linea_array');            
            $table->tinyInteger('tipo_persona_empresa')->nullable();
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->tinyInteger('estado')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dir__distribuidors');
    }
};
