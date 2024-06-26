<?php

namespace App\Http\Controllers;

use App\Models\GesPre_Venta2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GesPre_Venta_lista;
use PHPUnit\TextUI\Configuration\Merger;

class GesPreVenta2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->lista=='x') {
          //** lista normal por default */
             
        $buscararray = array();
        $bus = $request->query('buscarAlmTdn');
        if (!empty($request->buscar)) {          
            $buscararray = explode(" ", $request->buscar);           
            $valor = sizeof($buscararray);       
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {                   
                    if (empty($sqls)) {    
                                        
                        $sqls = "(
                                pp.codigo like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%'
                                or pp.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%'
                            
                               )";
                    } else {
                      
                        $sqls .= "and (
                             pp.codigo like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%'
                                or pp.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%'
                       )";
                    }
                } 
                // query start 
            
                $almacen = DB::table('pivot__modulo_tienda_almacens as pivot')
                ->select(
                    'pivot.id as id',
                    'gpv.id as gpv_id',
                    'aa.codigo as cod',
                    'ai.id as id_ingreso',
                    'ai.lote as lote',
                    'ai.stock_ingreso as stock_ingreso',
                    'ai.cantidad as cantidad_ingreso',
                    'ai.created_at as fecha_ingreso',
                    'ai.fecha_vencimiento as fecha_vencimiento',
                    'pp.id as id_producto',
                    'pp.nombre as nombre',
                    'pp.codigo as codigo_producto',
                    'pl.nombre as nombre_linea',
                    'ass.id as id_sucursal',
                    'ass.razon_social as razon_social',

                    DB::raw('NULL as id_tienda'),
                    'ai.idalmacen as id_almacen',
                    DB::raw("CASE 
                        WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                        WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                        WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                        ELSE NULL 
                    END AS leyenda"),
                    DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                    WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                    WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                    ELSE NULL 
                END AS iddispenserEnvase"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                    WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                    WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                    ELSE NULL 
                END AS cantidadEnvase"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                    WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                    WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                    ELSE NULL 
                END AS idformafarmaceuticaEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.preciolistaprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.preciolistasecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.preciolistaterciario, '')) 
                ELSE NULL 
                END AS preciolistaEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.precioventaprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.precioventasecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.precioventaterciario, '')) 
                ELSE NULL 
                END AS precioventaEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.tiempopedidoprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiempopedidosecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiempopedidoterciario, '')) 
                ELSE NULL 
                END AS tiempopedidoEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.metodoabcprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.metodoabcsecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.metodoabcterciario, '')) 
                ELSE NULL 
                END AS metodoabcEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
                ELSE NULL 
                END AS tiendaEnvase"),
                DB::raw("CASE 
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
                ELSE NULL 
                END AS almacenEnvase"),
                DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), ''), '')
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), ''), '')
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), ''), '')
                ELSE NULL
                END AS costocompraEnvase"),
                DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ai.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ai.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
                    'gpv.precio_lista_gespreventa as precio_lista_gespreventa',
                    'gpv.precio_venta_gespreventa as precio_venta_gespreventa',
                    'gpv.cantidad_envase_gespreventa as cantidad_envase_gespreventa',
                    'gpv.costo_compra_gespreventa as costo_compra_gespreventa',
                    'gpv.margen_20p_gespreventa as margen_20p_gespreventa',
                    'gpv.margen_30p_gespreventa as margen_30p_gespreventa',
                    'gpv.utilidad_bruta_gespreventa as utilidad_bruta_gespreventa',
                    'gpv.utilidad_neto_gespreventa as utilidad_neto_gespreventa',
                    'u.name as user_name',
                    DB::raw('GREATEST(gpv.created_at, gpv.updated_at) as fecha'),
                    'gpv.listo_venta as listo_venta',
                    'pte.nombre as tipoentrada'
                )
                
            
                ->join('alm__almacens as aa', 'aa.id', '=', 'pivot.id_tienda_almacen')
                ->join('alm__ingreso_producto as ai', 'ai.id', '=', 'pivot.id_ingreso')
                ->join('prod__productos as pp', 'pp.id', '=', 'ai.id_prod_producto')
                
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'ai.id_tipoentrada')
           
                ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
               
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
                ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
                ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
                ;
                $tienda = DB::table('pivot__modulo_tienda_almacens as pivot')
                ->select(
                    'pivot.id as id',
                    'gpv.id as gpv_id',
                    'tt.codigo as cod',
                    'ti.id as id_ingreso',
                    'ti.lote as lote',
                    'ti.stock_ingreso as stock_ingreso',
                    'ti.cantidad as cantidad_ingreso',
                    'ti.created_at as fecha_ingreso',
                    'ti.fecha_vencimiento as fecha_vencimiento',
                    'pp.id as id_producto',
                    'pp.nombre as nombre',
                    'pp.codigo as codigo_producto',
                    'l.nombre as nombre_linea',
                    'ass.id as id_sucursal',
                    'ass.razon_social as razon_social',
                   
                    'ti.idtienda as id_tienda',
                    DB::raw('NULL as id_almacen'),
                    DB::raw("CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                        ELSE NULL 
                    END AS leyenda"),
                    DB::raw("CASE 
                    WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                    WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                    WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                    ELSE NULL 
                END AS iddispenserEnvase"),
                DB::raw("CASE 
                    WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                    WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                    WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                    ELSE NULL 
                END AS cantidadEnvase"),
                DB::raw("CASE 
                    WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                    WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                    WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                    ELSE NULL 
                END AS idformafarmaceuticaEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.preciolistaprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.preciolistasecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.preciolistaterciario, '')) 
                ELSE NULL 
                END AS preciolistaEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.precioventaprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.precioventasecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.precioventaterciario, '')) 
                ELSE NULL 
                END AS precioventaEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiempopedidoprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiempopedidosecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiempopedidoterciario, '')) 
                ELSE NULL 
                END AS tiempopedidoEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.metodoabcprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.metodoabcsecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.metodoabcterciario, '')) 
                ELSE NULL 
                END AS metodoabcEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
                ELSE NULL 
                END AS tiendaEnvase"),
                DB::raw("CASE 
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
                ELSE NULL 
                END AS almacenEnvase"),
                DB::raw("CASE
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), ''), '')
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), ''), '')
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), ''), '')
                ELSE NULL
                END AS costocompraEnvase"),
                DB::raw("CASE
                WHEN ti.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ti.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ti.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
                    'gpv.precio_lista_gespreventa as precio_lista_gespreventa',
                    'gpv.precio_venta_gespreventa as precio_venta_gespreventa',
                    'gpv.cantidad_envase_gespreventa as cantidad_envase_gespreventa',
                    'gpv.costo_compra_gespreventa as costo_compra_gespreventa',
                    'gpv.margen_20p_gespreventa as margen_20p_gespreventa',
                    'gpv.margen_30p_gespreventa as margen_30p_gespreventa',
                    'gpv.utilidad_bruta_gespreventa as utilidad_bruta_gespreventa',
                    'gpv.utilidad_neto_gespreventa as utilidad_neto_gespreventa',
                    'u.name as user_name',
                    DB::raw('GREATEST(gpv.created_at, gpv.updated_at) as fecha'),
                    'gpv.listo_venta as listo_venta',
                    'pte.nombre as tipoentrada'
                )
                ->join('tda__tiendas as tt', 'tt.id', '=', 'pivot.id_tienda_almacen')
             ->join('tda__ingreso_productos as ti', 'ti.id', '=', 'pivot.id_ingreso')
             ->join('prod__productos as pp', 'pp.id', '=', 'ti.id_prod_producto')

                
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'ti.id_tipoentrada')
           
                ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'ti.idtienda')
                ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
            
                ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
                ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
               ;
               $queryCombinacion = $almacen->where('aa.codigo', '=', $bus)->whereRaw($sqls)
               ->unionAll($tienda->where('tt.codigo','=',$bus)->whereRaw($sqls)); 
               $queryCombinacion = $queryCombinacion -> orderByRaw('id DESC')->paginate(15); 
            }    
            return
            [
                'pagination' =>
                [
                    'total'         =>    $queryCombinacion->total(),
                    'current_page'  =>    $queryCombinacion->currentPage(),
                    'per_page'      =>    $queryCombinacion->perPage(),
                    'last_page'     =>    $queryCombinacion->lastPage(),
                    'from'          =>    $queryCombinacion->firstItem(),
                    'to'            =>    $queryCombinacion->lastItem(),
                ],
                'queryCombinacion' => $queryCombinacion,
            ];
        } else {
             // query start 
             $almacen = DB::table('pivot__modulo_tienda_almacens as pivot')
             ->select(
                'pivot.id as id',
                 'gpv.id as gpv_id',
                 'aa.codigo as cod',
                 'ai.id as id_ingreso',
                 'ai.lote as lote',
                 'ai.stock_ingreso as stock_ingreso',
                 'ai.cantidad as cantidad_ingreso',
                 'ai.created_at as fecha_ingreso',
                 'ai.fecha_vencimiento as fecha_vencimiento',
                 'pp.id as id_producto',
                 'pp.nombre as nombre',
                 'pp.codigo as codigo_producto',
                 'pl.nombre as nombre_linea',
                 'ass.id as id_sucursal',
                 'ass.razon_social as razon_social',
             
                 DB::raw('NULL as id_tienda'),
                 'ai.idalmacen as id_almacen',
                 DB::raw("CASE 
                     WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                     WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                     WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                     ELSE NULL 
                 END AS leyenda"),
                 DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                 WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                 WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                 ELSE NULL 
             END AS iddispenserEnvase"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                 WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                 WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                 ELSE NULL 
             END AS cantidadEnvase"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                 WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                 WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                 ELSE NULL 
             END AS idformafarmaceuticaEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.preciolistaprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.preciolistasecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.preciolistaterciario, '')) 
             ELSE NULL 
             END AS preciolistaEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.precioventaprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.precioventasecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.precioventaterciario, '')) 
             ELSE NULL 
             END AS precioventaEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.tiempopedidoprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiempopedidosecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiempopedidoterciario, '')) 
             ELSE NULL 
             END AS tiempopedidoEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.metodoabcprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.metodoabcsecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.metodoabcterciario, '')) 
             ELSE NULL 
             END AS metodoabcEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
             ELSE NULL 
             END AS tiendaEnvase"),
             DB::raw("CASE 
             WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
             WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
             WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
             ELSE NULL 
             END AS almacenEnvase"),
             DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), ''), '')
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), ''), '')
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), ''), '')
                ELSE NULL
                END AS costocompraEnvase"),
            DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ai.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ai.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
                 'gpv.precio_lista_gespreventa as precio_lista_gespreventa',
                 'gpv.precio_venta_gespreventa as precio_venta_gespreventa',
                 'gpv.cantidad_envase_gespreventa as cantidad_envase_gespreventa',
                 'gpv.costo_compra_gespreventa as costo_compra_gespreventa',
                 'gpv.margen_20p_gespreventa as margen_20p_gespreventa',
                 'gpv.margen_30p_gespreventa as margen_30p_gespreventa',
                 'gpv.utilidad_bruta_gespreventa as utilidad_bruta_gespreventa',
                 'gpv.utilidad_neto_gespreventa as utilidad_neto_gespreventa',
                 'u.name as user_name',
                 DB::raw('GREATEST(gpv.created_at, gpv.updated_at) as fecha'),
                 'gpv.listo_venta as listo_venta',
                 'pte.nombre as tipoentrada'
             )
             ->join('alm__almacens as aa', 'aa.id', '=', 'pivot.id_tienda_almacen')
             ->join('alm__ingreso_producto as ai', 'ai.id', '=', 'pivot.id_ingreso')
             ->join('prod__productos as pp', 'pp.id', '=', 'ai.id_prod_producto')
             ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
             ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'ai.id_tipoentrada')
             ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
             ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
             ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
             ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
             ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
             ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
          
             ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
             ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
             ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
             ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
             ;
             $tienda = DB::table('pivot__modulo_tienda_almacens as pivot')
             ->select(
                'pivot.id as id',
                 'gpv.id as gpv_id',
                 'tt.codigo as cod',
                 'ti.id as id_ingreso',
                 'ti.lote as lote',
                 'ti.stock_ingreso as stock_ingreso',
                 'ti.cantidad as cantidad_ingreso',
                 'ti.created_at as fecha_ingreso',
                 'ti.fecha_vencimiento as fecha_vencimiento',
                 'pp.id as id_producto',
                 'pp.nombre as nombre',
                 'pp.codigo as codigo_producto',
                 'l.nombre as nombre_linea',
                 'ass.id as id_sucursal',
                 'ass.razon_social as razon_social',
                 
                 'ti.idtienda as id_tienda',
               
                 DB::raw('NULL as id_almacen'),
                 DB::raw("CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                     ELSE NULL 
                 END AS leyenda"),
                 DB::raw("CASE 
                 WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                 WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                 WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                 ELSE NULL 
             END AS iddispenserEnvase"),
             DB::raw("CASE 
                 WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                 WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                 WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                 ELSE NULL 
             END AS cantidadEnvase"),
             DB::raw("CASE 
                 WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                 WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                 WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                 ELSE NULL 
             END AS idformafarmaceuticaEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.preciolistaprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.preciolistasecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.preciolistaterciario, '')) 
             ELSE NULL 
             END AS preciolistaEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.precioventaprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.precioventasecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.precioventaterciario, '')) 
             ELSE NULL 
             END AS precioventaEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiempopedidoprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiempopedidosecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiempopedidoterciario, '')) 
             ELSE NULL 
             END AS tiempopedidoEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.metodoabcprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.metodoabcsecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.metodoabcterciario, '')) 
             ELSE NULL 
             END AS metodoabcEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
             ELSE NULL 
             END AS tiendaEnvase"),
             DB::raw("CASE 
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
             ELSE NULL 
             END AS almacenEnvase"),
             DB::raw("CASE
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), ''), '')
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), ''), '')
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), ''), '')
                ELSE NULL
                END AS costocompraEnvase"),
            DB::raw("CASE
                WHEN ti.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ti.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ti.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),    
                 
                 'gpv.precio_lista_gespreventa as precio_lista_gespreventa',
                 'gpv.precio_venta_gespreventa as precio_venta_gespreventa',
                 'gpv.cantidad_envase_gespreventa as cantidad_envase_gespreventa',
                 'gpv.costo_compra_gespreventa as costo_compra_gespreventa',
                 'gpv.margen_20p_gespreventa as margen_20p_gespreventa',
                 'gpv.margen_30p_gespreventa as margen_30p_gespreventa',
                 'gpv.utilidad_bruta_gespreventa as utilidad_bruta_gespreventa',
                 'gpv.utilidad_neto_gespreventa as utilidad_neto_gespreventa',
                 'u.name as user_name',
                 DB::raw('GREATEST(gpv.created_at, gpv.updated_at) as fecha'),
                 'gpv.listo_venta as listo_venta',
                 'pte.nombre as tipoentrada'
             )

             ->join('tda__tiendas as tt', 'tt.id', '=', 'pivot.id_tienda_almacen')
             ->join('tda__ingreso_productos as ti', 'ti.id', '=', 'pivot.id_ingreso')
             ->join('prod__productos as pp', 'pp.id', '=', 'ti.id_prod_producto')

             ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
             ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'ti.id_tipoentrada')
           
             ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
             ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
             ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
             ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
             ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
             ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
             ->join('adm__sucursals as ass', 'ass.id', '=', 'ti.idtienda')
             ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
            
             ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
             ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
            ;
        
            $queryCombinacion = $almacen->where('pivot.tipo', '=', 'ALM')->where('aa.codigo', '=', $bus)
            ->unionAll($tienda->where('pivot.tipo', '=', 'TDA')->where('tt.codigo','=',$bus)); 

           
            $queryCombinacion = $queryCombinacion -> orderByRaw('id DESC')->paginate(15); 
           
            return [
                'pagination' => [
                    'total'         =>    $queryCombinacion->total(),
                    'current_page'  =>    $queryCombinacion->currentPage(),
                    'per_page'      =>    $queryCombinacion->perPage(),
                    'last_page'     =>    $queryCombinacion->lastPage(),
                    'from'          =>    $queryCombinacion->firstItem(),
                    'to'            =>    $queryCombinacion->lastItem(),
                ],
                'queryCombinacion' => $queryCombinacion
            ];
        }
        } else {
    /************************************ trabajo con lista ****************/
         
    $buscararray = array();
    $bus = $request->query('buscarAlmTdn');
    if (!empty($request->buscar)) {          
        $buscararray = explode(" ", $request->buscar);           
        $valor = sizeof($buscararray);       
        if ($valor > 0) {
            $sqls = '';
            foreach ($buscararray as $valor) {                   
                if (empty($sqls)) {    
                                    
                    $sqls = "(
                            pp.codigo like '%" . $valor . "%' 
                            or pl.nombre like '%" . $valor . "%'
                            or pp.nombre like '%" . $valor . "%' 
                            or u.name like '%" . $valor . "%'
                        
                           )";
                } else {
                  
                    $sqls .= "and (
                         pp.codigo like '%" . $valor . "%' 
                            or pl.nombre like '%" . $valor . "%'
                            or pp.nombre like '%" . $valor . "%' 
                            or u.name like '%" . $valor . "%'
                   )";
                }
            } 
            // query start 
        
            $almacen = DB::table('pivot__modulo_tienda_almacens AS pivot')
            ->select('pivot.id AS id',
                'gpv.id AS gpv_id',
                'aa.codigo AS cod',
                'ai.id AS id_ingreso',
                'ai.lote AS lote',
                'ai.stock_ingreso AS stock_ingreso',
                'ai.cantidad AS cantidad_ingreso',
                'ai.created_at AS fecha_ingreso',
                'ai.fecha_vencimiento AS fecha_vencimiento',
                'pp.id AS id_producto',
                'pp.nombre AS nombre',
                'pp.codigo AS codigo_producto',
                'pl.nombre AS nombre_linea',
                'ass.id AS id_sucursal',
                'ass.razon_social AS razon_social',
                DB::raw('NULL AS id_tienda'),
                'ai.idalmacen AS id_almacen',
                'prp.envase AS envase',
                'prp.id AS id_lista',
                'plist.id AS plist_id',
                'plist.nombre_lista AS nombre_lista',
                'plist.codigo AS cod_lista',
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                    WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                    WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                    ELSE NULL 
                END AS leyenda"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                    WHEN ai.envase = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                    WHEN ai.envase = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                    ELSE NULL 
                END AS iddispenserEnvase"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                    WHEN ai.envase = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                    WHEN ai.envase = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                    ELSE NULL 
                END AS cantidadEnvase"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                    WHEN ai.envase = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                    WHEN ai.envase = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                    ELSE NULL 
                END AS idformafarmaceuticaEnvase"),
                'prp.preciolista AS preciolistaEnvase',
                'prp.precioventa AS precioventaEnvase',
                'prp.tiempopedido AS tiempopedidoEnvase',
                'prp.metodoabc AS metodoabcEnvase',
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                    WHEN ai.envase = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                    WHEN ai.envase = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                    ELSE NULL 
                END AS tiendaEnvase"),
                DB::raw("CASE 
                    WHEN ai.envase = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                    WHEN ai.envase = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                    WHEN ai.envase = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                    ELSE NULL 
                END AS almacenEnvase"),
                DB::raw("CASE
                    WHEN ai.envase = 'primario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadprimario, 2), '')
                    WHEN ai.envase = 'secundario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadsecundario, 2), '')
                    WHEN ai.envase = 'terciario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadterciario, 2), '')
                    ELSE NULL
                END AS costocompraEnvase"),
                DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ai.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ai.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
                'gpv.precio_lista_gespreventa AS precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa AS precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa AS cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa AS costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa AS margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa AS margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa AS utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa AS utilidad_neto_gespreventa',
                'u.name AS user_name',
                DB::raw("GREATEST(gpv.created_at, gpv.updated_at) AS fecha"),
                'gpv.listo_venta AS listo_venta',
                'pte.nombre AS tipoentrada')
            ->join('prod__registro_pre_x_lists AS prp', 'prp.id_ingreso', '=', 'pivot.id_ingreso')
            ->join('alm__almacens AS aa', 'aa.id', '=', 'pivot.id_tienda_almacen')
            ->join('prod__productos AS pp', 'pp.id', '=', 'prp.id_producto')
            ->join('prod__lineas AS pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__listas AS plist', 'plist.id', '=', 'prp.id_lista')
            ->join('alm__ingreso_producto AS ai', 'ai.id', '=', 'pivot.id_ingreso')
            ->join('prod__tipo_entradas AS pte', 'pte.id', '=', 'ai.id_tipoentrada')
            ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('adm__sucursals AS ass', 'ass.id', '=', 'ai.idalmacen')
            ->join('prod__lineas AS l', 'l.id', '=', 'pp.idlinea')
            ->leftJoin('ges_pre__venta_listas AS gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
            ->leftJoin('users AS u', 'u.id', '=', 'gpv.idusuario')
            ;
            $tienda = DB::table('pivot__modulo_tienda_almacens as pivot')
            ->select(
                'pivot.id AS id',
                'gpv.id AS gpv_id',
                'tt.codigo AS cod',
                'ti.id AS id_ingreso',
                'ti.lote AS lote',
                'ti.stock_ingreso AS stock_ingreso',
                'ti.cantidad AS cantidad_ingreso',
                'ti.created_at AS fecha_ingreso',
                'ti.fecha_vencimiento AS fecha_vencimiento',
                'pp.id AS id_producto',
                'pp.nombre AS nombre',
                'pp.codigo AS codigo_producto',
                'pl.nombre AS nombre_linea',
                'ass.id AS id_sucursal',
                'ass.razon_social AS razon_social',
                'ti.idtienda AS id_tienda',
                DB::raw('null AS id_almacen'),
                'prp.envase as envase',
                'prp.id as id_lista',
                'plist.id as plist_id',
                'plist.nombre_lista as nombre_lista',
                'plist.codigo as cod_lista',
                DB::raw("CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                        ELSE NULL 
                    END AS leyenda"
                ),
                DB::raw("CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                        ELSE NULL 
                    END AS iddispenserEnvase"
                ),
                DB::raw("
                    CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                        ELSE NULL 
                    END AS cantidadEnvase"
                ),
                DB::raw("CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                        ELSE NULL 
                    END AS idformafarmaceuticaEnvase"
                ),
                'prp.preciolista as preciolistaEnvase',
                'prp.precioventa as precioventaEnvase',
                'prp.tiempopedido as tiempopedidoEnvase',
                'prp.metodoabc as metodoabcEnvase',
                DB::raw("CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
                        ELSE NULL 
                    END AS tiendaEnvase"
                ),
                DB::raw("
                    CASE 
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
                        ELSE NULL 
                    END AS almacenEnvase"
                ),
                DB::raw("CASE
                        WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadprimario, 2), ''), '')
                        WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadsecundario, 2), ''), '')
                        WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadterciario, 2), ''), '')
                        ELSE NULL
                    END AS costocompraEnvase"
                ),
                DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ai.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ai.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
                'gpv.precio_lista_gespreventa AS precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa AS precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa AS cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa AS costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa AS margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa AS margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa AS utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa AS utilidad_neto_gespreventa',
                'u.name AS user_name',
                DB::raw("GREATEST(gpv.created_at, gpv.updated_at) AS fecha"),
                'gpv.listo_venta AS listo_venta',
                'pte.nombre AS tipoentrada'
            )
            ->join('prod__registro_pre_x_lists AS prp', 'prp.id_ingreso', '=', 'pivot.id_ingreso')
            ->join('tda__tiendas AS tt', 'tt.id', '=', 'pivot.id_tienda_almacen')
            ->join('prod__productos AS pp', 'pp.id', '=', 'prp.id_producto')
            ->join('prod__lineas AS pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
            ->join('tda__ingreso_productos as ti', 'ti.id', '=', 'pivot.id_ingreso')
            ->join('prod__tipo_entradas AS pte', 'pte.id', '=', 'ti.id_tipoentrada')
    ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
    ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
    ->join('adm__sucursals AS ass', 'ass.id', '=', 'ti.idtienda')
    ->leftJoin('prod__lineas AS l', 'l.id', '=', 'pp.idlinea')
    ->leftJoin('ges_pre__venta_listas AS gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
    ->leftJoin('users AS u', 'u.id', '=', 'gpv.idusuario')
           ;
           $queryCombinacion = $almacen->where('prp.id_lista', '=', $request->lista)->whereRaw($sqls)
           ->unionAll($tienda->where('prp.id_lista', '=', $request->lista)->whereRaw($sqls)); 
           $queryCombinacion = $queryCombinacion -> orderByRaw('id DESC')->paginate(15); 
        }    
        return
        [
            'pagination' =>
            [
                'total'         =>    $queryCombinacion->total(),
                'current_page'  =>    $queryCombinacion->currentPage(),
                'per_page'      =>    $queryCombinacion->perPage(),
                'last_page'     =>    $queryCombinacion->lastPage(),
                'from'          =>    $queryCombinacion->firstItem(),
                'to'            =>    $queryCombinacion->lastItem(),
            ],
            'queryCombinacion' => $queryCombinacion,
        ];
    } else {
         // query start 
        
         $almacen = DB::table('pivot__modulo_tienda_almacens AS pivot')
         ->select('pivot.id AS id',
             'gpv.id AS gpv_id',
             'aa.codigo AS cod',
             'ai.id AS id_ingreso',
             'ai.lote AS lote',
             'ai.stock_ingreso AS stock_ingreso',
             'ai.cantidad AS cantidad_ingreso',
             'ai.created_at AS fecha_ingreso',
             'ai.fecha_vencimiento AS fecha_vencimiento',
             'pp.id AS id_producto',
             'pp.nombre AS nombre',
             'pp.codigo AS codigo_producto',
             'pl.nombre AS nombre_linea',
             'ass.id AS id_sucursal',
             'ass.razon_social AS razon_social',
             DB::raw('NULL AS id_tienda'),
             'ai.idalmacen AS id_almacen',
             'prp.envase AS envase',
             'prp.id AS id_lista',
             'plist.id AS plist_id',
             'plist.nombre_lista AS nombre_lista',
             'plist.codigo AS cod_lista',
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                 WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                 WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                 ELSE NULL 
             END AS leyenda"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                 WHEN ai.envase = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                 WHEN ai.envase = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                 ELSE NULL 
             END AS iddispenserEnvase"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                 WHEN ai.envase = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                 WHEN ai.envase = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                 ELSE NULL 
             END AS cantidadEnvase"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                 WHEN ai.envase = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                 WHEN ai.envase = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                 ELSE NULL 
             END AS idformafarmaceuticaEnvase"),
             'prp.preciolista AS preciolistaEnvase',
             'prp.precioventa AS precioventaEnvase',
             'prp.tiempopedido AS tiempopedidoEnvase',
             'prp.metodoabc AS metodoabcEnvase',
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                 WHEN ai.envase = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                 WHEN ai.envase = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                 ELSE NULL 
             END AS tiendaEnvase"),
             DB::raw("CASE 
                 WHEN ai.envase = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                 WHEN ai.envase = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                 WHEN ai.envase = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                 ELSE NULL 
             END AS almacenEnvase"),
             DB::raw("CASE
                 WHEN ai.envase = 'primario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadprimario, 2), '')
                 WHEN ai.envase = 'secundario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadsecundario, 2), '')
                 WHEN ai.envase = 'terciario' THEN COALESCE(FORMAT(prp.preciolista / pp.cantidadterciario, 2), '')
                 ELSE NULL
             END AS costocompraEnvase"),
             DB::raw("CASE
                WHEN ai.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ai.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ai.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
             'gpv.precio_lista_gespreventa AS precio_lista_gespreventa',
             'gpv.precio_venta_gespreventa AS precio_venta_gespreventa',
             'gpv.cantidad_envase_gespreventa AS cantidad_envase_gespreventa',
             'gpv.costo_compra_gespreventa AS costo_compra_gespreventa',
             'gpv.margen_20p_gespreventa AS margen_20p_gespreventa',
             'gpv.margen_30p_gespreventa AS margen_30p_gespreventa',
             'gpv.utilidad_bruta_gespreventa AS utilidad_bruta_gespreventa',
             'gpv.utilidad_neto_gespreventa AS utilidad_neto_gespreventa',
             'u.name AS user_name',
             DB::raw("GREATEST(gpv.created_at, gpv.updated_at) AS fecha"),
             'gpv.listo_venta AS listo_venta',
             'pte.nombre AS tipoentrada')
         ->join('prod__registro_pre_x_lists AS prp', 'prp.id_ingreso', '=', 'pivot.id_ingreso')
         ->join('alm__almacens AS aa', 'aa.id', '=', 'pivot.id_tienda_almacen')
         ->join('prod__productos AS pp', 'pp.id', '=', 'prp.id_producto')
         ->join('prod__lineas AS pl', 'pl.id', '=', 'pp.idlinea')
         ->join('prod__listas AS plist', 'plist.id', '=', 'prp.id_lista')
         ->join('alm__ingreso_producto AS ai', 'ai.id', '=', 'pivot.id_ingreso')
         ->join('prod__tipo_entradas AS pte', 'pte.id', '=', 'ai.id_tipoentrada')
         ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
         ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
         ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
         ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
         ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
         ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
         ->join('adm__sucursals AS ass', 'ass.id', '=', 'ai.idalmacen')
         ->join('prod__lineas AS l', 'l.id', '=', 'pp.idlinea')
         ->leftJoin('ges_pre__venta_listas AS gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
         ->leftJoin('users AS u', 'u.id', '=', 'gpv.idusuario')
         ;
         $tienda = DB::table('pivot__modulo_tienda_almacens as pivot')
         ->select(
             'pivot.id AS id',
             'gpv.id AS gpv_id',
             'tt.codigo AS cod',
             'ti.id AS id_ingreso',
             'ti.lote AS lote',
             'ti.stock_ingreso AS stock_ingreso',
             'ti.cantidad AS cantidad_ingreso',
             'ti.created_at AS fecha_ingreso',
             'ti.fecha_vencimiento AS fecha_vencimiento',
             'pp.id AS id_producto',
             'pp.nombre AS nombre',
             'pp.codigo AS codigo_producto',
             'pl.nombre AS nombre_linea',
             'ass.id AS id_sucursal',
             'ass.razon_social AS razon_social',
             'ti.idtienda AS id_tienda',
             DB::raw('null AS id_almacen'),
             'prp.envase as envase',
             'prp.id as id_lista',
             'plist.id as plist_id',
             'plist.nombre_lista as nombre_lista',
             'plist.codigo as cod_lista',
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                     ELSE NULL 
                 END AS leyenda"
             ),
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.iddispenserprimario, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.iddispensersecundario, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.iddispenserterciario, '')) 
                     ELSE NULL 
                 END AS iddispenserEnvase"
             ),
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.cantidadprimario, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.cantidadsecundario, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.cantidadterciario, '')) 
                     ELSE NULL 
                 END AS cantidadEnvase"
             ),
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaprimario, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.idformafarmaceuticasecundario, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.idformafarmaceuticaterciario, '')) 
                     ELSE NULL 
                 END AS idformafarmaceuticaEnvase"
             ),
             'prp.preciolista as preciolistaEnvase',
             'prp.precioventa as precioventaEnvase',
             'prp.tiempopedido as tiempopedidoEnvase',
             'prp.metodoabc as metodoabcEnvase',
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.tiendaprimario, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.tiendasecundario, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.tiendaterciario, '')) 
                     ELSE NULL 
                 END AS tiendaEnvase"
             ),
             DB::raw("
                 CASE 
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.almacenprimario, '')) 
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.almacensecundario, '')) 
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.almacenterciario, '')) 
                     ELSE NULL 
                 END AS almacenEnvase"
             ),
             DB::raw("
                 CASE
                     WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadprimario, 2), ''), '')
                     WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadsecundario, 2), ''), '')
                     WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(FORMAT(prp.preciolista / pp.cantidadterciario, 2), ''), '')
                     ELSE NULL
                 END AS costocompraEnvase"
             ),
             DB::raw(" CASE
                WHEN ti.envase = 'primario' THEN CONCAT(IFNULL(pp.cantidadprimario,''))
                WHEN ti.envase = 'secundario' THEN CONCAT(IFNULL(pp.cantidadsecundario,''))
                WHEN ti.envase = 'terciario' THEN CONCAT(IFNULL(pp.cantidadterciario,''))
                ELSE NULL
            END AS catidadEnvase"),  
             'gpv.precio_lista_gespreventa AS precio_lista_gespreventa',
             'gpv.precio_venta_gespreventa AS precio_venta_gespreventa',
             'gpv.cantidad_envase_gespreventa AS cantidad_envase_gespreventa',
             'gpv.costo_compra_gespreventa AS costo_compra_gespreventa',
             'gpv.margen_20p_gespreventa AS margen_20p_gespreventa',
             'gpv.margen_30p_gespreventa AS margen_30p_gespreventa',
             'gpv.utilidad_bruta_gespreventa AS utilidad_bruta_gespreventa',
             'gpv.utilidad_neto_gespreventa AS utilidad_neto_gespreventa',
             'u.name AS user_name',
             DB::raw("GREATEST(gpv.created_at, gpv.updated_at) AS fecha"),
             'gpv.listo_venta AS listo_venta',
             'pte.nombre AS tipoentrada'
         )
         ->join('prod__registro_pre_x_lists AS prp', 'prp.id_ingreso', '=', 'pivot.id_ingreso')
         ->join('tda__tiendas AS tt', 'tt.id', '=', 'pivot.id_tienda_almacen')
         ->join('prod__productos AS pp', 'pp.id', '=', 'prp.id_producto')
         ->join('prod__lineas AS pl', 'pl.id', '=', 'pp.idlinea')
         ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
         ->join('tda__ingreso_productos as ti', 'ti.id', '=', 'pivot.id_ingreso')
         ->join('prod__tipo_entradas AS pte', 'pte.id', '=', 'ti.id_tipoentrada')
 ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
 ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
 ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
 ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
 ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
 ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
 ->join('adm__sucursals AS ass', 'ass.id', '=', 'ti.idtienda')
 ->leftJoin('prod__lineas AS l', 'l.id', '=', 'pp.idlinea')
 ->leftJoin('ges_pre__venta_listas AS gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
 ->leftJoin('users AS u', 'u.id', '=', 'gpv.idusuario')
        ;
        $queryCombinacion = $almacen->where('prp.id_lista', '=', $request->lista)
        ->unionAll($tienda->where('prp.id_lista', '=', $request->lista)); 
        $queryCombinacion = $queryCombinacion -> orderByRaw('id DESC')->paginate(15); 
        return [
            'pagination' => [
                'total'         =>    $queryCombinacion->total(),
                'current_page'  =>    $queryCombinacion->currentPage(),
                'per_page'      =>    $queryCombinacion->perPage(),
                'last_page'     =>    $queryCombinacion->lastPage(),
                'from'          =>    $queryCombinacion->firstItem(),
                'to'            =>    $queryCombinacion->lastItem(),
            ],
            'queryCombinacion' => $queryCombinacion
        ];
    }
        }
       
         

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            try {
                if ($request->id_lista=="x") {
                    $precioVentaProducto = new GesPre_Venta2();
                    $precioVentaProducto->codigo=$request->codigo;
                    $precioVentaProducto->id_table_ingreso_tienda_almacen = $request->id_table_ingreso_tienda_almacen;
                    $precioVentaProducto->tienda = $request->tienda;
                    $precioVentaProducto->almacen = $request->almacen;
                    $precioVentaProducto->precio_lista_gespreventa = $request->precio_lista_gespreventa;
                    $precioVentaProducto->precio_venta_gespreventa = $request->precio_venta_gespreventa;
                    $precioVentaProducto->cantidad_envase_gespreventa = $request->cantidad_envase_gespreventa;
                    //$precioVentaProducto->cantidad_envase_gespreventa = preg_match('/[a-z]/',strtolower($request->cantidad_envase_gespreventa)) == 1 ? 1 :$request->cantidad_envase_gespreventa;
                    $precioVentaProducto->costo_compra_gespreventa = $request->costo_compra_gespreventa;
                    $precioVentaProducto->margen_20p_gespreventa = $request->margen_20p_gespreventa;
                    $precioVentaProducto->margen_30p_gespreventa = $request->margen_30p_gespreventa;
                    $precioVentaProducto->utilidad_bruta_gespreventa = $request->utilidad_bruta_gespreventa; 
                    $precioVentaProducto->utilidad_neto_gespreventa = $request->utilidad_neto_gespreventa;
                    $precioVentaProducto->listo_venta = 1;
                    $precioVentaProducto->idusuario = auth()->user()->id;
                    $precioVentaProducto->id_lista=$request->id_lista;
                    $precioVentaProducto->save();
                }else{
                    $precioVentaProducto = new GesPre_Venta_lista();
                    $precioVentaProducto->codigo=$request->codigo;
                    $precioVentaProducto->id_table_ingreso_tienda_almacen = $request->id_table_ingreso_tienda_almacen;
                    $precioVentaProducto->tienda = $request->tienda;
                    $precioVentaProducto->almacen = $request->almacen;
                    $precioVentaProducto->precio_lista_gespreventa = $request->precio_lista_gespreventa;
                    $precioVentaProducto->precio_venta_gespreventa = $request->precio_venta_gespreventa;
                    $precioVentaProducto->cantidad_envase_gespreventa = $request->cantidad_envase_gespreventa;
                    //$precioVentaProducto->cantidad_envase_gespreventa = preg_match('/[a-z]/',strtolower($request->cantidad_envase_gespreventa)) == 1 ? 1 :$request->cantidad_envase_gespreventa;
                    $precioVentaProducto->costo_compra_gespreventa = $request->costo_compra_gespreventa;
                    $precioVentaProducto->margen_20p_gespreventa = $request->margen_20p_gespreventa;
                    $precioVentaProducto->margen_30p_gespreventa = $request->margen_30p_gespreventa;
                    $precioVentaProducto->utilidad_bruta_gespreventa = $request->utilidad_bruta_gespreventa; 
                    $precioVentaProducto->utilidad_neto_gespreventa = $request->utilidad_neto_gespreventa;
                    $precioVentaProducto->listo_venta = 1;
                    $precioVentaProducto->idusuario = auth()->user()->id;
                    $precioVentaProducto->id_lista=$request->id_lista;
                    $precioVentaProducto->save(); 
                }
                
            } catch (\Throwable $th) {
                return response()->json(['error' => $th->getMessage()],500);
            }
         
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GesPre_Venta2 $gesPre_Venta2)
    {
        try {
            //$precioVentaProducto = GesPre_Venta2 :: firstWhere('id',$request->id);
            $precioVentaProducto = GesPre_Venta2::where('codigo', $request->codigo)
            ->where('id', $request->id)
            ->first();
            // $precioVentaProducto->codigo=$request->codigo;
           // $precioVentaProducto->id_table_ingreso_tienda_almacen = $request->id_table_ingreso_tienda_almacen;
          //  $precioVentaProducto->tienda = $request->tienda;
          //  $precioVentaProducto->almacen = $request->almacen;
            $precioVentaProducto->precio_lista_gespreventa = $request->precio_lista_gespreventa;
            $precioVentaProducto->precio_venta_gespreventa = $request->precio_venta_gespreventa;
            $precioVentaProducto->cantidad_envase_gespreventa = $request->cantidad_envase_gespreventa;
            //$precioVentaProducto->cantidad_envase_gespreventa = preg_match('/[a-z]/',strtolower($request->cantidad_envase_gespreventa)) == 1 ? 1 :$request->cantidad_envase_gespreventa;
            $precioVentaProducto->costo_compra_gespreventa = $request->costo_compra_gespreventa;
            $precioVentaProducto->margen_20p_gespreventa = $request->margen_20p_gespreventa;
            $precioVentaProducto->margen_30p_gespreventa = $request->margen_30p_gespreventa;
            $precioVentaProducto->utilidad_bruta_gespreventa = $request->utilidad_bruta_gespreventa; 
            $precioVentaProducto->utilidad_neto_gespreventa = $request->utilidad_neto_gespreventa;
            $precioVentaProducto->listo_venta = 1;
            $precioVentaProducto->idusuario = auth()->user()->id;
            $precioVentaProducto->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
        
       // return $request;
    }
    public function listarSucursal(){  
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as lista_id_almacen_id_tienda')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
        ->where('tda__tiendas.activo','=',1)
        ->where('adm__sucursals.activo','=',1)
        ;

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->where('aa.activo','=',1)
        ->where('ass.activo','=',1)
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'),'aa.id  as lista_id_almacen_id_tienda');

    $result = $tiendas->unionAll($almacenes)->get();    
    return $result;
           
     }
     public function listarLista(Request $request){
        $resultado = DB::table('prod__registro_pre_x_lists as prp')
        ->select(DB::raw('MAX(prp.id) AS id'), 'plist.id AS id_lista', DB::raw('MAX(plist.nombre_lista) AS nombre_lista'), 'prp.cod_tda_alm as cod_tda_alm', 'prp.tipo_tda_alm AS tipo_tda_alm','plist.codigo AS codigo_lista')
        ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
        ->where('prp.cod_tda_alm', '=', $request->codigo) // Corregido aquí
        ->where('prp.activo', '=', 1)
        ->where('plist.activo', '=', 1)
        ->groupBy('plist.id', 'cod_tda_alm', 'prp.tipo_tda_alm','plist.codigo')
        ->get();
        return $resultado;
    }

}
