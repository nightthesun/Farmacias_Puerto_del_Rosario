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
        Schema::create('par__producto_desc', function (Blueprint $table) {
            $table->id();
            $table->smallInteger("id_prod");
            $table->string("cod_prod");
            $table->string("envase");
            $table->string("tipo_tda_alm");
            $table->smallInteger("id_linea");  
            $table->smallInteger('id_descuento');   
            $table->string("leyenda");
                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__producto_desc');
    }
};
