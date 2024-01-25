<?php

namespace App\Http\Controllers;

use App\Models\Log_Traslado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogTrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log_Traslado $log_Traslado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log_Traslado $log_Traslado)
    {
        //
    }
    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS',
         DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as id_tienda_almacen')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',
        DB::raw('"Almacen" as tipoCodigo'),'aa.id as id_tienda_almacen');

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
         'id_tienda_almacen' => $sucursal->id_tienda_almacen
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $jsonSucrusal;
     
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
        ->where('it.id_tipoentrada', '=', 13)
        ->where('it.cod_1', '=', $request->codigo);
    
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
        ->where('it.cod_1', '=', $request->codigo);
    
    $resultado = $query1->unionAll($query2)->get();
    
    return $resultado;
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
     
        ->get();
        return $respuesta;
    }
    public function listarVehiculo(){
        
    }
}
