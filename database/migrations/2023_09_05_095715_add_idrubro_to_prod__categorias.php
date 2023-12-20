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
        Schema::table('prod__categorias', function (Blueprint $table) {
            $table->unsignedSmallInteger('idrubro')
                  ->after('id')
                  ->default(1)
                  ->comment("Identificador unico que hace referencia a la tabla rubros y que relaciona la tabla categorias");
            $table->foreign('idrubro')->references('id')->on('adm__rubros'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prod__categorias', function (Blueprint $table) {
            $table->dropForeign(['idrubro']);
            $table->dropColumn('idrubro');
        });
    }
};
