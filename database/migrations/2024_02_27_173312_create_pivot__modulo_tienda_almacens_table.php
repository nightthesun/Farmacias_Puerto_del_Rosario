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
        Schema::create('pivot__modulo_tienda_almacens', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_tienda_almacen')->comment('alamcen el codigo del alamcen o tienda');
            $table->smallInteger('id_ingreso')->comment('guarda el id ingreso de alamcen o tienda');
            $table->string('tipo',10)->comment('tipo si es tienda o almacen');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot__modulo_tienda_almacens');
    }
};
