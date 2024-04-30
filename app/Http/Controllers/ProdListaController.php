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
           $nuevaLista->id_sucursal=$request->id_sucursal;
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
        try {
            $lista=Prod_Lista::find($request->id);
        $lista->nombre_lista=$request->nombreLista;
        $lista->codigo_tda_alm=$request->codigo;
        $lista->id_tda_alm=$request->lista_id_almacen_id_tienda;
        $lista->id_usuario_modifica=auth()->user()->id;
        $lista->id_usuario=auth()->user()->id;
        $lista->id_sucursal=$request->id_sucursal;        
        $lista->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
    }

    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as lista_id_almacen_id_tienda','adm__sucursals.id as id_sucursal')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
        ->where('tda__tiendas.activo','=',1)
        ->where('adm__sucursals.activo','=',1)
        ;

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->where('aa.activo','=',1)
        ->where('ass.activo','=',1)
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'),'aa.id  as lista_id_almacen_id_tienda','ass.id as id_sucursal');

    $result = $tiendas->unionAll($almacenes)->get();
      return $result;
     
     }
   
     
     public function desactivar(Request $request)
    {
      
        $update = Prod_Lista::findOrFail($request->id);
        $update->activo = 0;
        $update->id_usuario=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {  $update = Prod_Lista::findOrFail($request->id);
        $update->activo = 1;
        $update->id_usuario=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    } 
  
}
