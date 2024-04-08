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
        Schema::create('adm__asig_mas_sucursales', function (Blueprint $table) {
            $table->smallInteger('id_user_role_sucu');
            $table->smallInteger('id_sucursal');
            $table->smallInteger('id_alm_tda');
            $table->string('cod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__asig_mas_sucursales');
    }
};
