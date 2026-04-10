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
        Schema::create('inv__gestion_inventario_bloqueo_sucursal', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_inventario');
            $table->smallInteger('id_linea');
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_usuario');
            $table->tinyInteger('activo')->default(1);
            $table->date('fecha_ini')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('observacion',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__gestion_inventario_bloqueo_sucursal');
    }
};
