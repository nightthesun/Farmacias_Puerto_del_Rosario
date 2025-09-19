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
        Schema::create('sis__bitacora_stock_v2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_producto');
            $table->integer('stock');
            $table->integer('anterior');
            $table->integer('suma');
            $table->mediumInteger('contador');
            $table->string('fecha_ingreso');        
            $table->smallInteger('id_sucursal')->nullable();
            $table->string('envase')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sis__bitacora_stock_v2');
    }
};
