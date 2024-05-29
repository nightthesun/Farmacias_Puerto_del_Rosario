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
        Schema::create('par__cantidad_precio', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('es_cantidad_es_monto')->nullable()->comment('1 = cantidad 2 = monto');
            $table->string('regla')->nullable()->comment('1 es menor ,2 es mayor,3 igual');
            $table->integer('cantidad_valor')->nullable();
            $table->smallInteger('id_descuento');
          
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__cantidad_precio');
    }
};
