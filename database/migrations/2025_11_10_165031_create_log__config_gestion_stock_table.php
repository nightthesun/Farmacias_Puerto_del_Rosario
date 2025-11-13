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
        Schema::create('log__config_gestion_stock', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('indice')->default(1);
            $table->tinyInteger('tipo_sucursal')->comment('1=  todas la sucursales y 2 =  sucursal en espesifico');
            $table->string('id_sucursales')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__config_gestion_stock');
    }
};
