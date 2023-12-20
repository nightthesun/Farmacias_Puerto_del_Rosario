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
        Schema::table('prod__productos', function (Blueprint $table) {
            $table->boolean('checkformafarmaceuticaprimario')->after('idformafarmaceuticaprimario')->nullable;
            $table->boolean('checkformafarmaceuticasecundario')->after('idformafarmaceuticasecundario')->nullable;
            $table->boolean('checkformafarmaceuticaterciario')->after('idformafarmaceuticaterciario')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prod__productos', function (Blueprint $table) {
            $table->dropColumn('checkformafarmaceuticaprimario');
            $table->dropColumn('checkformafarmaceuticasecundario');
            $table->dropColumn('checkformafarmaceuticaterciario');
        });
    }
};
