<?php

namespace App\Http\Controllers;

use App\Models\Prod_Linea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdLineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray=array();
        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0)
            {
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls))
                    {
                        $sqls="(codigo like '%".$valor."%' or nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (codigo like '%".$valor."%' or nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
                }
                $lineas= Prod_Linea::orderby('codigo','asc')
                                    ->whereraw($sqls)
                                    ->where('prod__lineas.idrubro',$request->idrubro)
                                    ->paginate(20);
            }
        }
        else{
            $lineas= Prod_Linea::orderby('codigo','asc')
                                ->where('prod__lineas.idrubro',$request->idrubro)
                                ->paginate(20);
        }
        
        //$lineas = Prod_Linea::all();
        
        $maxcorrelativo = Prod_Linea::select(DB::raw('max(correlativo) as maximo'))
                                ->get();
        return [
                'pagination'=>
                [
                    'total'         =>    $lineas->total(),
                    'current_page'  =>    $lineas->currentPage(),
                    'per_page'      =>    $lineas->perPage(),
                    'last_page'     =>    $lineas->lastPage(),
                    'from'          =>    $lineas->firstItem(),
                    'to'            =>    $lineas->lastItem(),
                 ] ,
                'lineas'=>$lineas,
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
        $letracodigo='L';
        $maxcorrelativo = Prod_Linea::select(DB::raw('max(correlativo) as maximo'))
                                ->get()->toArray();
        //dd($maxcorrelativo);
        $correlativo=$maxcorrelativo[0]['maximo'];
        //dd($correlativo);
        if(is_null($correlativo))
            $correlativo=1;
        else
            $correlativo=$correlativo+1;

        if($correlativo<10)
            $codigo='00'.$correlativo;
        else
            if($correlativo >=10 && $correlativo<=99)
                $codigo='0'.$correlativo;
            else
                $codigo=$correlativo;
        
        $codigo=$letracodigo.$codigo;
        
        $linea = new Prod_Linea();
        $linea->codigo=$codigo;
        $linea->correlativo=$correlativo;
        $linea->idrubro=$request->idrubro;
        $linea->nombre=$request->nombre;
        $linea->descripcion=$request->descripcion;
        $linea->tiempo_demora=$request->tiempo_demora;
        $linea->id_usuario_registra=auth()->user()->id;
        
        $linea->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prod_Linea  $prod_Linea
     * @return \Illuminate\Http\Response
     */
    public function show(Prod_Linea $prod_Linea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prod_Linea  $prod_Linea
     * @return \Illuminate\Http\Response
     */
    public function edit(Prod_Linea $prod_Linea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prod_Linea  $prod_Linea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prod_Linea $prod_Linea)
    {
        $linea = Prod_Linea::findOrFail($request->id);

        $linea->idrubro=$request->idrubro;
        $linea->nombre=$request->nombre;
        $linea->descripcion=$request->descripcion;
        $linea->tiempo_demora=$request->tiempo_demora;
        $linea->id_usuario_modifica=auth()->user()->id;
        $linea->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prod_Linea  $prod_Linea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prod_Linea $prod_Linea)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $linea = Prod_Linea::findOrFail($request->id);
        $linea->activo=0;
        $linea->id_usuario_modifica=auth()->user()->id;
        $linea->save();
    }

    public function activar(Request $request)
    {
        $linea = Prod_Linea::findOrFail($request->id);
        $linea->activo=1;
        $linea->id_usuario_modifica=auth()->user()->id;
        $linea->save();
    }
    public function selectLinea(Request $request)
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw(DB::raw('concat(codigo," ",nombre) as cod'));
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(codigo like '%".$valor."%' or nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (codigo like '%".$valor."%' or nombre like '%".$valor."%' )";
            }   
            $lineas = Prod_Linea::select($raw,'id','nombre','codigo')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('codigo','asc')
                                ->get();
        }
        else {
            if ($request->id){
                    $lineas = Prod_Linea::select($raw,'id','nombre','codigo')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('codigo','asc')
                                                ->get();
            }

            else
            {
                $lineas = Prod_Linea::select($raw,'id','nombre','codigo')
                                    ->where('activo',1)
                                    ->orderby('codigo','asc')
                                    ->get();
            }
              
        }
        return ['lineas' => $lineas];
    }

    public function selectLinea2(Request $request)
    {
        $raw=DB::raw(DB::raw('concat(codigo," ",nombre) as cod'));  
        $lineas = Prod_Linea::select($raw,'id','nombre','codigo')
                            ->where('activo',1)
                            ->where('idrubro',$request->idrubro)
                            ->orderby('codigo','asc')
                            ->get();
        return $lineas;
    }


    public function returnCodigoLiniea(Request $request)
    {
        $codigolinea = Prod_Linea::where('id','=',$request->id)
                            ->select('codigo')
                            ->get();
        return $codigolinea;
    }


}
