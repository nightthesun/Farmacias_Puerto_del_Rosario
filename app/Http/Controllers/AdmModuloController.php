<?php

namespace App\Http\Controllers;

use App\Models\Adm_Modulo;
use App\Models\Adm_AccionVentana;
use App\Models\Adm_VentanaModulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modulo= Adm_Modulo::orderby('nombre','asc')->get();
        foreach ($modulo as $value) {
            $ventana=Adm_VentanaModulo::where('idmodulo',$value->id)
                                        ->orderby('nombre','asc')
                                        ->get();
            foreach ($ventana as $valueVentana) {
                $accion=Adm_AccionVentana::where('idventana',$valueVentana->id)
                                            ->orderBy('nombre','asc')
                                            ->get();
                $valueVentana->accion=$accion;
                $valueVentana->mostraraccion=false;
            }

            $value->ventana=$ventana;
            $value->mostrarventana=false;
        }

        //$modulo = Adm_Modulo::all();
        return ['modulos'=>$modulo];
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__modulos']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $modulo = new Adm_Modulo();

        $modulo->nombre=$request->nombre;
        $modulo->nombre_icono=$request->nombre_icono;
        $modulo->id_usuario_registra=auth()->user()->id;
        $modulo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Modulo  $adm_Modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Modulo $adm_Modulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Modulo  $adm_Modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Modulo $adm_Modulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Modulo  $adm_Modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Modulo $adm_Modulo)
    {
        $modulo = Adm_Modulo::findOrFail($request->id);

        $modulo->nombre=$request->nombre;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Modulo  $adm_Modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Modulo $adm_Modulo)
    {
        //
    }
    public function selectFormacion(Request $request)
    {
        $modulos=Adm_Modulo::select('id','nombre')
                                ->where('activo',1)
                                ->orderby('nombre','asc')
                                ->get();
        return $modulos;
    }
    public function desactivar(Request $request)
    {
        $modulo = Adm_Modulo::findOrFail($request->id);
        $modulo->activo=0;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }

    public function activar(Request $request)
    {
        $modulo = Adm_Modulo::findOrFail($request->id);
        $modulo->activo=1;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }
}
