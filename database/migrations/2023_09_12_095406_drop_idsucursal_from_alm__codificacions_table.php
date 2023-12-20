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
        Schema::table('alm__codificacions', function (Blueprint $table) {
            $table->dropForeign(['idsucursal']);
            $table->dropColumn('idsucursal');
            $table->unsignedBigInteger('idalmacen')->after('id')->nullable()->comment("Identificador unico que relaciona la tabla codificacion y almacen");
            $table->foreign('idalmacen')->references('id')->on('alm__almacens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alm__codificacions', function (Blueprint $table) {
            $table->dropForeign(['idalmacen']);
            $table->dropColumn('idalmacen');
            $table->smallInteger('idsucursal')->after('id')->nullable()->unsigned();
            $table->foreign('idsucursal')->references('id')->on('adm__sucursals');
        });
    }
};
