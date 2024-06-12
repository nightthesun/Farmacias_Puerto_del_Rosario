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
        Schema::create('dir__clientes', function (Blueprint $table) {
            $table->id();
           
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->smallInteger('id_tipo_doc');
            $table->string('nom_a_facturar');
            $table->string('pais')->nullable();
            $table->string('ciudad')->nullable();            
            $table->smallInteger('id_user')->unsigned()->nullable()->comment('usuario general ');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');
            $table->timestamps();
            $table->smallInteger('id_per_emp');
            $table->string('num_documento');
            $table->tinyInteger('tipo_per_emp')->comment('si es persona tiene el valor 0, si es empresa valor 1');
            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('casual')->default(0)->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dir__clientes');
    }
};
