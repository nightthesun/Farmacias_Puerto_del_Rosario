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
        Schema::create('log__sistema', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_modulo')->nullable();
            $table->smallInteger('id_sub_modulo')->nullable();
            $table->integer('accion')->nullable()->comment('1=add, 2=delete, 3=create, 4=edit, 5=show');
            $table->string('descripcion')->nullable()->comment('descripcion del boton, tabla, modal, como se hizo');
            $table->unsignedSmallInteger('user_id');
            $table->timestamps();
            $table->smallInteger('id_movimiento')->nullable()->comment('id de venta o accion, o movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log__sistema');
    }
};
