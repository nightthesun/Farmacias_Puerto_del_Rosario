<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVentaVista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenGestorVentaVistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sucursalId = $request->id_sucursal;
$codigo = $request->codigo_tienda_almacen;
$startDate = $request->startDate;
$endDate = $request->endDate;

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
                            u.name like '%".$valor."%' 
                                or dc.nom_a_facturar like '%".$valor."%' 
                                or dc.num_documento like '%".$valor."%'                              
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            u.name like '%".$valor."%' 
                                or dc.nom_a_facturar like '%".$valor."%' 
                                or dc.num_documento like '%".$valor."%'  
                          )" ;
                    }    
                }
                $ventas_show = DB::table('ven__recibos as vr')
    ->join('dir__clientes as dc', 'vr.id_cliente', '=', 'dc.id')
    ->join('users as u', 'u.id', '=', 'vr.id_usuario')
    ->select(
        'vr.id',
        'vr.id_sucursal',
        'vr.id_cliente',
        'vr.nro_comprobante_venta',
        'vr.total_venta',
        'vr.efectivo_venta',
        'vr.cambio_venta',
        'vr.descuento_venta',
        'vr.estado_venta',
        'vr.anulado',
        'vr.created_at',
        'vr.total_sin_des',
        DB::raw("CASE
                    WHEN vr.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN vr.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE NULL
                 END AS tipo_venta_reci_fac"),
        'vr.razon_social as nom_a_facturar',
        'vr.nro_doc as num_documento',
        'u.name',
        'vr.cod',
        'nro_ref as numero_referencia'
    )
    ->where('vr.id_sucursal', $sucursalId)
    ->where('vr.cod', $codigo)
    ->whereBetween(DB::raw('DATE(vr.created_at)'), [$startDate, $endDate])
    ->whereRaw($sqls)
    ->orderByDesc('vr.id')
    ->paginate(15);

            }   
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $ventas_show->total(),
                            'current_page'  =>    $ventas_show->currentPage(),
                            'per_page'      =>    $ventas_show->perPage(),
                            'last_page'     =>    $ventas_show->lastPage(),
                            'from'          =>    $ventas_show->firstItem(),
                            'to'            =>    $ventas_show->lastItem(),
                        ] ,
                    'ventas_show'=>$ventas_show,
            ]; 
        } else {
            $ventas_show = DB::table('ven__recibos as vr')
    ->join('dir__clientes as dc', 'vr.id_cliente', '=', 'dc.id')
    ->join('users as u', 'u.id', '=', 'vr.id_usuario')
    ->select(
        'vr.id',
        'vr.id_sucursal',
        'vr.id_cliente',
        'vr.nro_comprobante_venta',
        'vr.total_venta',
        'vr.efectivo_venta',
        'vr.cambio_venta',
        'vr.descuento_venta',
        'vr.estado_venta',
        'vr.anulado',
        'vr.created_at',
        'vr.total_sin_des',
        DB::raw("CASE
                    WHEN vr.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN vr.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE NULL
                 END AS tipo_venta_reci_fac"),
                 'vr.razon_social as nom_a_facturar',
                 'vr.nro_doc as num_documento',
        'u.name',
        'vr.cod',
        'nro_ref as numero_referencia'
    )
    ->where('vr.id_sucursal', $sucursalId)
    ->where('vr.cod', $codigo)
    ->whereBetween(DB::raw('DATE(vr.created_at)'), [$startDate, $endDate])
   
    ->orderByDesc('vr.id')
    ->paginate(15);
    return 
    [
            'pagination'=>
                [
                    'total'         =>    $ventas_show->total(),
                    'current_page'  =>    $ventas_show->currentPage(),
                    'per_page'      =>    $ventas_show->perPage(),
                    'last_page'     =>    $ventas_show->lastPage(),
                    'from'          =>    $ventas_show->firstItem(),
                    'to'            =>    $ventas_show->lastItem(),
                ] ,
            'ventas_show'=>$ventas_show,
    ];
        }
        

   
    }
   
}
