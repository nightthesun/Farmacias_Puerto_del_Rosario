<?php

namespace App\Http\Controllers;

use App\Models\Inv_Recepcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;
use App\Models\Prod_Producto;
use App\Models\Inv_AjustePositivo;
use App\Models\Inv_Traspaso;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class InvRecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();
        $ini=$request->ini;
        $fini=$request->fini;
        $bus = $request->query('buscarAlmTdn');
     
     
        
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                it.numero_traspaso like '%" . $valor . "%' 
                                or it.envase like '%" . $valor . "%' 
                                or it.name_ori like '%" . $valor . "%'
                                or it.name_des like '%" . $valor . "%' 
                                or it.leyenda like '%" . $valor . "%'
                              
                                or it.cod_2 like '%" . $valor . "%' 
                               )";
                    } else {
                        $sqls .= "and (
                            it.numero_traspaso like '%" . $valor . "%' 
                            or it.envase like '%" . $valor . "%' 
                            or it.name_ori like '%" . $valor . "%'
                            or it.name_des like '%" . $valor . "%' 
                            or it.leyenda like '%" . $valor . "%'
                     
                            or it.cod_2 like '%" . $valor . "%'
                       )";
                    }
                }
                // codigo query
                $recepcion_alm = DB::table('inv__recepcions as ir')
                ->select(
                    'ir.id as id', 'ir.observacion as rec_observacion', 'it.id as id_traspaso', 'aa.id as id_almacen_tienda',
                    'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                    'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada',
                    'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad', 'it.fecha_vencimiento as fecha_vencimiento',
                    'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'ir.activo as activo', 'it.id_origen as id_origen',
                    'it.id_destino as id_destino', 'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso',
                    'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name', 'it.name_ori as name_ori',
                    'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2', 'lt.id as id_traslado',
                    'lt.codigo as codigo_traslado', 'lt.tiempo as tiempo', 'lt.observacion as observacion',
                    're.id as id_empleado', 'it.procesado as estado', 'ass.id AS id_sucursal',
                    DB::raw('GREATEST(ir.created_at, ir.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                    'it.id_ingreso as id_ingreso', 'lv.id as id_vehiculo', 'lv.matricula as name_vehiculo'
                )
                ->join('log__traslados as lt', 'lt.id', '=', 'ir.id_traslado')
                ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
                ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'ir.id_user')
                ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
                ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.cod_2', '=', $bus)
           
                ->whereRaw($sqls)
                ->whereBetween(DB::raw('DATE(ir.created_at)'), [$ini, $fini]) 
                ->orderBy('id', 'desc');
                $recepcion_tda = DB::table('inv__recepcions as ir')
                ->select(
                    'ir.id as id', 'ir.observacion as rec_observacion', 'it.id as id_traspaso', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto',
                    'pp.codigo as cod_prod', 'pp.nombre as name_prod', 'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase',
                    'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario',
                    'ir.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino', 'it.leyenda as leyenda',
                    'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id',
                    'u.name as user_name', 'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                    'lt.id as id_traslado', 'lt.codigo as codigo_traslado', 'lt.tiempo as tiempo', 'lt.observacion as observacion',
                    're.id as id_empleado', 'it.procesado as estado', 'ass.id AS id_sucursal',
                    DB::raw('GREATEST(ir.created_at, ir.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                    'it.id_ingreso as id_ingreso', 'lv.id as id_vehiculo', 'lv.matricula as name_vehiculo'
                )
                ->join('log__traslados as lt', 'lt.id', '=', 'ir.id_traslado')
                ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
                ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'ir.id_user')
                ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
                ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.cod_2', '=', $bus)
       
                ->whereBetween(DB::raw('DATE(ir.created_at)'), [$ini, $fini]) 
                ->whereRaw($sqls)
                ->orderBy('id', 'desc');
                $resultadoCombinacion = $recepcion_alm->unionAll($recepcion_tda)->paginate(15);   
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
        }
        else{
            $recepcion_alm = DB::table('inv__recepcions as ir')
                ->select(
                    'ir.id as id', 'ir.observacion as rec_observacion', 'it.id as id_traspaso', 'aa.id as id_almacen_tienda',
                    'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                    'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada',
                    'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad', 'it.fecha_vencimiento as fecha_vencimiento',
                    'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'ir.activo as activo', 'it.id_origen as id_origen',
                    'it.id_destino as id_destino', 'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso',
                    'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name', 'it.name_ori as name_ori',
                    'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2', 'lt.id as id_traslado',
                    'lt.codigo as codigo_traslado', 'lt.tiempo as tiempo', 'lt.observacion as observacion',
                    're.id as id_empleado', 'it.procesado as estado', 'ass.id AS id_sucursal',
                    DB::raw('GREATEST(ir.created_at, ir.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                    'it.id_ingreso as id_ingreso', 'lv.id as id_vehiculo', 'lv.matricula as name_vehiculo'
                )
                ->join('log__traslados as lt', 'lt.id', '=', 'ir.id_traslado')
                ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
                ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'ir.id_user')
                ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
                ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.cod_2', '=', $bus)
      
                ->whereBetween(DB::raw('DATE(ir.created_at)'), [$ini, $fini]) 
              ->orderBy('id', 'desc');
                $recepcion_tda = DB::table('inv__recepcions as ir')
                ->select(
                    'ir.id as id', 'ir.observacion as rec_observacion', 'it.id as id_traspaso', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto',
                    'pp.codigo as cod_prod', 'pp.nombre as name_prod', 'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase',
                    'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario',
                    'ir.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino', 'it.leyenda as leyenda',
                    'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id',
                    'u.name as user_name', 'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                    'lt.id as id_traslado', 'lt.codigo as codigo_traslado', 'lt.tiempo as tiempo', 'lt.observacion as observacion',
                    're.id as id_empleado', 'it.procesado as estado', 'ass.id AS id_sucursal',
                    DB::raw('GREATEST(ir.created_at, ir.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                    'it.id_ingreso as id_ingreso', 'lv.id as id_vehiculo', 'lv.matricula as name_vehiculo'
                )
                ->join('log__traslados as lt', 'lt.id', '=', 'ir.id_traslado')
                ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
                ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'ir.id_user')
                ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
                ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.cod_2', '=', $bus)
              
                ->whereBetween(DB::raw('DATE(ir.created_at)'), [$ini, $fini]) 
               ->orderBy('id', 'desc');
                $resultadoCombinacion = $recepcion_alm->unionAll($recepcion_tda)->paginate(15); 
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
        $primerGuardadoExitoso = false;
        $segundoGuardadoExitoso = false;
        $terceroGuardadoExitoso = false;
        $cuartoGuardadoExitoso =false;
        try {
        // Iniciar una transacción
        DB::beginTransaction();
        $tipo_tienda_almacen='';   
       $cod2 = $request->input('cod_2');
       
       $id_prod_producto = $request->input('id_prod_producto');    
   
       $resultadosAlmacen = DB::table('alm__almacens')
       ->select('id as id', 'codigo as codigo')
       ->where('codigo', $cod2);
   
       $resultadosTienda = DB::table('tda__tiendas')
       ->select('id as id', 'codigo as codigo')
       ->where('codigo', $cod2);
   
       $resultados = $resultadosAlmacen->unionAll($resultadosTienda)->get();
       if ($resultados) {
        if($resultadosAlmacen->count() > 0){
            $nuevoProducto = new Alm_IngresoProducto();
            $nuevoProducto->idalmacen = $request->id_destino;   
            $tipo_tienda_almacen='ALM';
           }
            if($resultadosTienda->count() > 0){
                $nuevoProducto = new Tda_IngresoProducto();
                $nuevoProducto->idtienda = $request->id_destino;
                $tipo_tienda_almacen='TDA';
            }
       }else{
        dd("error");
       }
       
           



   $productos = Prod_Producto::select('id', 'codigo')
            ->where('id','=',$id_prod_producto)
            ->get();
            $update = Inv_Traspaso::find($request->id_traspaso);  
            if ($productos->isEmpty() && $resultados->isEmpty() && $update->isEmpty()) {
                return redirect()->back()->with('error', 'Ha ocurrido un error durante el proceso. Por favor, inténtalo de nuevo.');
       
            } else {
        $nuevoProducto->id_prod_producto = $request->id_prod_producto;
        $nuevoProducto->envase = $request->envase;
        $nuevoProducto->cantidad = $request->cantidad;
        $nuevoProducto->stock_ingreso = $request->cantidad;
        $nuevoProducto->id_tipoentrada = $request->id_tipoentrada;
        $nuevoProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $nuevoProducto->lote = $request->lote;
        $nuevoProducto->registro_sanitario = $request->registro_sanitario;
        $nuevoProducto->id_usuario_registra=auth()->user()->id;
        $nuevoProducto->save();
        $primerGuardadoExitoso = true;  
        

        $ajustePositivo=new Inv_AjustePositivo();
        $ajustePositivo->id_usuario = auth()->user()->id;
        $ajustePositivo->usuario = auth()->user()->name;
        $ajustePositivo->id_usuario_registra = auth()->user()->id;
        $ajustePositivo->id_tipo=$request->id_tipoentrada;
        $ajustePositivo->id_producto_linea=$nuevoProducto->id;
        $ajustePositivo->codigo=$request->cod_prod;
        $ajustePositivo->linea=$request->linea_name;
        $ajustePositivo->producto=$request->name_prod;
        $ajustePositivo->cantidad=$request->cantidad;
        $ajustePositivo->stock=$request->cantidad;
        $ajustePositivo->fecha_ingreso=$nuevoProducto->created_at;
        $ajustePositivo->fecha_vencimiento=$request->fecha_vencimiento;
        $ajustePositivo->lote=$request->lote;
        $ajustePositivo->activo=1;
        $ajustePositivo->id_sucursal=$request->id_sucursal;
        $ajustePositivo->cod=$cod2;
        $ajustePositivo->id_ingreso=$nuevoProducto->id;
        $ajustePositivo->leyenda = $request->leyenda;
        $ajustePositivo->descripcion = $request->numero_traspaso;    
        $ajustePositivo->save();
        $segundoGuardadoExitoso=true;
       // $update = Inv_Traspaso::find($request->id_traspaso);
        $update->procesado=2;//0=pendiente,1=en proceso,2=procesado3=anulado
       $update->save();   
       $terceroGuardadoExitoso=true;     
        $recepcion=new Inv_Recepcion();
        $recepcion->id_traslado=$request->id_traslado;
        $recepcion->observacion=$request->observacion;        
        $recepcion->id_user=auth()->user()->id;
        $recepcion->id_usuario_registra=auth()->user()->id;
        $recepcion->save();
        $cuartoGuardadoExitoso=true;    
        $nuevoProductoID = $nuevoProducto->id;
        $datos = [
            'id_tienda_almacen' => $request->id_destino,              
            'id_ingreso' => $nuevoProductoID,
            'tipo' => $tipo_tienda_almacen,               
        ];
    
DB::table('pivot__modulo_tienda_almacens')->insert($datos);
        
    // Si llegamos aquí sin errores, confirmamos la transacción
    DB::commit();
         }
        } catch (\Throwable $th) {
           
            if ($primerGuardadoExitoso) {
       
                // Eliminar el producto guardado
                $nuevoProducto->delete();
            }
            if ($segundoGuardadoExitoso) {
   
                // Eliminar el producto guardado
                $ajustePositivo->delete();
            }
            if ($terceroGuardadoExitoso) {
        
                // Eliminar el producto guardado
                $update->delete();
            }
            if ($cuartoGuardadoExitoso) {
          
                // Eliminar el producto guardado
                $recepcion->delete();
            }
            if ($primerGuardadoExitoso ==true||$segundoGuardadoExitoso ==true
            ||$terceroGuardadoExitoso ==true||$cuartoGuardadoExitoso ==true) {
                DB::rollback();
              
            }
            
            return response()->json(['error' => $th->getMessage()],500);
        }
      
      
    }

    /**
     * Display the specified resource.
     */
    public function show(Inv_Recepcion $inv_Recepcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inv_Recepcion $inv_Recepcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inv_Recepcion $inv_Recepcion)                    
    {
        $update=Inv_Recepcion::find($request->id);
        $update->observacion=$request->observacion;  
        $update->save();
    }
    public function desactivar(Request $request)
    {
      
        $update = Inv_Recepcion::findOrFail($request->id);
        $update->activo = 0;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {  $update = Inv_Recepcion::findOrFail($request->id);
        $update->activo = 1;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inv_Recepcion $inv_Recepcion)
    {
        //
    }
    public function listarSucursal(){  
        $resultado  = DB::table(DB::raw('(SELECT
        tda__tiendas.id AS id_tienda,
        NULL AS id_almacen,
        tda__tiendas.codigo,
        adm__sucursals.razon_social,
        adm__sucursals.razon_social AS sucursal,
        adm__sucursals.cod AS codigoS,
        "Tienda" AS tipoCodigo,
        tda__tiendas.id AS id_tienda_almacen
        FROM
        tda__tiendas
         JOIN adm__sucursals ON tda__tiendas.idsucursal = adm__sucursals.id
      
            UNION ALL
      
         SELECT
        NULL AS id_tienda,
        aa.id AS id_almacen,
        aa.codigo,
        aa.nombre_almacen AS razon_social,
        ass.razon_social AS sucursal,
        ass.cod AS codigoS,
        "Almacen" AS tipoCodigo,
        aa.id AS id_tienda_almacen
      FROM
        alm__almacens AS aa
      JOIN adm__sucursals AS ass ON ass.id = aa.idsucursal) AS result'))
      ->leftJoin(DB::raw('(SELECT cod_2, COUNT(*) AS veces_repetido
      FROM inv__traspasos
      WHERE procesado IN (0, 1)     
      GROUP BY cod_2) AS traspasos'), 'result.codigo', '=', 'traspasos.cod_2')
      ->select('result.*', DB::raw('IFNULL(traspasos.veces_repetido, 0) AS veces_repetido'))
      ->get();
      
       
           return $resultado;
           
           }
           public function listarTraspaso(Request $request){

              
            $query1 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,'it.procesado as estado',   'ass.id AS id_sucursal',
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'it.id_ingreso as id_ingreso',
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo' )
            ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $query2 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,'it.procesado as estado',   'ass.id AS id_sucursal',
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'it.id_ingreso as id_ingreso',
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $resultado = $query1->unionAll($query2)->get();
        
        return $resultado;
        }
        
        public function listarRetornoTraspaso(Request $request){

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
                                it.numero_traspaso  like '%".$valor."%'
                                or it.leyenda like '%".$valor."%' 
                                or re.nombre like '%".$valor."%'                                
                                or  lv.matricula like '%".$valor."%' 
                                )";
                        }
                        else
                        {
                            $sqls.="and (
                                it.numero_traspaso  like '%".$valor."%'
                                or it.leyenda like '%".$valor."%' 
                                or re.nombre like '%".$valor."%'                                
                                or  lv.matricula like '%".$valor."%' 
                                 )";
                        }
                    }
                    //consulta--------------
                    $query1 = DB::table('inv__traspasos as it')
            ->select(
                'it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,'it.procesado as estado',   'ass.id AS id_sucursal',
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo' )
            ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->whereRaw($sqls)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $query2 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,'it.procesado as estado',   'ass.id AS id_sucursal',
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->whereRaw($sqls)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
                }
            
        $resultado = $query1->unionAll($query2)->get();
        
        return $resultado;
            } else {
                $query1 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo' )
            ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $query2 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2',
                'lt.id as id_traslado','lt.codigo as codigo_traslado','lt.tiempo as  tiempo','lt.observacion as observacion',
                're.id as id_empleado' ,
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
                'lv.id as id_vehiculo','lv.matricula as name_vehiculo')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->leftJoin('log__traslados as lt', 'lt.id_traspaso', '=', 'it.id')
            ->leftJoin('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
            ->leftJoin('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
            ->whereIn('it.procesado', [0, 1])
            ->where('it.activo', '=', 1)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.cod_2', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $resultado = $query1->unionAll($query2)->get();
        
        return $resultado;
            }   
            
        }   
}
