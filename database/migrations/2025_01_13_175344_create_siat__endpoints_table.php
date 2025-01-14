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
        Schema::create('siat__endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('Descripcion');
            $table->text('Url');
            $table->string('Version');
            $table->tinyInteger('tipo')->comment('1= produccion 2 =para pruebas');
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__endpoints');
    }
};
