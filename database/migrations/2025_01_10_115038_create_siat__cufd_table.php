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
        Schema::create('siat__cufd', function (Blueprint $table) {
            $table->id();
            $table->text('dato');
            $table->string('fecha_vigencia');
            $table->tinyInteger('estado');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__cufd');
    }
};
