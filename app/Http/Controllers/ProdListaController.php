<?php

namespace App\Http\Controllers;

use App\Models\Prod_Lista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $buscararray = array();
     
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                            pl.nombre_lista like '%" . $valor . "%' 
                                or pl.codigo like '%" . $valor . "%' 
                                or pl.codigo_tda_alm like '%" . $valor . "%'
                            
                               )";
                    } else {
                        $sqls .= "and (
                            pl.nombre_lista like '%" . $valor . "%' 
                                or pl.codigo like '%" . $valor . "%' 
                                or pl.codigo_tda_alm like '%" . $valor . "%'
                       )";
                    }
                }
                $resultadoCombinacion = DB::table('prod__listas as pl')
            ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
                     'pl.id_tda_alm as id_tda_alm', 'ass.razon_social as razon_social', 'ass.cod as cod', 'u.name as user_name',
                     'pl.estado as estado', 'pl.activo as activo')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'pl.id_tda_alm')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->join('users as u', 'u.id', '=', 'pl.id_usuario')
            ->whereColumn('tt.codigo', '=', 'pl.codigo_tda_alm')
            ->whereRaw($sqls)
            ->unionAll(
                DB::table('prod__listas as pl')
                    ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
                             'pl.id_tda_alm as id_tda_alm', 'aa.nombre_almacen as razon_social', 'ass.cod as cod', 'u.name as user_name',
                             'pl.estado as estado', 'pl.activo as activo')
                    ->join('alm__almacens as aa', 'aa.id', '=', 'pl.id_tda_alm')
                    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                    ->join('users as u', 'u.id', '=', 'pl.id_usuario')
                    ->whereColumn('aa.codigo', '=', 'pl.codigo_tda_alm')
                    ->whereRaw($sqls)
            )
            ->orderByDesc('id')
            ->paginate(15);
              
            }    
            return
            [
                'pagination' =>
                [
                    'total'         =>    $resultadoCombinacion->total(),
                    'current_page'  =>    $resultadoCombinacion->currentPage(),
                    'per_page'      =>    $resultadoCombinacion->perPage(),
                    'last_page'     =>    $resultadoCombinacion->lastPage(),
                    'from'          =>    $resultadoCombinacion->firstItem(),
                    'to'            =>    $resultadoCombinacion->lastItem(),
                ],
                'resultadoCombinacion' => $resultadoCombinacion,
            ];
        } else{
            $resultadoCombinacion = DB::table('prod__listas as pl')
            ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
                     'pl.id_tda_alm as id_tda_alm', 'ass.razon_social as razon_social', 'ass.cod as cod', 'u.name as user_name',
                     'pl.estado as estado', 'pl.activo as activo')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'pl.id_tda_alm')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->join('users as u', 'u.id', '=', 'pl.id_usuario')
            ->whereColumn('tt.codigo', '=', 'pl.codigo_tda_alm')
            ->unionAll(
                DB::table('prod__listas as pl')
                    ->select('pl.id as id', 'pl.nombre_lista as nombre_lista', 'pl.codigo as codigo_lista', 'pl.codigo_tda_alm as codigo_tda_alm',
                             'pl.id_tda_alm as id_tda_alm', 'aa.nombre_almacen as razon_social', 'ass.cod as cod', 'u.name as user_name',
                             'pl.estado as estado', 'pl.activo as activo')
                    ->join('alm__almacens as aa', 'aa.id', '=', 'pl.id_tda_alm')
                    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                    ->join('users as u', 'u.id', '=', 'pl.id_usuario')
                    ->whereColumn('aa.codigo', '=', 'pl.codigo_tda_alm')
            )
            ->orderByDesc('id')
            ->paginate(15);
            return
            [
                'pagination' =>
                [
                    'total'         =>    $resultadoCombinacion->total(),
                    'current_page'  =>    $resultadoCombinacion->currentPage(),
                    'per_page'      =>    $resultadoCombinacion->perPage(),
                    'last_page'     =>    $resultadoCombinacion->lastPage(),
                    'from'          =>    $resultadoCombinacion->firstItem(),
                    'to'            =>    $resultadoCombinacion->lastItem(),
                ],
                'resultadoCombinacion' => $resultadoCombinacion,
            ]; 
        }

     
    }  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $maxcorrelativo = Prod_Lista::select(DB::raw('max(correlativo) as maximo'))
            //->where('idlinea',$request->idlinea)
            ->get()->toArray();

$correlativo=$maxcorrelativo[0]['maximo'];
if(is_null($correlativo))
$correlativo=1;
else
$correlativo=$correlativo+1;

if($correlativo<10)
$codigo='00'.$correlativo;
else
if($correlativo<100)
$codigo='0'.$correlativo;
else
{
//if($correlativo<1000)
//    $codigo='0';
//else
$codigo=$correlativo;
}


$codigo="LIS".$codigo;

           $nuevaLista=new Prod_Lista();
           $nuevaLista->nombre_lista=$request->nombreLista;
           
           $nuevaLista->codigo=$codigo;
           $nuevaLista->codigo_tda_alm=$request->codigo;
           $nuevaLista->id_tda_alm=$request->lista_id_almacen_id_tienda;
           $nuevaLista->correlativo = $correlativo;
           $nuevaLista->id_usuario= auth()->user()->id;
           $nuevaLista->id_usuario_registra= auth()->user()->id;
           $nuevaLista->save();

        } catch (\Throwable $th) {
          
            return response()->json(['error' => $th->getMessage()],500);
        }
    }  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prod_Lista $prod_Lista)
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
   
     public function listarProducto(Request $request){
        $envase = $request->envase;
        $codigo = $request->codigo;
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
           
        }
       
        $resultado = DB::table('prod__productos as pp')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->select('pp.id as id','pp.codigo as prod_cod','pl.nombre as linea_name', 'pl.codigo as linea_cod', 'pp.codigo as cod_prod', 'pp.nombre as cod_name', 'ar.nombre as rubro_name', 
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
            ->whereRaw($where)
            ->get(); 
        
        return $resultado;
        
     } 
  
}
