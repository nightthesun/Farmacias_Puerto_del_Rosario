<?php

namespace App\Http\Controllers;

use App\Models\Ser_Prestacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerPrestacionController extends Controller
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
                        $sqls="(ser__areas.codigo like '%".$valor."%' or ser__areas.nombre like '%".$valor."%' or ser__prestacions.codigo like '%".$valor."%' or ser__prestacions.nombre like '%".$valor."%' or precio like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (ser__areas.codigo like '%".$valor."%' or ser__areas.nombre like '%".$valor."%' or ser__prestacions.codigo like '%".$valor."%' or ser__prestacions.nombre like '%".$valor."%' or precio like '%".$valor."%')" ;
                    }
    
                }
                $prestaciones= Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                                        ->select('ser__areas.nombre as nomarea',
                                                'ser__areas.codigo as codarea',
                                                'ser__prestacions.codigo as codigo',
                                                'ser__prestacions.id as id',
                                                'ser__prestacions.nombre as nombre',
                                                'precio',
                                                'ser__prestacions.descripcion',
                                                'ser__prestacions.activo'
                                                )
                                                ->orderby('ser__areas.codigo','asc')
                                                ->orderby('ser__prestacions.codigo','asc')
                                            ->whereraw($sqls)
                                            //->where('ser__prestacions.activo',1)
                                            ->paginate(20);
            }
        }
        
        else
        {
            $prestaciones= Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                                        ->select('ser__areas.nombre as nomarea',
                                                 'ser__areas.codigo as codarea',
                                                'ser__prestacions.codigo as codigo',
                                                'ser__prestacions.id as id',
                                                'ser__prestacions.nombre as nombre',
                                                'precio',
                                                'ser__prestacions.descripcion',
                                                'ser__prestacions.activo'
                                                )
                                        ->orderby('ser__areas.codigo','asc')
                                        ->orderby('ser__prestacions.codigo','asc')
                                        //->where('ser__prestacions.activo',1)
                                        ->paginate(20);
        }
        
        //$prestaciones = Ser_Prestacion::all();
        
        
        return ['pagination'=>[
            'total'         =>    $prestaciones->total(),
            'current_page'  =>    $prestaciones->currentPage(),
            'per_page'      =>    $prestaciones->perPage(),
            'last_page'     =>    $prestaciones->lastPage(),
            'from'          =>    $prestaciones->firstItem(),
            'to'            =>    $prestaciones->lastItem(),

        ] ,
                'prestaciones'=>$prestaciones, 
                
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
        $maxcorrelativo = Ser_Prestacion::select(DB::raw('max(correlativo) as maximo'))
        ->where('idarea',$request->idarea)
        ->get()->toArray();

        $correlativo=$maxcorrelativo[0]['maximo'];

        if(is_null($correlativo))
        $correlativo=1;
        else
        $correlativo=$correlativo+1;

        if($correlativo<10)
        $codigo='00'.$correlativo;
        else
        if($correlativo<100)
        $codigo='0'.$correlativo;

        $prestacion = new Ser_Prestacion();

        $prestacion->idarea=$request->idarea;
        $prestacion->nombre=$request->nombre;
        $prestacion->precio=$request->precio;
        $prestacion->descripcion=$request->descripcion;
        $prestacion->codigo=$codigo;
        $prestacion->correlativo=$correlativo;
        $prestacion->id_usuario_registra=auth()->user()->id;
        $prestacion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ser_Prestacion  $ser_Prestacion
     * @return \Illuminate\Http\Response
     */
    public function show(Ser_Prestacion $ser_Prestacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ser_Prestacion  $ser_Prestacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Ser_Prestacion $ser_Prestacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ser_Prestacion  $ser_Prestacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ser_Prestacion $ser_Prestacion)
    {
        $prestacion = Ser_Prestacion::findOrFail($request->id);

        $prestacion->nombre=$request->nombre;
        $prestacion->precio=$request->precio;
        $prestacion->descripcion=$request->descripcion;
        $prestacion->id_usuario_modifica=auth()->user()->id;
        $prestacion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ser_Prestacion  $ser_Prestacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ser_Prestacion $ser_Prestacion)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $prestacion = Ser_Prestacion::findOrFail($request->id);
        $prestacion->activo=0;
        $prestacion->id_usuario_modifica=auth()->user()->id;
        $prestacion->save();
    }

    public function activar(Request $request)
    {
        $prestacion = Ser_Prestacion::findOrFail($request->id);
        $prestacion->activo=1;
        $prestacion->id_usuario_modifica=auth()->user()->id;
        $prestacion->save();
    }
    public function selectPrestacion()
    {
        $raw=DB::raw(DB::raw('concat(ser__areas.codigo,ser__prestacions.codigo," ",ser__prestacions.nombre) as cod'));
        $prestaciones=Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                                ->select($raw,
                                        'ser__prestacions.nombre',
                                        'ser__prestacions.id',
                                        'ser__prestacions.precio')
                                ->where('ser__areas.activo',1)
                                ->where('ser__prestacions.activo',1)
                                ->orderby('ser__prestacions.nombre','asc')
                                ->get();
        return $prestaciones;
    }
    public function selectPrestaciones(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw(DB::raw('concat(ser__areas.codigo,ser__prestacions.codigo," ",ser__prestacions.nombre) as cod'));
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(ser__areas.codigo like '%".$valor."%' or ser__prestacions.codigo like '%".$valor."%' or ser__prestacions.nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (ser__areas.codigo like '%".$valor."%' or ser__prestacions.codigo like '%".$valor."%' or ser__prestacions.nombre like '%".$valor."%')";
            }   
            $prestaciones = Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                                            ->select($raw,
                                                    'ser__prestacions.nombre',
                                                    'ser__prestacions.id',
                                                    'ser__prestacions.precio')
                                            ->where('ser__areas.activo',1)
                                            ->where('ser__prestacions.activo',1)
                                            ->whereraw($sqls)
                                            ->orderby('ser__prestacions.codigo','asc')
                                            ->get();
        }
        else {
            if ($request->id){
                    $prestaciones = Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                                                ->select($raw,
                                                        'ser__prestacions.nombre',
                                                        'ser__prestacions.id',
                                                        'ser__prestacions.precio')
                                                ->where('ser__areas.activo',1)
                                                ->where('ser__prestacions.activo',1)
                                                ->where('ser__prestacions.id',$request->id)
                                                ->orderby('ser__prestacions.codigo','asc')
                                                ->get();
            }

            else
            {
                $prestaciones = Ser_Prestacion::join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                ->select($raw,
                        'ser__prestacions.nombre',
                        'ser__prestacions.id',
                        'ser__prestacions.precio')
                ->where('ser__areas.activo',1)
                ->where('ser__prestacions.activo',1)
                ->orderby('ser__prestacions.codigo','asc')
                ->get();
            }
              
        }
        return $prestaciones;
    }
}
