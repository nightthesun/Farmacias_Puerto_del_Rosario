<?php

namespace App\Http\Controllers;

use App\Models\GesPre_Venta2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Configuration\Merger;

class GesPreVenta2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
                                or pp.codigo like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%'
                                or pp.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%'
                            
                               )";
                    } else {
                        $sqls .= "and (
                             or pp.codigo like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%'
                                or pp.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%'
                       )";
                    }
                } 
                // query start 
                $almacen = DB::table('prod__productos as pp')
                ->select(
                    'ai.id as id',
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
                    'gpv.listo_venta as listo_venta'
                )
                ->join('alm__ingreso_producto as ai', 'pp.id', '=', 'ai.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                ->join('alm__almacens as aa', 'aa.id', '=', 'ai.idalmacen')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
                ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'ai.id')
                ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
                ;
                $tienda = DB::table('prod__productos as pp')
                ->select(
                    'ti.id as id',
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
                    'gpv.listo_venta as listo_venta'
                )
                ->join('tda__ingreso_productos as ti', 'pp.id', '=', 'ti.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'ti.idtienda')
                ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
                ->join('tda__tiendas as tt', 'tt.id', '=', 'ti.idtienda')
                ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'ti.id')
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
             $almacen = DB::table('prod__productos as pp')
             ->select(
                 'ai.id as id',
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
                 'gpv.listo_venta as listo_venta'
             )
             ->join('alm__ingreso_producto as ai', 'pp.id', '=', 'ai.id_prod_producto')
             ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
             ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
             ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
             ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
             ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
             ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
             ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
             ->join('alm__almacens as aa', 'aa.id', '=', 'ai.idalmacen')
             ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
             ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
             ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'ai.id')
             ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
             ;
             $tienda = DB::table('prod__productos as pp')
             ->select(
                 'ti.id as id',
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
                 'gpv.listo_venta as listo_venta'
             )
             ->join('tda__ingreso_productos as ti', 'pp.id', '=', 'ti.id_prod_producto')
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
             ->join('tda__tiendas as tt', 'tt.id', '=', 'ti.idtienda')
             ->leftJoin('ges_pre__venta2s as gpv', 'gpv.id_table_ingreso_tienda_almacen', '=', 'ti.id')
             ->leftJoin('users as u', 'u.id', '=', 'gpv.idusuario')
            ;
            $queryCombinacion = $almacen->where('aa.codigo', '=', $bus)
            ->unionAll($tienda->where('tt.codigo','=',$bus)); 
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GesPre_Venta2 $gesPre_Venta2)
    {
        //
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

}