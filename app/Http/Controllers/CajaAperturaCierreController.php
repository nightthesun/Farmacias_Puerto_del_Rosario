<?php

namespace App\Http\Controllers;

use App\Models\Caja_AperturaCierre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $where = "(cac.id_sucursal = $request->id_sucursal and cac.tipo_caja_c_a = $request->a_e and ca.id_usuario = $user)";            
        } else {
            $where = "(cac.id_sucursal = $request->id_sucursal and cac.tipo_caja_c_a = $request->a_e)";
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
                ->select('cac.id','cac.id_arqueo','cac.turno_caja','cac.tipo_caja_c_a','cac.total_venta_caja',
                       'cac.total_ingreso_caja','cac.total_salida_caja','cac.total_caja',
                        'cac.total_arqueo_caja','cac.diferencia_caja','cac.estado_caja','cac.created_at','u.name',
                        DB::raw('(cac.total_venta_caja + cac.total_ingreso_caja) as ingresos'),
                       'ca.cantidad_billete','ca.total_billete','ca.cantidad_moneda','ca.total_moneda','ca.tipo_moneda'
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
                ->select('cac.id','cac.id_arqueo','cac.turno_caja','cac.tipo_caja_c_a','cac.total_venta_caja',
                      'cac.total_ingreso_caja','cac.total_salida_caja','cac.total_caja',
                        'cac.total_arqueo_caja','cac.diferencia_caja','cac.estado_caja','cac.created_at','u.name',
                        DB::raw('(cac.total_venta_caja + cac.total_ingreso_caja) as ingresos'),
                        'ca.cantidad_billete','ca.total_billete','ca.cantidad_moneda','ca.total_moneda','ca.tipo_moneda'
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
                  
                    if ($request->tipo_caja_c_a=="0") {
                        $total_venta_caja=0;
                      
                       
                        $total_ingreso_caja=0;
                        $total_salida_caja=0;
                    }else{
                        if ($request->tipo_caja_c_a=="9") {

                            return "error 9";                     
                       
                        }else {
                            return "erro no entrada ";        
                        
                        }                       
                    }
             
                    $apertura_cierre->total_venta_caja = $total_venta_caja;
              
                    
                    $apertura_cierre->total_ingreso_caja = $total_ingreso_caja;       
                    $apertura_cierre->total_salida_caja = $total_salida_caja;   
                    $apertura_cierre->total_caja = $request->total_caja; 
                    $apertura_cierre->total_arqueo_caja = $request->total_arqueo_caja; 
                    $apertura_cierre->diferencia_caja = $request->diferencia; 
                    $apertura_cierre->estado_caja = $request->estado;
                    $apertura_cierre->save();        
                              
            
            DB::commit();
           
        } catch (\Throwable $th) {
            return $th;
        }
    }

  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_AperturaCierre $caja_AperturaCierre)
    {
        //
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
      $id_user = Auth()->user()->id;
      $ultimoRegistro=DB::table('caja__apertura_cierres as cac')
      ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
      ->join('users as u', 'u.id', '=', 'ca.id_usuario')
      ->select('cac.turno_caja', 'cac.tipo_caja_c_a', 'cac.total_caja', 'cac.estado_caja')
      ->where('cac.id_sucursal', $request->id_sucursal)
      ->where('ca.id_usuario', $id_user)
      ->where('cac.tipo_caja_c_a', 0)
      ->where('cac.id_apertura_cierre', 0)
      ->orderBy('cac.created_at', 'desc')
      ->first();
    
        if($ultimoRegistro==null){
          $ultimoRegistro=0;  
        }     
       return $ultimoRegistro;      
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
}
