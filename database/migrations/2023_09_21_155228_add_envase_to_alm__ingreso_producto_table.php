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
            $table->string('envase',50)
                  ->after('id_prod_producto')
                  ->nullable()
                  ->comment('Esta columna es para indentificar con que envase se registro un producto en la tabla alm__ingreso_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__ingreso_producto', function (Blueprint $table) {
            $table->dropColumn('envase');
        });
    }
};
