<?php

namespace App\Http\Controllers;

use App\Models\Tda_ingresoProducto2;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TdaIngresoProducto2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
       
        $buscararray=array();   
        $ini=$request->ini;
        $fini=$request->fini;
        
        if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id;           
            $where = "(tip.idtienda = $request->id_tienda and pp.activo = 1 and tip.id_usuario_registra = $user)";            
        } else {
            $where = "(tip.idtienda = $request->id_tienda and pp.activo = 1)";   
        }     
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
                            pl.nombre like '%".$valor."%' 
                                or pp.codigo like '%".$valor."%' 
                                or pl.nombre like '%".$valor."%' 
                                or tip.lote like '%".$valor."%'
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            pl.nombre like '%".$valor."%' 
                            or pp.codigo like '%".$valor."%' 
                            or pl.nombre like '%".$valor."%' 
                            or tip.lote like '%".$valor."%'  
                          )" ;
                    }    
                }
                $tienda = DB::table('tda__ingreso_productos as tip')
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
                    'tip.idtienda',
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
                ->whereRaw($sqls)
                ->orderBy('id', 'desc')
                ->paginate(15);              
            }    
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $tienda->total(),
                            'current_page'  =>    $tienda->currentPage(),
                            'per_page'      =>    $tienda->perPage(),
                            'last_page'     =>    $tienda->lastPage(),
                            'from'          =>    $tienda->firstItem(),
                            'to'            =>    $tienda->lastItem(),
                        ] ,
                    'tienda'=>$tienda,
            ]; 
        }else{
            $tienda = DB::table('tda__ingreso_productos as tip')
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
                'tip.idtienda',
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
                            'total'         =>    $tienda->total(),
                            'current_page'  =>    $tienda->currentPage(),
                            'per_page'      =>    $tienda->perPage(),
                            'last_page'     =>    $tienda->lastPage(),
                            'from'          =>    $tienda->firstItem(),
                            'to'            =>    $tienda->lastItem(),
                        ] ,
                    'tienda'=>$tienda,
            ]; 
        }    
    }

    public function listarProductos_tienda(Request $request){

        $primario = DB::table('prod__productos as pp')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->join('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('pp.almacenprimario', 0)
    ->where('pp.tiendaprimario', 1)
    ->where('pp.idrubro', 1)
    ->where('pp.activo', 1)
    ->select(
        'pp.id',
        'pp.codigo as codigo_prod',
        'pp.nombre as nombre_prod',
        'pp.idlinea as id_liena',
        'pl.nombre as nombre_linea',
        'pp.idrubro as id_rubro',
        'ar.nombre as nombre_rubro',
        'pp.iddispenserprimario as id_dispenser',
        'pd_1.nombre as nombre_dispenser',
        'pp.cantidadprimario as cantidad_D',
        'pp.idformafarmaceuticaprimario as id_forma',
        'ff_1.nombre as nombre_forma',
        'pp.preciolistaprimario as precio_prod_lista',
        'pp.precioventaprimario as precio_venta',
        'pp.tiempopedidoprimario as tiempo_D',
        'pp.metodoabcprimario as metodo',
        DB::raw("'primario' as envase"),
        DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadprimario, ''), ' ', IFNULL(ff_1.nombre, '')) AS leyenda")
    )
    ->orderBy('pp.id', 'desc');

    $secundario = DB::table('prod__productos as pp')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispensersecundario')
    ->join('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticasecundario')
    ->where('pp.almacensecundario', 0)
    ->where('pp.tiendasecundario', 1)
    ->where('pp.idrubro', 1)
    ->where('pp.activo', 1)
    ->select(
        'pp.id',
        'pp.codigo as codigo_prod',
        'pp.nombre as nombre_prod',
        'pp.idlinea as id_liena',
        'pl.nombre as nombre_linea',
        'pp.idrubro as id_rubro',
        'ar.nombre as nombre_rubro',
        'pp.iddispensersecundario as id_dispenser',
        'pd_1.nombre as nombre_dispenser',
        'pp.cantidadsecundario as cantidad_D',
        'pp.idformafarmaceuticasecundario as id_forma',
        'ff_1.nombre as nombre_forma',
        'pp.preciolistasecundario as precio_prod_lista',
        'pp.precioventasecundario as precio_venta',
        'pp.tiempopedidosecundario as tiempo_D',
        'pp.metodoabcsecundario as metodo',
        DB::raw("'secundario' as envase"),
        DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadsecundario, ''), ' ', IFNULL(ff_1.nombre, '')) AS leyenda")
    )->orderBy('pp.id', 'desc');

$terciario = DB::table('prod__productos as pp')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserterciario')
    ->join('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaterciario')
    ->where('pp.almacenterciario', 0)
    ->where('pp.tiendaterciario', 1)
    ->where('pp.idrubro', 1)
    ->where('pp.activo', 1)
    ->select(
        'pp.id',
        'pp.codigo as codigo_prod',
        'pp.nombre as nombre_prod',
        'pp.idlinea as id_liena',
        'pl.nombre as nombre_linea',
        'pp.idrubro as id_rubro',
        'ar.nombre as nombre_rubro',
        'pp.iddispenserterciario as id_dispenser',
        'pd_1.nombre as nombre_dispenser',
        'pp.cantidadterciario as cantidad_D',
        'pp.idformafarmaceuticaterciario as id_forma',
        'ff_1.nombre as nombre_forma',
        'pp.preciolistaterciario as precio_prod_lista',
        'pp.precioventaterciario as precio_venta',
        'pp.tiempopedidoterciario as tiempo_D',
        'pp.metodoabcterciario as metodo',
        DB::raw("'terciario' as envase"),
        DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadterciario, ''), ' ', IFNULL(ff_1.nombre, '')) AS leyenda")
    )->orderBy('pp.id', 'desc');
    $productos = $primario->union($secundario)->union($terciario)->get();
    return $productos;
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        $primerGuardadoExitoso = false;
        try {
              // Iniciar una transacción
             DB::beginTransaction();
            $nuevoProducto = new Tda_IngresoProducto2();
            $nuevoProducto->id_prod_producto = $request->id_prod_producto;
            $nuevoProducto->envase = $request->envase;
            $nuevoProducto->idtienda = $request->idtienda;
            $nuevoProducto->cantidad = $request->cantidad;
            $nuevoProducto->stock_ingreso = $request->cantidad;
            $nuevoProducto->id_tipoentrada = $request->id_tipo_entrada;
            $nuevoProducto->fecha_vencimiento = $request->fecha_vencimiento;
            $nuevoProducto->lote = $request->lote;
            $nuevoProducto->registro_sanitario = $request->registro_sanitario;
            $nuevoProducto->activo = 1;
            $nuevoProducto->id_usuario_registra=auth()->user()->id;
            $nuevoProducto->save();
            // Obtener el ID asignado al nuevo producto
            $nuevoProductoID = $nuevoProducto->id;
            // Indicar que el primer guardado fue exitoso
        $primerGuardadoExitoso = true;         
            $datos = [
                'id_tienda_almacen' => $request->idtienda,              
                'id_ingreso' => $nuevoProductoID,
                'tipo' => "TDA",               
            ];
            DB::table('pivot__modulo_tienda_almacens')->insert($datos);
            // Si llegamos aquí sin errores, confirmamos la transacción
            $fechaHoy = Carbon::now()->format('Y-m-d');

            $datos_2=[
                'id_producto' => $request->id_prod_producto,
                'stock' => $request->cantidad,
                'fecha_ingreso' => $fechaHoy,
                'tipo' =>1,
                 'id_tienda_almacen' => $request->idtienda
            ];
           DB::table('sis_bitacora_stock')->insert($datos_2);  
            DB::commit();
        } catch (\Throwable $th) {
            // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
            if ($primerGuardadoExitoso) {
                DB::rollback();
                // Eliminar el producto guardado
                $nuevoProducto->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
        }
       
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tda_ingresoProducto2 $tda_ingresoProducto2)
    {
        $actualizarProducto = Tda_ingresoProducto2::findOrFail($request->id);
        $actualizarProducto->id_prod_producto = $request->id_prod_producto;
        $actualizarProducto->envase = $request->envase;
        $actualizarProducto->idtienda = $request->idalmacen;
        $actualizarProducto->cantidad = $request->cantidad;
        $actualizarProducto->stock_ingreso = $request->cantidad;
        $actualizarProducto->id_tipoentrada = $request->id_tipo_entrada;
        $actualizarProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $actualizarProducto->lote = $request->lote;
        $actualizarProducto->registro_sanitario = $request->registro_sanitario;
        $actualizarProducto->id_usuario_registra=auth()->user()->id;
        $actualizarProducto->save();
    }
    public function listaTienda(Request $request){
        $iduserrolesuc = session('iduserrolesuc');
        $idsuc = session('idsuc');
        $id_user2 = session('id_user2'); 
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        
        if ($user_1 == 1 && $user_2 == 'admin') {
            // Uniendo ambas consultas para el administrador
          
    
            $resultadoConsulta = DB::table('tda__tiendas as tt')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
    ->select(
        'tt.id AS id_tienda',
        DB::raw('NULL AS id_almacen'),
        'tt.codigo',
        'ass.razon_social AS razon_social',
        'ass.nombre_comercial AS sucursal',
        'ass.cod AS codigoS',
        DB::raw("'Tienda' AS tipoCodigo"),
        'tt.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
    )
    ->where('ass.activo', 1)
    ->get();
    
            
            return $resultadoConsulta;
        } else {
            // Consultas para otros usuarios
            $asignaciones = DB::table('adm__asig_mas_sucursales')
                ->where('id_user_role_sucu', $id_user2)
                ->get();
            
            $codigos = [];
            $where1 = '';
            $where2 = '';
            
            if ($asignaciones->isNotEmpty()) {
                foreach ($asignaciones as $value) {
                    $codigos[] = "'" . $value->cod . "'";
                }
                $where1 = 'aa.codigo IN (' . implode(',', $codigos) . ')';
                $where2 = 'tda__tiendas.codigo IN (' . implode(',', $codigos) . ')';
            } else {
                $where1 = 'ass.id = ' . $idsuc;
                $where2 = 'ass.id = ' . $idsuc;
            }
          
            
            $resultadoConsulta = DB::table('tda__tiendas as tt')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->select(
                'tt.id AS id_tienda',
                DB::raw('NULL AS id_almacen'),
                'tt.codigo',
                'ass.razon_social AS razon_social',
                'ass.nombre_comercial AS sucursal',
                'ass.cod AS codigoS',
                DB::raw("'Tienda' AS tipoCodigo"),
                'tt.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)            
                ->whereRaw($where1)->get();
            
           
            return $resultadoConsulta;
        }
       }
       public function desactivar(Request $request)
       {
           $actualizarProducto = Tda_IngresoProducto2::findOrFail($request->id);
           $actualizarProducto->activo = 0;
           $actualizarProducto->id_usuario_modifica=auth()->user()->id;
           $actualizarProducto->save();
       }
   
       public function activar(Request $request)
       {
           $actualizarProducto = Tda_IngresoProducto2::findOrFail($request->id);
           $actualizarProducto->activo = 1;
           $actualizarProducto->id_usuario_modifica=auth()->user()->id;
           $actualizarProducto->save();
       }
}
