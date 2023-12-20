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
        Schema::table('alm__almacens', function (Blueprint $table) {
            
            $table->dropColumn("razon_social");
            $table->renameColumn("nombre_comercial","nombre_almacen");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__almacens', function (Blueprint $table) {
            
            $table->string("razon_social")->nullable()->default('**')->comment("Nombre del almacen");
            $table->renameColumn("nombre_almacen","nombre_comercial");
        });
    }
};
