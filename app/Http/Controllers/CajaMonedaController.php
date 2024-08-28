<?php

namespace App\Http\Controllers;

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
                                                    
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            cm.tipo_corte like '%".$valor."%' 
                                or cm.valor like '%".$valor."%' 
                               
                          )" ;
                    }    
                }
                $monedas = DB::table('caja__monedas as cm')
        ->select('cm.id', 'cm.tipo_corte', 'cm.valor', 'cm.unidad')
        ->where('id_nacionalidad_pais', $request->id)
        ->whereRaw($sqls)
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
            $monedas = DB::table('caja__monedas')
            ->select('id', 'tipo_corte', 'valor', 'unidad')
            ->where('id_nacionalidad_pais', $request->id)     
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
        $caja = new Caja_Moneda();
        $caja->tipo_corte = $request->nombre;
        $caja->valor = $request->valor;
        $caja->unidad = $request->unidad;
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
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
       
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Moneda $caja_Moneda)
    {
        //
    }

   public function listarNacionalidad(Request $request){
    $nacionalidades = DB::table('adm__nacionalidads')
    ->select('id', 'activo', 'pais','simbolo')
    ->get();
    return $nacionalidades;
   }


}
