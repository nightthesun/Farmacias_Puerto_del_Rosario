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
        ->whereIn('tipo_venta', [1, 4])
        ->selectRaw('COALESCE(SUM(total_venta), 0) as total')
        ->value('total');

       
       
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
    'sumaSalida' => $sumaSalida,    
  
]);
    }

    public function cierre_store(Request $request ){

        try {
      
                if ($request->user==auth()->user()->name) {
                    $id_sucursal=$request->id_sucursal;
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
                    //// para imprimir boleta QR o tarjeta
                  
                        $fechaHora = Carbon::now(); // Se usará automáticamente el formato correcto
                        DB::table('ven__trasferencias as t')   
                        ->join('ven__recibos as r', 'r.id', '=', 't.id_venta')
                        ->where('r.id_apertura', $request->id_apertura) 
                        ->update(['t.contador' => 0,'t.updated_at' => $fechaHora]);

                    $generarstocks=$this->generarstocks($id_sucursal);
                    foreach ($generarstocks as $key => $value) {
                        $fechaHoy = Carbon::now()->format('Y-m-d');

            $datos_3=[
                'id_producto' => $value->id_producto,
                'stock' => $value->stock_total,
                'fecha_ingreso' => $fechaHoy, 
                'id_sucursal' => $id_sucursal
            ];
           DB::table('sis_bitacora_stock')->insert($datos_3);  
   // $pivote = new Pivot_Modulo_tienda_almacen();
                    }        
                    
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
      $id_user = Auth()->user()->id;

      $contar = DB::table('caja__apertura_cierres as c')
    ->join('caja__arqueo as a', 'c.id_arqueo', '=', 'a.id')
    ->where('a.id_usuario', $id_user)
    //->where('id_sucursal', $request->id_sucursal)   
    ->where('c.id_cierre', 0)
    ->count('c.id');

    if ($contar>0) {
        return response()->json([                        
            'ultimoRegistro' => 2,
            'ultimoRegistro_2' => 2, 
        ]);
    } else {
        $totalRegistros = DB::table('caja__apertura_cierres')
        ->where('id_sucursal', $request->id_sucursal)   
        ->count();
        if ($totalRegistros===0) {
            return response()->json([                        
                'ultimoRegistro' => 1,
                'ultimoRegistro_2' => 1, 
            ]);
        } else {
           
            $ultimoRegistro=DB::table('caja__apertura_cierres as cac')
            ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
            ->join('users as u', 'u.id', '=', 'ca.id_usuario')
            ->select('cac.turno_caja', 'cac.tipo_caja_c_a', 'cac.total_caja', 'cac.estado_caja')
            ->where('cac.id_sucursal', $request->id_sucursal)
            // ->where('cac.id_caja', $request->id_caja)   
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
 
           $data_11="";
           $data_12="";
            if ($ultimoRegistro==null) {
                $data_11=0;
            } else {
                $data_11=$ultimoRegistro;
            }

            if ($ultimoRegistro_2==null) {
                $data_12=0;
            } else {
                $data_12=$ultimoRegistro_2;
            }
         
                return response()->json([                        
                    'ultimoRegistro' => $data_11,
                    'ultimoRegistro_2' => $data_12, 
                ]);     
        }
    }
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

    public function getModalApertura(Request $request){
        $resultado = DB::table('adm__credecial_correos')
    ->select('id', 'modal_apertura')
    ->first();

    $usuario = auth()->user()->super_usuario;

    return response()->json([                        
        'resultado' => $resultado,
        'usuario' => $usuario, 
    ]);
    
    }

    public function getImpTrans(Request $request){
       
        $data = DB::table('adm__credecial_correos as cc')
        ->join('adm__nacionalidads as n', 'cc.moneda', '=', 'n.id')
        ->select('cc.nom_empresa', 'cc.imprimir_trans', 'n.simbolo')
        ->where('cc.id', 1)
        ->first();
    
    $nomEmpresa = $data->nom_empresa;
    $imprimirTrans = $data->imprimir_trans;
    $simbolo = $data->simbolo;


        if ($imprimirTrans==1) {
            $user_id=auth()->user()->id;
            $array_v = DB::table('ven__recibos as v')
        ->join('ven__trasferencias as t', 't.id_venta', '=', 'v.id')
        ->select('v.nro_comprobante_venta','v.monto_apagar',
            DB::raw("CASE 
                    WHEN v.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN v.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE 'OTRO'
                END AS tipo_venta"),
            't.tipo','v.created_at',
            DB::raw('SUM(v.monto_apagar) OVER () AS suma_total')
        )
        ->where('v.id_usuario', $user_id)
        ->where('v.id_apertura', $request->id_apertura)
        ->where('v.anulado', 0)        
        ->get();
        if( count($array_v)>0){
            $sucursal = DB::table('adm__sucursals')
            ->select('razon_social', 'direccion' ,'ciudad')
            ->where('id', $request->id_sucursal)
            ->first();
    
            
    
            $usuario = DB::table('users as u')
        ->join('rrh__empleados as e', 'e.id', '=', 'u.idempleado')
        ->select('u.name', 'e.nombre')
        ->where('u.id', $user_id)
        ->first();
        $nombre_sucursal = $sucursal->razon_social;
        $direccion = $sucursal->direccion;
        $ciudad = $sucursal->ciudad;
        $user_name = $usuario->name;
        $nombre = $usuario->nombre;
        $total=$array_v[0]->suma_total;
        return response()->json([                        
            'nomEmpresa' => $nomEmpresa,'imprimirTrans' => $imprimirTrans, 'simbolo' => $simbolo,'array_v' => $array_v, 
            'nombre_sucursal' => $nombre_sucursal,'direccion' => $direccion,'ciudad' => $ciudad,'user_name' => $user_name,'nombre' => $nombre,'total' => $total   
        ]);
        }else{
            return response()->json([                        
               'array_v' => $array_v                 
            ]); 
        }
            }else{
                $array_v=null;
                return response()->json([                        
                    'array_v' => $array_v                 
                 ]); 
            }

    }


    ///funcion publica para la funcion caja cierra
public function  generarstocks($id_sucursal){
  
// Subconsulta gettion_tienda
$gettionTienda = DB::table('prod__productos as pp')
    ->join('tda__ingreso_productos as tip', 'tip.id_prod_producto', '=', 'pp.id')
    ->join('tda__tiendas as tt','tt.id','=','tip.idtienda')
    ->join('adm__sucursals as ass','tt.idsucursal','=','ass.id') 
    ->join('pivot__modulo_tienda_almacens as pivot', function ($join) {
        $join->on('pivot.id_ingreso', '=', 'tip.id')
             ->where('pivot.tipo', '=', 'TDA');
    })
    ->join('ges_pre__venta2s as gpv2', 'gpv2.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
    ->join('prod__dispensers as pd', DB::raw("pd.id"), '=', DB::raw("
        CASE 
            WHEN tip.envase = 'primario' THEN pp.iddispenserprimario
            WHEN tip.envase = 'secundario' THEN pp.iddispensersecundario
            WHEN tip.envase = 'terciario' THEN pp.iddispenserterciario
        END
    "))
    ->join('prod__forma_farmaceuticas as pff', DB::raw("pff.id"), '=', DB::raw("
        CASE 
            WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
            WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
            WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
        END
    "))
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->select(
        'pp.id as id_producto',
        'pl.nombre as nombre_linea',
        'pl.tiempo_demora',
        'pp.nombre as nombre_producto',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.tiempopedidoprimario
                WHEN tip.envase = 'secundario' THEN pp.tiempopedidosecundario
                WHEN tip.envase = 'terciario' THEN pp.tiempopedidoterciario
                ELSE NULL
            END as tiempo_producto
        "),
        'pd.nombre as nombre_dis',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.cantidadprimario
                WHEN tip.envase = 'secundario' THEN pp.cantidadsecundario
                WHEN tip.envase = 'terciario' THEN pp.cantidadterciario
                ELSE NULL
            END as cantidad_dispenser_producto
        "),
        'pff.nombre as nombre_forma_farmaceutica',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.preciolistaprimario
                WHEN tip.envase = 'secundario' THEN pp.preciolistasecundario
                WHEN tip.envase = 'terciario' THEN pp.preciolistaterciario
                ELSE NULL
            END as precio_lista_producto
        "),
        'tip.stock_ingreso',
        'gpv2.utilidad_neto_gespreventa',
        'gpv2.costo_compra_gespreventa',
        'tip.envase',
        DB::raw("'Tienda' as tipo")
    )
        ->where('ass.id',$id_sucursal);
      
        

// Subconsulta gettion_almacen
$gettionAlmacen = DB::table('prod__productos as pp')
    ->join('alm__ingreso_producto as aip', 'aip.id_prod_producto', '=', 'pp.id')
    ->join('alm__almacens as aa', 'aip.idalmacen', '=', 'aa.id')
    ->join('adm__sucursals as ass', 'aa.idsucursal', '=', 'ass.id')

    ->join('pivot__modulo_tienda_almacens as pivot', function ($join) {
        $join->on('pivot.id_ingreso', '=', 'aip.id')
             ->where('pivot.tipo', '=', 'ALM');
    })
    ->join('ges_pre__venta2s as gpv2', 'gpv2.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
    ->join('prod__dispensers as pd', DB::raw("pd.id"), '=', DB::raw("
        CASE 
            WHEN aip.envase = 'primario' THEN pp.iddispenserprimario
            WHEN aip.envase = 'secundario' THEN pp.iddispensersecundario
            WHEN aip.envase = 'terciario' THEN pp.iddispenserterciario
        END
    "))
    ->join('prod__forma_farmaceuticas as pff', DB::raw("pff.id"), '=', DB::raw("
        CASE 
            WHEN aip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
            WHEN aip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
            WHEN aip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
        END
    "))
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->select(
        'pp.id as id_producto',
        'pl.nombre as nombre_linea',
        'pl.tiempo_demora',
        'pp.nombre as nombre_producto',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.tiempopedidoprimario
                WHEN aip.envase = 'secundario' THEN pp.tiempopedidosecundario
                WHEN aip.envase = 'terciario' THEN pp.tiempopedidoterciario
                ELSE NULL
            END as tiempo_producto
        "),
        'pd.nombre as nombre_dis',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.cantidadprimario
                WHEN aip.envase = 'secundario' THEN pp.cantidadsecundario
                WHEN aip.envase = 'terciario' THEN pp.cantidadterciario
                ELSE NULL
            END as cantidad_dispenser_producto
        "),
        'pff.nombre as nombre_forma_farmaceutica',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.preciolistaprimario
                WHEN aip.envase = 'secundario' THEN pp.preciolistasecundario
                WHEN aip.envase = 'terciario' THEN pp.preciolistaterciario
                ELSE NULL
            END as precio_lista_producto
        "),
        'aip.stock_ingreso',
        'gpv2.utilidad_neto_gespreventa',
        'gpv2.costo_compra_gespreventa',
        'aip.envase',
        DB::raw("'Almacen' as tipo")
    )
    ->where('ass.id',$id_sucursal);

// Unión de tienda y almacén
$combinado = $gettionTienda->unionAll($gettionAlmacen);

// Consulta principal con agrupación
$resultado = DB::table(DB::raw("({$combinado->toSql()}) as sub"))
    ->mergeBindings($combinado)
    ->select(
        'sub.id_producto',
        'sub.nombre_linea',
        'sub.tiempo_demora',
        'sub.nombre_producto',
        'sub.tiempo_producto',
        'sub.nombre_dis',
        'sub.cantidad_dispenser_producto',
        'sub.nombre_forma_farmaceutica',
        'sub.precio_lista_producto',
        DB::raw('SUM(sub.stock_ingreso) AS stock_total'),
        DB::raw('AVG(sub.utilidad_neto_gespreventa) AS utilidad_neta'),
        DB::raw('AVG(sub.costo_compra_gespreventa) AS precio_unitario'),
        'sub.envase',
        'sub.tipo'
    )
    ->groupBy(
        'sub.id_producto',
        'sub.nombre_linea',
        'sub.tiempo_demora',
        'sub.nombre_producto',
        'sub.tiempo_producto',
        'sub.nombre_dis',
        'sub.cantidad_dispenser_producto',
        'sub.nombre_forma_farmaceutica',
        'sub.precio_lista_producto',
        'sub.envase',
        'sub.tipo'
    )
    ->get();

    return $resultado;        

    }

}
