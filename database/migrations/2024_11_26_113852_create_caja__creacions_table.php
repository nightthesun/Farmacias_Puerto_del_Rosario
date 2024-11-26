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
        Schema::create('caja__creacions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre_caja');
            $table->smallInteger('id_sucursal');
            $table->string('id_users');
            $table->decimal('monto_caja',20,2)->nullable();
            $table->tinyText('moneda')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__creacions');
    }
};
