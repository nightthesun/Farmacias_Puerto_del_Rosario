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
        Schema::create('prod__registro_pre_x_lists', function (Blueprint $table) {
            $table->id();
            $table->string('envase')->comment('guarda el tipo de envase');
            $table->smallInteger('id_producto')->comment('guarda el id de producto');
            $table->smallInteger('id_lista')->comment('id de lista');
            $table->smallInteger('id_tda_alm')->comment('contiene el id si es alamcen o tienda');
            $table->string('cod_tda_alm')->comment('guarda el tipo de codigo si es tda o alm');
            $table->string('tipo_tda_alm')->comment('guarda el tipo si es una tienda o almacen');
            $table->decimal('preciolista')->comment('Precio mas alto posible que un comprador pagara por un producto específico antes de cualquier descuento');
            $table->decimal('precioventa')->comment('Precio mas alto posible que un comprador pagara por un producto');
            $table->bigInteger('tiempopedido')->comment('Tiempo o ciclo de pedido de un producto 1,2,3,6,12');
            $table->string('metodoabc')->comment('Importancia de un producto en las zonas A, B y C segun la ley de pareto');
            $table->boolean('activo')->default(1);
            $table->tinyInteger('estado')->default(1)->comment('1->activo, 0->inactivo');
            $table->smallInteger('id_usuario')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que esta modificando el almacen');
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que esta registrando el almacen');         
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod__registro_pre_x_lists');
    }
};
