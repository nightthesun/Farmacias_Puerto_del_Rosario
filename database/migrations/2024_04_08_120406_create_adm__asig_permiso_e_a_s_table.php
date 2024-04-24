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
        Schema::create('adm__asig_permiso_e_a_s', function (Blueprint $table) {
            $table->smallInteger('id_user_role_sucu');
            $table->tinyInteger('edit')->comment('1 si el valor es verdadero y 2 si es falso');
            $table->tinyInteger('activar')->comment('1 si el valor es verdadero y 2 si es falso');
            $table->smallInteger('id_ventana')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__asig_permiso_e_a_s');
    }
};
