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
        Schema::create('inv__gestion_inventario_periodo_detalle_tres', function (Blueprint $table) {
        $table->id();  
        $table->bigInteger('id_gestion_inv_periodo_dos');
            $table->bigInteger('id_ingreso');
            $table->bigInteger('id_producto');
            $table->string('envase');
            $table->smallInteger('cantidad_sis_detalle_inventario');
            $table->smallInteger('cantidad_reg_detalle_inventario');
            $table->smallInteger('diferencia_detalle_inventario');
            $table->string('estado');  
            $table->string('lote')->nullable();
            $table->string('fecha_v')->nullable();  
            $table->text('observacion')->nullable();
            $table->tinyInteger('bloqueado')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv__gestion_inventario_periodo_detalle_tres');
    }
};
