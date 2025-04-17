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
        Schema::create('ven__trasferencias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_venta');
            $table->string('tipo',60)->default('error');
            $table->bigInteger('num_tar_o_boleto')->nullable();
            $table->text('mas_datos')->nullable();
            $table->smallInteger('id_banco')->nullable();
            $table->tinyInteger('contador')->default(1);
            $table->string('user_name',200)->nullable();           
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ven__trasferencias');
    }
};
