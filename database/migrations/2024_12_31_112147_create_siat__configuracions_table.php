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
        Schema::create('siat__configuracions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_sis', 200);
            $table->tinyInteger('tipo_ambiente');
            $table->string('formato_fecha');
            $table->smallInteger('max_paquete'); // Cambié el tipo aquí
            $table->text('token_delegado');
            $table->string('url_QR', 255);
            $table->string('vencimiento_token')->nullable();
            $table->integer('tiempo_espera');
            $table->tinyInteger('tipo_modalidad');
            $table->tinyInteger('tipo_certificado');
            $table->string('name')->nullable(); // Nombre del archivo original
            $table->string('path')->nullable(); // Ruta del archivo almacenado
            $table->string('extension', 10)->nullable(); // Extensión del archivo
            $table->string('password')->nullable();
            $table->text('llave_privada')->nullable();
            $table->text('certificado_x509')->nullable();
            $table->timestamps();
            $table->tinyInteger('id_sincro_auto')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__configuracions');
    }
};
