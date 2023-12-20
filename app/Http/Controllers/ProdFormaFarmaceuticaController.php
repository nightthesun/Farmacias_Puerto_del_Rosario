<?php

namespace App\Http\Controllers;

use App\Models\Prod_FormaFarmaceutica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdFormaFarmaceuticaController extends Controller
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
                        $sqls="(nombre like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (nombre like '%".$valor."%')" ;
                    }
    
                }
                $formafarm= Prod_FormaFarmaceutica::orderby('nombre','asc')->whereraw($sqls)->paginate(20);
            }
        }
        
        else
        {
            $formafarm= Prod_FormaFarmaceutica::orderby('nombre','asc')->paginate(20);
        }
        
        //$formafarm = Prod_FormaFarmaceutica::all();
        return 
        [
            'pagination'=>
            [
                'total'         =>    $formafarm->total(),
                'current_page'  =>    $formafarm->currentPage(),
                'per_page'      =>    $formafarm->perPage(),
                'last_page'     =>    $formafarm->lastPage(),
                'from'          =>    $formafarm->firstItem(),
                'to'            =>    $formafarm->lastItem(),
            ] ,
           'formafarm'=>$formafarm,
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:prod__forma_farmaceuticas']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $formafarm = new Prod_FormaFarmaceutica();

        $formafarm->nombre=$request->nombre;
        $formafarm->id_usuario_registra=auth()->user()->id;
        $formafarm->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prod_FormaFarmaceutica  $prod_FormaFarmaceutica
     * @return \Illuminate\Http\Response
     */
    public function show(Prod_FormaFarmaceutica $prod_FormaFarmaceutica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prod_FormaFarmaceutica  $prod_FormaFarmaceutica
     * @return \Illuminate\Http\Response
     */
    public function edit(Prod_FormaFarmaceutica $prod_FormaFarmaceutica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prod_FormaFarmaceutica  $prod_FormaFarmaceutica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prod_FormaFarmaceutica $prod_FormaFarmaceutica)
    {
        $formafarm = Prod_FormaFarmaceutica::findOrFail($request->id);

        $formafarm->nombre=$request->nombre;
        $formafarm->id_usuario_modifica=auth()->user()->id;
        $formafarm->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prod_FormaFarmaceutica  $prod_FormaFarmaceutica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prod_FormaFarmaceutica $prod_FormaFarmaceutica)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $formafarm = Prod_FormaFarmaceutica::findOrFail($request->id);
        $formafarm->activo=0;
        $formafarm->id_usuario_modifica=auth()->user()->id;
        $formafarm->save();
    }

    public function activar(Request $request)
    {
        $formafarm = Prod_FormaFarmaceutica::findOrFail($request->id);
        $formafarm->activo=1;
        $formafarm->id_usuario_modifica=auth()->user()->id;
        $formafarm->save();
    }
    public function selectFormaFarm(Request $request)
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (nombre like '%".$valor."%' )";
            }   
            $formafarm = Prod_FormaFarmaceutica::select('id','nombre')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('nombre','asc')
                                ->get();
        }
        else {
            if ($request->id){
                    $formafarm = Prod_FormaFarmaceutica::select('id','nombre')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('nombre','asc')
                                                ->get();
            }

            else
            {
                $formafarm = Prod_FormaFarmaceutica::select('id','nombre')
                                    ->where('activo',1)
                                    ->orderby('nombre','asc')
                                    ->get();
            }
              
        }
        return ['formafarm' => $formafarm];

    }
    public function selectFormaFarm2(Request $request)
    {
        $formafarm = Prod_FormaFarmaceutica::select('id','nombre')
                                    ->where('activo',1)
                                    ->orderby('nombre','asc')
                                    ->get();
        return $formafarm;

    }
}
