<?php

namespace App\Http\Controllers;

use App\Models\Alm_IngresoProducto2;
use App\Models\Tda_ingresoProducto2;
use App\Models\Tda_Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenCaducidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array(); 
        $fecha_actual =  Carbon::now()->format('Y-m-d');
        $id_1=$request->id_sucursal;
        $codigo=$request->codigo_tienda_almacen;       
        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls=''; 
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                            u.name like '%".$valor."%' 
                                or pp.codigo like '%".$valor."%' 
                                or pl.nombre like '%".$valor."%'                                                           
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            u.name like '%".$valor."%' 
                                or pp.codigo like '%".$valor."%' 
                                or pl.nombre like '%".$valor."%'  
                          )" ;
                    }    
                }
                //--- query  
                $resultado =  DB::table('tda__ingreso_productos as tip')
                ->leftJoin('pivot__modulo_tienda_almacens as pivot', function($join) {
                    $join->on('pivot.id_ingreso', '=', 'tip.id')
                        ->where('pivot.tipo', '=', 'TDA');
                })
                ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
                ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                ->where('tip.idtienda', $id_1)
                ->where('tt.codigo',  $codigo)
                ->where('tip.stock_ingreso', '>', 0)
                ->where('tip.activo', 1)
                ->where('tip.cantidad', '>', 0)
                ->whereRaw($sqls)
                ->select([
                    'pivot.id',
                    'pivot.id_ingreso',
                    'pp.codigo',
                    'pl.nombre',
                    DB::raw("
                        CASE 
                            WHEN tip.envase = 'primario' THEN CONCAT(
                                UPPER(COALESCE(pp.nombre, '')), ' ', 
                                UPPER(COALESCE(pd_1.nombre, '')), ' X ', 
                                COALESCE(pp.cantidadprimario, ''), ' ', 
                                UPPER(COALESCE(ff_1.nombre, ''))
                            )
                            WHEN tip.envase = 'secundario' THEN CONCAT(
                                UPPER(COALESCE(pp.nombre, '')), ' ', 
                                UPPER(COALESCE(pd_2.nombre, '')), ' X ', 
                                COALESCE(pp.cantidadsecundario, ''), ' ', 
                                UPPER(COALESCE(ff_2.nombre, ''))
                            )
                            WHEN tip.envase = 'terciario' THEN CONCAT(
                                UPPER(COALESCE(pp.nombre, '')), ' ', 
                                UPPER(COALESCE(pd_3.nombre, '')), ' X ', 
                                COALESCE(pp.cantidadterciario, ''), ' ', 
                                UPPER(COALESCE(ff_3.nombre, ''))
                            )
                            ELSE NULL 
                        END AS leyenda
                    "),
                    DB::raw("
                        CASE 
                            WHEN tip.envase = 'primario' THEN UPPER(COALESCE(pp.tiempopedidoprimario, ''))
                            WHEN tip.envase = 'secundario' THEN UPPER(COALESCE(pp.tiempopedidosecundario, ''))
                            WHEN tip.envase = 'terciario' THEN UPPER(COALESCE(pp.tiempopedidoterciario, ''))
                            ELSE NULL 
                        END AS siglo
                    "),
                    'tip.stock_ingreso',
                    'tip.fecha_vencimiento',
                    DB::raw("DATEDIFF(tip.fecha_vencimiento, '{$fecha_actual}') AS diferencia_dias"),
                    'tip.prioridad_caducidad','tip.idtienda as id_tda_alm','tt.codigo as cod_tda_alm'
                ])
                ->unionAll(
                    DB::table('alm__ingreso_producto as aip')
                        ->leftJoin('pivot__modulo_tienda_almacens as pivot', function($join) {
                            $join->on('pivot.id_ingreso', '=', 'aip.id')
                                ->where('pivot.tipo', '=', 'ALM');
                        })
                        ->join('alm__almacens as aa', 'aa.id', '=', 'aip.idalmacen')
                        ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
                        ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                        ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                        ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                        ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                        ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                        ->where('aip.idalmacen', $id_1)
                ->where('aa.codigo',  $codigo)
                ->where('aip.stock_ingreso', '>', 0)
                ->where('aip.activo', 1)
                ->where('aip.cantidad', '>', 0)
                ->whereRaw($sqls)
                        ->select([
                            'pivot.id',
                            'pivot.id_ingreso',
                            'pp.codigo',
                            'pl.nombre',
                            DB::raw("
                                CASE 
                                    WHEN aip.envase = 'primario' THEN CONCAT(
                                        UPPER(COALESCE(pp.nombre, '')), ' ', 
                                        UPPER(COALESCE(pd_1.nombre, '')), ' X ', 
                                        COALESCE(pp.cantidadprimario, ''), ' ', 
                                        UPPER(COALESCE(ff_1.nombre, ''))
                                    )
                                    WHEN aip.envase = 'secundario' THEN CONCAT(
                                        UPPER(COALESCE(pp.nombre, '')), ' ', 
                                        UPPER(COALESCE(pd_2.nombre, '')), ' X ', 
                                        COALESCE(pp.cantidadsecundario, ''), ' ', 
                                        UPPER(COALESCE(ff_2.nombre, ''))
                                    )
                                    WHEN aip.envase = 'terciario' THEN CONCAT(
                                        UPPER(COALESCE(pp.nombre, '')), ' ', 
                                        UPPER(COALESCE(pd_3.nombre, '')), ' X ', 
                                        COALESCE(pp.cantidadterciario, ''), ' ', 
                                        UPPER(COALESCE(ff_3.nombre, ''))
                                    )
                                    ELSE NULL 
                                END AS leyenda
                            "),
                            DB::raw("
                                CASE 
                                    WHEN aip.envase = 'primario' THEN UPPER(COALESCE(pp.tiempopedidoprimario, ''))
                                    WHEN aip.envase = 'secundario' THEN UPPER(COALESCE(pp.tiempopedidosecundario, ''))
                                    WHEN aip.envase = 'terciario' THEN UPPER(COALESCE(pp.tiempopedidoterciario, ''))
                                    ELSE NULL 
                                END AS siglo
                            "),
                            'aip.stock_ingreso',
                            'aip.fecha_vencimiento',
                            DB::raw("DATEDIFF(aip.fecha_vencimiento, '{$fecha_actual}') AS diferencia_dias"),
                            'aip.prioridad_caducidad','aip.idalmacen as id_tda_alm','aa.codigo as cod_tda_alm'
                        ])
                )
                ->orderBy('diferencia_dias', 'asc')
                ->paginate(15);            
            }    
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ]; 
        }  else {
            $resultado =  DB::table('tda__ingreso_productos as tip')
            ->leftJoin('pivot__modulo_tienda_almacens as pivot', function($join) {
                $join->on('pivot.id_ingreso', '=', 'tip.id')
                    ->where('pivot.tipo', '=', 'TDA');
            })
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->where('tip.idtienda', $id_1)
            ->where('tt.codigo',  $codigo)
            ->where('tip.stock_ingreso', '>', 0)
            ->where('tip.activo', 1)
            ->where('tip.cantidad', '>', 0)
     
            ->select([
                'pivot.id',
                'pivot.id_ingreso',
                'pp.codigo',
                'pl.nombre',
                DB::raw("
                    CASE 
                        WHEN tip.envase = 'primario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_1.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadprimario, ''), ' ', 
                            UPPER(COALESCE(ff_1.nombre, ''))
                        )
                        WHEN tip.envase = 'secundario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_2.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadsecundario, ''), ' ', 
                            UPPER(COALESCE(ff_2.nombre, ''))
                        )
                        WHEN tip.envase = 'terciario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_3.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadterciario, ''), ' ', 
                            UPPER(COALESCE(ff_3.nombre, ''))
                        )
                        ELSE NULL 
                    END AS leyenda
                "),
                DB::raw("
                    CASE 
                        WHEN tip.envase = 'primario' THEN UPPER(COALESCE(pp.tiempopedidoprimario, ''))
                        WHEN tip.envase = 'secundario' THEN UPPER(COALESCE(pp.tiempopedidosecundario, ''))
                        WHEN tip.envase = 'terciario' THEN UPPER(COALESCE(pp.tiempopedidoterciario, ''))
                        ELSE NULL 
                    END AS siglo
                "),
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                DB::raw("DATEDIFF(tip.fecha_vencimiento, '{$fecha_actual}') AS diferencia_dias"),
                'tip.prioridad_caducidad','tip.idtienda as id_tda_alm','tt.codigo as cod_tda_alm'
            ])
            ->unionAll(
                DB::table('alm__ingreso_producto as aip')
                    ->leftJoin('pivot__modulo_tienda_almacens as pivot', function($join) {
                        $join->on('pivot.id_ingreso', '=', 'aip.id')
                            ->where('pivot.tipo', '=', 'ALM');
                    })
                    ->join('alm__almacens as aa', 'aa.id', '=', 'aip.idalmacen')
                    ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
                    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
                    ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
                    ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
                    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
                    ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
                    ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
                    ->where('aip.idalmacen', $id_1)
            ->where('aa.codigo',  $codigo)
            ->where('aip.stock_ingreso', '>', 0)
            ->where('aip.activo', 1)
            ->where('aip.cantidad', '>', 0)
          
                    ->select([
                        'pivot.id',
                        'pivot.id_ingreso',
                        'pp.codigo',
                        'pl.nombre',
                        DB::raw("
                            CASE 
                                WHEN aip.envase = 'primario' THEN CONCAT(
                                    UPPER(COALESCE(pp.nombre, '')), ' ', 
                                    UPPER(COALESCE(pd_1.nombre, '')), ' X ', 
                                    COALESCE(pp.cantidadprimario, ''), ' ', 
                                    UPPER(COALESCE(ff_1.nombre, ''))
                                )
                                WHEN aip.envase = 'secundario' THEN CONCAT(
                                    UPPER(COALESCE(pp.nombre, '')), ' ', 
                                    UPPER(COALESCE(pd_2.nombre, '')), ' X ', 
                                    COALESCE(pp.cantidadsecundario, ''), ' ', 
                                    UPPER(COALESCE(ff_2.nombre, ''))
                                )
                                WHEN aip.envase = 'terciario' THEN CONCAT(
                                    UPPER(COALESCE(pp.nombre, '')), ' ', 
                                    UPPER(COALESCE(pd_3.nombre, '')), ' X ', 
                                    COALESCE(pp.cantidadterciario, ''), ' ', 
                                    UPPER(COALESCE(ff_3.nombre, ''))
                                )
                                ELSE NULL 
                            END AS leyenda
                        "),
                        DB::raw("
                            CASE 
                                WHEN aip.envase = 'primario' THEN UPPER(COALESCE(pp.tiempopedidoprimario, ''))
                                WHEN aip.envase = 'secundario' THEN UPPER(COALESCE(pp.tiempopedidosecundario, ''))
                                WHEN aip.envase = 'terciario' THEN UPPER(COALESCE(pp.tiempopedidoterciario, ''))
                                ELSE NULL 
                            END AS siglo
                        "),
                        'aip.stock_ingreso',
                        'aip.fecha_vencimiento',
                        DB::raw("DATEDIFF(aip.fecha_vencimiento, '{$fecha_actual}') AS diferencia_dias"),
                        'aip.prioridad_caducidad','aip.idalmacen as id_tda_alm','aa.codigo as cod_tda_alm'
                    ])
            )
            ->orderBy('diferencia_dias', 'asc')
            ->paginate(15);
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ];
        }  
    }
    
    public function prioridad(Request $request){
         try {
            $prefijo = substr($request->cod_tda_alm, 0, 3);
            if ($prefijo==='TDA') {
                $tt = Tda_ingresoProducto2::findOrFail($request->id_ingreso);
                $tt->prioridad_caducidad=$request->prioridad;       
                $tt->save();
            } else {
                if ($prefijo==='ALM') {
                    $aa = Alm_IngresoProducto2::findOrFail($request->id_ingreso);
                    $aa->prioridad_caducidad = $request->prioridad;           
                    $aa->save();
                } else {
                    dd("error de ingreso de TDA o ALM...");
                }
    
            }
         } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
         }
       
    }
}
