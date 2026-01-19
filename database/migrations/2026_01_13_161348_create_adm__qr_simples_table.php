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
        Schema::create('adm__qr_simples', function (Blueprint $table) {
            $table->id();
            $table->string('nom_servicio_qr',200);          
            $table->tinyInteger('tipo_qr');
            $table->string('usuario_qr',200);
            $table->text('contraseña_qr');
            $table->text('token_qr')->nullable();
            $table->text('url_banco_servicio')->nullable();
            $table->tinyInteger('activo')->default(1);
            $table->string('usuario')->nullable();
            $table->tinyInteger('prioridad')->default(0)->comment('solo puede estar uno activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__qr_simples');
    }
};
