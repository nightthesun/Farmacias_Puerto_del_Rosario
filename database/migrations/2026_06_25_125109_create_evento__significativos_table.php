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
        Schema::create('evento__significativos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('codigo_evento');
            $table->string('cod_recep_even',255);
            $table->text('descrip');
            $table->string('fechaI',160)->nullable();
            $table->string('fechaF',160)->nullable();
            $table->tinyInteger('estado')->default(1); 
            $table->smallInteger('cod_sucursal_siat');
            $table->smallInteger('cod_punto_venta_siat');
            $table->string('cafc',255)->nullable(); 
            $table->tinyInteger('ambiente')->default(0); 
           $table->smallInteger('cod_sector')->default(0); 
           $table->tinyInteger('cod_contigencia')->default(0)->comment('estos era mayor mente valor 2 fuera de linea');
            $table->tinyInteger('tipo_factura')->default(0)->comment('estos era mayor mente valor 1 factura cond erecho a credito fiscal');
            $table->tinyInteger('modalidad')->default(0);                                     
             $table->bigInteger('id_cuis');
              $table->bigInteger('id_cufd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento__significativos');
    }
};
