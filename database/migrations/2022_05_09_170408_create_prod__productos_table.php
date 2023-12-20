<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod__productos', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('idlinea')->unsigned();
            // $table->string('codigo',15);
            // $table->integer('correlativo')->unsigned();
            // $table->string('nombre',100);
            // $table->bigInteger('iddispenser')->unsigned();
            // $table->bigInteger('idcategoria')->unsigned();
            // $table->string('cantidad',15);
            // $table->bigInteger('idformafarm')->unsigned();
            // $table->string('indicaciones')->nullable();
            // $table->string('dosificacion')->nullable();
            // $table->string('accion_terapeutica')->nullable();
            // $table->string('principio_activo')->nullable();
            // $table->string('imagen')->nullable();
            // $table->tinyInteger('tiempo_pedido');
            // $table->decimal('precio_lista');
            // $table->decimal('precio_venta');
            // $table->string('metodoabc',2);
            
            $table->string('codigo')->comment('Identificar unico del producto registrado');
            $table->unsignedBigInteger('idlinea')->comment("Identificador que hace referencia a la tabla prod__lineas");
            $table->string('nombre')->comment('nombre del producto que se le dio al momento de crearlo');
            $table->bigInteger('correlativo')->unsigned();
            $table->bigInteger('iddispenserprimario')->comment('Identificador del envase primario que hace referencia a la tabla prod__dispensers');
            $table->string('cantidadprimario')->comment('La cantidad de unidades del envase primario, si la el campo lleva caracteres alfanumericos la cantidad representa 1 unidad, si solo lleva numeros la cantidad representa solo ese numero');
            $table->bigInteger('idformafarmaceuticaprimario')->comment('identificador unico de la tabla de forma farmaceutica');
            $table->decimal('preciolistaprimario')->comment('Precio mas alto posible que un comprador pagara por un producto específico antes de cualquier descuento');
            $table->decimal('precioventaprimario')->comment('Precio mas alto posible que un comprador pagara por un producto');
            $table->bigInteger('tiempopedidoprimario')->comment('Tiempo o ciclo de pedido de un producto');
            $table->string('metodoabcprimario')->comment('Importancia de un producto en las zonas A, B y C segun la ley de pareto');
            $table->boolean('tiendaprimario')->comment('Lugar donde ira el producto');
            $table->boolean('almacenprimario')->comment('Lugar donde ira el producto');
            $table->bigInteger('iddispensersecundario')->comment('Identificador del envase secundario que hace referencia a la tabla prod__dispensers');
            $table->string('cantidadsecundario')->comment('La cantidad de unidades del envase secundario, si la el campo lleva caracteres alfanumericos la cantidad representa 1 unidad, si solo lleva numeros la cantidad representa solo ese numero');
            $table->bigInteger('idformafarmaceuticasecundario')->comment('identificador unico de la tabla de forma farmaceutica');
            $table->decimal('preciolistasecundario')->comment('Precio mas alto posible que un comprador pagara por un producto específico antes de cualquier descuento');
            $table->decimal('precioventasecundario')->comment('Precio mas alto posible que un comprador pagara por un producto');
            $table->bigInteger('tiempopedidosecundario')->comment('Tiempo o ciclo de vida de un producto');
            $table->string('metodoabcsecundario')->comment('Importancia de un producto en las zonas A, B y C segun la ley de pareto');
            $table->boolean('tiendasecundario')->comment('Lugar donde ira el producto');
            $table->boolean('almacensecundario')->comment('Lugar donde ira el producto');
            $table->bigInteger('iddispenserterciario')->comment('Identificador del envase terciario que hace referencia a la tabla prod__dispensers');
            $table->string('cantidadterciario')->comment('La cantidad de unidades del envase terciario, si la el campo lleva caracteres alfanumericos la cantidad representa 1 unidad, si solo lleva numeros la cantidad representa solo ese numero');
            $table->bigInteger('idformafarmaceuticaterciario')->comment('identificador unico de la tabla de forma farmaceutica');
            $table->decimal('preciolistaterciario')->comment('Precio mas alto posible que un comprador pagara por un producto específico antes de cualquier descuento');
            $table->decimal('precioventaterciario')->comment('Precio mas alto posible que un comprador pagara por un producto');
            $table->bigInteger('tiempopedidoterciario')->comment('Tiempo o ciclo de vida de un producto');
            $table->string('metodoabcterciario')->comment('Importancia de un producto en las zonas A, B y C segun la ley de pareto');
            $table->boolean('tiendaterciario')->comment('Lugar donde ira el producto');
            $table->boolean('almacenterciario')->comment('Lugar donde ira el producto');
            $table->unsignedBigInteger('idcategoria')->comment('Identificar unico a la que pertenece el producto, que hace referencia a la tabla prod__categorias');
            $table->string('indicaciones')->nullable()->comment('Indicaciones o instrucciones de uso del producto');
            $table->string('dosificacion')->nullable()->comment('Cantidad determinada a consumirse');
            $table->string('principio')->nullable()->comment('Componente activo de dicho medicamento');
            $table->string('accion')->nullable()->comment('a');
            $table->string('foto')->nullable()->comment('Imagen de referencia del producto');
            $table->string('codigointernacional')->nullable()->comment('Codigo del producto -parecido al codigo de barras-');
            $table->bigInteger('mostrardetalles')->comment('True: muestra los detalles de un producto, Fale: oculta los detalles de un producto');
        
            $table->tinyInteger('estado')->default(1)->comment('1->');
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('identificador del usuario que registro el producto');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('identificador del usuario que modifico el producto');
            $table->timestamps();

            $table->foreign('idlinea')->references('id')->on('prod__lineas');
            // $table->foreign('iddispenserprimario')->references('id')->on('prod__dispensers');
            // $table->foreign('iddispensersecundario')->references('id')->on('prod__dispensers');
            // $table->foreign('iddispenserterciario')->references('id')->on('prod__dispensers');
            // $table->foreign('idformafarmaceuticaprimario')->references('id')->on('prod__forma_farmaceuticas');
            // $table->foreign('idformafarmaceuticasecundario')->references('id')->on('prod__forma_farmaceuticas');
            // $table->foreign('idformafarmaceuticaterciario')->references('id')->on('prod__forma_farmaceuticas');
            $table->foreign('idcategoria')->references('id')->on('prod__categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod__productos');
    }
}
