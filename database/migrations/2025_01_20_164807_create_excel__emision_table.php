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
        Schema::create('excel__emision', function (Blueprint $table) {            
            $table->text('descripcion'); 
            $table->integer('codigo'); 
            $table->integer('id_catalogo'); // Cambiado de MEDIUMINT a INTEGER
            $table->bigInteger('id_erp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel__emision');
    }
};
