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
        Schema::create('prod__listas', function (Blueprint $table) {
            $table->id();
          
            $table->string("nombre_lista")->comment("Nombre de la lista a crear");
            $table->string("codigo")->comment("codigo de la lista a crear");
            $table->string("codigo_tda_alm")->comment("codigo para reconocer si es un almacen o tienda");
            $table->string("id_tda_alm")->comment("id de tienda o almacen");
            
            $table->boolean('activo')->default(1);
            $table->tinyInteger('estado')->default(1)->comment('1->activo, 0->inactivo');
            $table->smallInteger('correlativo')->unsigned();
            $table->smallInteger('id_usuario')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');
            $table->timestamps();
            $table->smallInteger('id_sucursal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod__listas');
    }
};
