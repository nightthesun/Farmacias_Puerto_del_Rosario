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
        Schema::create('adm_actividad_economicas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre')->comment('nombre del rubro segun nit');
            $table->string('descripcion')->nullable();
            $table->boolean('areamedica')->default(1)->comment('1->si es area media, 2-> no es area media, modifica detalles de medicamentos de algunos formularios');
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->bigInteger('codigo_activdad_siat')->default(0)->nullable();
            $table->tinyInteger('uso_unico')->default(0)->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm_actividad_economicas');
    }
};
