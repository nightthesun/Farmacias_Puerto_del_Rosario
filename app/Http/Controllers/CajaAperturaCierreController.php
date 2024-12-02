<?php

namespace App\Http\Controllers;

use App\Models\Caja_AperturaCierre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Else_;

class CajaAperturaCierreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();   
        ///persona
        
        $ini=$request->ini;
        $fini=$request->fini;
        
        if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id; 
            if ($request->a_e==0) {
                $where = "(cac.id_sucursal = $request->id_sucursal AND ca.id_usuario = $user and cac.id_caja = $request->id_caja)"; 
            } else {
                $where = "(cac.id_sucursal = $request->id_sucursal  and cac.id_cierre <> 0 AND ca.id_usuario = $user and cac.id_caja = $request->id_caja)"; 
            }         
        } else {
            if ($request->a_e==0) {
                $where = "(cac.id_sucursal = $request->id_sucursal and cac.id_caja = $request->id_caja)";
            } else {
                $where = "(cac.id_sucursal = $request->id_sucursal and cac.id_cierre <> 0 and cac.id_caja = $request->id_caja)";
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
                                u.name like '%" . $valor . "%' 
                                or cac.estado_caja like '%" . $valor . "%' 
                            or cac.id like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (u.name like '%" . $valor . "%' 
                        or cac.estado_caja like '%" . $valor . "%' 
                       or cac.id like '%" . $valor . "%' 
                       )";
                    }
                }
                $resultado = DB::table('caja__apertura_cierres as cac')
                ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
                ->join('users as u', 'u.id', '=', 'ca.id_usuario')
                ->join('caja__creacions as cc_1','cc_1.id','=','cac.id_caja')
                ->leftJoin('caja__cierre as cc', 'cac.id_cierre', '=', 'cc.id')
                ->select('cac.id','cac.id_arqueo','cac.turno_caja','cac.tipo_caja_c_a','cac.total_caja',
                        'cac.total_arqueo_caja','cac.diferencia_caja','cac.estado_caja','cac.created_at','u.name','cac.id_cierre as id_apertura_cierre',
                
                       'ca.cantidad_billete','ca.total_billete','ca.cantidad_moneda','ca.total_moneda','ca.tipo_moneda',
                       'cc.id as id_cierre_2',
        'cc.id_arqueo as id_arqueo_cierre',
        'cc.total_venta_caja as total_venta_caja_cierre',
        'cc.total_ingreso_caja as total_ingreso_caja_cierre',
        'cc.total_salida_caja as total_salida_caja_cierre',
        'cc.total_caja as total_caja_cierre',
        'cc.total_arqueo_caja as total_arqueo_caja_cierre',
        'cc.diferencia_caja as diferencia_caja_cierre',
        'cc.estado_caja as estado_caja_cierre',
        'cc.created_at as created_at_cierre','cc_1.monto_caja as caja_monto_v2','cc_1.moneda as caja_moneda_v2','cc_1.codigo as caja_codigo_v2','cc_1.nombre_caja as caja_nombre_caja_v2'
                        )
                     //   ->where('cac.id_sucursal','=',$request->id_sucursal)
                     //   ->where('cac.tipo_caja_c_a','=',$request->a_e)          
                     //   ->where('ca.id_usuario','=',$user)   
                     ->whereRaw($where)
                     ->whereRaw($sqls)               
                ->orderByDesc('cac.id')
           
                ->paginate(15);               
            }   
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ];  
        } else {
            $resultado = DB::table('caja__apertura_cierres as cac')
                ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
                ->join('users as u', 'u.id', '=', 'ca.id_usuario')
                ->join('caja__creacions as cc_1','cc_1.id','=','cac.id_caja')
                ->leftJoin('caja__cierre as cc', 'cac.id_cierre', '=', 'cc.id')
                ->select('cac.id','cac.id_arqueo','cac.turno_caja','cac.tipo_caja_c_a','cac.total_caja',
                        'cac.total_arqueo_caja','cac.diferencia_caja','cac.estado_caja','cac.created_at','u.name',
                'cac.id_cierre as id_apertura_cierre',
                        'ca.cantidad_billete','ca.total_billete','ca.cantidad_moneda','ca.total_moneda','ca.tipo_moneda',
                        'cc.id as id_cierre_2',
        'cc.id_arqueo as id_arqueo_cierre',
        'cc.total_venta_caja as total_venta_caja_cierre',
        'cc.total_ingreso_caja as total_ingreso_caja_cierre',
        'cc.total_salida_caja as total_salida_caja_cierre',
        'cc.total_caja as total_caja_cierre',
        'cc.total_arqueo_caja as total_arqueo_caja_cierre',
        'cc.diferencia_caja as diferencia_caja_cierre',
        'cc.estado_caja as estado_caja_cierre',    
        'cc.created_at as created_at_cierre','cc_1.monto_caja as caja_monto_v2','cc_1.moneda as caja_moneda_v2','cc_1.codigo as caja_codigo_v2','cc_1.nombre_caja as caja_nombre_caja_v2'
                        )
                        ->whereRaw($where) 
              ->whereBetween(DB::raw('DATE(cac.created_at)'), [$ini, $fini]) 
                ->orderByDesc('cac.id')
                ->paginate(15);  
                return 
                [
                        'pagination'=>
                            [
                                'total'         =>    $resultado->total(),
                                'current_page'  =>    $resultado->currentPage(),
                                'per_page'      =>    $resultado->perPage(),
                                'last_page'     =>    $resultado->lastPage(),
                                'from'          =>    $resultado->firstItem(),
                                'to'            =>    $resultado->lastItem(),
                            ] ,
                        'resultado'=>$resultado,
                ];
        }       
    }
    
    public function suma_operacion_v2(Request $request){
        $suma_venta = DB::table('ven__recibos')
        ->where('id_usuario', auth()->user()->id)
        ->where('id_apertura', $request->id_apertura) 
        ->sum('total_venta');
        // Sum of "entrada" values
        $sumaEntrada = DB::table('caja__entrada_salidas')
            ->where('id_apertura_cierre', $request->id_apertura)
            ->where('entrada_salida', 1)
            ->sum('valor');

// Sum of "salida" values
$sumaSalida = DB::table('caja__entrada_salidas')
->where('id_apertura_cierre', $request->id_apertura)
->where('entrada_salida', 2)
->sum('valor');
return response()->json([                        
    'suma_venta' => $suma_venta,
    'sumaEntrada' => $sumaEntrada, 
    'sumaSalida' => $sumaSalida
]);
    }

    public function cierre_store(Request $request ){

        try {
      
                if ($request->user==auth()->user()->name) {
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
                        DB::table('caja__arqueo_array')->insert($datos_2);
                    }
                    $currentDateTime = Carbon::now();
                    $numero = $request->diferencia;
                    $resultado=0;
                        if ($numero < 0) {
                            $resultado=$numero*-1 ;
                        } else {
                            $resultado = $request->diferencia;
                        }
                    $datos_2 = [
                        'id_apertura' => $request->id_apertura,
                        'id_arqueo' => $id,
                        'total_venta_caja' => $request->total_venta_caja,                       
                        'total_ingreso_caja' => $request->total_ingreso_caja,  
                        'total_salida_caja' => $request->total_salida_caja, 
                        'total_caja' => $request->total_caja, 
                        'total_arqueo_caja' => $request->total_arqueo_caja, 
                        'diferencia_caja' => $resultado,  
                        'estado_caja' => $request->estado, 
                        'created_at' => $currentDateTime, 
                        'updated_at' => $currentDateTime,                     
                    ];    

                   $id_1 = DB::table('caja__cierre')->insertGetId($datos_2);   
                    $apertura_cierre = Caja_AperturaCierre::findOrFail($request->id_apertura);
                    $apertura_cierre->id_cierre = $id_1;
                    $apertura_cierre->save(); 
                    DB::commit();
                } else {
                    return "La operacióm debe ser relziada por el mismo usuario";
                }
          
        } catch (\Throwable $th) {
            return $th;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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
                        DB::table('caja__arqueo_array')->insert($datos_2);
                    }                 
                
                    $apertura_cierre = new Caja_AperturaCierre(); 
                    $apertura_cierre->id_sucursal = $request->id_sucursal;
                    $apertura_cierre->id_arqueo = $id; 
                    $apertura_cierre->turno_caja = $request->selectTurno;
                    $apertura_cierre->tipo_caja_c_a = $request->tipo_caja_c_a;
                  
                    $apertura_cierre->total_caja = $request->total_caja; 
                    $apertura_cierre->total_arqueo_caja = $request->total_arqueo_caja; 
                    $apertura_cierre->diferencia_caja = $request->diferencia; 
                    $apertura_cierre->estado_caja = $request->estado;
                    $apertura_cierre->id_caja = $request->id_cajaxUsuario;
                    $apertura_cierre->save();        
                              
            
            DB::commit();
           
        } catch (\Throwable $th) {
            return $th;
        }
    }

  
       

    public function verificador_moneda_sistemas(Request $request){
        
        $moneda = DB::table('adm__credecial_correos')
        ->where('id', 1)
        ->value('moneda');

// Asigna directamente el valor de $moneda a $data_1
$data_1 = $moneda;
        if ($data_1==0||$data_1==null||$data_1=="") { 
                      
            return response()->json([                        
                'moneda' => 0,
                'listaMoneda' => null, 
            ]);
        }else {
         
            $monedas_2 =  DB::table('caja__monedas')
            ->select('id', 'tipo_corte', 'valor', 'unidad', 'unidad_entera', DB::raw('0.00 AS valor_default'),DB::raw('0 AS input'),'id_nacionalidad_pais')
            ->where('id_nacionalidad_pais', $moneda)
            ->where('activo', 1)
            ->get();
           
            return response()->json([                        
                'moneda' => $moneda,
                'listaMoneda' => $monedas_2, 
            ]);
        }
    }

    public function cajaAnteriror(Request $request){
      //  $ultimoRegistro = DB::table('caja__apertura_cierres')
      //  ->select('turno_caja', 'tipo_caja_c_a', 'total_caja', 'estado_caja')
      //  ->where('id_sucursal', $request->id_sucursal)    
      //  ->orderBy('created_at', 'desc')
      //  ->first();
    $totalRegistros = DB::table('caja__apertura_cierres')
    ->where('id_sucursal', $request->id_sucursal)
    ->where('id_caja', $request->id_caja)    
    ->count();
        
    if ($totalRegistros===0) {
        return response()->json([                        
            'ultimoRegistro' => 1,
            'ultimoRegistro_2' => 1, 
        ]);
    } else {
        $id_user = Auth()->user()->id;
        $ultimoRegistro=DB::table('caja__apertura_cierres as cac')
        ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
        ->join('users as u', 'u.id', '=', 'ca.id_usuario')
        ->select('cac.turno_caja', 'cac.tipo_caja_c_a', 'cac.total_caja', 'cac.estado_caja')
        ->where('cac.id_sucursal', $request->id_sucursal)
        ->where('cac.id_caja', $request->id_caja)   
        ->where('ca.id_usuario', $id_user)
        ->where('cac.tipo_caja_c_a', 0)
        ->where('cac.id_cierre', 0)
        ->orderBy('cac.created_at', 'desc')
        ->first();
  
          // verifica si la seguna apertura es correta o contiene 
        $ultimoRegistro_2 = DB::table('caja__cierre as cc')
      ->join('caja__apertura_cierres as cac', 'cac.id_cierre', '=', 'cc.id')
      ->select('cc.id', 'cc.diferencia_caja', 'cc.estado_caja')
      ->where('cac.id_sucursal',  $request->id_sucursal)
      ->where('cac.id_caja', $request->id_caja)   
      ->orderBy('cc.created_at', 'desc')
      ->first();
    
        if ($ultimoRegistro==null) {
            return response()->json([                        
                'ultimoRegistro' => 0,
                'ultimoRegistro_2' => $ultimoRegistro_2, 
            ]);
        }else{
            return response()->json([                        
                'ultimoRegistro' => $ultimoRegistro,
                'ultimoRegistro_2' => $ultimoRegistro_2, 
            ]);
        }

          
    }

      

      // return $ultimoRegistro;      
    }
    
    public function monedaModal(Request $request){
       
        $resultado = DB::table('caja__arqueo_array as caa')
    ->join('caja__monedas as cm', 'caa.id_moneda', '=', 'cm.id')
    ->select('caa.id_moneda as id','cm.unidad_entera', 'cm.unidad', 'cm.tipo_corte', 'cm.valor', 'caa.cantidad')
    ->where('caa.id_arqueo','=',$request->id_arqueo)
    ->get();
  
    return $resultado;

    }

    public function getmoneda(Request $request){
    $resultado=  DB::table('caja__entrada_salida_array as cesa')
    ->join('caja__monedas as cm', 'cm.id', '=', 'cesa.id_moneda')
    ->select('cesa.id_arqueo', 'cesa.cantidad', 'cm.id', 'cm.tipo_corte', 'cm.valor', 'cm.unidad', 'cm.unidad_entera')
    ->where('cesa.id_arqueo', $request->id_arqueo)
    ->get();    
    return $resultado;
    }

    public function getCaja_x_usuario(Request $request){
        $usuario = auth()->user()->id;
        $resultado = DB::table('caja__creacions as cc')
            ->select('cc.id', 'cc.codigo', 'cc.nombre_caja', 'cc.monto_caja', 'cc.moneda')
            ->where('cc.id_sucursal', $request->id_sucursal)
            ->whereRaw('FIND_IN_SET(?, cc.id_users)', [$usuario])
            ->where('cc.estado', 1)
            ->get();
        
        return $resultado;
    }
}
