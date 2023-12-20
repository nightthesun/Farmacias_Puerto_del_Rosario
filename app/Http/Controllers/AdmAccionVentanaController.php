<?php

namespace App\Http\Controllers;

use App\Models\Adm_AccionVentana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmAccionVentanaController extends Controller
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
        
        
        $accion = new Adm_AccionVentana();
        $accion->nombre=$request->nombre;
        $accion->metodo_vue=$request->metodovue;
        $accion->idventana=$request->idventana;
        $accion->descripcion=$request->descripcion;
        $accion->id_usuario_registra=auth()->user()->id;
        $accion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_AccionVentana  $adm_AccionVentana
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_AccionVentana $adm_AccionVentana)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_AccionVentana  $adm_AccionVentana
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_AccionVentana $adm_AccionVentana)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_AccionVentana  $adm_AccionVentana
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_AccionVentana $adm_AccionVentana)
    {
        $accion = Adm_AccionVentana::findOrFail($request->id);

        $accion->nombre=$request->nombre;

        $accion->metodo_vue=$request->metodovue;
        $accion->descripcion=$request->descripcion;
        $accion->id_usuario_modifica=auth()->user()->id;
        $accion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_AccionVentana  $adm_AccionVentana
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_AccionVentana $adm_AccionVentana)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $modulo = Adm_AccionVentana::findOrFail($request->id);
        $modulo->activo=0;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }

    public function activar(Request $request)
    {
        $modulo = Adm_AccionVentana::findOrFail($request->id);
        $modulo->activo=1;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }
}
