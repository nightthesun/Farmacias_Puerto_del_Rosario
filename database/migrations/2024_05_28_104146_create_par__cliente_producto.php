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
        Schema::create('par__cliente_producto', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_cliente_p')->nullable();           
            $table->string('num_documento')->nullable();   
            $table->smallInteger('id_descuento');   
            $table->string('nom_facturar'); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__cliente_producto');
    }
};
