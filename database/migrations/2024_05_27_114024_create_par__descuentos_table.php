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
        Schema::create('par__descuentos', function (Blueprint $table) {
            $table->id();       
            $table->string('id_tipo_tabla');        
            $table->string('tipo_tabla');          
            $table->string('nombre_descuento');
            $table->string('descripcion')->nullable()->comment('debe describir como sera el descuento');          
            $table->tinyInteger('desc_num')->comment('0 es igual porcentaje, y 1 es igual valor numerico');
            $table->decimal('monto_descuento',8, 2)->unsigned();                       
            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('estado')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('par__descuentos');
    }
};
