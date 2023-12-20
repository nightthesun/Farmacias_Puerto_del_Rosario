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
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->boolean('listo_venta')
                  ->default(false)
                  ->after('idusuario')
                  ->comment("Columna que hace referencia a que si un prodicto esta listo para la venta, esto significa que se llenaron los datos en la tabla ges_pre__ventas");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->dropColumn('listo_venta');
        });
    }
};
