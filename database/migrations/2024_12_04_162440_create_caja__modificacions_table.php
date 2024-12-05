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
        Schema::create('caja__modificacions', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_cierre');
            $table->decimal('monto_dif',20,2)->nullable();
            $table->string('estado',90)->nullable();
            $table->string('motivo')->nullable();   
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
        Schema::dropIfExists('caja__modificacions');
    }
};
