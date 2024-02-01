<?php

namespace App\Http\Controllers;

use App\Models\Inv_Recepcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvRecepcionController extends Controller
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
        //
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
