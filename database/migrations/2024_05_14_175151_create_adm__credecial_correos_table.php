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
        Schema::create('adm__credecial_correos', function (Blueprint $table) {
            $table->id();
    $table->string('host');
    $table->string('correo');
    $table->integer('puerto');
    $table->string('usuario');
    $table->string('contraseña'); // Cambiado de password_hash a string
    $table->tinyInteger('ssl');
    $table->timestamps();
    $table->string('nit')->nullable();
    $table->string('nro_celular',35)->nullable();
    $table->string('nom_empresa',150)->nullable();
    $table->tinyInteger('factura_dosificacion')->nullable()->comment('1=factura 2=dosificacion');    
    $table->smallInteger('id_dosificacion_siat')->nullable()->comment('lleva la ide de modulo de dosificacio o siat');
    $table->string('actividad_economica',200)->nullable();
    $table->integer('moneda')->nullable();
    $table->integer('tiempo_limite')->default(0)->nullable();
    $table->decimal('monto_limite',11,2)->default(0)->nullable();
    $table->tinyInteger('modal_apertura')->default(0)->nullable()->comment('0=no tiene, 1=normal,  2=modal modificado');
    $table->tinyInteger('imprimir_trans')->default(0)->nullable()->comment('1=imprime el qr desde apertura o cierre');
    $table->string('alias', 255)->nullable();
    $table->tinyInteger('uso_alias')->default(0)->nullable()->comment('0=no tiene, 1=nombre empresa, 2=alias');
    $table->smallInteger('stock_medio')->default(0)->nullable()->comment('0= defaul, 1=stock normal, 2=stock autmatico,3>etc');  
    $table->tinyInteger('efecto_cobrante')->default(1)->nullable()->comment('0=no tiene, 1=por defecto, 2=con sobrante');
    $table->tinyInteger('qr_in_uso')->default(0)->nullable()->comment('0=desactivado, 1=activado,');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__credecial_correos');
    }
};
