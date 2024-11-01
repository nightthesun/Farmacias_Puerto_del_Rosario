<?php

namespace App\Http\Controllers;

use App\Models\Egr_Tesoreria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgrTesoreriaController extends Controller
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

    public function getTesoreria(Request $request)
    {  
    $resultado = DB::table('egr__tesorerias as et')
    ->select('et.id', 'et.monto_actual_abrir', 'et.abrir_cerrar')
    ->where('et.id_sucursal', $request->id_sucursal)

    ->orderBy('et.created_at', 'desc')   
    ->first();
    return $resultado;
    }   
}
