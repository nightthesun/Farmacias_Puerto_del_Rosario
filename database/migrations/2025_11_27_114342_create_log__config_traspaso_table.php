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
        Schema::create('log__config_traspaso', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('indice')->default(1);
            $table->tinyInteger('activo_traspaso')->default(0)->comment('0 = inactivo, 1 = activo');   
            $table->tinyInteger('activo_traslado')->default(0)->comment('0 = inactivo, 1 = activo');   
            $table->tinyInteger('activo_recepcion')->default(0)->comment('0 = inactivo, 1 = activo');
            $table->smallInteger('dias_acumulados')->default(30);
            $table->string('glosa_traspaso',255)->default('[Automatico]')->nullable();
            $table->tinyInteger('estado_traspaso')->default(0)->comment('0=pendiente, 1=listo'); 
            $table->string('id_users_traslado')->nullable();
            $table->string('id_vehiculo_traslado')->nullable();
            $table->time('ini_traslado')->default('00:00:00');
            $table->time('fin_traslado')->default('00:00:00');
            $table->smallInteger('max_items_traslado')->default(0);
            $table->string('observacion_traslado',255)->default('[Automatico]')->nullable();
            $table->string('observacion_recepcion',255)->default('[Automatico]')->nullable();   
            $table->integer('limiteTraspaso_1')->default(1);
            $table->integer('limiteTraspaso_2')->default(2);
            $table->integer('limiteTraslado_1')->default(1);
            $table->integer('limiteTraslado_2')->default(2);
            $table->integer('limiteRecepcion_1')->default(1);
            $table->integer('limiteRecepcion_2')->default(2); 
            $table->decimal('valor_Z', 10, 2)->default(0.01);                      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__config_traspaso');
    }
};
