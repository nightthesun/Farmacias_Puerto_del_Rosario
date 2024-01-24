<?php

namespace App\Http\Controllers;

use App\Models\Log_Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Log_AsignacionSucursalVehiculo;

class LogVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();
        

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
                                iv.razon_social like '%".$valor."%' 
                                or iv.matricula like '%".$valor."%' 
                                or iv.tipo like '%".$valor."%' 
                                or u.name like '%".$valor."%'
                                or re.nombre like '%".$valor."%' 
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            iv.razon_social like '%".$valor."%' 
                            or iv.matricula like '%".$valor."%' 
                            or iv.tipo like '%".$valor."%' 
                            or u.name like '%".$valor."%' 
                            or re.nombre like '%".$valor."%' 
                          )" ;
                    }
    
                }

                $almacenes =  DB::table('log__vehiculos as iv')
                ->join('alm__almacens as aa', function ($join) {
                    $join->on('aa.id', '=', 'iv.idsucursal')
                        ->on('aa.codigo', '=', 'iv.nombre_comercial');
                })
                ->join('users as u', 'u.id', '=', 'iv.id_user')
                ->leftJoin('users as u2', 'u2.id', '=', 'iv.id_emple')
                ->leftJoin('rrh__empleados as re', 'u2.idempleado', '=', 're.id')
                ->select(
                    'iv.id as id',
                    'iv.razon_social as razon_social',
                    'iv.idsucursal as idsucursal',
                    'iv.matricula as matricula',
                    'aa.codigo as codigo',
                    'iv.telefono as telefono',
                    'iv.tipo as tipo',
                    'iv.estado as estado',
                    'iv.activo as activo',
                    'u.id as id_user',
                    'u.name as user_name',
                    'iv.id_emple as id_emple',
                    DB::raw('GREATEST(iv.created_at, iv.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo")
                    
                );
                $tiendas= DB::table('log__vehiculos as iv')
                ->join('tda__tiendas as tt', function ($join) {
                    $join->on('tt.id', '=', 'iv.idsucursal')
                        ->on('tt.codigo', '=', 'iv.nombre_comercial');
                })
                ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
                ->join('users as u', 'u.id', '=', 'iv.id_user')
                ->leftJoin('users as u2', 'u2.id', '=', 'iv.id_emple')
                ->leftJoin('rrh__empleados as re', 'u2.idempleado', '=', 're.id')
                ->select(
                    'iv.id as id',
                    'iv.razon_social as razon_social',
                    'iv.idsucursal as idsucursal',
                    'iv.matricula as matricula',
                    'tt.codigo as codigo',
                    'iv.telefono as telefono',
                    'iv.tipo as tipo',
                    'iv.estado as estado',
                    'iv.activo as activo',
                    'u.id as id_user',
                    'u.name as user_name',
                    'iv.id_emple as id_emple',
                    DB::raw('GREATEST(iv.created_at, iv.updated_at) AS fecha'),
                    DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo")
                );
                $resultadocombinado = $almacenes 
                ->whereRaw($sqls)
                ->orderBy('id', 'desc')
                ->unionAll($tiendas
                ->whereRaw($sqls)
                    ->orderBy('id', 'desc')
                )->paginate(15);
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
        }else {
           
            $almacenes =  DB::table('log__vehiculos as iv')
            ->join('alm__almacens as aa', function ($join) {
                $join->on('aa.id', '=', 'iv.idsucursal')
                    ->on('aa.codigo', '=', 'iv.nombre_comercial');
            })
            ->join('users as u', 'u.id', '=', 'iv.id_user')
            ->leftJoin('users as u2', 'u2.id', '=', 'iv.id_emple')
            ->leftJoin('rrh__empleados as re', 'u2.idempleado', '=', 're.id')
            ->select(
                'iv.id as id',
                'iv.razon_social as razon_social',
                'iv.idsucursal as idsucursal',
                'iv.matricula as matricula',
                'aa.codigo as codigo',
                'iv.telefono as telefono',
                'iv.tipo as tipo',
                'iv.estado as estado',
                'iv.activo as activo',
                'u.id as id_user',
                'u.name as user_name',
                'iv.id_emple as id_emple',
                DB::raw('GREATEST(iv.created_at, iv.updated_at) AS fecha'),
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo")
            );
            $tiendas= DB::table('log__vehiculos as iv')
            ->join('tda__tiendas as tt', function ($join) {
                $join->on('tt.id', '=', 'iv.idsucursal')
                    ->on('tt.codigo', '=', 'iv.nombre_comercial');
            })
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->join('users as u', 'u.id', '=', 'iv.id_user')
            ->leftJoin('users as u2', 'u2.id', '=', 'iv.id_emple')
            ->leftJoin('rrh__empleados as re', 'u2.idempleado', '=', 're.id')
            ->select(
                'iv.id as id',
                'iv.razon_social as razon_social',
                'iv.idsucursal as idsucursal',
                'iv.matricula as matricula',
                'tt.codigo as codigo',
                'iv.telefono as telefono',
                'iv.tipo as tipo',
                'iv.estado as estado',
                'iv.activo as activo',
                'u.id as id_user',
                'u.name as user_name',
                'iv.id_emple as id_emple',
                DB::raw('GREATEST(iv.created_at, iv.updated_at) AS fecha'),
                DB::raw("CONCAT_WS(' ', re.nombre, re.papellido, re.sapellido) as nom_completo")
            );
            $resultadocombinado = $almacenes 
            ->orderBy('id', 'desc')
            ->unionAll($tiendas
            
                ->orderBy('id', 'desc')
            )->paginate(15);
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
        $vehiculo = new Log_Vehiculo();
        $vehiculo->idsucursal = $request->id_tienda_almacen;
        $vehiculo->matricula = $request->matricula;
        $vehiculo->razon_social = $request->razon_social_des;
        $vehiculo->nombre_comercial = $request->codigo;
        $vehiculo->telefono = $request->telefono;
        $vehiculo->id_emple = $request->id_emple;
        $vehiculo->tipo = $request->selectTipo;
        $vehiculo->estado = $request->estado;
        $vehiculo->id_user = auth()->user()->id;
        $vehiculo->id_usuario_registra = auth()->user()->id;

        $vehiculo->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Log_Vehiculo $log_Vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log_Vehiculo $log_Vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log_Vehiculo $log_Vehiculo)
    {
        $vehiculo =Log_Vehiculo::find($request->id);
        $vehiculo->idsucursal = $request->id_tienda_almacen;
        $vehiculo->matricula = $request->matricula;
        $vehiculo->razon_social = $request->razon_social_des;
        $vehiculo->nombre_comercial = $request->codigo;
        $vehiculo->telefono = $request->telefono;
        $vehiculo->id_emple = $request->id_emple;
        $vehiculo->tipo = $request->selectTipo;     
        $vehiculo->id_user = auth()->user()->id;
        $vehiculo->id_usuario_modifica = auth()->user()->id;

        $vehiculo->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log_Vehiculo $log_Vehiculo)
    {
        //
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
        ->where('rc.id', '=', 18)
        ->get();
        return $respuesta;
    }
    public function desactivar(Request $request)
    {
      
        $update = Log_Vehiculo::findOrFail($request->id);
        $update->activo = 0;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {  $update = Log_Vehiculo::findOrFail($request->id);
        $update->activo = 1;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function asignar(Request $request, Log_AsignacionSucursalVehiculo $Log_AsignacionSucursalVehiculo ){
       // Buscar asignaciones existentes para el vehículo
       $asignarExistente = Log_AsignacionSucursalVehiculo::where('id_vehiculo', $request->id)->get();

       // Si existen asignaciones, eliminarlas
       if ($asignarExistente->count() > 0) {
           Log_AsignacionSucursalVehiculo::where('id_vehiculo', $request->id)->delete();
       }
     
       $bloque = $request->bloque;
           foreach ($bloque as $item) {
            $codigo = $item['codigo'];
            $idSucursal = $item['id_sucursal'];
            $idTiendaAlmacen = $item['id_tienda_almacen'];
            $datos = [
                'id_vehiculo' => $request->id,
                'id_sucursal' => $idSucursal,
                'id_alm_tda' => $idTiendaAlmacen,
                'cod' => $codigo,
            ];
        
            DB::table('log__asignacion_sucursal_vehiculos')->insert($datos);
         
         }
       
       
       
    
    }
    public function listarAsignar(){
        $datos = DB::table('log__asignacion_sucursal_vehiculos')->get();
        return $datos;
    }

    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS',
         DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as id_tienda_almacen', 'tda__tiendas.idsucursal as id_sucursal')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',
        DB::raw('"Almacen" as tipoCodigo'),'aa.id as id_tienda_almacen','aa.idsucursal as id_sucursal');

    $result = $tiendas->unionAll($almacenes)->get();
 
 
         $jsonSucrusal = [];
 
 foreach ($result as $key=>$sucursal) {
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
         'id_sucursal' => $sucursal->id_sucursal
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $jsonSucrusal;
     
     }
}
