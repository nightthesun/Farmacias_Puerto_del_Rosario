<?php

namespace App\Http\Controllers;

use App\Models\Alm_IngresoProducto2;
use App\Models\Inv_InventarioInicial;
use App\Models\Tda_ingresoProducto2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvInventarioInicialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();   
        $ini=$request->ini;
        $fini=$request->fini;
        $id_almacen=$request->id_almacen;
           $id_sucursal=$request->id_sucursal;
            $id_tienda=$request->id_tienda;            
               // dd ($request->all());
            if ($id_tienda!="null"&&$id_almacen=="null") {
                if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id;           
            $where = "(tip.idtienda = $request->id_tienda and pp.activo = 1 and tip.id_usuario_registra = $user)";            
        } else {
            $where = "(tip.idtienda = $request->id_tienda and pp.activo = 1)";   
        } 
                return $this->listarIngresoTienda($where,$ini,$fini);
            }else{
                if ($id_tienda=="null"&&$id_almacen!="null") {
                    if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id;           
            $where = "(aip.idalmacen = $request->id_almacen and pp.activo = 1 and aip.id_usuario_registra = $user)";            
        } else {
            $where = "(aip.idalmacen = $request->id_almacen and pp.activo = 1)";    
        }   
                    return $this->listarIngresoAlmacen($where,$ini,$fini);
                }else{
                    dd("error");
                }
            }
        
         

    }

    private function listarIngresoTienda($where,$ini,$fini){
         $query = DB::table('tda__ingreso_productos as tip')
            ->select(
                'tip.id',
                'tip.id_prod_producto',
                'tip.id_tipoentrada',
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.registro_sanitario',
                'tip.fecha_vencimiento',
                'tip.lote',
                'tip.activo',
                'pp.codigo as codigo_prod',
                'pl.nombre as nombre_linea',
                'pl.id as id_linea',
                DB::raw("CASE
                    WHEN tip.envase = 'primario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadprimario, ''), ' ', IFNULL(ff_1.nombre, ''))
                    WHEN tip.envase = 'secundario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_2.nombre, ''), ' x ', IFNULL(pp.cantidadsecundario, ''), ' ', IFNULL(ff_2.nombre, ''))
                    WHEN tip.envase = 'terciario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_3.nombre, ''), ' x ', IFNULL(pp.cantidadterciario, ''), ' ', IFNULL(ff_3.nombre, ''))
                    ELSE NULL
                END AS leyenda"),
                'u.name as user_name',
                'u.id as user_id',
                'u_modi.name as user_name_M',
                'u_modi.id as user_id_M',
                'tip.num_traspaso',
                DB::raw('GREATEST(tip.created_at, tip.updated_at) AS fecha'),
                'tip.idtienda as id_tienda_almacen',
                'tt.codigo as codigo_alm'
            )
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'ar.id', '=', 'pp.idrubro')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'tip.id_tipoentrada')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('users as u', 'u.id', '=', 'tip.id_usuario_registra')
            ->leftJoin('users as u_modi', 'u_modi.id', '=', 'tip.id_usuario_modifica')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            //->where('tip.idtienda', $request->id_tienda)
            //->where('pp.activo', 1)
            ->whereRaw($where)
            ->whereBetween(DB::raw('DATE(tip.created_at)'), [$ini, $fini]) 
            ->orderBy('id', 'desc')
            ->paginate(15);  
           
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $query->total(),
                            'current_page'  =>    $query->currentPage(),
                            'per_page'      =>    $query->perPage(),
                            'last_page'     =>    $query->lastPage(),
                            'from'          =>    $query->firstItem(),
                            'to'            =>    $query->lastItem(),
                        ] ,
                    'query'=>$query,
            ]; 

    }

    private function listarIngresoAlmacen($where,$ini,$fini){
         $query = DB::table('alm__ingreso_producto as aip')
            ->select([
                'aip.id',
                'aip.id_prod_producto',
                'aip.id_tipoentrada',
                'aip.envase',
                'aip.cantidad',
                'aip.stock_ingreso',
                'aip.registro_sanitario',
                'aip.fecha_vencimiento',
                'aip.lote',
                'aip.activo',
                'pp.codigo as codigo_prod',
                'pl.nombre as nombre_linea',
                'pl.id as id_linea',
                DB::raw("CASE
                    WHEN aip.envase = 'primario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadprimario, ''), ' ', IFNULL(ff_1.nombre, ''))
                    WHEN aip.envase = 'secundario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_2.nombre, ''), ' x ', IFNULL(pp.cantidadsecundario, ''), ' ', IFNULL(ff_2.nombre, ''))
                    WHEN aip.envase = 'terciario' THEN CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_3.nombre, ''), ' x ', IFNULL(pp.cantidadterciario, ''), ' ', IFNULL(ff_3.nombre, ''))
                    ELSE NULL
                END as leyenda"),
                'u.name as user_name',
                'u.id as user_id',
                'u_modi.name as user_name_M',
                'u_modi.id as user_id_M','aip.num_traspaso',
                DB::raw('GREATEST(aip.created_at, aip.updated_at) as fecha'),'aip.idalmacen as id_tienda_almacen','aa.codigo as codigo_alm'
            ])
            ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'aip.id_tipoentrada')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('users as u', 'u.id', '=', 'aip.id_usuario_registra')
            ->leftJoin('users as u_modi', 'u_modi.id', '=', 'aip.id_usuario_modifica')
            ->join('alm__almacens as aa', 'aa.id', '=', 'aip.idalmacen')
            //->where('aip.idalmacen', $request->id_almacen)
            //->where('pp.activo', 1)
            ->whereRaw($where)
            ->whereBetween(DB::raw('DATE(aip.created_at)'), [$ini, $fini]) 
            ->orderBy('id', 'desc')
            ->paginate(15); 
         
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $query->total(),
                            'current_page'  =>    $query->currentPage(),
                            'per_page'      =>    $query->perPage(),
                            'last_page'     =>    $query->lastPage(),
                            'from'          =>    $query->firstItem(),
                            'to'            =>    $query->lastItem(),
                        ] ,
                    'query'=>$query,
            ]; 
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
               // Iniciar una transacción
            DB::beginTransaction();
            $id_almacen=$request->id_almacen;
            $id_sucursal=$request->id_sucursal;
            $id_tienda=$request->id_tienda;
            $arrayAñadir=$request->arrayAñadir;
            if ($id_tienda!=null&&$id_almacen==null) {
                foreach ($arrayAñadir as $key => $value) {
                    $nuevoProducto = new Tda_ingresoProducto2();
                    $nuevoProducto->id_prod_producto   = $value['id_producto'];
        $nuevoProducto->envase             = $value['envase'];
        $nuevoProducto->idtienda            = $id_tienda;
        $nuevoProducto->cantidad            = $value['cantidad'];
        $nuevoProducto->stock_ingreso       = $value['cantidad'];
        $nuevoProducto->id_tipoentrada      = $value['selectEntrada'];
        $nuevoProducto->fecha_vencimiento   = $value['fechaFormateada'];
        $nuevoProducto->lote                = $value['lote'];
        $nuevoProducto->registro_sanitario  = $value['registrosanitario'];
        $nuevoProducto->activo              = 1;   
                    $nuevoProducto->id_usuario_registra=auth()->user()->id;
                    $nuevoProducto->save();
            // Obtener el ID asignado al nuevo producto
            $nuevoProductoID = $nuevoProducto->id;      
            $datos = [
                'id_tienda_almacen' => $id_tienda,              
                'id_ingreso' => $nuevoProductoID,
                'tipo' => "TDA",               
            ];
            DB::table('pivot__modulo_tienda_almacens')->insert($datos);
            // Si llegamos aquí sin errores, confirmamos la transacción
                }
            }else{
                if ($id_tienda==null&&$id_almacen!=null) {
                    foreach ($arrayAñadir as $key => $value) {
                        $nuevoProducto = new Alm_IngresoProducto2();
                      
                         $nuevoProducto->id_prod_producto   = $value['id_producto'];
        $nuevoProducto->envase             = $value['envase'];
        $nuevoProducto->idalmacen            = $id_almacen;
        $nuevoProducto->cantidad            = $value['cantidad'];
        $nuevoProducto->stock_ingreso       = $value['cantidad'];
        $nuevoProducto->id_tipoentrada      = $value['selectEntrada'];
        $nuevoProducto->fecha_vencimiento   = $value['fechaFormateada'];
        $nuevoProducto->lote                = $value['lote'];
        $nuevoProducto->registro_sanitario  = $value['registrosanitario'];
        $nuevoProducto->activo              = 1;   
                        $nuevoProducto->id_usuario_registra = auth()->user()->id;
                        $nuevoProducto->save();
                        // Obtener el ID asignado al nuevo producto
                        $nuevoProductoID = $nuevoProducto->id;
                        // Indicar que el primer guardado fue exitoso         
                        $datos = [
                            'id_tienda_almacen' => $id_almacen,              
                            'id_ingreso' => $nuevoProductoID,
                            'tipo' => "ALM",               
                        ];
        
                        DB::table('pivot__modulo_tienda_almacens')->insert($datos);
                    }
                }else{
                    dd("error");
                }
            }
                    
            DB::commit();
             return 1;  
        } catch (\Throwable $th) {
             DB::rollBack();
            return $th;
        }
    }

    public function getSelectProducto(){
       
$primario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadprimario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'primario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispenserprimario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario');

$secundario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadsecundario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'secundario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispensersecundario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticasecundario');

$terciario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadterciario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'terciario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispenserterciario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaterciario');

$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->orderBy('id_prod', 'desc')
    ->get();
 return $resultado;
    }

}