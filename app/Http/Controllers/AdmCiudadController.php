<?php

namespace App\Http\Controllers;

use App\Models\Adm_Ciudad;
use Illuminate\Http\Request;

class AdmCiudadController extends Controller
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
        $ciudad = new Adm_Ciudad();

        $ciudad->iddepartamento=$request->iddepartamento;
        $ciudad->nombre=$request->nombre;
        $ciudad->id_usuario_registra=auth()->user()->id;
        $ciudad->save();
        return ['idciudad'=>$ciudad->id];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Ciudad  $adm_Ciudad
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Ciudad $adm_Ciudad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Ciudad  $adm_Ciudad
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Ciudad $adm_Ciudad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Ciudad  $adm_Ciudad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Ciudad $adm_Ciudad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Ciudad  $adm_Ciudad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Ciudad $adm_Ciudad)
    {
        //
    }
    public function selectCiudad()
    {
        $ciudads=Adm_Ciudad::join('adm__departamentos','adm__departamentos.id','adm__ciudads.iddepartamento')
                            ->select('abrev',
                            'adm__ciudads.nombre',
                            'adm__ciudads.id')
                    ->where('adm__ciudads.activo',1)
                    ->orderby('adm__departamentos.id','asc')
                    ->orderby('adm__ciudads.nombre','asc')
                    ->get();
        return $ciudads;
        

    }
}
