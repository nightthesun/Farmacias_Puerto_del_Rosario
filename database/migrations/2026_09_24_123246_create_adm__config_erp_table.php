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
        Schema::create('adm__config_erp', function (Blueprint $table) {
                $table->id();    
    $table->timestamps();
    $table->string('nit')->nullable();
    $table->string('nro_celular',35)->nullable();
    $table->string('nom_empresa',150)->nullable();
    $table->tinyInteger('factura_dosificacion')->nullable()->comment('1=factura 2=dosificacion');    
    $table->smallInteger('id_dosificacion_siat')->nullable()->comment('lleva la ide de modulo de dosificacio o siat');
    $table->text('actividad_economica')->nullable();
    $table->integer('moneda')->nullable();
    $table->integer('tiempo_limite')->default(0)->nullable();
    $table->decimal('monto_limite',11,2)->default(0)->nullable();
    $table->tinyInteger('modal_apertura')->default(0)->nullable()->comment('0=no tiene, 1=normal,  2=modal modificado');
    $table->tinyInteger('imprimir_trans')->default(0)->nullable()->comment('1=imprime el qr desde apertura o cierre');
    $table->string('alias', 255)->nullable();
    $table->tinyInteger('uso_alias')->default(0)->nullable()->comment('0=no tiene, 1=nombre empresa, 2=alias');
    $table->smallInteger('stock_medio')->default(0)->nullable()->comment('0= defaul, 1=stock normal, 2=stock autmatico,3>etc');  
    $table->tinyInteger('efecto_sobrante')->default(1)->nullable()->comment('0=no tiene, 1=por defecto, 2=con sobrante');
    $table->tinyInteger('qr_in_uso')->default(0)->nullable()->comment('0=desactivado, 1=activado,');
    $table->tinyInteger('tipo_caja')->default(1)->nullable()->comment('0=no tiene, 1=normal, 2=modificado');
    $table->tinyInteger('use_actividad_normal')->default(1)->nullable()->comment(' 1 en uso 0, sin uso  pero cuandoe sta cero usa actividad registrada en siat');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__config_erp');
    }
};
