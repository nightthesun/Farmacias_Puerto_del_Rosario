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
        Schema::create('siat__json_xmls', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',255);
            $table->string('sector',255);
            $table->longText('xml');
            $table->longText('json');
            $table->tinyInteger('modalidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siat__json_xmls');
    }
};
