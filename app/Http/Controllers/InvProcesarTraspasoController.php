<?php

namespace App\Http\Controllers;

use App\Models\Inv_ProcesarTraspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;

class InvProcesarTraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();
        $bus = $request->query('identificador');
 
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                if ($bus=="1") {
                 foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                       $sqls = "(
                                it.numero_traspaso like '%" . $valor . "%' 
                                or it.envase like '%" . $valor . "%' 
                                or it.name_ori like '%" . $valor . "%'
                                or it.name_des like '%" . $valor . "%' 
                                or it.leyenda like '%" . $valor . "%'
                                or it.cod_1 like '%" . $valor . "%'
                                or it.cod_2 like '%" . $valor . "%' 
                                or pp.nombre like '%" . $valor . "%' 
                               )";
                    } else {
                        $sqls .= "and (
                            it.numero_traspaso like '%" . $valor . "%' 
                            or it.envase like '%" . $valor . "%' 
                            or it.name_ori like '%" . $valor . "%'
                            or it.name_des like '%" . $valor . "%' 
                            or it.leyenda like '%" . $valor . "%'
                            or it.cod_1 like '%" . $valor . "%'
                            or it.cod_2 like '%" . $valor . "%'
                            or pp.nombre like '%" . $valor . "%' 
                       )";
                }
               // foreach ($buscararray as $valor) {
               //     if (empty($sqls)) {
                //        $sqls = "(
                 //               it.numero_traspaso like '%" . $valor . "%' 
                 //               or it.envase like '%" . $valor . "%' 
                 //               or it.name_ori like '%" . $valor . "%'
                 //               or it.name_des like '%" . $valor . "%' 
                 //               or it.leyenda like '%" . $valor . "%'
                 //               or it.cod_1 like '%" . $valor . "%'
                 //               or it.cod_2 like '%" . $valor . "%' 
                 //              )";
                 //   } else {
                 //       $sqls .= "and (
                 //           it.numero_traspaso like '%" . $valor . "%' 
                 //           or it.envase like '%" . $valor . "%' 
                 //           or it.name_ori like '%" . $valor . "%'
                 //           or it.name_des like '%" . $valor . "%' 
                 //           or it.leyenda like '%" . $valor . "%'
                 //           or it.cod_1 like '%" . $valor . "%'
                 //           or it.cod_2 like '%" . $valor . "%'
                 //      )";

                    }
                }
    $traspasos_alm = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as cod_prod',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.created_at as fecha_traspaso',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('alm__almacens as aa_1', 'aa_1.codigo', '=', 'it.cod_1')
    ->join('alm__ingreso_producto as ai_1', 'ai_1.id', '=', 'it.id_ingreso')
    ->leftJoin('alm__almacens as aa_2', 'aa_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');

    $traspasos_tienda = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as cod_prod',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.created_at as fecha_traspaso',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('tda__tiendas as tt_1', 'tt_1.codigo', '=', 'it.cod_1')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt_1.idsucursal')
    ->join('tda__ingreso_productos as ti_1', 'ti_1.id', '=', 'it.id_ingreso')
    ->leftJoin('tda__tiendas as tt_2', 'tt_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');
    $reusltadocombinado = $traspasos_alm
  
    ->whereRaw($sqls)
    ->orderBy('id', 'desc')
    ->unionAll($traspasos_tienda
        
        ->whereRaw($sqls)
        ->orderBy('id', 'desc')
    )->paginate(15);
   
            }
            
        
            return
                [
                    'pagination' =>
                    [
                        'total'         =>    $reusltadocombinado->total(),
                        'current_page'  =>    $reusltadocombinado->currentPage(),
                        'per_page'      =>    $reusltadocombinado->perPage(),
                        'last_page'     =>    $reusltadocombinado->lastPage(),
                        'from'          =>    $reusltadocombinado->firstItem(),
                        'to'            =>    $reusltadocombinado->lastItem(),
                    ],
                    'reusltadocombinado' => $reusltadocombinado,
                ];
        } else {

            $traspasos_alm = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as cod_prod',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.created_at as fecha_traspaso',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('alm__almacens as aa_1', 'aa_1.codigo', '=', 'it.cod_1')
    ->join('alm__ingreso_producto as ai_1', 'ai_1.id', '=', 'it.id_ingreso')
    ->leftJoin('alm__almacens as aa_2', 'aa_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');

    $traspasos_tienda = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as cod_prod',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.created_at as fecha_traspaso',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('tda__tiendas as tt_1', 'tt_1.codigo', '=', 'it.cod_1')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt_1.idsucursal')
    ->join('tda__ingreso_productos as ti_1', 'ti_1.id', '=', 'it.id_ingreso')
    ->leftJoin('tda__tiendas as tt_2', 'tt_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');
    
    $reusltadocombinado = $traspasos_alm
    
    ->orderBy('id', 'desc')
    ->unionAll($traspasos_tienda
   
    ->orderBy('id', 'desc'))

                    ->paginate(15);
            return [
                'pagination' => [
                    'total'         =>    $reusltadocombinado->total(),
                    'current_page'  =>    $reusltadocombinado->currentPage(),
                    'per_page'      =>    $reusltadocombinado->perPage(),
                    'last_page'     =>    $reusltadocombinado->lastPage(),
                    'from'          =>    $reusltadocombinado->firstItem(),
                    'to'            =>    $reusltadocombinado->lastItem(),
                ],
                'reusltadocombinado' => $reusltadocombinado,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inv_ProcesarTraspaso $inv_ProcesarTraspaso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inv_ProcesarTraspaso $inv_ProcesarTraspaso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inv_ProcesarTraspaso $inv_ProcesarTraspaso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inv_ProcesarTraspaso $inv_ProcesarTraspaso)
    {
        //
    }
    public function listarUsuario(){
        $resultado = DB::table('rrh__empleados as re')
        ->join('users as u', 're.id', '=', 'u.idempleado')
        ->select(
            're.id as id_empleado',
            're.nombre',
            're.papellido',
            're.sapellido',
            DB::raw('CONCAT_WS(" ", re.papellido, re.sapellido) as apellidos_combinados'),
            'u.id as id_user',
            'u.name as nom_user'
        )
        ->get();
    return $resultado;
    }
    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'))
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'));

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
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $jsonSucrusal;
     
     }

     public function listarTraspaso(Request $request){
       $buscararray=array();
       if (!empty($request->busqueda)) {
        $buscararray = explode(" ",$request->busqueda);
        $valor = sizeof($buscararray);
        if($valor > 0){
            $sqls='';
            foreach($buscararray as $valor)
            {
                if(empty($sqls)){
                    # code...
                }
                else{

                }
            }
            //consulta

        }
       } 
     }

     
}
