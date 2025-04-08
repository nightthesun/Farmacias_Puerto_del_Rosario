<?php

namespace App\Http\Controllers;

use App\Models\Siat_Homologacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatHomologacionController extends Controller
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

  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siat_Homologacion $siat_Homologacion)
    {
        //
    }

    public function getCodigoActividadProducto(){
        $homo = DB::table('excel__emision')
        ->where('id_catalogo', 16)
        ->get();
        return $homo;    
    }

    public function getProductoHomo(){
        $productos = DB::table('prod__productos')
    ->select('id', 'codigo', 'nombre')
    ->whereNull('codigoActividad')
    ->whereNull('codigoProducto')
    ->where('estado', 1)
    ->where('activo', 1)
    ->get();
return $productos;
    }
}
