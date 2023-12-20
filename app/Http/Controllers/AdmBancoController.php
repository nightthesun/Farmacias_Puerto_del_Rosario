<?php

namespace App\Http\Controllers;

use App\Models\Adm_Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmBancoController extends Controller
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__bancos']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $banco = new Adm_Banco();

        $banco->nombre=$request->nombre;
        $banco->id_usuario_registra=auth()->user()->id;
        $banco->save();
        return ['idbanco'=>$banco->id];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Banco  $adm_Banco
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Banco $adm_Banco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Banco  $adm_Banco
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Banco $adm_Banco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Banco  $adm_Banco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Banco $adm_Banco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Banco  $adm_Banco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Banco $adm_Banco)
    {
        //
    }
    public function selectBanco()
    {
        $bancos=Adm_Banco::select('nombre','id')
                    ->orderby('id','asc')
                    ->get();
        return $bancos;
        

    }
}
