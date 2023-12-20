<?php

namespace App\Http\Controllers;

use App\Models\Prod_TipoDescuento;
use Illuminate\Http\Request;

class ProdTipoDescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prod_TipoDescuento  $prod_TipoDescuento
     * @return \Illuminate\Http\Response
     */
    public function show(Prod_TipoDescuento $prod_TipoDescuento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prod_TipoDescuento  $prod_TipoDescuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Prod_TipoDescuento $prod_TipoDescuento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prod_TipoDescuento  $prod_TipoDescuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prod_TipoDescuento $prod_TipoDescuento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prod_TipoDescuento  $prod_TipoDescuento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prod_TipoDescuento $prod_TipoDescuento)
    {
        //
    }
    public function selectTipoDescuento(Request $request)
    {
        $tipodescuentos=Prod_TipoDescuento::select('id','cod','aplica_a','estado','descripcion','subcategorias')
                                            ->where('activo',1)
                                            ->get();
        
        //$arraydias=config('app.arraydias');
        
        return ['tipodescuentos'=>$tipodescuentos];
    }
}
