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
        Schema::create('adm__cuenta_banco', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_banco');
            $table->string('nombre');
            $table->string('nro_cuenta');
            $table->string('titular');
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__cuenta_banco');
    }
};
