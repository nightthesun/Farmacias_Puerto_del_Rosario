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
        Schema::create('adm__credencial_erp', function (Blueprint $table) {
            $table->id();
            $table->string('mail_mailer',20)->nullable();
            $table->string('mail_host',200)->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username',200)->nullable();
            $table->text('mail_password')->nullable();
            $table->string('mail_encryp',20)->nullable();
            $table->string('mail_from_add',200)->nullable();
            $table->string('mail_from_na',250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adm__credencial_erp');
    }
};
