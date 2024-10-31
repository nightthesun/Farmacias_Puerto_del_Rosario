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
    public function show(Egr_Tesoreria $egr_Tesoreria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Egr_Tesoreria $egr_Tesoreria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Egr_Tesoreria $egr_Tesoreria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Egr_Tesoreria $egr_Tesoreria)
    {
        $result = DB::table('egr__tesorerias as et')
    ->select('et.id', 'et.monto_actual_abrir', 'et.abrir_cerrar')
    ->where('et.id_sucursal', 1)
    ->where('et.abrir_cerrar', 9)
    ->orderByDesc('et.id')
    ->limit(1)
    ->first();
    }
}
