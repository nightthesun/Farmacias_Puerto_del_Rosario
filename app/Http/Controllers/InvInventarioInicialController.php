<?php

namespace App\Http\Controllers;

use App\Models\Inv_InventarioInicial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvInventarioInicialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

    public function getSelectProducto(){
       
$primario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadprimario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'primario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispenserprimario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario');

$secundario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadsecundario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'secundario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispensersecundario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticasecundario');

$terciario = DB::table('prod__productos as pp')
    ->select(
        'pp.id as id_prod',
        'pp.nombre as name_prod',
        'pl.id as id_linea',
        'pl.nombre as name_linea',
        'pp.cantidadterciario as cantidad_dis',
        'pd.nombre as name_dis',
        'pff.nombre as name_forma',
        DB::raw("'terciario' as tipo")
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('prod__dispensers as pd', 'pd.id', '=', 'pp.iddispenserterciario')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaterciario');

$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->orderBy('id_prod', 'desc')
    ->get();
 return $resultado;
    }

}