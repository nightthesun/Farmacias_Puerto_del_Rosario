<?php

namespace App\Http\Controllers;

use App\Models\Inv_MetodoABC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvMetodoABCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fecha_inicio = $request->startDate;
        $fecha_fin = $request->endDate;
        $id_sucursal= $request->id_sucursal;

/* ================= PRIMARIO ================= */
$primario = DB::table('ven__detalle_ventas as v1')
    ->join('ven__recibos as v2', 'v2.id', '=', 'v1.id_venta')
    ->join('prod__productos as p', 'p.id', '=', 'v1.id_producto')
    ->join('prod__lineas as l', 'l.id', '=', 'v1.id_linea')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'p.iddispenserprimario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'p.idformafarmaceuticaprimario')
    ->selectRaw("
        CONCAT(
            COALESCE(p.nombre,''),' ',
            COALESCE(pd_1.nombre,''),' x ',
            COALESCE(p.cantidadprimario,''),' ',
            COALESCE(ff_1.nombre,'')
        ) as leyenda,
        l.nombre as nombre_linea,
        v1.id_ingreso,
        v1.envase,
        p.codigo,
        v1.precio_venta,
        SUM(v1.cantidad_venta) as total_cantidad
    ")
    ->where('v2.anulado', 0)
    ->where('v2.id_sucursal', $id_sucursal)
    ->where('v1.envase', 'primario')
    ->whereBetween('v2.created_at', [$fecha_inicio, $fecha_fin])
    ->groupBy(
        'v1.id_ingreso',
        'v1.id_producto',
        'v1.envase',
        'v1.precio_venta',
        'p.nombre',
        'pd_1.nombre',
        'p.cantidadprimario',
        'ff_1.nombre',
        'l.nombre','p.codigo'
    );

/* ================= SECUNDARIO ================= */
$secundario = DB::table('ven__detalle_ventas as v1')
    ->join('ven__recibos as v2', 'v2.id', '=', 'v1.id_venta')
    ->join('prod__productos as p', 'p.id', '=', 'v1.id_producto')
    ->join('prod__lineas as l', 'l.id', '=', 'v1.id_linea')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'p.iddispensersecundario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'p.idformafarmaceuticasecundario')
    ->selectRaw("
        CONCAT(
            COALESCE(p.nombre,''),' ',
            COALESCE(pd_1.nombre,''),' x ',
            COALESCE(p.cantidadsecundario,''),' ',
            COALESCE(ff_1.nombre,'')
        ) as leyenda,
        l.nombre as nombre_linea,
        v1.id_ingreso,
        v1.envase,
        p.codigo,
        v1.precio_venta,
        SUM(v1.cantidad_venta) as total_cantidad
    ")
    ->where('v2.anulado', 0)
    ->where('v2.id_sucursal', $id_sucursal)
    ->where('v1.envase', 'secundario')
    ->whereBetween('v2.created_at', [$fecha_inicio, $fecha_fin])
    ->groupBy(
        'v1.id_ingreso',
        'v1.id_producto',
        'v1.envase',
        'v1.precio_venta',
        'p.nombre',
        'pd_1.nombre',
        'p.cantidadsecundario',
        'ff_1.nombre',
        'l.nombre','p.codigo'
    );

/* ================= TERCIARIO ================= */
$terciario = DB::table('ven__detalle_ventas as v1')
    ->join('ven__recibos as v2', 'v2.id', '=', 'v1.id_venta')
    ->join('prod__productos as p', 'p.id', '=', 'v1.id_producto')
    ->join('prod__lineas as l', 'l.id', '=', 'v1.id_linea')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'p.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'p.idformafarmaceuticaterciario')
    ->selectRaw("
        CONCAT(
            COALESCE(p.nombre,''),' ',
            COALESCE(pd_1.nombre,''),' x ',
            COALESCE(p.cantidadterciario,''),' ',
            COALESCE(ff_1.nombre,'')
        ) as leyenda,
        l.nombre as nombre_linea,
        v1.id_ingreso,
        v1.envase,
        p.codigo,
        v1.precio_venta,
        SUM(v1.cantidad_venta) as total_cantidad
    ")
    ->where('v2.anulado', 0)
    ->where('v2.id_sucursal', $id_sucursal)
    ->where('v1.envase', 'terciario')
    ->whereBetween('v2.created_at', [$fecha_inicio, $fecha_fin])
    ->groupBy(
        'v1.id_ingreso',
        'v1.id_producto',
        'v1.envase',
        'v1.precio_venta',
        'p.nombre',
        'pd_1.nombre',
        'p.cantidadterciario',
        'ff_1.nombre',
        'l.nombre','p.codigo'
    );

/* ================= UNION ================= */
$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->get(); 
    return $resultado;
    }

    
}
