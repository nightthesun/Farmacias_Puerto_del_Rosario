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
        Schema::create('adm__qr_glosa', function (Blueprint $table) {
            $table->id();
            $table->string('currency');
            $table->tinyInteger('tipoFecha')->comment('1 cada dia 2 fecha especifica');
            $table->date('expirationDate')->nullable();
            $table->string('gloss',250);  
            $table->boolean('singleUse');      
            $table->string('additionalData',250);
           $table->tinyInteger('destinationAccountId')->comment('1 nacional 2 internacional');     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__qr_glosa');
    }
};
