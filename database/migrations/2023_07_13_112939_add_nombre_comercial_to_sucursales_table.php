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
        Schema::table('adm__sucursals', function (Blueprint $table) {
            $table->string('nombre_comercial')
            ->after("razon_social")
            ->default("Grupo Empresarial Puerto del Rosario")
            ->comment("Esta columna almacena en nombre comercial que tiene asignado cada sucursal");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adm__sucursals', function (Blueprint $table) {
            $table->dropColumn('nombre_comercial');
        });
    }
};
