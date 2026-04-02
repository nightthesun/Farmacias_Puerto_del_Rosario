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
        $caja = Adm_Modulo::where('nombre', 'Caja')->first(); 
        $comp = Adm_Modulo::where('nombre', 'Compras')->first(); 
        $siat = Adm_Modulo::where('nombre', 'Siat')->first(); 

        //administracion 100

        DB::table('adm__ventana_modulos')->insert(['codventana'=>'101','idmodulo'=>$admin->id,'nombre'=>'Mod. Vent. Acc.','template'=>'modulo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'102','idmodulo'=>$admin->id,'nombre'=>'Roles-Permisos','template'=>'rolpermiso-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'103','idmodulo'=>$admin->id,'nombre'=>'Usuarios','template'=>'usuario-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'104','idmodulo'=>$admin->id,'nombre'=>'Sucursales','template'=>'sucursal-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'105','idmodulo'=>$admin->id,'nombre'=>'Rubros','template'=>'rubros-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'106','idmodulo'=>$admin->id,'nombre'=>'Configuración','template'=>'configuracion-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'107','idmodulo'=>$admin->id,'nombre'=>'Dosificación','template'=>'dofisicacion-component']); 
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'108','idmodulo'=>$admin->id,'nombre'=>'QR Simple','template'=>'qr-simple-component']);  
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'109','idmodulo'=>$admin->id,'nombre'=>'Bancos / Nacionalidad','template'=>'banco-nacionalidad-component']);       
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
       DB::table('adm__ventana_modulos')->insert(['codventana'=>'905','idmodulo'=>$inv->id,'nombre'=>'Gestor de stock','template'=>'gestor-stock-component']);
       DB::table('adm__ventana_modulos')->insert(['codventana'=>'906','idmodulo'=>$inv->id,'nombre'=>'Configuración stock','template'=>'configuracion-stock-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'907','idmodulo'=>$inv->id,'nombre'=>'Auto GestioStock','template'=>'auto-gestion-stock-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'908','idmodulo'=>$inv->id,'nombre'=>'Auto Proceso T.T.R.','template'=>'auto-ttr-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'909','idmodulo'=>$inv->id,'nombre'=>'Inventario inicial','template'=>'invetario-inicial-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'910','idmodulo'=>$inv->id,'nombre'=>'Inventario físico','template'=>'gestion-inventario-periodo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'911','idmodulo'=>$inv->id,'nombre'=>'Metodo ABC','template'=>'metodo-abc-component']);
        //logistica 1000
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1001','idmodulo'=>$log->id,'nombre'=>'Vehiculos','template'=>'vehiculo-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1002','idmodulo'=>$log->id,'nombre'=>'Traslados','template'=>'traslado-component']);
        //Directorio 1100
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1101','idmodulo'=>$dir->id,'nombre'=>'Clientes','template'=>'cliente-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1102','idmodulo'=>$dir->id,'nombre'=>'Proveedor','template'=>'proveedor-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1103','idmodulo'=>$dir->id,'nombre'=>'Distribuidor','template'=>'distribuidor-component']);
        //Directorio 1200
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1201','idmodulo'=>$ven->id,'nombre'=>'Gestor de Ventas','template'=>'gestor-venta-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1202','idmodulo'=>$ven->id,'nombre'=>'Detalle de Ventas','template'=>'venta-detalle-component']);       
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1203','idmodulo'=>$ven->id,'nombre'=>'Venta rapida','template'=>'venta-rapida-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1204','idmodulo'=>$ven->id,'nombre'=>'Caducidad','template'=>'caducidad-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1205','idmodulo'=>$ven->id,'nombre'=>'Trans. Electrónica','template'=>'transaccion-electronica-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1206','idmodulo'=>$ven->id,'nombre'=>'Prospecto','template'=>'prospecto-component']);
       //caja 1300
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1301','idmodulo'=>$caja->id,'nombre'=>'Apertura / Cierre','template'=>'apertura_cierre-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1302','idmodulo'=>$caja->id,'nombre'=>'Moneda','template'=>'moneda-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1303','idmodulo'=>$caja->id,'nombre'=>'Entrada / Salida','template'=>'entrada_salida-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1304','idmodulo'=>$caja->id,'nombre'=>'Transacción','template'=>'transaccion-component']);      
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1305','idmodulo'=>$caja->id,'nombre'=>'Corregir caja','template'=>'corregir_caja-component']);
        DB::table('adm__ventana_modulos')->insert(['codventana'=>'1306','idmodulo'=>$caja->id,'nombre'=>'Crear caja','template'=>'crear_caja-component']);
        //compras 1400
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1401','idmodulo'=>$comp->id,'nombre'=>'Inversiones','template'=>'inversiones-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1402','idmodulo'=>$comp->id,'nombre'=>'Gastos','template'=>'gastos-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1403','idmodulo'=>$comp->id,'nombre'=>'Tesoreria','template'=>'tesoreria-component']);
         //siat 1500
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1501','idmodulo'=>$siat->id,'nombre'=>'Configuración siat','template'=>'siat-configuracion-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1502','idmodulo'=>$siat->id,'nombre'=>'Añadir sucursal','template'=>'siat-sucursal-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1503','idmodulo'=>$siat->id,'nombre'=>'Administración','template'=>'siat-administracion-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1504','idmodulo'=>$siat->id,'nombre'=>'Emisor','template'=>'siat-emisor-component']);
         DB::table('adm__ventana_modulos')->insert(['codventana'=>'1505','idmodulo'=>$siat->id,'nombre'=>'Homologación','template'=>'siat-homologacion-component']);
    }
}
