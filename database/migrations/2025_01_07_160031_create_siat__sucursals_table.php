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
        Schema::create('siat__sucursals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_suc_siat');
            $table->integer('codigo_siat');
            $table->smallInteger('departamento');
            $table->string('id_sucursal');
            $table->tinyInteger('estado')->default(1);  
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->BIGINT('id_cuis')->nullable();
            $table->BIGINT('id_cufd')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__sucursals');
    }
};
