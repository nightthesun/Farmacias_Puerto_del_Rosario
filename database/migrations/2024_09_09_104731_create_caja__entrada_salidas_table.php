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
        Schema::create('caja__entrada_salidas', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_arqueo');
            $table->decimal('valor',11,2);
            $table->string('observacion');
            $table->string('mensaje')->comment('usuario o persona a quien se dirige');
            $table->tinyInteger('entrada_salida')->comment('1=emisor, 2=receptor');
            $table->timestamps();
            $table->smallInteger('id_apertura_cierre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja__entrada_salidas');
    }
};
