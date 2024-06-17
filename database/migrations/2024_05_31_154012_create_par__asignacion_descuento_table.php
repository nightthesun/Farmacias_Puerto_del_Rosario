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
        Schema::create('par__asignacion_descuento', function (Blueprint $table) {
            $table->smallInteger('id_descuento');
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_alm_tda');
            $table->string('cod')->nullable();
            $table->tinyInteger('personalizado')->nullable()->default(0);
            $table->tinyInteger('cliente')->nullable()->default(0);
            $table->tinyInteger('producto')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__asignacion_descuento');
    }
};
