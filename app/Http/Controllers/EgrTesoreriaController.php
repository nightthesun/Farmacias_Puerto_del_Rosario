<?php

namespace App\Http\Controllers;

use App\Models\Egr_Tesoreria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgrTesoreriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $buscararray = array();  
        if (auth()->user()->id==1) {
            $idsuc=1;
        } else {
            $idsuc = session('idsuc');
        }
        $where = "(et.id_sucursal = $idsuc)"; 
        $ini=$request->ini;
        $fini=$request->fini;

        if ($request->abrir_cerrar == 0) {
            $fecha_2 = 'et.created_at';
            $joinArqueo = 'et.id_arqueo_abrir';
        } else {
            if ($request->abrir_cerrar == 9) {
                $fecha_2 = 'et.updated_at';
                $joinArqueo = 'et.id_arqueo_cerrar';
            } else {
               dd("error de entrada");
            }           
        }
        

        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                et.id like '%" . $valor . "%' 
                           
                            or u.name like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (et.id like '%" . $valor . "%' 
                
                            or u.name like '%" . $valor . "%'  
                       )";
                    }
                }
                  //consulta...      
                    
                  $respuesta = DB::table('egr__tesorerias as et')
                  ->join('caja__arqueo as ca','ca.id','=',$joinArqueo) 
                  ->join('users as u', 'u.id', '=', 'ca.id_usuario')
                  ->join('adm__nacionalidads as an', 'an.id', '=', 'ca.tipo_moneda')
                  ->select(
                      'et.id',
                      'et.id_arqueo_abrir',
                      'et.total_arqueo_caja_abrir',
                      'et.monto_actual_abrir',
                      'et.comentario_abrir',
                      'et.abrir_cerrar',
                      'et.id_arqueo_cerrar',
                      'et.total_arqueo_caja_cerrar',
                      'et.comentario_cerrar',
                      'et.created_at',
                      'et.updated_at',
                      'ca.cantidad_billete',
                      'ca.total_billete',
                      'ca.total_moneda',
                      'ca.cantidad_moneda',
                      'ca.total_arqueo',
                      'u.name',
                      'an.simbolo'
                  )
              
                  ->where('et.id_sucursal', $request->id_sucursal)
                  ->whereRaw($where)
                  ->whereRaw($sqls)
                  ->orderByDesc('et.id')
                  ->paginate(15);
            }
            return     
            [
                'pagination'=>
                    [
                        'total'         =>    $respuesta->total(),
                        'current_page'  =>    $respuesta->currentPage(),
                        'per_page'      =>    $respuesta->perPage(),
                        'last_page'     =>    $respuesta->lastPage(),
                        'from'          =>    $respuesta->firstItem(),
                        'to'            =>    $respuesta->lastItem(),
                    ] ,
                'respuesta'=>$respuesta,
        ];   
        }  else {
            
           
           // dd($fecha_2);
            $respuesta = DB::table('egr__tesorerias as et')
            ->join('caja__arqueo as ca','ca.id','=',$joinArqueo) 
            ->join('users as u', 'u.id', '=', 'ca.id_usuario')
            ->join('adm__nacionalidads as an', 'an.id', '=', 'ca.tipo_moneda')
            ->select(
                'et.id',
                'et.id_arqueo_abrir',
                'et.total_arqueo_caja_abrir',
                'et.monto_actual_abrir',
                'et.comentario_abrir',
                'et.abrir_cerrar',
                'et.id_arqueo_cerrar',
                'et.total_arqueo_caja_cerrar',
                'et.comentario_cerrar',
                'et.created_at',
                'et.updated_at',
                'ca.cantidad_billete',
                'ca.total_billete',
                'ca.total_moneda',
                'ca.cantidad_moneda',
                'ca.total_arqueo',
                'u.name',
                'an.simbolo'
            )
          
            ->where('et.id_sucursal', $request->id_sucursal)
            ->whereRaw($where)
            ->orderByDesc('et.id')            
            ->whereBetween(DB::raw("DATE($fecha_2)"), [$ini, $fini])
            ->paginate(15);
            return     
            [
                'pagination'=>
                    [
                        'total'         =>    $respuesta->total(),
                        'current_page'  =>    $respuesta->currentPage(),
                        'per_page'      =>    $respuesta->perPage(),
                        'last_page'     =>    $respuesta->lastPage(),
                        'from'          =>    $respuesta->firstItem(),
                        'to'            =>    $respuesta->lastItem(),
                    ] ,
                'respuesta'=>$respuesta,
            ];
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {           
           // $query = $request->all();
           // return response()->json(['query' => $query]);
            DB::beginTransaction();
            $datos = [
                'id_usuario' => auth()->user()->id,
                'total_arqueo' => $request->total_arqueo_caja,                       
                'cantidad_billete' => $request->cantidadBilletes,  
                'total_billete' => $request->totalBilletas, 
                'cantidad_moneda' => $request->cantidadMonedas, 
                'total_moneda' => $request->totalMonedas, 
                'tipo_moneda' => $request->moneda_s1                      
            ];                
            $id = DB::table('caja__arqueo')->insertGetId($datos); 
            
            foreach ($request->input as $key => $value) {                       
                $datos_2 = [                            
                    'id_arqueo' => $id,                       
                    'id_moneda' => $key,  
                    'cantidad' => $value                                              
                ];  
                DB::table('caja__entrada_salida_array')->insert($datos_2);
            }     
        $crear = new Egr_Tesoreria();
        $crear->id_sucursal=$request->id_sucursal;
        $crear->id_arqueo_abrir=$id;
        $crear->total_arqueo_caja_abrir=$request->monto_sumado_X;
        $crear->monto_actual_abrir=$request->monto_sumado_X;            
        $crear->comentario_abrir=$request->observacion_X; 
        $crear->save();
       // return DB::commit();   
        DB::commit();             
        } catch (\Throwable $th) {
           return $th;
        } 
    }

    
    public function update(Request $request)
    {
        try {           
           // $query = $request->all();
           // return response()->json(['query' => $query]);
            DB::beginTransaction();
            $datos = [
                'id_usuario' => auth()->user()->id,
                'total_arqueo' => $request->total_arqueo_caja,                       
                'cantidad_billete' => $request->cantidadBilletes,  
                'total_billete' => $request->totalBilletas, 
                'cantidad_moneda' => $request->cantidadMonedas, 
                'total_moneda' => $request->totalMonedas, 
                'tipo_moneda' => $request->moneda_s1                      
            ];                
            $id = DB::table('caja__arqueo')->insertGetId($datos); 
            
            foreach ($request->input as $key => $value) {                       
                $datos_2 = [                            
                    'id_arqueo' => $id,                       
                    'id_moneda' => $key,  
                    'cantidad' => $value                                              
                ];  
                DB::table('caja__entrada_salida_array')->insert($datos_2);
            }     
            $actualizar = Egr_Tesoreria::findOrFail($request->id);
        $actualizar->abrir_cerrar=9;
        $actualizar->id_arqueo_cerrar=$id;
        $actualizar->total_arqueo_caja_cerrar=$request->monto_sumado_X;
            
        $actualizar->comentario_cerrar=$request->observacion_X; 
        $actualizar->save();
       // return DB::commit();   
        DB::commit();             
        } catch (\Throwable $th) {
           return $th;
        } 
    }


    public function getTesoreria(Request $request)
    {     
    $registro = DB::table('egr__tesorerias as et')
    ->select('et.id', 'et.monto_actual_abrir','et.abrir_cerrar')
    ->where('et.id_sucursal', $request->id_sucursal)     
    ->orderBy('et.id', 'desc')    
    ->first();
    return $registro;
    }   
}
