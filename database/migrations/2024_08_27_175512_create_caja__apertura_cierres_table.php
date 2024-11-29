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
        Schema::create('caja__apertura_cierres', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_arqueo');
            $table->tinyInteger('turno_caja')->comment('1=MAÑANA (TURNO UNO), 2=TARDE (TURNO DOS) 3=TODO EL DIA (COMPLETO)');
            $table->tinyInteger('tipo_caja_c_a')->comment('0 = APERTURA , 9 = CIERRE');
           // $table->decimal('total_venta_caja',11,2);
            
          //  $table->decimal('total_ingreso_caja',11,2);
          //  $table->decimal('total_salida_caja',11,2);
            $table->decimal('total_caja',11,2);
            $table->decimal('total_arqueo_caja',11,2);
            $table->decimal('diferencia_caja',11,2);
            $table->string('estado_caja',110);
            $table->timestamps();
            $table->smallInteger('id_cierre')->default(0)->comment('valor 0 es para indicar cuando se tiene apertura abierta');
            $table->smallInteger('id_caja')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__apertura_cierres');
    }
};
