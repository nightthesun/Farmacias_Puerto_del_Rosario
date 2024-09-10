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
        Schema::create('adm__credecial_correos', function (Blueprint $table) {
            $table->id();
    $table->string('host');
    $table->string('correo');
    $table->integer('puerto');
    $table->string('usuario');
    $table->string('contraseña'); // Cambiado de password_hash a string
    $table->tinyInteger('ssl');
    $table->timestamps();
    $table->string('nit')->nullable();
    $table->string('nro_celular',35)->nullable();
    $table->string('nom_empresa',150)->nullable();
    $table->tinyInteger('factura_dosificacion')->nullable()->comment("1=factura 2=dosificacion");    
    $table->smallInteger('id_dosificacion_siat')->nullable()->comment("lleva la ide de modulo de dosificacio o siat");
    $table->string('actividad_economica',200)->nullable();
    $table->integer('moneda')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__credecial_correos');
    }
};
