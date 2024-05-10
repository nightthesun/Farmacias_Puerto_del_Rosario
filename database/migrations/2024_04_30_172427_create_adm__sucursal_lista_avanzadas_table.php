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
        Schema::create('adm__sucursal_lista_avanzadas', function (Blueprint $table) {
            $table->smallInteger('id_sucursal');
            $table->tinyInteger('avanzado');
            $table->smallInteger('id_lista');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__sucursal_lista_avanzadas');
    }
};
