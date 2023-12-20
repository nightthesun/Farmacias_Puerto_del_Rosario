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
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->renameColumn('idalmingresoproducto','id_table_ingreso_tienda_almacen');
            $table->renameColumn('margen_30p_gespreventa','margen_20p_gespreventa');
            $table->renameColumn('margen_40p_gespreventa','margen_30p_gespreventa');
            $table->renameColumn('precio_compra_gespreventa','costo_compra_gespreventa');    
        });
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->boolean("tienda")->default(0)->after('id_table_ingreso_tienda_almacen')->comment("Esta columna determina si el registro en la tabla ges_pre__ventas pertenece a la tienda");
            $table->boolean("almacen")->default(0)->after('tienda')->comment("Esta columna determina si el registro en la tabla ges_pre__ventas pertenece a almacen");
            $table->decimal('precio_lista_gespreventa', $precision = 8, $scale = 2)->after('almacen')->comment('');
            $table->decimal('cantidad_envase_gespreventa', $precision = 8, $scale = 2)->after('precio_lista_gespreventa')->comment('');
            $table->decimal('utilidad_bruta_gespreventa', $precision = 8, $scale = 2)->after('margen_30p_gespreventa')->comment('');
            $table->decimal('precio_venta_gespreventa', $precision = 8, $scale = 2)->after('precio_lista_gespreventa')->comment('costo_compra_gespreventa');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->renameColumn('id_table_ingreso_tienda_almacen','idalmingresoproducto');
            $table->renameColumn('margen_30p_gespreventa','margen_40p_gespreventa');
            $table->renameColumn('margen_20p_gespreventa','margen_30p_gespreventa');
            $table->renameColumn('costo_compra_gespreventa','precio_compra_gespreventa');
        });
        Schema::table('ges_pre__ventas', function (Blueprint $table) {
            $table->dropColumn("tienda");
            $table->dropColumn("almacen");
            $table->dropColumn("precio_lista_gespreventa");
            $table->dropColumn("cantidad_envase_gespreventa");
            $table->dropColumn("utilidad_bruta_gespreventa");
            $table->dropColumn("precio_venta_gespreventa");
        });
    }
};
