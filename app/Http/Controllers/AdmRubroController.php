<?php

namespace App\Http\Controllers;

use App\Models\Adm_Rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmRubroController extends Controller
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
                        $sqls="(nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
    
                }
                $rubros= Adm_Rubro::orderby('nombre','asc')->whereraw($sqls)->paginate(20);
            }
        }
        
        else
        {
            $rubros= Adm_Rubro::orderby('nombre','asc')->paginate(20);
        }
        
        //$rubros = Adm_Rubro::all();
        
        
        return ['pagination'=>[
            'total'         =>    $rubros->total(),
            'current_page'  =>    $rubros->currentPage(),
            'per_page'      =>    $rubros->perPage(),
            'last_page'     =>    $rubros->lastPage(),
            'from'          =>    $rubros->firstItem(),
            'to'            =>    $rubros->lastItem(),

            ] ,
                'rubros'=>$rubros
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
    {   try {
        DB::beginTransaction();
        $codigo =$request->selectActEco;

        if ($codigo=="0"||$codigo==null) {
            $codigo =0;
        }else{
            $codigo = intval($codigo);
        }
        $rubro = new Adm_Rubro();       
        $rubro->nombre=$request->nombre;
        $rubro->descripcion=$request->descripcion;
        $rubro->areamedica=$request->areamedica;
        $rubro->id_usuario_registra=auth()->user()->id;
        $rubro->codigo_activdad_siat=$codigo;
        
        $rubro->save();
        DB::commit();
    } catch (\Throwable $th) {
       return $th;
    }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Rubro  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Rubro $adm_Rubro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Rubro  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Rubro $adm_Rubro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Rubro  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Rubro $adm_Rubro)
    {
        $codigo =$request->selectActEco;

        if ($codigo=="0"||$codigo==null) {
            $codigo =0;
        }else{
            $codigo = intval($request->selectActEco);
        }

        $rubro = Adm_Rubro::findOrFail($request->id);
        $rubro->nombre=$request->nombre;
        $rubro->descripcion=$request->descripcion;
        $rubro->areamedica=$request->areamedica;
        $rubro->id_usuario_modifica=auth()->user()->id;
        $rubro->codigo_activdad_siat=$codigo;
        $rubro->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Rubro  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Rubro $adm_Rubro)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $rubro = Adm_Rubro::findOrFail($request->id);
        $rubro->activo=0;
        $rubro->id_usuario_modifica=auth()->user()->id;
        $rubro->save();
    }

    public function activar(Request $request)
    {
        $rubro = Adm_Rubro::findOrFail($request->id);
        $rubro->activo=1;
        $rubro->id_usuario_modifica=auth()->user()->id;
        $rubro->save();
    }

    public function selectRubro(Request $request)
    {
        // $rubros=Adm_Rubro::select('id','nombre','areamedica')
        //                         ->where('activo',1)
        //                         ->orderBy('nombre', 'asc')
        //                         ->get();
        // return $rubros;
        
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw(DB::raw('concat(id," ",nombre) as cod'));
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (nombre like '%".$valor."%' )";
            }   
            $rubros = Adm_Rubro::select($raw,'id','nombre','codigo')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('codigo','asc')
                                ->get();
        }
        else {
            if ($request->id)
            {
                $rubros = Adm_Rubro::select($raw,'id','nombre','areamedica')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('id','asc')
                                                ->get();

                    // $rubros = Adm_Rubro::select($raw,'id','nombre','codigo')
                    //                              ->where('activo',1)
                    //                             ->where('id',$request->id)
                    //                             ->orderby('codigo','asc')
                    //                             ->get();
            }

            else
            {
                
                $rubros=Adm_Rubro::select('id','nombre','areamedica')
                                        ->where('activo',1)
                                        ->orderBy('nombre', 'asc')
                                        ->get();

                // $rubros = Adm_Rubro::select('id','nombre','codigo')
                //                     ->where('activo',1)
                //                     ->orderby('codigo','asc')
                //                     ->get();
            }
              
        }
        return ['rubros' => $rubros];
        

    }


}
