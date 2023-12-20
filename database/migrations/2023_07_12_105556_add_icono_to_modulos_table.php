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
        Schema::table('adm__modulos', function (Blueprint $table) {
            $table->string('nombre_icono',50)
                  ->after("nombre")
                  ->default("icon-bag")
                  ->comment("Esta columna es para alamcenar el nombre del icono que se utilizara en cada opcion de modulo del sistema");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adm__modulos', function (Blueprint $table) {
            $table->dropColumn('nombre_icono');
        });
    }
};
