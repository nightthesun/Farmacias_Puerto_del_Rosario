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
        Schema::create('log__distribuidor_auto', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_linea');
            $table->smallInteger('id_distribuidor');
            $table->tinyInteger('forma_pago')->comment('1=cheque,2=contado,credito=3,trasferencia=4');
            $table->bigInteger('intervalo_pago');
            $table->string('Plazo_pago');
            $table->tinyInteger('entrega_pedido')->comment('1=mañana,2=tarde');
            $table->string('observacion')->nullable();
            $table->integer('ciclo_2')->default(0);
            $table->smallInteger('lim_inferior')->default(0);
            $table->smallInteger('lim_superior')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__distribuidor_auto');
    }
};
