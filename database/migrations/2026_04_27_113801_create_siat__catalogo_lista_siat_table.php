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
        Schema::create('siat__catalogo_lista_siat', function (Blueprint $table) {
            $table->id();
           $table->smallInteger('id_catalogo')->unsigned();
           $table->smallInteger('codigo')->unsigned();
           $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__catalogo_lista_siat');
    }
};
