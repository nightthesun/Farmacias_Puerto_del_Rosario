<?php

namespace App\Http\Controllers;

use App\Models\Adm_VentanaModulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmVentanaModuloController extends Controller
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__ventana_modulos',
                                                    'template'=>'unique:adm__ventana_modulos']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        $maxcodigo = Adm_VentanaModulo::select(DB::raw('max(codventana) as maximo'))
                                        ->where('idmodulo',$request->idmodulo)
                                        ->get()->toArray();
        
        $codigo=$maxcodigo[0]['maximo']+1;
        if($codigo<100)
            $codigo=$request->idmodulo."0".$codigo;
                   
        


        
        $ventana = new Adm_VentanaModulo();
        $ventana->codventana=$codigo;
        $ventana->nombre=$request->nombre;
        $ventana->template=$request->nomtemplate;
        $ventana->idmodulo=$request->idmodulo;
        $ventana->id_usuario_registra=auth()->user()->id;
        $ventana->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_VentanaModulo  $adm_VentanaModulo
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_VentanaModulo $adm_VentanaModulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_VentanaModulo  $adm_VentanaModulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_VentanaModulo $adm_VentanaModulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_VentanaModulo  $adm_VentanaModulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ventana = Adm_VentanaModulo::findOrFail($request->id);
        $ventana->nombre=$request->nombre;
        $ventana->template=$request->nomtemplate;
        //$ventana->idmodulo=$request->idmodulo;
        $ventana->id_usuario_modifica=auth()->user()->id;
        $ventana->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_VentanaModulo  $adm_VentanaModulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_VentanaModulo $adm_VentanaModulo)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $modulo = Adm_VentanaModulo::findOrFail($request->id);
        $modulo->activo=0;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }

    public function activar(Request $request)
    {
        $modulo = Adm_VentanaModulo::findOrFail($request->id);
        $modulo->activo=1;
        $modulo->id_usuario_modifica=auth()->user()->id;
        $modulo->save();
    }
}
