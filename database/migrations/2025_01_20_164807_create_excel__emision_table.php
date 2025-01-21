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
            
            $table->string('descripcion'); 
            $table->smallInteger('codigo');
            $table->smallInteger('id_catalogo');     
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
