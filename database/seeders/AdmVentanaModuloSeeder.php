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
        $inv = Adm_Modulo::where('nombre', 'inventario')->first();  
        $log = Adm_Modulo::where('nombre', 'Logistica')->first();         
        $dir = Adm_Modulo::where('nombre', 'Directorio')->first(); 
        $ven = Adm_Modulo::where('nombre', 'Ventas')->first(); 
        
        //administracion 100

        DB::table('adm__ventana_modulos')->insert(['codventana'=>'101','idmodulo'=>$admin->id,'nombre'=>'Mod. Vent. Acc.','template'=>'modulo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'102','idmodulo'=>$admin->id,'nombre'=>'Roles-Permisos','template'=>'rolpermiso-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'103','idmodulo'=>$admin->id,'nombre'=>'Usuarios','template'=>'usuario-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'104','idmodulo'=>$admin->id,'nombre'=>'Sucursales','template'=>'sucursal-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'105','idmodulo'=>$admin->id,'nombre'=>'Rubros','template'=>'rubros-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'106','idmodulo'=>$admin->id,'nombre'=>'Configuración','template'=>'configuracion-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'107','idmodulo'=>$admin->id,'nombre'=>'Dosificación','template'=>'dofisicacion-component']);        
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
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'304','idmodulo'=>$alm->id,'nombre'=>'Ingreso Productos2','template'=>'nuevo-almacen2-component']);
                
        //Tienda 400
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'401','idmodulo'=>$tienda->id,'nombre'=>'Tiendas','template'=>'tienda-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'402','idmodulo'=>$tienda->id,'nombre'=>'Ingreso Productos','template'=>'tienda-ingreso-producto-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'403','idmodulo'=>$tienda->id,'nombre'=>'Codificacion','template'=>'tienda-codificacion-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'404','idmodulo'=>$tienda->id,'nombre'=>'Ingreso Productos2','template'=>'tienda-ingreso-producto2-component']);

        //servicios 500
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'501','idmodulo'=>$serv->id,'nombre'=>'Areas','template'=>'area-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'502','idmodulo'=>$serv->id,'nombre'=>'Prestaciones','template'=>'prestaciones-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'503','idmodulo'=>$serv->id,'nombre'=>'Venta Servicios','template'=>'ventas-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'504','idmodulo'=>$serv->id,'nombre'=>'Historial Ventas','template'=>'histventas-component']);


        //configuraciones 600
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'601','idmodulo'=>$config->id,'nombre'=>'Desc. Servicios','template'=>'descuentos-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'602','idmodulo'=>$config->id,'nombre'=>'Desc. Productos','template'=>'descproductos-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'603','idmodulo'=>$config->id,'nombre'=>'Descuentos','template'=>'descuentosx-component']);


        //productos 700
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'701','idmodulo'=>$prod->id,'nombre'=>'Lineas y Marcas','template'=>'linea-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'702','idmodulo'=>$prod->id,'nombre'=>'Registro Producto','template'=>'producto-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'703','idmodulo'=>$prod->id,'nombre'=>'Envases y Embalaje','template'=>'dispenser-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'704','idmodulo'=>$prod->id,'nombre'=>'Forma o U. Medida','template'=>'formafarm-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'705','idmodulo'=>$prod->id,'nombre'=>'Categorias','template'=>'categoria-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'706','idmodulo'=>$prod->id,'nombre'=>'Tipo Entrada','template'=>'tipo-entrada-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'707','idmodulo'=>$prod->id,'nombre'=>'Nueva Lista','template'=>'lista-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'708','idmodulo'=>$prod->id,'nombre'=>'Registro precio x lista','template'=>'reg-pre-x-lista-component']);

        //gestion de productos 800
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'801','idmodulo'=>$gestprec->id,'nombre'=>'Costo Compra','template'=>'precio-venta-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'802','idmodulo'=>$gestprec->id,'nombre'=>'Costo Compra 2','template'=>'precio-venta-component2']);
        //inventario 900
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'901','idmodulo'=>$inv->id,'nombre'=>'Ajustes Negativos','template'=>'ajuste-negativo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'902','idmodulo'=>$inv->id,'nombre'=>'Ajustes Positivos','template'=>'ajuste-positivo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'903','idmodulo'=>$inv->id,'nombre'=>'Traspasos','template'=>'traspaso-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'904','idmodulo'=>$inv->id,'nombre'=>'Recepcion','template'=>'recepcion-traspasos-component']);
       
        //logistica 1000
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1001','idmodulo'=>$log->id,'nombre'=>'Vehiculos','template'=>'vehiculo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1002','idmodulo'=>$log->id,'nombre'=>'Traslados','template'=>'traslado-component']);
        //Directorio 1100
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1101','idmodulo'=>$dir->id,'nombre'=>'Clientes','template'=>'cliente-component']);
        //Directorio 1200
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1201','idmodulo'=>$ven->id,'nombre'=>'Gestor de Ventas','template'=>'gestor-venta-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1202','idmodulo'=>$ven->id,'nombre'=>'Detalle de Ventas','template'=>'venta-detalle-component']);       
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1203','idmodulo'=>$ven->id,'nombre'=>'Venta rapida','template'=>'venta-rapida-component']);
    }
}
