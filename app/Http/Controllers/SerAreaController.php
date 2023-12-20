<?php

namespace App\Http\Controllers;

use App\Models\Ser_Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray=array();
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(codigo like '%".$valor."%' or nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (codigo like '%".$valor."%' or nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
    
                }
                $areas= Ser_Area::orderby('codigo','asc')->whereraw($sqls)->paginate(10);
            }
        }
        
        else
        {
            $areas= Ser_Area::orderby('codigo','asc')->paginate(10);
        }
        
        //$areas = Ser_Area::all();
        
        $maxcorrelativo = Ser_Area::select(DB::raw('max(correlativo) as maximo'))
                                ->get();
        return ['pagination'=>[
            'total'         =>    $areas->total(),
            'current_page'  =>    $areas->currentPage(),
            'per_page'      =>    $areas->perPage(),
            'last_page'     =>    $areas->lastPage(),
            'from'          =>    $areas->firstItem(),
            'to'            =>    $areas->lastItem(),

        ] ,
                'areas'=>$areas,
                'maxcorrelativo'=>$maxcorrelativo];
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
        $area = new Ser_Area();

        $area->nombre=$request->nombre;
        $area->descripcion=$request->descripcion;
        $area->codigo="S".$request->codigo;
        $area->correlativo=$request->correlativo;
        $area->id_usuario_registra=auth()->user()->id;
        $area->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ser_Area  $ser_Area
     * @return \Illuminate\Http\Response
     */
    public function show(Ser_Area $ser_Area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ser_Area  $ser_Area
     * @return \Illuminate\Http\Response
     */
    public function edit(Ser_Area $ser_Area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ser_Area  $ser_Area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ser_Area $ser_Area)
    {
        $area = Ser_Area::findOrFail($request->id);

        $area->nombre=$request->nombre;
        $area->descripcion=$request->descripcion;
        $area->id_usuario_modifica=auth()->user()->id;
        $area->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ser_Area  $ser_Area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ser_Area $ser_Area)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $area = Ser_Area::findOrFail($request->id);
        $area->activo=0;
        $area->id_usuario_modifica=auth()->user()->id;
        $area->save();
    }

    public function activar(Request $request)
    {
        $area = Ser_Area::findOrFail($request->id);
        $area->activo=1;
        $area->id_usuario_modifica=auth()->user()->id;
        $area->save();
    }
    public function selectArea()
    {
        $areas=Ser_Area::select(DB::raw('concat(codigo, " - ",nombre) as area'),
                            'id')
                    ->where('activo',1)
                    ->orderby('codigo','asc')
                    ->get();
        return $areas;
        

    }
}
