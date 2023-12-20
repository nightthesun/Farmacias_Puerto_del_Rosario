<?php

namespace App\Http\Controllers;

use App\Models\Rrh_Profesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RrhProfesionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesion= Rrh_Profesion::orderby('nombre','asc')->paginate(50);
        //$profesion = Rrh_Profesion::all();
        return ['pagination'=>[
            'total'         =>    $profesion->total(),
            'current_page'  =>    $profesion->currentPage(),
            'per_page'      =>    $profesion->perPage(),
            'last_page'     =>    $profesion->lastPage(),
            'from'          =>    $profesion->firstItem(),
            'to'            =>    $profesion->lastItem(),

            ] ,
                'profesion'=>$profesion
                ];
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:rrh__profesions']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $profesion = new Rrh_Profesion();

        $profesion->nombre=$request->nombre;
        $profesion->id_usuario_registra=auth()->user()->id;
        $profesion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrh_Profesion  $rrh_Profesion
     * @return \Illuminate\Http\Response
     */
    public function show(Rrh_Profesion $rrh_Profesion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrh_Profesion  $rrh_Profesion
     * @return \Illuminate\Http\Response
     */
    public function edit(Rrh_Profesion $rrh_Profesion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrh_Profesion  $rrh_Profesion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrh_Profesion $rrh_Profesion)
    {
       $profesion = Rrh_Profesion::findOrFail($request->id);
 
        $profesion->nombre=$request->nombre;
        $profesion->id_usuario_modifica=auth()->user()->id;
        $profesion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrh_Profesion  $rrh_Profesion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rrh_Profesion $rrh_Profesion)
    {
        //
    }
    public function selectProfesion(Request $request)
    {
        $profesions=Rrh_Profesion::select('id','nombre')
                                ->where('activo',1)
                                ->orderby('nombre','asc')
                                ->get();
        return $profesions;
    }
    public function desactivar(Request $request)
    {
        $profesion = Rrh_Profesion::findOrFail($request->id);
        $profesion->activo=0;
        $profesion->id_usuario_modifica=auth()->user()->id;
        $profesion->save();
    }

    public function activar(Request $request)
    {
        $profesion = Rrh_Profesion::findOrFail($request->id);
        $profesion->activo=1;
        $profesion->id_usuario_modifica=auth()->user()->id;
        $profesion->save();
    }
}
