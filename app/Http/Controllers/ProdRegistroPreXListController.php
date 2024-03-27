<?php

namespace App\Http\Controllers;

use App\Models\Prod_Registro_pre_x_list;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdRegistroPreXListController extends Controller
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
                                pp.codigo like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%' 
                                or pp.nombre like '%" . $valor . "%'
                                or prp.envase like '%" . $valor . "%' 
                                or plist.nombre_lista like '%" . $valor . "%'
                              
                             
                               )";
                    } else {
                        $sqls .= "and (
                            pp.codigo like '%" . $valor . "%' 
                            or pl.nombre like '%" . $valor . "%'
                            or pp.nombre like '%" . $valor . "%' 
                            or prp.envase like '%" . $valor . "%'
                            or plist.nombre_lista like '%" . $valor . "%'
                     
                       )";
                    }
                }
                // codigo query
                $tienda = DB::table('prod__registro_pre_x_lists as prp')
                ->select('prp.id as id', 'prp.envase as envase', 'prp.id_producto as id_producto', 'pp.codigo as codigo_prod', 'pp.nombre as name_prod','ti.cantidad as cantidad','ti.lote as lote',
                DB::raw('CASE 
                                WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pd_1.nombre, ""))
                                WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pd_2.nombre, ""))
                                WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pd_3.nombre, ""))
                                ELSE NULL 
                            END AS cantidadEnvase'),  
                DB::raw('CASE 
                                WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_1.nombre, ""), " x ", IFNULL(pp.cantidadprimario, ""), " ", IFNULL(ff_1.nombre, ""))
                                WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_2.nombre, ""), " x ", IFNULL(pp.cantidadsecundario, ""), " ", IFNULL(ff_2.nombre, ""))
                                WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_3.nombre, ""), " x ", IFNULL(pp.cantidadterciario, ""), " ", IFNULL(ff_3.nombre, ""))
                                ELSE NULL 
                            END AS leyenda'),
                    'prp.tipo_tda_alm as tipo_tienda_almacen', 'prp.id_tda_alm as id_tda_alm', 'prp.cod_tda_alm  as cod_tda_alm', 'ass.razon_social as razon_social', 'prp.preciolista as preciolista', 'prp.precioventa as precioventa',
                    'prp.tiempopedido as tiempopedido1', 'prp.metodoabc as metodoabc', 'prp.activo as activo', 'prp.estado as estado', 'u.id as id_name', 'u.name as user_name', 'pl.nombre as linea_name', 'plist.id as id_lista', 'plist.nombre_lista as nombre_lista','ar.nombre as rubro_name')
                ->join('prod__productos as pp', 'pp.id', '=', 'prp.id_producto')
                ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
                ->join('tda__tiendas as tt', 'tt.codigo', '=', 'prp.cod_tda_alm')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('users as u', 'u.id', '=', 'prp.id_usuario')
                ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                ->join('adm__rubros as ar', 'ar.id', '=', 'pp.idrubro')             
                ->join('tda__ingreso_productos as ti','ti.id', '=','prp.id_ingreso') 
                ->where('prp.cod_tda_alm', '=', $bus)
                ->whereRaw($sqls)
                ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
            ->orderBy('prp.id', 'desc')
                ;
                $almacen = DB::table('prod__registro_pre_x_lists as prp')
    ->select('prp.id as id', 'prp.envase as envase', 'prp.id_producto as id_producto', 'pp.codigo as codigo_prod', 'pp.nombre as name_prod','ai.cantidad','ai.lote',
    DB::raw('CASE 
    WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pd_1.nombre, ""))
    WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pd_2.nombre, ""))
    WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pd_3.nombre, ""))
    ELSE NULL 
END AS cantidadEnvase'),    
    DB::raw('CASE 
                    WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_1.nombre, ""), " x ", IFNULL(pp.cantidadprimario, ""), " ", IFNULL(ff_1.nombre, ""))
                    WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_2.nombre, ""), " x ", IFNULL(pp.cantidadsecundario, ""), " ", IFNULL(ff_2.nombre, ""))
                    WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_3.nombre, ""), " x ", IFNULL(pp.cantidadterciario, ""), " ", IFNULL(ff_3.nombre, ""))
                    ELSE NULL 
                END AS leyenda'),
        'prp.tipo_tda_alm as tipo_tienda_almacen', 'prp.id_tda_alm as id_tda_alm', 'prp.cod_tda_alm  as cod_tda_alm', 'ass.razon_social as razon_social', 'prp.preciolista as preciolista', 'prp.precioventa as precioventa',
        'prp.tiempopedido as tiempopedido1', 'prp.metodoabc as metodoabc', 'prp.activo as activo', 'prp.estado as estado', 'u.id as id_name', 'u.name as user_name', 'pl.nombre as linea_name', 'plist.id as id_lista', 'plist.nombre_lista as nombre_lista','ar.nombre as rubro_name')
    ->join('prod__productos as pp', 'pp.id', '=', 'prp.id_producto')
    ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
    ->join('alm__almacens as aa', 'aa.codigo', '=', 'prp.cod_tda_alm')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('users as u', 'u.id', '=', 'prp.id_usuario')
    ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
    ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
    ->join('adm__rubros as ar', 'ar.id', '=', 'pp.idrubro')
    ->join('alm__ingreso_producto as ai','ai.id', '=','prp.id_ingreso') 
                ->where('prp.cod_tda_alm', '=', $bus)
                ->whereRaw($sqls)
                ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
                
                ->orderBy('prp.id', 'desc')
                ;
            $resultadocombinado= $tienda->unionAll($almacen)->paginate(10);    
            }
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultadocombinado->total(),
                            'current_page'  =>    $resultadocombinado->currentPage(),
                            'per_page'      =>    $resultadocombinado->perPage(),
                            'last_page'     =>    $resultadocombinado->lastPage(),
                            'from'          =>    $resultadocombinado->firstItem(),
                            'to'            =>    $resultadocombinado->lastItem(),
                        ] ,
                    'resultadocombinado'=>$resultadocombinado,
            ];
        }  else{
            $tienda = DB::table('prod__registro_pre_x_lists as prp')
            ->select('prp.id as id', 'prp.envase as envase', 'prp.id_producto as id_producto', 'pp.codigo as codigo_prod', 'pp.nombre as name_prod','ti.cantidad','ti.lote',
            DB::raw('CASE 
            WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pd_1.nombre, ""))
            WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pd_2.nombre, ""))
            WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pd_3.nombre, ""))
            ELSE NULL 
        END AS cantidadEnvase'),      
            DB::raw('CASE 
                            WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_1.nombre, ""), " x ", IFNULL(pp.cantidadprimario, ""), " ", IFNULL(ff_1.nombre, ""))
                            WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_2.nombre, ""), " x ", IFNULL(pp.cantidadsecundario, ""), " ", IFNULL(ff_2.nombre, ""))
                            WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_3.nombre, ""), " x ", IFNULL(pp.cantidadterciario, ""), " ", IFNULL(ff_3.nombre, ""))
                            ELSE NULL 
                        END AS leyenda'),
                'prp.tipo_tda_alm as tipo_tienda_almacen', 'prp.id_tda_alm as id_tda_alm', 'prp.cod_tda_alm  as cod_tda_alm', 'ass.razon_social as razon_social', 'prp.preciolista as preciolista', 'prp.precioventa as precioventa',
                'prp.tiempopedido as tiempopedido1', 'prp.metodoabc as metodoabc', 'prp.activo as activo', 'prp.estado as estado', 'u.id as id_name', 'u.name as user_name', 'pl.nombre as linea_name', 'plist.id as id_lista', 'plist.nombre_lista as nombre_lista','ar.nombre as rubro_name')
            ->join('prod__productos as pp', 'pp.id', '=', 'prp.id_producto')
            ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'prp.cod_tda_alm')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('users as u', 'u.id', '=', 'prp.id_usuario')
            ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('adm__rubros as ar', 'ar.id', '=', 'pp.idrubro')
            ->join('tda__ingreso_productos as ti','ti.id', '=','prp.id_ingreso') 
                ->where('prp.cod_tda_alm', '=', $bus)
           
                ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
            ->orderBy('prp.id', 'desc')
                ;
                $almacen = DB::table('prod__registro_pre_x_lists as prp')
    ->select('prp.id as id', 'prp.envase as envase', 'prp.id_producto as id_producto', 'pp.codigo as codigo_prod', 'pp.nombre as name_prod','ai.cantidad','ai.lote',
    DB::raw('CASE 
    WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pd_1.nombre, ""))
    WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pd_2.nombre, ""))
    WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pd_3.nombre, ""))
    ELSE NULL 
END AS cantidadEnvase'),    
    DB::raw('CASE 
                    WHEN prp.envase = "primario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_1.nombre, ""), " x ", IFNULL(pp.cantidadprimario, ""), " ", IFNULL(ff_1.nombre, ""))
                    WHEN prp.envase = "secundario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_2.nombre, ""), " x ", IFNULL(pp.cantidadsecundario, ""), " ", IFNULL(ff_2.nombre, ""))
                    WHEN prp.envase = "terciario" THEN CONCAT(IFNULL(pp.nombre, ""), " ", IFNULL(pd_3.nombre, ""), " x ", IFNULL(pp.cantidadterciario, ""), " ", IFNULL(ff_3.nombre, ""))
                    ELSE NULL 
                END AS leyenda'),
        'prp.tipo_tda_alm as tipo_tienda_almacen', 'prp.id_tda_alm as id_tda_alm', 'prp.cod_tda_alm  as cod_tda_alm', 'ass.razon_social as razon_social', 'prp.preciolista as preciolista', 'prp.precioventa as precioventa',
        'prp.tiempopedido as tiempopedido1', 'prp.metodoabc as metodoabc', 'prp.activo as activo', 'prp.estado as estado', 'u.id as id_name', 'u.name as user_name', 'pl.nombre as linea_name', 'plist.id as id_lista', 'plist.nombre_lista as nombre_lista','ar.nombre as rubro_name')
    ->join('prod__productos as pp', 'pp.id', '=', 'prp.id_producto')
    ->join('prod__listas as plist', 'plist.id', '=', 'prp.id_lista')
    ->join('alm__almacens as aa', 'aa.codigo', '=', 'prp.cod_tda_alm')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('users as u', 'u.id', '=', 'prp.id_usuario')
    ->leftJoin('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->leftJoin('prod__dispensers AS pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
    ->leftJoin('prod__dispensers AS pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas AS ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
    ->join('adm__rubros as ar', 'ar.id', '=', 'pp.idrubro')
    ->join('alm__ingreso_producto as ai','ai.id', '=','prp.id_ingreso') 
                ->where('prp.cod_tda_alm', '=', $bus)
                
                ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
                
                ->orderBy('prp.id', 'desc')
                ;
            $resultadocombinado= $tienda->unionAll($almacen)->paginate(10);   
            return [
                'pagination'=>[
                 'total'         =>    $resultadocombinado->total(),
                            'current_page'  =>    $resultadocombinado->currentPage(),
                            'per_page'      =>    $resultadocombinado->perPage(),
                            'last_page'     =>    $resultadocombinado->lastPage(),
                            'from'          =>    $resultadocombinado->firstItem(),
                            'to'            =>    $resultadocombinado->lastItem(),
                ] ,
                'resultadocombinado'=>$resultadocombinado,
           ];
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $NuevaLista=new Prod_Registro_pre_x_list(); 
            $NuevaLista->envase=$request->envase;
            $NuevaLista->id_producto=$request->id_producto;
            $NuevaLista->id_lista=$request->id_lista;
            $NuevaLista->id_tda_alm=$request->id_tda_alm;
            $NuevaLista->cod_tda_alm=$request->cod_tda_alm;
            $NuevaLista->tipo_tda_alm=$request->tipo_tda_alm;
            $NuevaLista->preciolista=$request->preciolista; 
            $NuevaLista->precioventa=$request->precioventa;
            $NuevaLista->tiempopedido=$request->tiempopedido;
            $NuevaLista->metodoabc=$request->metodoabc;
            $NuevaLista->id_usuario=auth()->user()->id;
            $NuevaLista->id_usuario_registra=auth()->user()->id;
            $NuevaLista->id_ingreso=$request->id_ingreso;
            $NuevaLista->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
      
    }   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prod_Registro_pre_x_list $prod_Registro_pre_x_list)
    { 
        try {
            $update=Prod_Registro_pre_x_list::find($request->id);
            $update->id_lista=$request->id_lista;
            $update->preciolista=$request->preciolista;
            $update->precioventa=$request->precioventa;
            $update->tiempopedido=$request->tiempopedido;
            $update->metodoabc=$request->metodoabc;
            $update->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
    }
    public function listarLista(Request $request){
        $resultadoCombinacion = DB::table('prod__listas as pl')
    ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
             'pl.id_tda_alm as id_tda_alm', 'ass.razon_social as razon_social', 'ass.cod as cod', 'u.name as user_name',
             'pl.estado as estado', 'pl.activo as activo')
    ->join('tda__tiendas as tt', 'tt.id', '=', 'pl.id_tda_alm')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
    ->join('users as u', 'u.id', '=', 'pl.id_usuario')
    ->whereColumn('tt.codigo', '=', 'pl.codigo_tda_alm')
    ->where('pl.activo', '=', 1)
    ->where('pl.codigo_tda_alm', '=', $request->codigo)
    ->unionAll(
        DB::table('prod__listas as pl')
            ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
                     'pl.id_tda_alm as id_tda_alm', 'aa.nombre_almacen as razon_social', 'ass.cod as cod', 'u.name as user_name',
                     'pl.estado as estado', 'pl.activo as activo')
            ->join('alm__almacens as aa', 'aa.id', '=', 'pl.id_tda_alm')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->join('users as u', 'u.id', '=', 'pl.id_usuario')
            ->whereColumn('aa.codigo', '=', 'pl.codigo_tda_alm')
            ->where('pl.activo', '=', 1)
            ->where('pl.codigo_tda_alm', '=', $request->codigo)
    )->get();

return $resultadoCombinacion;

    }
    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"TDA" as tipoCodigo'),'tda__tiendas.id as lista_id_almacen_id_tienda')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
        ->where('tda__tiendas.activo','=',1)
        ->where('adm__sucursals.activo','=',1)
        ;

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->where('aa.activo','=',1)
        ->where('ass.activo','=',1)
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"ALM" as tipoCodigo'),'aa.id  as lista_id_almacen_id_tienda');

    $result = $tiendas->unionAll($almacenes)->get();
      return $result;
     
     }
   
     public function listarProducto(Request $request){
        $envase = $request->envase;
        $codigo = $request->codigo;
        $tienda_almacen =$request->alm_tda;
        $where="";
        if ($codigo=="TDA") {
            if ($envase=="primario") {
                $where="(pp.tiendaprimario=1)";
            }
            if ($envase=="secundario") {
                $where="(pp.tiendasecundario=1)";
            }
            if ($envase=="terciario") {
                $where="(pp.tiendaterciario=1)";
            }  
            $resultado = DB::table('tda__ingreso_productos as ti')
            ->join('prod__productos as pp', 'ti.id_prod_producto', '=', 'pp.id')
            ->join('tda__tiendas as tt','ti.idtienda','=','tt.id' ) 
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->select('pp.id as id','pp.codigo as prod_cod','pl.nombre as linea_name', 'pl.codigo as linea_cod', 'pp.codigo as cod_prod', 'pp.nombre as cod_name', 'ar.nombre as rubro_name', 
            'ti.cantidad as cantidad','ti.lote as lote','ti.id as id_ingreso',
            DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                    WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                    WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                    ELSE NULL 
                END AS leyenda"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                    ELSE NULL 
                END AS iddispenserEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                    ELSE NULL 
                END AS cantidadEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                    ELSE NULL 
                END AS idformafarmaceuticaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
                    ELSE NULL 
                END AS preciolistaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
                    ELSE NULL 
                END AS precioventaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
                    ELSE NULL 
                END AS tiempopedidoEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
                    ELSE NULL 
                END AS metodoabcEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                    ELSE NULL 
                END AS tiendaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                    ELSE NULL 
                END AS almacenEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
                    ELSE NULL 
                END AS costocompraEnvase"))
            ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
            ->where('tt.codigo','=',$tienda_almacen)
            ->whereRaw($where)
            ->get(); 
                
        }
        if ($codigo=="ALM") {
            if ($envase=="primario") {
                $where="(pp.almacenprimario=1)";
            }
            if ($envase=="secundario") {
                $where="(pp.almacensecundario=1)";
            }
            if ($envase=="terciario") {
                $where="(pp.almacenterciario=1)";
            }   
            $resultado = DB::table('alm__ingreso_producto as ai')
            ->join('prod__productos as pp', 'ai.id_prod_producto', '=', 'pp.id')
            ->join('alm__almacens as aa','ai.idalmacen','=','aa.id' ) 
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->select('pp.id as id','pp.codigo as prod_cod','pl.nombre as linea_name', 'pl.codigo as linea_cod', 'pp.codigo as cod_prod', 'pp.nombre as cod_name', 'ar.nombre as rubro_name', 
            'ai.cantidad as cantidad','ai.lote as lote','ai.id as id_ingreso',
            DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                    WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                    WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                    ELSE NULL 
                END AS leyenda"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                    ELSE NULL 
                END AS iddispenserEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                    ELSE NULL 
                END AS cantidadEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                    ELSE NULL 
                END AS idformafarmaceuticaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
                    ELSE NULL 
                END AS preciolistaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
                    ELSE NULL 
                END AS precioventaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
                    ELSE NULL 
                END AS tiempopedidoEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
                    ELSE NULL 
                END AS metodoabcEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                    ELSE NULL 
                END AS tiendaEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                    ELSE NULL 
                END AS almacenEnvase"),
                DB::raw("CASE 
                    WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
                    WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
                    WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
                    ELSE NULL 
                END AS costocompraEnvase"))
            ->where('pp.idrubro', '=', 1)
            ->where('pp.activo', '=', 1)
            ->where('aa.codigo','=',$tienda_almacen)
            ->whereRaw($where)
            ->get(); 
        
           
        }
   
      
        return $resultado;
        
     }
     public function listarProductoRetorno(Request $request){
        
        $buscararray=array();
        if(!empty($request->input))
        {
            $buscararray = explode(" ",$request->input);
                $valor=sizeof($buscararray);
                if($valor > 0)
                {
                    $sqls='';
                    foreach($buscararray as $valor)
                    {
                        if(empty($sqls)){
                            $sqls="(
                                pp.codigo like '%".$valor."%'                               
                                or pp.nombre like '%".$valor."%'                                
                                or pl.nombre  like '%".$valor."%' 
                      
                                )";
                        }
                        else
                        {
                            $sqls.="and (
                                pp.codigo like '%".$valor."%'                               
                                or pp.nombre like '%".$valor."%'                                
                                or pl.nombre  like '%".$valor."%' 
                 
                                 )";
                        }
                    }
                    //query...................
                    $envase = $request->envase;
                    $codigo = $request->codigo;
                    $tienda_almacen =$request->alm_tda;
                    $where = [];
                    
                    if ($codigo == "TDA") {
                        if ($envase == "primario") {
                            $where[] = "(pp.tiendaprimario=1)";
                        }
                        if ($envase == "secundario") {
                            $where[] = "(pp.tiendasecundario=1)";
                        }
                        if ($envase == "terciario") {
                            $where[] = "(pp.tiendaterciario=1)";
                        }
                        $resultado = DB::table('tda__ingreso_productos as ti')
                    ->join('prod__productos as pp', 'ti.id_prod_producto', '=', 'pp.id')
                    ->join('tda__tiendas as tt','ti.idtienda','=','tt.id' ) 
                        ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
                        ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
                        ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                        ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                        ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                        ->select(
                            'pp.id as id',
                            'pp.codigo as prod_cod',
                            'pl.nombre as linea_name',
                            'pl.codigo as linea_cod',
                            'pp.codigo as cod_prod',
                            'pp.nombre as cod_name',
                            'ar.nombre as rubro_name',
                            'ti.cantidad as cantidad',
                            'ti.lote as lote',
                            'ti.id as id_ingreso',
                            DB::raw("'$envase' as tipoE"),
                            DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                                WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                                WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                                ELSE NULL 
                            END AS leyenda"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                                ELSE NULL 
                            END AS iddispenserEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                                ELSE NULL 
                            END AS cantidadEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                                ELSE NULL 
                            END AS idformafarmaceuticaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
                                ELSE NULL 
                            END AS preciolistaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
                                ELSE NULL 
                            END AS precioventaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
                                ELSE NULL 
                            END AS tiempopedidoEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
                                ELSE NULL 
                            END AS metodoabcEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                                ELSE NULL 
                            END AS tiendaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                                ELSE NULL 
                            END AS almacenEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
                                ELSE NULL 
                            END AS costocompraEnvase")
                        )
                        ->where('pp.idrubro', '=', 1)
                        ->where('tt.codigo','=',$tienda_almacen)
                        ->where('pp.activo', '=', 1)
                        ->whereRaw(implode(' AND ', $where))
                        ->whereRaw($sqls)
                        ->get(); 
                    }
                    if ($codigo == "ALM") {
                        if ($envase == "primario") {
                            $where[] = "(pp.almacenprimario=1)";
                        }
                        if ($envase == "secundario") {
                            $where[] = "(pp.almacensecundario=1)";
                        }
                        if ($envase == "terciario") {
                            $where[] = "(pp.almacenterciario=1)";
                        }
                        $resultado = DB::table('alm__ingreso_producto as ai')
                        ->join('prod__productos as pp', 'ai.id_prod_producto', '=', 'pp.id')
                        ->join('alm__almacens as aa','ai.idalmacen','=','aa.id' ) 
                        ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
                        ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
                        ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                        ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                        ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                        ->select(
                            'pp.id as id',
                            'pp.codigo as prod_cod',
                            'pl.nombre as linea_name',
                            'pl.codigo as linea_cod',
                            'pp.codigo as cod_prod',
                            'pp.nombre as cod_name',
                            'ar.nombre as rubro_name',
                            'ai.cantidad as cantidad',
                            'ai.lote as lote',
                            'ai.id as id_ingreso',
                            DB::raw("'$envase' as tipoE"),
                            DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
                                WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
                                WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
                                ELSE NULL 
                            END AS leyenda"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
                                ELSE NULL 
                            END AS iddispenserEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
                                ELSE NULL 
                            END AS cantidadEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
                                ELSE NULL 
                            END AS idformafarmaceuticaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
                                ELSE NULL 
                            END AS preciolistaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
                                ELSE NULL 
                            END AS precioventaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
                                ELSE NULL 
                            END AS tiempopedidoEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
                                ELSE NULL 
                            END AS metodoabcEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
                                ELSE NULL 
                            END AS tiendaEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
                                ELSE NULL 
                            END AS almacenEnvase"),
                            DB::raw("CASE 
                                WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
                                WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
                                WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
                                ELSE NULL 
                            END AS costocompraEnvase")
                        )
                        ->where('pp.idrubro', '=', 1)
                        ->where('aa.codigo','=',$tienda_almacen)
                        ->where('pp.activo', '=', 1)
                        ->whereRaw(implode(' AND ', $where))
                        ->whereRaw($sqls)
                        ->get(); 
                    }
                    
                    
                    
                   
                }
                return $resultado;
        }
        else
        {
           
            $envase = $request->envase;
$codigo = $request->codigo;
$tienda_almacen =$request->alm_tda;
$where = [];

if ($codigo == "TDA") {
    if ($envase == "primario") {
        $where[] = "(pp.tiendaprimario=1)";
    }
    if ($envase == "secundario") {
        $where[] = "(pp.tiendasecundario=1)";
    }
    if ($envase == "terciario") {
        $where[] = "(pp.tiendaterciario=1)";
    }
    $resultado = DB::table('tda__ingreso_productos as ti')
->join('prod__productos as pp', 'ti.id_prod_producto', '=', 'pp.id')
->join('tda__tiendas as tt','ti.idtienda','=','tt.id' ) 
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
    ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
    ->select(
        'pp.id as id',
        'pp.codigo as prod_cod',
        'pl.nombre as linea_name',
        'pl.codigo as linea_cod',
        'pp.codigo as cod_prod',
        'pp.nombre as cod_name',
        'ar.nombre as rubro_name',
        'ti.cantidad as cantidad',
        'ti.lote as lote',
        'ti.id as id_ingreso',
        DB::raw("'$envase' as tipoE"),
        DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
            WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
            WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
            ELSE NULL 
        END AS leyenda"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
            ELSE NULL 
        END AS iddispenserEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
            ELSE NULL 
        END AS cantidadEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
            ELSE NULL 
        END AS idformafarmaceuticaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
            ELSE NULL 
        END AS preciolistaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
            ELSE NULL 
        END AS precioventaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
            ELSE NULL 
        END AS tiempopedidoEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
            ELSE NULL 
        END AS metodoabcEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
            ELSE NULL 
        END AS tiendaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
            ELSE NULL 
        END AS almacenEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
            ELSE NULL 
        END AS costocompraEnvase")
    )
    ->where('pp.idrubro', '=', 1)
    ->where('tt.codigo','=',$tienda_almacen)
            ->where('pp.activo', '=', 1)
    ->whereRaw(implode(' AND ', $where))
    ->get(); 
}
if ($codigo == "ALM") {
    if ($envase == "primario") {
        $where[] = "(pp.almacenprimario=1)";
    }
    if ($envase == "secundario") {
        $where[] = "(pp.almacensecundario=1)";
    }
    if ($envase == "terciario") {
        $where[] = "(pp.almacenterciario=1)";
    }
    $resultado = DB::table('alm__ingreso_producto as ai')
    ->join('prod__productos as pp', 'ai.id_prod_producto', '=', 'pp.id')
    ->join('alm__almacens as aa','ai.idalmacen','=','aa.id' ) 
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
    ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
    ->select(
        'pp.id as id',
        'pp.codigo as prod_cod',
        'pl.nombre as linea_name',
        'pl.codigo as linea_cod',
        'pp.codigo as cod_prod',
        'pp.nombre as cod_name',
        'ar.nombre as rubro_name',
        'ai.cantidad as cantidad',
        'ai.lote as lote',
        'ai.id as id_ingreso',
        DB::raw("'$envase' as tipoE"),
        DB::raw("CONCAT(pl.codigo, ' ', pl.nombre) AS lineaS"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')) 
            WHEN '$envase' = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')) 
            WHEN '$envase' = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')) 
            ELSE NULL 
        END AS leyenda"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.iddispenserprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.iddispensersecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.iddispenserterciario, '') 
            ELSE NULL 
        END AS iddispenserEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.cantidadprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.cantidadsecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.cantidadterciario, '') 
            ELSE NULL 
        END AS cantidadEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.idformafarmaceuticaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.idformafarmaceuticasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.idformafarmaceuticaterciario, '') 
            ELSE NULL 
        END AS idformafarmaceuticaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.preciolistaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.preciolistasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.preciolistaterciario, '') 
            ELSE NULL 
        END AS preciolistaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.precioventaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.precioventasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.precioventaterciario, '') 
            ELSE NULL 
        END AS precioventaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.tiempopedidoprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, '') 
            ELSE NULL 
        END AS tiempopedidoEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.metodoabcprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.metodoabcsecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.metodoabcterciario, '') 
            ELSE NULL 
        END AS metodoabcEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.tiendaprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.tiendasecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.tiendaterciario, '') 
            ELSE NULL 
        END AS tiendaEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(pp.almacenprimario, '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(pp.almacensecundario, '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(pp.almacenterciario, '') 
            ELSE NULL 
        END AS almacenEnvase"),
        DB::raw("CASE 
            WHEN '$envase' = 'primario' THEN COALESCE(FORMAT(pp.preciolistaprimario / pp.cantidadprimario, 2), '') 
            WHEN '$envase' = 'secundario' THEN COALESCE(FORMAT(pp.preciolistasecundario / pp.cantidadsecundario, 2), '') 
            WHEN '$envase' = 'terciario' THEN COALESCE(FORMAT(pp.preciolistaterciario / pp.cantidadterciario, 2), '') 
            ELSE NULL 
        END AS costocompraEnvase")
    )
    ->where('pp.idrubro', '=', 1)
    ->where('aa.codigo','=',$tienda_almacen)
            ->where('pp.activo', '=', 1)
    ->whereRaw(implode(' AND ', $where))
    ->get(); 
}



return $resultado;
                

        }
       
     }
     
     public function desactivar(Request $request)
     {
       
         $update = Prod_Registro_pre_x_list::findOrFail($request->id);
         $update->activo = 0;
         $update->id_usuario=auth()->user()->id;
         $update->id_usuario_modifica=auth()->user()->id;
         $update->save();
     }
 
     public function activar(Request $request)
     {  $update = Prod_Registro_pre_x_list::findOrFail($request->id);
         $update->activo = 1;
         $update->id_usuario=auth()->user()->id;
         $update->id_usuario_modifica=auth()->user()->id;
         $update->save();
     }
}
