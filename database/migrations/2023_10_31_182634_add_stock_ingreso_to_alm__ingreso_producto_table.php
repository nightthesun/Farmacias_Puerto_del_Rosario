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
        Schema::table('alm__ingreso_producto', function (Blueprint $table) {
            $table->unsignedInteger('stock_ingreso')->default(0)->after('cantidad')->comment("Columna que controla la cantidad de stock de un producto ingresado a almacenes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__ingreso_producto', function (Blueprint $table) {
            $table->dropColumn('stock_ingreso');
        });
    }
};
