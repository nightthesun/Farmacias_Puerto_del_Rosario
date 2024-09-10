<?php

namespace App\Http\Controllers;

use App\Helpers\converso_numero_a_texto;
use App\Helpers\conversorNumaTexoParaTodos;
use App\Models\Caja_Moneda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaMonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();        
        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls=''; 
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                            cm.tipo_corte like '%".$valor."%' 
                                or cm.valor like '%".$valor."%'
                                or cm.texto_unidad_entera like '%".$valor."%'                            
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            cm.tipo_corte like '%".$valor."%' 
                                or cm.valor like '%".$valor."%' 
                               or cm.texto_unidad_entera like '%".$valor."%'     
                          )" ;
                    }    
                }
                $monedas = DB::table('caja__monedas as cm') 
    ->join('adm__nacionalidads as an', 'an.id', '=', 'cm.id_nacionalidad_pais')
    ->select('cm.id', 'cm.tipo_corte', 'cm.valor', 'cm.unidad', 'cm.activo', 'an.simbolo','cm.texto_unidad_entera','cm.unidad_entera')
    ->where('cm.id_nacionalidad_pais',  $request->id)
    ->whereRaw($sqls)
    ->orderBy('cm.valor', 'asc')
    ->paginate(15);

            }
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $monedas->total(),
                            'current_page'  =>    $monedas->currentPage(),
                            'per_page'      =>    $monedas->perPage(),
                            'last_page'     =>    $monedas->lastPage(),
                            'from'          =>    $monedas->firstItem(),
                            'to'            =>    $monedas->lastItem(),
                        ] ,
                    'monedas'=>$monedas,
            ]; 
        } else {
            $monedas = DB::table('caja__monedas as cm')
    ->join('adm__nacionalidads as an', 'an.id', '=', 'cm.id_nacionalidad_pais')
    ->select('cm.id', 'cm.tipo_corte', 'cm.valor', 'cm.unidad', 'cm.activo', 'an.simbolo','cm.texto_unidad_entera','cm.unidad_entera')
    ->where('cm.id_nacionalidad_pais',  $request->id) 
    ->orderBy('cm.valor', 'asc')   
    ->paginate(15);
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $monedas->total(),
                            'current_page'  =>    $monedas->currentPage(),
                            'per_page'      =>    $monedas->perPage(),
                            'last_page'     =>    $monedas->lastPage(),
                            'from'          =>    $monedas->firstItem(),
                            'to'            =>    $monedas->lastItem(),
                        ] ,
                    'monedas'=>$monedas,
            ]; 
        }    
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)   
    {
        try {
            DB::beginTransaction();
          
            $datoTexto = conversorNumaTexoParaTodos::conversorNumaTexoParaTodos($request->valor_entero);
            
        $caja = new Caja_Moneda();
        $caja->tipo_corte = $request->nombre;
        $caja->valor = $request->valor;
        $caja->unidad = $request->unidad;
        $caja->id_nacionalidad_pais=$request->id_nacionalidad_pais;
        $caja->texto_unidad_entera=$datoTexto;
        $caja->unidad_entera=$request->valor_entero;
        $caja->save();
        $nuevoId = $caja->id;  
        
        $now = Carbon::now();
        //1=add, 2=delete, 3=create, 4=edit, 5=show
        $datos = [
            'id_modulo' => $request->id_modulo,
            'id_sub_modulo' => $request->id_sub_modulo,
            'accion' => 3,
            'descripcion' => $request->des,          
            'user_id' =>auth()->user()->id, 
            'created_at'=>$now,
            'id_movimiento'=>$nuevoId        
        ];
    
        DB::table('log__sistema')->insert($datos);   
        DB::commit(); 
        return  DB::commit();   
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
       
    }
    /**
     * Update the specified resource in storage.
     */
    public function actualizar(Request $request)
    {
        try {
            DB::beginTransaction();          
            $datoTexto = conversorNumaTexoParaTodos::conversorNumaTexoParaTodos($request->valor_entero);
            $update=Caja_Moneda::find($request->id);
            $update->tipo_corte = $request->nombre;
            $update->valor = $request->valor;
            $update->unidad = $request->unidad;
            $update->id_nacionalidad_pais=$request->id_nacionalidad_pais;
            $update->texto_unidad_entera=$datoTexto;
            $update->unidad_entera=$request->valor_entero;
            $update->save();
            $now = Carbon::now();
        //1=add, 2=delete, 3=create, 4=edit, 5=show
        $datos = [
            'id_modulo' => $request->id_modulo,
            'id_sub_modulo' => $request->id_sub_modulo,
            'accion' => 4,
            'descripcion' => $request->des,          
            'user_id' =>auth()->user()->id, 
            'created_at'=>$now,
            'id_movimiento'=>$request->id       
        ];
    
        DB::table('log__sistema')->insert($datos);   
        DB::commit(); 
        return  DB::commit();   

        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }

   public function listarNacionalidad(Request $request){
    $nacionalidades = DB::table('adm__nacionalidads')
    ->select('id', 'activo', 'pais','simbolo')
    ->get();
    return $nacionalidades;
   }

    public function desactivar(Request $request){
        try {
            DB::beginTransaction(); 
            $des = Caja_Moneda::findOrFail($request->id);
            $des->activo=0;
            $des->save();
            $now = Carbon::now();
            //1=add, 2=delete, 3=create, 4=edit, 5=show
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$now,
                'id_movimiento'=>$request->id       
            ];
        
            DB::table('log__sistema')->insert($datos);   
            DB::commit(); 
            return DB::commit(); 
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }

    public function activar(Request $request){
        try {
            DB::beginTransaction(); 
            $des = Caja_Moneda::findOrFail($request->id);
            $des->activo=1;
            $des->save();
            $now = Carbon::now();
            //1=add, 2=delete, 3=create, 4=edit, 5=show
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$now,
                'id_movimiento'=>$request->id       
            ];
        
            DB::table('log__sistema')->insert($datos);   
            DB::commit(); 
            return DB::commit(); 
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }
}
