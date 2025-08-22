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
        Schema::create('ven_prospectos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_usuario');
            $table->bigInteger('id_producto');
            $table->tinyInteger('envase');
            $table->string('descripcion',180);
            $table->tinyInteger('activo')->default(1);
            $table->smallInteger('id_sucursal');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven_prospectos');
    }
};
