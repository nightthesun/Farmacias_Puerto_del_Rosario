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
        Schema::create('par__personalizado', function (Blueprint $table) {
            $table->id();
            $table->decimal('max', 8, 2)->unsigned()->nullable()->comment('valor maximo');
            $table->integer('min')->default(0);        
            $table->smallInteger('id_descuento');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__personalizado');
    }
};
