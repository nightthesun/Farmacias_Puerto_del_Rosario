<?php

namespace App\Http\Controllers;

use App\Models\Adm_Nacionalidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmNacionalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('adm__nacionalidads as na')
    ->leftJoin('users as u', 'na.id_usuario_modifica', '=', 'u.id')
    ->select(
        'na.id',
        'na.nombre',
        'na.activo',
        'na.id_usuario_modifica',
        'na.updated_at',
        'na.pais',
        'na.simbolo',
        'na.codigo',
        'u.name'
    )->orderBy('na.id', 'asc')
    
    ->paginate(20);
    return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $query->total(),
                            'current_page'  =>    $query->currentPage(),
                            'per_page'      =>    $query->perPage(),
                            'last_page'     =>    $query->lastPage(),
                            'from'          =>    $query->firstItem(),
                            'to'            =>    $query->lastItem(),
                        ] ,
                    'query'=>$query,
            ]; 
    }

    public function createNacionalidad(Request $request){
        try {
            DB::beginTransaction();
             
        $nacionalidad = new Adm_Nacionalidad();
        $nacionalidad->nombre=$request->nombre;
        $nacionalidad->id_usuario_registra=auth()->user()->id;
        $nacionalidad->id_usuario_modifica=auth()->user()->id;
        $nacionalidad->pais=$request->pais;
        $nacionalidad->simbolo=$request->simbolo;
        $nacionalidad->codigo=$request->codigo;
        $nacionalidad->save(); 
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }                    
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__nacionalidads']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $nacionalidad = new Adm_Nacionalidad();

        $nacionalidad->nombre=$request->nombre;
        $nacionalidad->id_usuario_registra=auth()->user()->id;
        $nacionalidad->save();
    }

    

    public function editNacionalidad(Request $request){
         try {
            DB::beginTransaction();    
           
        $nacionalidad = Adm_Nacionalidad::findOrFail($request->id);       
        $nacionalidad->nombre=$request->nombre;
        $nacionalidad->id_usuario_modifica=auth()->user()->id;
        $nacionalidad->pais=$request->pais;
        $nacionalidad->simbolo=$request->simbolo;
        $nacionalidad->codigo=$request->codigo;
        $nacionalidad->save(); 
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }  
    }

    public function activar_or_desactivar(Request $request){
                $banco = Adm_Nacionalidad::findOrFail($request->id);

        $banco->activo=$request->puntero;
       
        $banco->save();
    }

  
    public function selectNacion()
    {
        $nacions=Adm_Nacionalidad::select('nombre',
                            'id')
                    ->where('activo',1)
                    ->orderby('id','asc')
                    ->get();

        $idboliviano=Adm_Nacionalidad::where('nombre','boliviano')
                                        ->get()->toarray();
        //dd($idboliviano[0]['id']);
        return ['nacions'=>$nacions,
                'idboliviano'=>$idboliviano[0]['id']
                ];
        

    }
}
