<?php

namespace Database\Seeders;

use App\Models\Adm_Modulo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmVentanaModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Adm_Modulo::where('nombre', 'administración')->first();        
        $rh = Adm_Modulo::where('nombre', 'recursos humanos')->first();        
        $alm = Adm_Modulo::where('nombre', 'almacenes')->first();
        $tienda = Adm_Modulo::where('nombre', 'tienda')->first();         
        $serv = Adm_Modulo::where('nombre', 'servicios')->first();        
        $config = Adm_Modulo::where('nombre', 'parametros')->first();        
        $prod = Adm_Modulo::where('nombre', 'productos')->first();
        $gestprec = Adm_Modulo::where('nombre', 'gestion precios')->first();        

        
        
        //administracion 100

        DB::table('adm__ventana_modulos')->insert(['codventana'=>'101','idmodulo'=>$admin->id,'nombre'=>'Mod. Vent. Acc.','template'=>'modulo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'102','idmodulo'=>$admin->id,'nombre'=>'Roles-Permisos','template'=>'rolpermiso-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'103','idmodulo'=>$admin->id,'nombre'=>'Usuarios','template'=>'usuario-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'104','idmodulo'=>$admin->id,'nombre'=>'Sucursales','template'=>'sucursal-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'105','idmodulo'=>$admin->id,'nombre'=>'Rubros','template'=>'rubros-component']);
        
        //recursos humanos 200
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'201','idmodulo'=>$rh->id,'nombre'=>'Nivel de Formacion','template'=>'rrhnivel-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'202','idmodulo'=>$rh->id,'nombre'=>'Profesiones','template'=>'rrhprofesion-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'203','idmodulo'=>$rh->id,'nombre'=>'Unidad Organizacional','template'=>'rrhuorg-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'204','idmodulo'=>$rh->id,'nombre'=>'Cargos','template'=>'rrhcargos-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'205','idmodulo'=>$rh->id,'nombre'=>'Empleados','template'=>'rrhempleados-component']);

        //almacenes 300

        DB::table('adm__ventana_modulos')->insert(['codventana'=>'301','idmodulo'=>$alm->id,'nombre'=>'Nuevo Almacen','template'=>'nuevo-almacen-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'302','idmodulo'=>$alm->id,'nombre'=>'Codificacion','template'=>'codificacion-component']);
        //DB::table('adm__ventana_modulos')->insert(['codventana'=>'303','idmodulo'=>$alm->id,'nombre'=>'Ingreso Productos','template'=>'almacen-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'303','idmodulo'=>$alm->id,'nombre'=>'Ingreso Productos','template'=>'ingreso-producto-component']);
        

        //Tienda 400
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'401','idmodulo'=>$tienda->id,'nombre'=>'Tiendas','template'=>'tienda-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'402','idmodulo'=>$tienda->id,'nombre'=>'Ingreso Productos','template'=>'tienda-ingreso-producto-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'403','idmodulo'=>$tienda->id,'nombre'=>'Codificacion','template'=>'tienda-codificacion-component']);


        //servicios 500
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'501','idmodulo'=>$serv->id,'nombre'=>'Areas','template'=>'area-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'502','idmodulo'=>$serv->id,'nombre'=>'Prestaciones','template'=>'prestaciones-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'503','idmodulo'=>$serv->id,'nombre'=>'Venta Servicios','template'=>'ventas-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'504','idmodulo'=>$serv->id,'nombre'=>'Historial Ventas','template'=>'histventas-component']);


        //configuraciones 600
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'601','idmodulo'=>$config->id,'nombre'=>'Desc. Servicios','template'=>'descuentos-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'602','idmodulo'=>$config->id,'nombre'=>'Desc. Productos','template'=>'descproductos-component']);


        //productos 700
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'701','idmodulo'=>$prod->id,'nombre'=>'Lineas y Marcas','template'=>'linea-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'702','idmodulo'=>$prod->id,'nombre'=>'Registro Producto','template'=>'producto-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'703','idmodulo'=>$prod->id,'nombre'=>'Envases y Embalaje','template'=>'dispenser-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'704','idmodulo'=>$prod->id,'nombre'=>'Forma o U. Medida','template'=>'formafarm-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'705','idmodulo'=>$prod->id,'nombre'=>'Categorias','template'=>'categoria-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'706','idmodulo'=>$prod->id,'nombre'=>'Tipo Entrada','template'=>'tipo-entrada-component']);

        //gestion de productos 800
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'801','idmodulo'=>$gestprec->id,'nombre'=>'Costo Compra','template'=>'precio-venta-component']);
        
        
    }
}
