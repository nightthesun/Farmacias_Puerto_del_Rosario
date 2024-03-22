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
        
        Schema::create('log__asignacion_sucursal_vehiculos', function (Blueprint $table) {
            $table->smallInteger('id_veiculo');
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_alm_tda');
            $table->string('cod')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__asignacion_sucursal_vehiculos');
    }
};
