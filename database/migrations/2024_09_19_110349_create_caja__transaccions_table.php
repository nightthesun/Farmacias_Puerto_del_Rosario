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
        Schema::create('caja__transaccions', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_cuenta');
            $table->string('Comprobante',90);
            $table->string('id_salida',300)->comment('separados por comas las salidas');
            $table->decimal('monto_total',11,2);
            $table->string('observacion')->nullable();
            $table->smallInteger('id_usuario_registra')->nullable();
            $table->smallInteger('id_usuario_modifica')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->tinyInteger('tipo_deposito')->nullable()->comment('1=persona, 2=banco');              
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__transaccions');
    }
};
