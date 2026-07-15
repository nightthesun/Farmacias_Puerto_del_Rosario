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
        Schema::create('siat__sincronizacions', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion',200)->comment('nombre de la accion');
            $table->tinyInteger('estado')->comment('0 deactivado 1 activado');           
            $table->tinyInteger('prioridad')->comment('1 primero , 2 segundo , 3 tercero');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__sincronizacions');
    }
};
