<?php

namespace App\Http\Controllers;

use App\Models\Rrh_Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RrhCargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cargo= Rrh_Cargo::join('rrh__unidad_organizacionals','rrh__unidad_organizacionals.id','rrh__cargos.idunidadorganizacional')
                            ->select('rrh__unidad_organizacionals.nombre as nomunidadorg',
                                        'rrh__cargos.id',
                                        'rrh__cargos.nombre',
                                        'rrh__cargos.descripcion',
                                        'act_especificas',
                                        'rrh__cargos.activo',
                                        'rrh__cargos.idunidadorganizacional')
                            ->orderby('rrh__unidad_organizacionals.nombre','asc')
                            ->orderby('rrh__cargos.nombre','asc')
                            ->paginate(50);
        //$cargo = Rrh_Cargo::all();
        return ['pagination'=>[
            'total'         =>    $cargo->total(),
            'current_page'  =>    $cargo->currentPage(),
            'per_page'      =>    $cargo->perPage(),
            'last_page'     =>    $cargo->lastPage(),
            'from'          =>    $cargo->firstItem(),
            'to'            =>    $cargo->lastItem(),

            ] ,
                'cargo'=>$cargo
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:rrh__cargos']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $cargo = new Rrh_Cargo();

        $cargo->nombre=$request->nombre;
        $cargo->idunidadorganizacional=$request->idunidadorganizacional;
        $cargo->descripcion=$request->descripcion;
        $cargo->act_especificas=$request->act_especificas;
        $cargo->id_usuario_registra=auth()->user()->id;
        $cargo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrh_Cargo  $rrh_Cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Rrh_Cargo $rrh_Cargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrh_Cargo  $rrh_Cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Rrh_Cargo $rrh_Cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrh_Cargo  $rrh_Cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrh_Cargo $rrh_Cargo)
    {
        $cargo = Rrh_Cargo::findOrFail($request->id);

        $cargo->nombre=$request->nombre;
        $cargo->idunidadorganizacional=$request->idunidadorganizacional;
        $cargo->descripcion=$request->descripcion;
        $cargo->act_especificas=$request->act_especificas;
        $cargo->id_usuario_modifica=auth()->user()->id;
        $cargo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrh_Cargo  $rrh_Cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rrh_Cargo $rrh_Cargo)
    {
        //
    }
    public function selectCargo(Request $request)
    {
        $cargos=Rrh_Cargo::select('id','nombre')
                                ->where('activo',1)
                                ->orderby('nombre','asc')
                                ->get();
        return $cargos;
    }
    public function desactivar(Request $request)
    {
        $cargo = Rrh_Cargo::findOrFail($request->id);
        $cargo->activo=0;
        $cargo->id_usuario_modifica=auth()->user()->id;
        $cargo->save();
    }

    public function activar(Request $request)
    {
        $cargo = Rrh_Cargo::findOrFail($request->id);
        $cargo->activo=1;
        $cargo->id_usuario_modifica=auth()->user()->id;
        $cargo->save();
    }
}
