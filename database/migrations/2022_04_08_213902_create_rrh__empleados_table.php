<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRrhEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__empleados', function (Blueprint $table) {
            $table->smallIncrements('id');
            //////////datos empleado///////////////
            $table->string('codempleado',30)->nullable();
            $table->string('nombre',50);
            $table->string('papellido',50)->nullable();
            $table->string('sapellido',50)->nullable();
            $table->string('sexo',1);
            $table->bigInteger('ci')->nullable();
            $table->string('complementoci',6)->nullable();
            $table->tinyInteger('iddepartamento')->nullable();
            $table->date('fechanacimiento')->nullable();
            $table->string('foto',150)->nullable();
            $table->string('estadocivil')->nullable();
            $table->smallInteger('idnacionalidad')->nullable();
            ///////////contacto/////////////////
            $table->string('domicilio',50)->nullable();
            $table->smallInteger('idciudad')->nullable();
            $table->string('telefonos',100)->nullable();
            $table->string('celular',100)->nullable();
            /////////datos laborales////////////////////
            $table->smallInteger('idformacion')->unsigned(); 
            $table->smallInteger('idprofesion')->unsigned();
            $table->smallinteger('idcargo')->unsigned();
            $table->string('nit')->nullable();
            $table->date('fechaingreso')->nullable();
            $table->date('fecharetiro')->nullable();
            ////////////datos para pagos//////////////
            $table->tinyInteger('idbanco')->nullable();
            $table->string('nrcuenta',20)->nullable();
            $table->string('tipo_cuenta',20)->nullable();
            
            $table->string('obs',150)->nullable();
            $table->boolean('activo')->default(1);
            $table->smallInteger('id_usuario_registra')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->smallInteger('id_usuario_modifica')->unsigned()->nullable()->comment('null->viene del seeder');
            $table->timestamps();

            $table->foreign('idformacion')->references('id')->on('rrh__formacions');
            $table->foreign('idprofesion')->references('id')->on('rrh__profesions');
            $table->foreign('idcargo')->references('id')->on('rrh__cargos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrh__empleados');
    }
}
