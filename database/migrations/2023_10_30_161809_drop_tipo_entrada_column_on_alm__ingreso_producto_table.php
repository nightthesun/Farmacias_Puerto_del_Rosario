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
            $table->dropColumn('tipo_entrada');
            $table->unsignedBigInteger('id_tipoentrada')->after('id_prod_producto')->default(3)->comment("Identificador unico, que relaciona la tabla de alm__ingreso_producto y la tabla prod__tipo_entradas");
            $table->foreign('id_tipoentrada')->references('id')->on('prod__tipo_entradas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__ingreso_producto', function (Blueprint $table) {
            $table->dropForeign(['id_tipoentrada']);
            $table->dropColumn('id_tipoentrada');
            $table->string('tipo_entrada')->default('Compra')->comment('Es la manera en que el producto esta ingresando, ya sea por compra, cambio, devolucion, donacion, etc');
        });
    }
};
