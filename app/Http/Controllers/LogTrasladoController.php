<?php

namespace App\Http\Controllers;

use App\Models\Log_Traslado;
use App\Models\Inv_Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogTrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();
        $bus = $request->query('buscarAlmTdn');
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                            it.numero_traspaso like '%".$valor."%' 
                            or it.leyenda  like '%".$valor."%' 
                            or lv.matricula   like '%".$valor."%' 
                            or  re.nombre  like '%".$valor."%' 
                               
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            it.numero_traspaso like '%".$valor."%' 
                            or it.leyenda  like '%".$valor."%' 
                            or lv.matricula   like '%".$valor."%' 
                            or  re.nombre  like '%".$valor."%' 
                            or  lt.created_at  like '%".$valor."%' 
                            
                          )" ;
                    }
    
                }
                $resultado = DB::table('log__traslados as lt')
                 ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
                 ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                 ->join('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
                 ->join('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
                 ->join('users as u', 'u.id', '=', 'lt.id_user')
                  ->select('lt.id as id', 
                        'lt.codigo as codigo',
                        'lt.tiempo as tiempo',
                        'lt.id_traspaso as id_traspaso',
                        'pte.nombre as tipo_nombre',
                        'it.cantidad__stock_ingreso as cantidad',
                        'it.id_ingreso as id_ingreso',
                        'it.leyenda as leyenda',
                        'it.numero_traspaso as numero_traspaso',
                        'it.name_ori as origen',
                        'it.name_des as destino',
                        'lt.id_empleado as id_empleado',
                         DB::raw('CONCAT_WS(re.nombre, re.papellido, re.sapellido) as nom_completo'),
                        're.celular as celular',
                        'lt.id_vehiculo as id_vehiculo',
                        'lv.matricula as vehiculo',
                        'lv.telefono as tele_vehi',
                        'u.name as user_name',
                        'lt.observacion as observacion',
                        'lt.activo as activo',
                        'it.cod_1 as cod_1',
                        'it.cod_2 as cod_2',
                        DB::raw('GREATEST(lt.created_at, lt.updated_at) as fecha'),
                        'lv.tipo as tipo_vehi' )
                        ->whereRaw($sqls)
                        ->where('it.cod_1', '=', $bus)
                        
                        ->whereDate('lt.created_at', '>=', now()->subDays(30)) ->orderBy('id', 'desc')->paginate(8);

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
        }
        else{
             $resultado = DB::table('log__traslados as lt')
    ->join('inv__traspasos as it', 'it.id', '=', 'lt.id_traspaso')
    ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
    ->join('rrh__empleados as re', 're.id', '=', 'lt.id_empleado')
    ->join('log__vehiculos as lv', 'lv.id', '=', 'lt.id_vehiculo')
    ->join('users as u', 'u.id', '=', 'lt.id_user')
    ->select(
        'lt.id as id',
        'lt.codigo as codigo',
        'lt.tiempo as tiempo',
        'lt.id_traspaso as id_traspaso',
        'pte.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.id_ingreso as id_ingreso',
        'it.leyenda as leyenda',
        'it.numero_traspaso as numero_traspaso',
        'it.name_ori as origen',
        'it.name_des as destino',
        're.id as id_empleado',
        DB::raw('CONCAT_WS(re.nombre, re.papellido, re.sapellido) as nom_completo'),
        're.celular as celular',
        'lt.id_vehiculo as id_vehiculo',
        'lv.matricula as vehiculo',
        'lv.telefono as tele_vehi',
        'u.name as user_name',
        'lt.observacion as observacion',
        'lt.activo as activo',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        DB::raw('GREATEST(lt.created_at, lt.updated_at) as fecha'),
        'lv.tipo as tipo_vehi')
        ->where('it.cod_1', '=', $bus)
        
        ->whereDate('lt.created_at', '>=', now()->subDays(30))->orderBy('id', 'desc')->paginate(8);
     return  
                [ 'pagination'=>
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
        $letracodigo='TRL'; 
      
       $maxcorrelativo = Log_Traslado::select(DB::raw('max(correlativo) as maximo'))
                                      ->get()->toArray();
        $correlativo=$maxcorrelativo[0]['maximo'];
     
        if(is_null($correlativo))
            $correlativo=1;
        else
            $correlativo=$correlativo+1;

      
        if($correlativo>=0 && $correlativo<10)
            $codigo='0000000'.$correlativo;
        if($correlativo>=10 && $correlativo<100)
            $codigo='000000'.$correlativo;
        if($correlativo>=100 && $correlativo<1000)
            $codigo='00000'.$correlativo;
        if($correlativo>=1000 && $correlativo<10000)
            $codigo='0000'.$correlativo;
        if($correlativo>=10000 && $correlativo<100000)
            $codigo='000'.$correlativo;
        if($correlativo>=100000 && $correlativo<1000000)
            $codigo='00'.$correlativo;
        if($correlativo>=1000000 && $correlativo<10000000)
                 $codigo='0'.$correlativo;
        
        //codigo 
         $tralado=new Log_Traslado();
         $tralado->id_traspaso=$request->id_traspaso;
         $tralado->id_empleado=$request->id_empleado;
         $tralado->id_vehiculo=$request->id_vehiculo;
         $tralado->tiempo=$request->time;
         $tralado->codigo=$letracodigo.$codigo;
         $tralado->correlativo=$correlativo;
         $tralado->observacion=$request->observacion;
         $tralado->activo=$request->activo;
         $tralado->id_user = auth()->user()->id;
         $tralado->id_usuario_modifica = auth()->user()->id;
         $tralado->id_usuario_registra = auth()->user()->id;
        $update = Inv_Traspaso::find($request->id_traspaso);
        $update->procesado=1;
         $tralado->save();
       $update->save();
    }
    public function repetidor(Request $request){
        
        $resultados = DB::table('log__traslados as lt')
    ->select('lt.id', 'lt.codigo','lt.correlativo')
    ->where('lt.id_traspaso', '=', $request->id_traspaso)
    ->where('lt.id_empleado', '=', $request->id_empleado)
    ->where('lt.id_vehiculo', '=', $request->id_vehiculo)
    ->get();
    
    return $resultados;
    }

    /**
     * Display the specified resource.
     */
    public function show(Log_Traslado $log_Traslado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log_Traslado $log_Traslado)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log_Traslado $log_Traslado)
    {
        $update=Log_Traslado::find($request->id);
        $update->id_usuario_modifica= auth()->user()->id;
        $update->id_user = auth()->user()->id;
        $update->id_empleado=$request->id_empleado;
        $update->id_vehiculo=$request->id_vehiculo;
        $update->tiempo=$request->time;
        $update->observacion=$request->observacion;
        $update->save();   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log_Traslado $log_Traslado)
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
->leftJoin(DB::raw('(SELECT cod_1, COUNT(*) AS veces_repetido
FROM inv__traspasos
WHERE procesado = 0
GROUP BY cod_1) AS traspasos'), 'result.codigo', '=', 'traspasos.cod_1')
->select('result.*', DB::raw('IFNULL(traspasos.veces_repetido, 0) AS veces_repetido'))
->get();

 
         $jsonSucrusal = [];
 
 foreach ($resultado as $key=>$sucursal) {
     $elemento = [
         'id' => $key,
         'id_tienda' => $sucursal->id_tienda,
         'id_almacen' => $sucursal->id_almacen,
         'codigo' => $sucursal->codigo,
         'razon_social' => $sucursal->razon_social,
         'sucursal' => $sucursal->sucursal,
         'codigoS' => $sucursal->codigoS,
         'tipoCodigo' =>$sucursal->tipoCodigo,
         'id_tienda_almacen' => $sucursal->id_tienda_almacen,
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $resultado;
     
     }

     public function listarTraspaso(Request $request){

              
        $query1 = DB::table('inv__traspasos as it')
        ->select('it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
            'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
            'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
            'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
            'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
        ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
        ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
        ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
        ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
        ->join('users as u', 'u.id', '=', 'it.user_id')
        ->where('it.procesado', '=', 0)
        ->where('it.activo', '=', 1)
        ->where('it.id_tipoentrada', '=', 13)
        ->where('it.cod_1', '=', $request->codigo)
        ->whereDate('it.created_at', '>=', now()->subDays(30));
    
    $query2 = DB::table('inv__traspasos as it')
        ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
            'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
            'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
            'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
            'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
        ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
        ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
        ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
        ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
        ->join('users as u', 'u.id', '=', 'it.user_id')
        ->where('it.procesado', '=', 0)
        ->where('it.activo', '=', 1)
        ->where('it.id_tipoentrada', '=', 13)
        ->where('it.cod_1', '=', $request->codigo)
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
                            pp.codigo like '%".$valor."%' 
                            or pp.nombre like '%".$valor."%' 
                            or it.registro_sanitario like '%".$valor."%'                     
                           
                            or  it.id_destino like '%".$valor."%' 
                            or  it.numero_traspaso  like '%".$valor."%'
                            or  it.lote  like '%".$valor."%' 
                            )";
                    }
                    else
                    {
                        $sqls.="and ( pp.codigo like '%".$valor."%' 
                        or pp.nombre like '%".$valor."%' 
                        or it.registro_sanitario like '%".$valor."%'                     
                        or  it.id_destino like '%".$valor."%  
                        or  it.numero_traspaso  like '%".$valor."%'  
                        or  it.lote  like '%".$valor."%'                  
                       )";
                    }
                }
       
                $query1 = DB::table('inv__traspasos as it')
                ->select('it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                    'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                    'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                    'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
                ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'it.user_id')
                ->where('it.procesado', '=', 0)
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.activo', '=', 1)
                ->whereRaw($sqls)
                ->where('it.cod_1', '=', $request->codigo)
                ->whereDate('it.created_at', '>=', now()->subDays(30));
            
            $query2 = DB::table('inv__traspasos as it')
                ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                    'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                    'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                    'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
                ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
                ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
                ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
                ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
                ->join('users as u', 'u.id', '=', 'it.user_id')
                ->where('it.procesado', '=', 0)
                ->where('it.id_tipoentrada', '=', 13)
                ->where('it.activo', '=', 1)
                ->whereRaw($sqls)
                ->where('it.cod_1', '=', $request->codigo)
                ->whereDate('it.created_at', '>=', now()->subDays(30));
             
            }
            $resultado = $query1->unionAll($query2)->get();
            return $resultado;
        }else{
            $query1 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'aa.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
            ->join('alm__almacens as aa', 'aa.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->where('it.procesado', '=', 0)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.activo', '=', 1)
            ->where('it.cod_1', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
        
        $query2 = DB::table('inv__traspasos as it')
            ->select('it.id as id', 'tt.id as id_almacen_tienda', 'it.id_prod_producto as id_prod_producto', 'pp.codigo as cod_prod', 'pp.nombre as name_prod',
                'pl.id as pl_id', 'pl.nombre as linea_name', 'it.envase as envase', 'it.id_tipoentrada as id_tipoentrada', 'pte.nombre as tipo_name', 'it.cantidad__stock_ingreso as cantidad',
                'it.fecha_vencimiento as fecha_vencimiento', 'it.lote as lote', 'it.registro_sanitario as registro_sanitario', 'it.activo as activo', 'it.id_origen as id_origen', 'it.id_destino as id_destino',
                'it.leyenda as leyenda', 'it.glosa as glosa', 'it.numero_traspaso as numero_traspaso', 'it.procesado as procesado', 'u.id as user_id', 'u.name as user_name',
                'it.name_ori as name_ori', 'it.name_des as name_des', 'it.cod_1 as cod_1', 'it.cod_2 as cod_2')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'it.cod_1')
            ->join('prod__productos as pp', 'pp.id', '=', 'it.id_prod_producto')
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('prod__tipo_entradas as pte', 'pte.id', '=', 'it.id_tipoentrada')
            ->join('users as u', 'u.id', '=', 'it.user_id')
            ->where('it.procesado', '=', 0)
            ->where('it.id_tipoentrada', '=', 13)
            ->where('it.activo', '=', 1)
            ->where('it.cod_1', '=', $request->codigo)
            ->whereDate('it.created_at', '>=', now()->subDays(30));
            $resultado = $query1->unionAll($query2)->get();
            
            return $resultado;
        }
      

     }
     public function listarUsuario(){
        $respuesta = DB::table('users as u')
        ->select(
            'u.id as user_id',
            'u.name as user_name',
            're.id as emple_id',
            DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo"),
            'rc.id as id_cargo',
            'rc.nombre as cargo'
        )
        ->join('rrh__empleados as re', 'u.idempleado', '=', 're.id')
        ->join('rrh__cargos as rc', 're.idcargo', '=', 'rc.id')
        ->where('re.activo', '=', 1)
        ->get();
        return $respuesta;
    }

    public function listarVehiculo(Request $request){
      
        $result = DB::table('log__asignacion_sucursal_vehiculos as lasv')
    ->select('lasv.cod as codigo', 'lasv.id_vehiculo as id_vehiculo', 'lv.tipo as tipo_vehiculo', 'lv.matricula as matricula', 'lv.telefono as telefono', 
        'aa.nombre_almacen as nombre_local', 'ass.cod as codigo_sucursal', 'ass.razon_social')
    ->join('log__vehiculos as lv', 'lv.id', '=', 'lasv.id_vehiculo')
    ->join('alm__almacens as aa', 'aa.codigo', '=', 'lasv.cod')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'lasv.id_sucursal')
    ->where('lv.activo', 1)
    ->where('lasv.cod', $request->codigo)
    ->unionAll(
        DB::table('log__asignacion_sucursal_vehiculos as lasv')
            ->select('lasv.cod as codigo', 'lasv.id_vehiculo as id_vehiculo', 'lv.tipo as tipo_vehiculo', 'lv.matricula as matricula', 'lv.telefono as telefono', 
                'ass.razon_social as nombre_local', 'ass.cod as codigo_sucursal', 'ass.razon_social')
            ->join('log__vehiculos as lv', 'lv.id', '=', 'lasv.id_vehiculo')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'lasv.cod')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'lasv.id_sucursal')
            ->where('lv.activo', 1)
            ->where('lasv.cod', $request->codigo)
    )
    ->get();
    
    return $result;
    }
    public function desactivar(Request $request)
    {
        $result = DB::table('log__traslados as lt')
    ->join('inv__traspasos as it', 'lt.id_traspaso', '=', 'it.id')
    ->select('lt.id_traspaso')
    ->first();
        $update2 = Inv_Traspaso::findOrFail($result->id_traspaso);
        $update2->procesado=0;
        $update = Log_Traslado::findOrFail($request->id);
        $update->activo = 0;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
        $update2->save();
    }

    public function activar(Request $request)
    {  
        $result = DB::table('log__traslados as lt')
    ->join('inv__traspasos as it', 'lt.id_traspaso', '=', 'it.id')
    ->select('lt.id_traspaso')
    ->first();
        $update2 = Inv_Traspaso::findOrFail($result->id_traspaso);
        $update2->procesado=1;
        $update = Log_Traslado::findOrFail($request->id);
        $update->activo = 1;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
        $update2->save();
    }

}
