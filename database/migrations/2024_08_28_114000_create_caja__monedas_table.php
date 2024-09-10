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
        Schema::create('caja__monedas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_corte');
            $table->decimal('valor',11,2);
            $table->string('unidad',50);
            $table->smallInteger('id_nacionalidad_pais');
            $table->tinyInteger('activo')->default(1);
            $table->string('texto_unidad_entera',100)->nullable(); 
            $table->integer('unidad_entera')->nullable();
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__monedas');
    }
};
