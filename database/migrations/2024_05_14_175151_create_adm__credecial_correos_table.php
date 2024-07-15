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
