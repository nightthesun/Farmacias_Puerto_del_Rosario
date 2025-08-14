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
        Schema::create('inv__tabla_extra_gestion_stock', function (Blueprint $table) {
        
            $table->bigInteger('id_gestion_stock');
            $table->bigInteger('id_producto');
             $table->string('envase');            
            $table->smallInteger('cantidad_extra');
             $table->decimal('precio_extra',25,2);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__tabla_extra_gestion_stock');
    }
};
