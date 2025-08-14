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
        Schema::create('inv__gestion_stocks', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_usuario');
            $table->smallInteger('id_distribuidor');
            $table->decimal('total_programacion',25,2)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->tinyInteger('forma_pago')->nullable()->comment('1:CHEQUE,2:CONTADO,3:CREDITO,4:TRASFERENCIA BANCARIA');
            $table->tinyInteger('turno_pedido')->nullable()->comment('1:mañana,2:tarde');
            $table->smallInteger('plazo_pedido')->nullable();
            $table->text('observacion')->nullable();           
            $table->tinyInteger('tipo')->nullable()->comment('1:minimi,2:naranja');
            $table->string('simbolo',30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__gestion_stocks');
    }
};
