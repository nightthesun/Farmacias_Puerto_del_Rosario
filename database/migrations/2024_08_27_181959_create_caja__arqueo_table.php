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
        Schema::create('caja__arqueo', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_usuario');           
            $table->decimal('total_arqueo',11,2);         
            $table->decimal('cantidad_billete',11,2);
            $table->smallInteger('total_billete');  
            $table->decimal('cantidad_moneda',11,2);
            $table->smallInteger('total_moneda');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__arqueo');
    }
};
