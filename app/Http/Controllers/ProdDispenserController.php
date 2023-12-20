<?php

namespace App\Http\Controllers;

use App\Models\Prod_Dispenser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdDispenserController extends Controller
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
                $dispenser= Prod_Dispenser::orderby('nombre','asc')->whereraw($sqls)->paginate(20);
            }
        }
        
        else
        {
            $dispenser= Prod_Dispenser::orderby('nombre','asc')->paginate(20);
        }
        
        //$dispenser = Prod_Dispenser::all();
        return ['pagination'=>[
            'total'         =>    $dispenser->total(),
            'current_page'  =>    $dispenser->currentPage(),
            'per_page'      =>    $dispenser->perPage(),
            'last_page'     =>    $dispenser->lastPage(),
            'from'          =>    $dispenser->firstItem(),
            'to'            =>    $dispenser->lastItem(),

        ] ,
                'dispenser'=>$dispenser,
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
        $validator=Validator::make($request->all(),['nombre'=>'unique:prod__dispensers']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }

        $dispenser = new Prod_Dispenser();

        $dispenser->nombre=$request->nombre;
        $dispenser->id_usuario_registra=auth()->user()->id;
        $dispenser->save();
        //return $validador;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prod_Dispenser  $prod_Dispenser
     * @return \Illuminate\Http\Response
     */
    public function show(Prod_Dispenser $prod_Dispenser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prod_Dispenser  $prod_Dispenser
     * @return \Illuminate\Http\Response
     */
    public function edit(Prod_Dispenser $prod_Dispenser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prod_Dispenser  $prod_Dispenser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prod_Dispenser $prod_Dispenser)
    {
        $dispenser = Prod_Dispenser::findOrFail($request->id);

        $dispenser->nombre=$request->nombre;
        $dispenser->id_usuario_modifica=auth()->user()->id;
        $dispenser->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prod_Dispenser  $prod_Dispenser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prod_Dispenser $prod_Dispenser)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $dispenser = Prod_Dispenser::findOrFail($request->id);
        $dispenser->activo=0;
        $dispenser->id_usuario_modifica=auth()->user()->id;
        $dispenser->save();
    }

    public function activar(Request $request)
    {
        $dispenser = Prod_Dispenser::findOrFail($request->id);
        $dispenser->activo=1;
        $dispenser->id_usuario_modifica=auth()->user()->id;
        $dispenser->save();
    }
    public function selectDispenser(Request $request)
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
            $dispensers = Prod_Dispenser::select('id','nombre')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('nombre','asc')
                                ->get();
        }
        else {
            if ($request->id){
                    $dispensers = Prod_Dispenser::select('id','nombre')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('nombre','asc')
                                                ->get();
            }

            else
            {
                $dispensers = Prod_Dispenser::select('id','nombre')
                                    ->where('activo',1)
                                    ->orderby('nombre','asc')
                                    ->get();
            }
              
        }
        return ['dispensers' => $dispensers];
        
        

    }
    public function selectDispenser2(Request $request)
    {
        
        $dispensers = Prod_Dispenser::select('id','nombre')
                                    ->where('activo',1)
                                    ->orderby('nombre','asc')
                                    ->get();
        return $dispensers;
        
        

    }
}
