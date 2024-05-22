<?php

namespace App\Http\Controllers;

use App\Models\Alm_IngresoProducto2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class AlmIngresoProducto2Controller extends Controller
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
    public function show(Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

    public function listarProductos_almacen(Request $request){

        $primario = DB::table('prod__productos as pp')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
    ->join('prod__dispensers AS pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
    ->join('prod__forma_farmaceuticas AS ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('pp.almacenprimario', 1)
    ->where('pp.tiendaprimario', 0)
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
    ->where('pp.almacensecundario', 1)
    ->where('pp.tiendasecundario', 0)
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
    ->where('pp.almacenterciario', 1)
    ->where('pp.tiendaterciario', 0)
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

   

   public function listaAlmacen(Request $request){
    $iduserrolesuc = session('iduserrolesuc');
    $idsuc = session('idsuc');
    $id_user2 = session('id_user2'); 
    $user_1 = auth()->user()->id;
    $user_2 = auth()->user()->name;
    
    if ($user_1 == 1 && $user_2 == 'admin') {
        // Uniendo ambas consultas para el administrador
      

        $resultadoConsulta = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(
                DB::raw('NULL as id_tienda'),
                'aa.id as id_almacen',
                'aa.codigo',
                'aa.nombre_almacen as razon_social',
                'ass.razon_social as sucursal',
                'ass.cod as codigoS',
                DB::raw('"Almacen" as tipoCodigo'),
                'aa.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)->get();

        
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
      
        
        $resultadoConsulta = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(
                DB::raw('NULL as id_tienda'),
                'aa.id as id_almacen',
                'aa.codigo',
                'aa.nombre_almacen as razon_social',
                'ass.razon_social as sucursal',
                'ass.cod as codigoS',
                DB::raw('"Almacen" as tipoCodigo'),
                'aa.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)
            ->whereRaw($where1)->get();
        
       
        return $resultadoConsulta;
    }
   }
}
