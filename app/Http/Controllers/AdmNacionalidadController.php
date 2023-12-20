<?php

namespace App\Http\Controllers;

use App\Models\Adm_Nacionalidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmNacionalidadController extends Controller
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__nacionalidads']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $nacionalidad = new Adm_Nacionalidad();

        $nacionalidad->nombre=$request->nombre;
        $nacionalidad->id_usuario_registra=auth()->user()->id;
        $nacionalidad->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Nacionalidad  $adm_Nacionalidad
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Nacionalidad $adm_Nacionalidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Nacionalidad  $adm_Nacionalidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Nacionalidad $adm_Nacionalidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Nacionalidad  $adm_Nacionalidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Nacionalidad $adm_Nacionalidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Nacionalidad  $adm_Nacionalidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Nacionalidad $adm_Nacionalidad)
    {
        //
    }
    public function selectNacion()
    {
        $nacions=Adm_Nacionalidad::select('nombre',
                            'id')
                    ->where('activo',1)
                    ->orderby('id','asc')
                    ->get();

        $idboliviano=Adm_Nacionalidad::where('nombre','boliviano')
                                        ->get()->toarray();
        //dd($idboliviano[0]['id']);
        return ['nacions'=>$nacions,
                'idboliviano'=>$idboliviano[0]['id']
                ];
        

    }
}
