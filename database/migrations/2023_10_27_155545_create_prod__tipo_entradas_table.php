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
        Schema::create('prod__tipo_entradas', function (Blueprint $table) {
            $table->id()->comment('Identificador unico de registro');
            $table->string('nombre')->comment('Nombre del tipo de entrada');
            $table->boolean('activo')->default(1)->comment('Estado del registro, 1 -> activo, 0 ->inactivo');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el producto');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el producto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod__tipo_entradas');
    }
};
