<?php

namespace App\Http\Controllers;

use App\Models\AdmActividadEconomica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmActividadEconomicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
                $rubros= AdmActividadEconomica::orderby('nombre','asc')->whereraw($sqls)->paginate(20);
            }
        }
        
        else
        {
            $rubros= AdmActividadEconomica::orderby('nombre','asc')->paginate(20);
        }
        
        
        
        
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
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
            $rubro = new AdmActividadEconomica();       
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
     */
    public function show(AdmActividadEconomica $admActividadEconomica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdmActividadEconomica $admActividadEconomica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, AdmActividadEconomica $admActividadEconomica)
    {
        $codigo =$request->selectActEco;

        if ($codigo=="0"||$codigo==null) {
            $codigo =0;
        }else{
            $codigo = intval($request->selectActEco);
        }

        $rubro = AdmActividadEconomica::findOrFail($request->id);
        $rubro->nombre=$request->nombre;
        $rubro->descripcion=$request->descripcion;
        $rubro->areamedica=$request->areamedica;
        $rubro->id_usuario_modifica=auth()->user()->id;
        $rubro->codigo_activdad_siat=$codigo;
        $rubro->save();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdmActividadEconomica $admActividadEconomica)
    {
        //
    }

     public function desactivar(Request $request)
    {
        $rubro = AdmActividadEconomica::findOrFail($request->id);
        $rubro->activo=0;
        $rubro->id_usuario_modifica=auth()->user()->id;
        $rubro->save();
    }

    public function activar(Request $request)
    {
        $rubro = AdmActividadEconomica::findOrFail($request->id);
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
            $rubros = AdmActividadEconomica::select($raw,'id','nombre','codigo')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('codigo','asc')
                                ->get();
        }
        else {
            if ($request->id)
            {
                $rubros = AdmActividadEconomica::select($raw,'id','nombre','areamedica')
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
                
                $rubros=AdmActividadEconomica::select('id','nombre','areamedica')
                                        ->where('activo',1)
                                        ->orderBy('nombre', 'asc')
                                        ->get();

                // $rubros = AdmActividadEconomica::select('id','nombre','codigo')
                //                     ->where('activo',1)
                //                     ->orderby('codigo','asc')
                //                     ->get();
            }
              
        }
        return ['rubros' => $rubros];
        

    }

     public function use_active(Request $request){
        
    AdmActividadEconomica::query()->update([
    'uso_unico' => 0
]);

        $rubro = AdmActividadEconomica::findOrFail($request->id);
        $rubro->uso_unico=$request->uso;       
        $rubro->save();

    }
}
