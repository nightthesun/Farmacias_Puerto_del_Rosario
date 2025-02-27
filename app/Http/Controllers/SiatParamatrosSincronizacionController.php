<?php

namespace App\Http\Controllers;

use App\Models\Siat_Paramatros_sincronizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatParamatrosSincronizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registros = DB::table('siat__paramatros_sincronizacions')
  
    ->limit(25)
    ->orderBy('id', 'desc') // Reordena después de obtener los últimos 30
    ->get();
    return $registros;
    }  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $crear=new Siat_Paramatros_sincronizacion();
            $crear->tipo=$request->tipo;
            $crear->estado=$request->estado;
            $crear->descripcion=$request->descripcion;
            $crear->save();
            DB::commit();
        } catch (\Throwable $th) {
           return $th;
        }
    }

}
