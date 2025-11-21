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
        Schema::create('log__config_gestion_stock', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('indice')->default(1);
            $table->tinyInteger('tipo_sucursal')->default(0)->comment('1=  todas la sucursales y 2 =  sucursal en espesifico');
            $table->string('id_sucursales')->nullable();
            $table->time('hora')->default('00:00:00'); // Guarda solo la hora (HH:MM:SS)
            $table->tinyInteger('frecuencia')->default(1)->comment('en días');     
            $table->tinyInteger('activo_c_r_1')->default(0)->comment('0 = inactivo, 1 = activo');   
            $table->tinyInteger('activo_d_l_2')->default(0)->comment('0 = inactivo, 1 = activo');  
            $table->tinyInteger('activo_d_m_m_3')->default(0)->comment('0 = inactivo, 1 = activo');   
            $table->tinyInteger('activo_m_abc_4')->default(0)->comment('0 = inactivo, 1 = activo');  
            $table->tinyInteger('activo_canal_5')->default(0)->comment('0 = inactivo, 1 = activo');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__config_gestion_stock');
    }
};
