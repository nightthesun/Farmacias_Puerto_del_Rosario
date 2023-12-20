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
            $table->dropColumn('codigo_internacional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__ingreso_producto', function (Blueprint $table) {
            $table->string('codigo_internacional')->comment('Codigo internacional o codigo de barras que tiene los productos');
        });
    }
};
