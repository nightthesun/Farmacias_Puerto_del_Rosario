<?php

namespace App\Http\Controllers;

use App\Models\Caja_EntradaSalida;
use App\Models\Caja_Transaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaTransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();
        if ($request->tipo_deposito==1) {           
            if (!empty($request->buscar)) {
                $buscararray = explode(" ", $request->buscar);
                $valor = sizeof($buscararray);
                if ($valor > 0) {
                    $sqls = '';    
                    foreach ($buscararray as $valor) {
                        if (empty($sqls)) {
                            $sqls = "(                                
                                    ct.comprobante like '%" . $valor . "%'                               
                                   )";
                        } else {
                            $sqls .= "and (
                            ct.comprobante like '%" . $valor . "%' 
                           )";
                        }
                    }
                    $resultado = DB::table('caja__transaccions as ct')
        ->join('users as u', 'u.id', '=', 'ct.id_cuenta')
        ->join('rrh__empleados as re', 're.id', '=', 'u.idempleado')
        ->join('rrh__empleado as re', 're.id', '=', 'u.idempleado')
        ->join('users as u_2', 'u_2.id', '=', 'ct.id_usuario_registra')
        ->select(
            'ct.id',
            'ct.id_cuenta',
            'u_2.name',
            'ct.comprobante',
            'ct.id_salida',
            'ct.monto_total',
            'ct.observacion',
            'ct.estado',
            'ct.tipo_deposito',
            'ct.created_at',
            DB::raw("CONCAT(
                COALESCE(u.name, ''),
                ' ',
                COALESCE(re.nombre, ''),
                ' ',
                COALESCE(re.papellido, ''),
                ' ',
                COALESCE(re.sapellido, '')
            ) AS name_all"),
              // Fecha y hora actual
        DB::raw('NOW() AS fecha_actual'),

        // Fecha y hora actual más 24 horas
        DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY) AS fecha_actual_mas_24_horas'),

        // Fecha created_at más 24 horas
        DB::raw('DATE_ADD(ct.created_at, INTERVAL 1 DAY) AS fecha_created_at_mas_24_horas'),

        // Diferencia en horas entre la fecha actual más 24 horas y created_at más 24 horas
        DB::raw('GREATEST(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(ct.created_at, INTERVAL 1 DAY)), 0) AS horas_restantes')
        )
        ->where('ct.tipo_deposito', $request->tipo_deposito)
        ->where('ct.id_sucursal', $request->id_sucursal)
        ->whereRaw($sqls)
        ->orderByDesc('ct.id')
        ->limit(500)
        ->paginate(50);   
                }    
                return 
                    ['pagination' =>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ],
                        'resultado' => $resultado,
                    ];
            } else {
                $resultado = DB::table('caja__transaccions as ct')
                ->join('users as u', 'u.id', '=', 'ct.id_cuenta')
                ->join('users as u_2', 'u_2.id', '=', 'ct.id_usuario_registra')
                ->join('rrh__empleados as re', 're.id', '=', 'u.idempleado')
                ->select(
                    'ct.id',
                    'ct.id_cuenta',
                    'u_2.name',
                    'ct.comprobante',
                    'ct.id_salida',
                    'ct.monto_total',
                    'ct.observacion',
                    'ct.estado',
                    'ct.tipo_deposito',
                    'ct.created_at',
                    DB::raw("CONCAT(
                        COALESCE(u.name, ''),
                        ' ',
                        COALESCE(re.nombre, ''),
                        ' ',
                        COALESCE(re.papellido, ''),
                        ' ',
                        COALESCE(re.sapellido, '')
                    ) AS name_all"),
                      // Fecha y hora actual
        DB::raw('NOW() AS fecha_actual'),

        // Fecha y hora actual más 24 horas
        DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY) AS fecha_actual_mas_24_horas'),

        // Fecha created_at más 24 horas
        DB::raw('DATE_ADD(ct.created_at, INTERVAL 1 DAY) AS fecha_created_at_mas_24_horas'),

        // Diferencia en horas entre la fecha actual más 24 horas y created_at más 24 horas
        DB::raw('GREATEST(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(ct.created_at, INTERVAL 1 DAY)), 0) AS horas_restantes')
                )
                ->where('ct.tipo_deposito', $request->tipo_deposito)
                ->where('ct.id_sucursal', $request->id_sucursal)
             
                ->orderByDesc('ct.id')
                ->limit(250)
                ->paginate(50);   
                return 
                ['pagination' =>
                    [
                        'total'         =>    $resultado->total(),
                        'current_page'  =>    $resultado->currentPage(),
                        'per_page'      =>    $resultado->perPage(),
                        'last_page'     =>    $resultado->lastPage(),
                        'from'          =>    $resultado->firstItem(),
                        'to'            =>    $resultado->lastItem(),
                    ],
                    'resultado' => $resultado,
                ];
            }

        } else {
            if ($request->tipo_deposito==2) {
            
                if (!empty($request->buscar)) {
                    $buscararray = explode(" ", $request->buscar);
                    $valor = sizeof($buscararray);
                    if ($valor > 0) {
                        $sqls = '';
        
                        foreach ($buscararray as $valor) {
                            if (empty($sqls)) {
                                $sqls = "(                                
                                        ct.comprobante like '%" . $valor . "%'                               
                                       )";
                            } else {
                                $sqls .= "and (
                                ct.comprobante like '%" . $valor . "%' 
                               )";
                            }
                        }
                       // Segunda consulta
    $resultado = DB::table('caja__transaccions as ct')
    ->join('adm__cuenta_banco as acb', 'acb.id', '=', 'ct.id_cuenta')
    ->join('adm__bancos as ab', 'ab.id', '=', 'acb.id_banco')
    ->join('users as u_2', 'u_2.id', '=', 'ct.id_usuario_registra')
    ->select(
        'ct.id',
        'ct.id_cuenta',
        'u_2.name',
        'ct.comprobante',
        'ct.id_salida',
        'ct.monto_total',
        'ct.observacion',
        'ct.estado',
        'ct.tipo_deposito',
        'ct.created_at',
        DB::raw("CONCAT(
            COALESCE(acb.nombre, ''),
            ' ',
            COALESCE(ab.nombre, '')
        ) AS name_all"),
          // Fecha y hora actual
        DB::raw('NOW() AS fecha_actual'),

        // Fecha y hora actual más 24 horas
        DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY) AS fecha_actual_mas_24_horas'),

        // Fecha created_at más 24 horas
        DB::raw('DATE_ADD(ct.created_at, INTERVAL 1 DAY) AS fecha_created_at_mas_24_horas'),

        // Diferencia en horas entre la fecha actual más 24 horas y created_at más 24 horas
        DB::raw('GREATEST(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(ct.created_at, INTERVAL 1 DAY)), 0) AS horas_restantes')
    )
    ->where('ct.tipo_deposito', $request->tipo_deposito)
    ->where('ct.id_sucursal', $request->id_sucursal)  
    ->whereRaw($sqls)
    ->orderByDesc('ct.id')
    ->limit(250)
    ->paginate(50);    
                    }    
                    return 
                        ['pagination' =>
                            [
                                'total'         =>    $resultado->total(),
                                'current_page'  =>    $resultado->currentPage(),
                                'per_page'      =>    $resultado->perPage(),
                                'last_page'     =>    $resultado->lastPage(),
                                'from'          =>    $resultado->firstItem(),
                                'to'            =>    $resultado->lastItem(),
                            ],
                            'resultado' => $resultado,
                        ];
                } else {
                    // Segunda consulta
    $resultado = DB::table('caja__transaccions as ct')
    ->join('adm__cuenta_banco as acb', 'acb.id', '=', 'ct.id_cuenta')
    ->join('adm__bancos as ab', 'ab.id', '=', 'acb.id_banco')
    ->join('users as u_2', 'u_2.id', '=', 'ct.id_usuario_registra')
    ->select(
        'ct.id',
        'ct.id_cuenta',
        'u_2.name',
        'ct.comprobante',
        'ct.id_salida',
        'ct.monto_total',
        'ct.observacion',
        'ct.estado',
        'ct.tipo_deposito',
        'ct.created_at',
        DB::raw("CONCAT(
            COALESCE(acb.nombre, ''),
            ' ',
            COALESCE(ab.nombre, '')
        ) AS name_all"),
          // Fecha y hora actual
        DB::raw('NOW() AS fecha_actual'),

        // Fecha y hora actual más 24 horas
        DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY) AS fecha_actual_mas_24_horas'),

        // Fecha created_at más 24 horas
        DB::raw('DATE_ADD(ct.created_at, INTERVAL 1 DAY) AS fecha_created_at_mas_24_horas'),

        // Diferencia en horas entre la fecha actual más 24 horas y created_at más 24 horas
        DB::raw('GREATEST(TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(ct.created_at, INTERVAL 1 DAY)), 0) AS horas_restantes')
    )
    ->where('ct.tipo_deposito', $request->tipo_deposito)
    ->where('ct.id_sucursal', $request->id_sucursal)  
    ->orderByDesc('ct.id')
    ->limit(250)
    ->paginate(50);   
                    return 
                    ['pagination' =>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ],
                        'resultado' => $resultado,
                    ];
                }

            } else {
                dd("error...");
            }            
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        DB::beginTransaction();        
         $crear = new Caja_Transaccion();
         $crear->id_sucursal=$request->id_sucursal;
         $crear->id_cuenta=$request->id_cuenta;
         $crear->comprobante=$request->comprobante;
         $crear->id_salida=$request->id_salida;
         $crear->monto_total=$request->monto_total;
         $crear->observacion=$request->observacion;
         $crear->id_usuario_registra=auth()->user()->id;
         $crear->tipo_deposito=$request->tipo_deposito;
         $crear->save();
         $array=$request->array;
       
            foreach ($array as $value) {  
                      
                $entrada_salida = Caja_EntradaSalida::find($value['id']);  
                if ($entrada_salida) {
                    $entrada_salida->transaccion=1;
                    $entrada_salida->save();
                }
                
            }        

         DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }       
    }   
   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Transaccion $caja_Transaccion)
    {
        try {
           
            DB::beginTransaction();   
            $now = Carbon::now();
            $array=$request->array;
            $e = Caja_Transaccion::find($request->id_transaccion);
           
          
             $e->id_sucursal=$request->id_sucursal;
             $e->id_cuenta=$request->id_cuenta;
             $e->comprobante=$request->comprobante;
             $e->id_salida=$request->id_salida;
             $e->monto_total=$request->monto_total;
             $e->observacion=$request->observacion;
             $e->id_usuario_modifica=auth()->user()->id;
             $e->tipo_deposito=$request->tipo_deposito;
             $e->save();
             
             if (count($array)>0) {
                foreach ($array as $value) {                         
                    $entrada_salida = Caja_EntradaSalida::find($value['id']);  
                    if ($entrada_salida) {
                        $entrada_salida->transaccion=1;
                        $entrada_salida->save();
                    }                   
                }  
                
            }

                $palabra= $request->des." ".$request->id_salida;
                $datos = [
                    'id_modulo' => $request->id_modulo,
                    'id_sub_modulo' => $request->id_sub_modulo,
                    'accion' => 2,
                    'descripcion' => $palabra,          
                    'user_id' =>auth()->user()->id, 
                    'created_at'=>$now,
                    'id_movimiento'=>$request->id_transaccion        
                ];
            
                DB::table('log__sistema')->insert($datos);       
               return DB::commit(); 
             DB::commit();
            } catch (\Throwable $th) {
                return $th;
            }    
    }

    public function get_cuenta_salida(Request $request){
        $user=auth()->user()->id;
        $cuentasBancos = DB::table('adm__cuenta_banco as acb')
        ->join('adm__bancos as ab', 'ab.id', '=', 'acb.id_banco')
        ->select('acb.id', 'acb.nombre as nom_cuenta', 'acb.nro_cuenta', 'ab.nombre as nom_banco')
        ->where('acb.estado', 1)
        ->get();       
    
    $cajaEntradasSalidas = DB::table('caja__entrada_salidas as ces')
    ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
    ->join('adm__nacionalidads as na', 'na.id', '=', 'ca.tipo_moneda')
    ->select('ces.id', 'ces.id_arqueo', 'ces.valor', 'ces.observacion', 'ces.mensaje', 'na.simbolo')
    ->where('ces.entrada_salida', 2)
    ->where('ca.id_usuario', $user)
    ->where('ces.transaccion', 0)
    ->where('ces.id_sucursal', $request->id_sucursal)
    ->get();
        return response()->json([                      
            'cuentasBancos' => $cuentasBancos,   
            'cajaEntradasSalidas' => $cajaEntradasSalidas
         ]);
    }
    
    
}
