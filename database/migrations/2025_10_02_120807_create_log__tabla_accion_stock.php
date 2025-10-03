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
        Schema::create('log__tabla_accion_stock', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_user');
            $table->smallInteger('id_sucursal');           
            $table->tinyInteger('tipo_tabla')->comment('1->tabla 1,2->tabla2, etc..');
            $table->date('fecha');
            $table->time('hora');
            $table->tinyInteger('accion')->comment('1->modulo configuracion manual,2=otros 3....., 0=cierre de caja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__tabla_accion_stock');
    }
};
