<?php

namespace App\Http\Controllers;

use App\Models\Rrh_UnidadOrganizacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RrhUnidadOrganizacionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidadorg= Rrh_UnidadOrganizacional::orderby('nombre','asc')->paginate(50);
        //$unidadorg = Rrh_UnidadOrganizacional::all();
        return ['pagination'=>[
            'total'         =>    $unidadorg->total(),
            'current_page'  =>    $unidadorg->currentPage(),
            'per_page'      =>    $unidadorg->perPage(),
            'last_page'     =>    $unidadorg->lastPage(),
            'from'          =>    $unidadorg->firstItem(),
            'to'            =>    $unidadorg->lastItem(),

            ] ,
                'unidadorg'=>$unidadorg
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
        // $validator=Validator::make($request->all(),[
        //     'nombre'=>'unique:rrh__unidad_organizacionals'
        // ]);

        // //dd($validator->errors());
        
        // if($validator->fails())
        // {
        //     return 'error';
        // }
        $validate=$request->validate([
            'nombre'=>'required | unique:rrh__unidad_organizacionals'
        ]);
        $unidadorg = new Rrh_UnidadOrganizacional();
        $unidadorg->nombre=$request->nombre;
        $unidadorg->descripcion=$request->descripcion;
        //$unidadorg->id_usuario_registra=auth()->user()->id;
        $unidadorg->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrh_UnidadOrganizacional  $rrh_UnidadOrganizacional
     * @return \Illuminate\Http\Response
     */
    public function show(Rrh_UnidadOrganizacional $rrh_UnidadOrganizacional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrh_UnidadOrganizacional  $rrh_UnidadOrganizacional
     * @return \Illuminate\Http\Response
     */
    public function edit(Rrh_UnidadOrganizacional $rrh_UnidadOrganizacional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrh_UnidadOrganizacional  $rrh_UnidadOrganizacional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrh_UnidadOrganizacional $rrh_UnidadOrganizacional)
    {
        $unidadorg = Rrh_UnidadOrganizacional::findOrFail($request->id);

        if($unidadorg->nombre != $request->nombre)
        {   
            $validate=$request->validate([
                'nombre'=>'required | unique:rrh__unidad_organizacionals'
            ]);
        }
        $unidadorg->nombre=$request->nombre;
        $unidadorg->descripcion=$request->descripcion;
        //$unidadorg->id_usuario_modifica=auth()->user()->id;
        $unidadorg->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrh_UnidadOrganizacional  $rrh_UnidadOrganizacional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rrh_UnidadOrganizacional $rrh_UnidadOrganizacional)
    {
        //
    }
    public function selectUnidadOrg(Request $request)
    {
        $unidadorgs=Rrh_UnidadOrganizacional::select('id','nombre')
                                ->where('activo',1)
                                ->orderby('nombre','asc')
                                ->get();
        return $unidadorgs;
    }
    public function desactivar(Request $request)
    {
        $unidadorg = Rrh_UnidadOrganizacional::findOrFail($request->id);
        $unidadorg->activo=0;
        //$unidadorg->id_usuario_modifica=auth()->user()->id;
        $unidadorg->save();
    }

    public function activar(Request $request)
    {
        $unidadorg = Rrh_UnidadOrganizacional::findOrFail($request->id);
        $unidadorg->activo=1;
        //$unidadorg->id_usuario_modifica=auth()->user()->id;
        $unidadorg->save();
    }
}
