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
        Schema::create('siat__sincronizacions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo_sincronizacion')->comment('1=manual 2=automatico');
            $table->smallInteger('id_sucursal_siat');           
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__sincronizacions');
    }
};
