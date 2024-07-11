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
        Schema::create('ven__recibos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_cliente');
            $table->smallInteger('id_usuario');
            $table->string('nro_comprobante_venta', 200);
            $table->decimal('total_venta', 11, 2);
            $table->decimal('efectivo_venta', 11, 2)->default(0.00);
            $table->decimal('cambio_venta', 11, 2)->default(0.00);
            $table->string('estado_venta')->default('ACEPTADO');
            $table->decimal('descuento_venta', 11, 2)->default(0.00);
            $table->tinyInteger('anulado')->default(0)->comment('1 esta anulado, 0 no anulado');           
            $table->timestamps();
            $table->decimal('total_sin_des',11,2);
            $table->tinyInteger('tipo_venta_reci_fac')->comment('1 para recibo 2 para factura');
            $table->integer('contador')->nullable();
            $table->string('cod',25)->nullable();
            $table->smallInteger('id_lista_v2')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__recibos');
    }
};
