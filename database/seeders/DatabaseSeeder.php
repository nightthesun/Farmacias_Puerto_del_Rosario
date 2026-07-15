<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== INICIANDO SEEDERS ===');

        $this->truncateTables([
            'adm__rubros',
            'adm__sucursals',
            'rrh__unidad_organizacionals',
            'rrh__cargos',
            'rrh__formacions',
            'rrh__profesions',
            'rrh__empleados',
            'adm__modulos',
            'adm__ventana_modulos',
            'adm__accion_ventanas',
            'adm__roles',
            'adm__role_accions',
            'users',
            'adm__user_role_sucursals',
            'adm__departamentos',
            'adm__nacionalidads',
            'adm__ciudads',
            'adm__bancos',
            'prod__categorias',
            'prod__tipo_descuentos',
            'par__desc_servicios',
            'adm__qr_glosa',
            'adm__credecial_correos',
            'auto__sincronizacion',
            'caja__monedas',
            'dir__tipo_doc',
            'excel__emision',
            'log__config_gestion_stock',
            'log__config_traspaso',
            'log__sincro_ges_stock',
            'par_tipo_tabla',
            'siat__catalogo',
            'siat__configuracions',
            'siat__endpoints',
            'ven_metodo_pago',
            'siat__sincronizacions'
        ]);

        $seeders = [
            AdmRubroSeeder::class,
            AdmSucursalSeeder::class,
            RrhUnidadOrganizacionalSeeder::class,
            RrhCargoSeeder::class,
            RrhFormacionSeeder::class,
            RrhProfesionSeeder::class,
            RrhEmpleadoSeeder::class,
            AdmModuloSeeder::class,
            AdmVentanaModuloSeeder::class,
            AdmAccionVentanaSeeder::class,
            AdmRoleSeeder::class,
            AdmRoleAccionSeeder::class,
            UsersSeeder::class,
            AdmUserRoleSucursalSeeder::class,
            AdmDepartamentoSeeder::class,
            AdmNacionalidadSeeder::class,
            AdmCiudadSeeder::class,
            AdmBancoSeeder::class,
            ProdCategoriaSeeder::class,
            ProdFormaUnidadMedidaSeeder::class,
            ProdTipoDescuentSeeder::class,
            ParDescServiciosSeeder::class,
            ProdTipoEntradasSeeder::class,
            AdmConfigQrGlosaSeeder::class,
            AdmCredencialCorreoSeeder::class,
            AutoSincronizacionSeeder::class,
            CajaMonedaSeeder::class,
            DirTipoDocumentoSeeder::class,
            ExcelEmisionSeeder::class,
            LogConfigGestionStockSeeder::class,
            LogConfigTraspasoSeeder::class,
            LogSincroGesstockSeeder::class,
            ParTipoTabla::class,
            SiatCatalogoSeeder::class, 
            SiatConfiguracion::class,
            SiatEndpontSeeder::class, 
            VenMetodoPagoSeeder::class,
            AutoSincronizacionComplemento::class,
        ];
 
        foreach ($seeders as $seeder) {
            $this->command->info("→ Ejecutando {$seeder}");
            $this->call($seeder);
        }

        $this->command->info('=== SEEDERS COMPLETADOS ===');
    }

    protected function truncateTables(array $tables): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
