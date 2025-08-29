<?php

namespace App\Http\Controllers;

use App\Models\Inv_GestionStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

class InvGestionStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }



    public function alerta_modal_parte_superior(Request $request){
        $cont = 0;
        $importe_total =0;
        
        $id_linea_array=$request->id_distri_lista;
        $tipo_modal=$request->tipo;
        
        $arrayMostrar=[];
$id_sucursal=$request->id_sucursal;
   
    $simbolos = DB::table('adm__credecial_correos as a')
    ->join('adm__nacionalidads as b', 'a.moneda', '=', 'b.id')
    ->select('b.simbolo')
    ->first();
// Convertir la cadena a array
$elementos = array_filter(explode(',', $id_linea_array));
    
// Recorrer con foreach (más flexible)
    foreach ($elementos as $valor) {
        $id_linea = $valor;
   
        //obtenemos el resultado del metodo total venta
          $rspta = $this->alerta_query($id_linea);
           
          foreach ($rspta as $key => $value) {
        
            $id_producto = $value->id_prod_producto;
            $total_venta = $value->total_veta;
        
            $rspta1 = $this->listarControl($id_producto,$id_sucursal); 
       
            foreach ($rspta1 as $key => $value_2) {
                   $linea = $value_2->nombre_linea;
                   $envase = $value_2->envase;
                if(is_numeric($value_2->cantidad_dispenser_producto)){
                  
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_dis." X ".$value_2->cantidad_dispenser_producto." ".$value_2->nombre_forma_farmaceutica;
                        $dis = $value_2->cantidad_dispenser_producto;
                }else{
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_dis." ".$value_2->cantidad_dispenser_producto." ".$value_2->nombre_forma_farmaceutica;
                    $dis=1;
                }
                 
                $precio_lista = $value_2->precio_lista_producto;
                $stock_total = $value_2->stock_total;
                $utilidad_neta = $value_2->utilidad_neta;
                $ciclo = $value_2->tiempo_producto;
                $plazo = $value_2->tiempo_demora;
                 //OBTENEMOS EL STOCK PROMEDIO
                $rspta2 = $this->promediostock($id_producto);
                    foreach ($rspta2 as $key => $value_3) {
                         $promstock = $value_3->promedioStock;
                        //PARA LOS CALCULOS DE LA TABLA
                        $consumo_mensual = $total_venta / $ciclo;
                        $mes = $ciclo * 30;
                        $tiempo_retardo = $mes + $plazo;
                        $consumo_dia = $consumo_mensual / 30;
                        $stmax = $consumo_dia * $tiempo_retardo;
                        $minimo = $consumo_dia * $plazo;
                        $alerta = $minimo * 2;
                        $stmedio = $promstock;
                        $indicerot = $consumo_mensual / $stmedio;
                        $stpedido = $stmax - $stock_total;
                        $indicecober = $stock_total / $consumo_mensual;
                        $rentabilidad = $utilidad_neta * $indicerot;
                        //para el color del label
                        $color = 3;
                        
                        // colores 0=rojo peligro, 1=minimo amarillo ,2=alerta naranja
                        if($stock_total == 0){
                            $color = 0;
                        }else{
                            if($stock_total <= $minimo){
                                $color = 1;
                            }else{
                                if($stock_total <= $alerta && $stock_total > $minimo){
                                    $color = 2;
                                }
                            }
                        }
                  
                        if($tipo_modal==1){
                          
                            if($stock_total <= $alerta){
                            //LISTADO FINAL
                            $dispedido=round($stpedido / $dis);
                            $subtotal=$precio_lista * $dispedido;
                            $importe_total = $importe_total + $subtotal;
                            $arrayMostrar[] = [
                            'id' => $cont,    
                            'id_producto' => $id_producto,  
                            'linea' => $linea,
                            'producto' => $producto, 
                            'ciclo' => $ciclo,
                            'consumo_mensual' => round($consumo_mensual),
                            'plazo' => $plazo,
                            'consumo_dia' => round($consumo_dia),
                            'stmax' => round($stmax),
                            'stmedio' => round($stmedio),
                            'stock_total' => $stock_total,
                            'stpedido' => round($stpedido),
                            'dispedido'=>round($dispedido),
                            'precio_lista'=>$precio_lista,  
                            'subtotal'=>$subtotal,
                            'envase'=>$envase,
                                          
                            ]; 
                            $cont=$cont+1;
                        }   
                        }
                        if($tipo_modal==2){
                            $importe_total=0;
                            if(0 <= $minimo){
                                 $dispedido=round($stpedido / $dis);
                            $subtotal=$precio_lista * $dispedido;
                            $importe_total = $importe_total + $subtotal;
                            $arrayMostrar[] = [
                            'id' => $cont,    
                            'id_producto' => $id_producto,  
                            'linea' => $linea,
                            'producto' => $producto, 
                            'ciclo' => $ciclo,
                            'consumo_mensual' => round($consumo_mensual),
                            'plazo' => $plazo,
                            'consumo_dia' => round($consumo_dia),
                            'stmax' => round($stmax),
                            'stmedio' => round($stmedio),
                            'stock_total' => $stock_total,
                            'stpedido' => round($stpedido),
                            'dispedido'=>round($dispedido),
                            'precio_lista'=>$precio_lista,  
                            'subtotal'=>$subtotal,
                            'envase'=>$envase,               
                            ]; 
                            $cont=$cont+1;
                            }
                        }                                                  
                    }
                 }

          }
    }
     return response()->json([
                'arrayMostrar' => $arrayMostrar,
                'importe_total' => $importe_total,   
                'simbolos'  => $simbolos        
            ]);

    }

   
    public function getGestorStockModal(Request $request){

        $arrayMostrar=[];
        $arrayLinea=[];
        $id_sucursal=$request->id_sucursal;
        $getStock=$this->get_totalventa($id_sucursal);        
        if (count($getStock)>0) {
            foreach ($getStock as $key => $value) {
                $id_producto = $value->id_producto;
                $total_venta = $value->total_venta_cantidad;
              
                //OBTENEMOS DATOS DEL METODO LISTARCONTROL
                 $rspta1 = $this->listarControl($id_producto,$id_sucursal);      

                 foreach ($rspta1 as $key => $value_2) {
                   $linea = $value_2->nombre_linea;
                   $id_linea=$value_2->id_linea;
                if(is_numeric($value_2->cantidad_dispenser_producto)){
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_forma_farmaceutica." X ".$value_2->nombre_dis." ".$value_2->nombre_forma_farmaceutica;
                }else{
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_forma_farmaceutica." ".$value_2->nombre_dis." ".$value_2->nombre_forma_farmaceutica;
                }
                $precio_lista = $value_2->precio_lista_producto;
                $stock_total = $value_2->stock_total;
                $utilidad_neta = $value_2->utilidad_neta;
                $ciclo = $value_2->tiempo_producto;
                $plazo = $value_2->tiempo_demora;
                 //OBTENEMOS EL STOCK PROMEDIO
                $rspta2 = $this->promediostock($id_producto);
                    foreach ($rspta2 as $key => $value_3) {
                         $promstock = $value_3->promedioStock;
                        //PARA LOS CALCULOS DE LA TABLA
                        $consumo_mensual = $total_venta / $ciclo;
                        $mes = $ciclo * 30;
                        $tiempo_retardo = $mes + $plazo;
                        $consumo_dia = $consumo_mensual / 30;
                        $stmax = $consumo_dia * $tiempo_retardo;
                        $minimo = $consumo_dia * $plazo;
                        $alerta = $minimo * 2;
                        $stmedio = $promstock;
                        $indicerot = $consumo_mensual / $stmedio;
                        $stpedido = $stmax - $stock_total;
                        $indicecober = $stock_total / $consumo_mensual;
                        $rentabilidad = $utilidad_neta * $indicerot;
                        //para el color del label
                        $color = 3;
                        // colores 0=rojo peligro, 1=minimo amarillo ,2=alerta naranja
                        if($stock_total == 0){
                            $color = 0;
                        }else{
                            if($stock_total <= $minimo){
                                $color = 1;
                                $arrayLinea[] = ['id_linea'=> $id_linea,'color'=>'amarillo'];
                            }else{
                                if($stock_total <= $alerta && $stock_total > $minimo){
                                    $color = 2;
                                $arrayLinea[] = ['id_linea'=> $id_linea,'color'=>'naranja'];
                                }
                            }
                        }
                        if ($stpedido < 0) {
                            $stpedido = 0;
                        }
                            //LISTADO FINAL
                            $arrayMostrar[] = [
                            'id_producto' => $id_producto,  
                            'linea' => $linea,
                            'producto' => $producto, 
                            'ciclo' => $ciclo,
                            'consumo_mensual' => round($consumo_mensual),
                            'plazo' => $plazo,
                            'consumo_dia' => round($consumo_dia),
                            'stmax' => round($stmax),
                            'stmedio' => round($stmedio),
                            'stock_total' => $stock_total,
                            'stpedido' => round($stpedido),
                            'indicerot' => number_format(round($indicerot, 2), 2),
                            'indicecober' => round($indicecober).' Meses',
                            'rentabilidad' => round($rentabilidad).' %',   
                            'color'=> $color                      
                            ];  
                    }
                 }
            
            }
            return response()->json([
                'arrayMostrar' => $arrayMostrar,
                'arrayLinea' => $arrayLinea      
            ]);
           
        }else{
            return response()->json([
                'arrayMostrar' => $arrayMostrar,
                'arrayLinea' => $arrayLinea      
            ]);
        }
      
    }

    public function get_distribuidor_gesSctock(){
     
         $query = DB::table('dir__distribuidors as dd')
        ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
        ->select('dd.id','dd.id_cliente','dd.id_linea_array','dd.nom_linea_array','dc.nom_a_facturar','dc.num_documento')
        ->where('dd.estado', 1)
        ->orderByDesc('dd.id')  // También puedes usar ->orderBy('dd.id', 'desc')
        
        ->get();
        return $query;
       
    }
    
    private function get_totalventa($id_sucursal){
        

    $hoy = Carbon::now()->toDateString();

// Subconsulta: venta_tienda
$ventaTienda = DB::table('ven__recibos as vr')
    ->join('ven__detalle_ventas as vdv', 'vdv.id_venta', '=', 'vr.id')
    ->join('tda__ingreso_productos as tip', 'vdv.id_ingreso', '=', 'tip.id')
    ->join('prod__productos as pp', 'tip.id_prod_producto', '=', 'pp.id')
    ->select(
        'vdv.id_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) AS total_venta_cantidad'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) AS cantidad_ingresada')
    )    
    ->whereRaw('DATE(vr.created_at) <= CURDATE()')
    ->where('vr.id_sucursal',$id_sucursal)
    ->where('pp.activo', 1)
    ->where(DB::raw('LEFT(vr.cod,3)'), '=', 'TDA')
    ->whereRaw("
        DATE(vr.created_at) >= DATE_SUB(CURDATE(), INTERVAL 
            CASE 
                WHEN tip.envase = 'primario' THEN COALESCE(pp.tiempopedidoprimario, 0)
                WHEN tip.envase = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, 0)
                WHEN tip.envase = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, 0)
                ELSE 0
            END MONTH
        )
    ")
    ->groupBy('vdv.id_producto');

// Subconsulta: venta_almacen
$ventaAlmacen = DB::table('ven__recibos as vr')
    ->join('ven__detalle_ventas as vdv', 'vdv.id_venta', '=', 'vr.id')
    ->join('alm__ingreso_producto as aip', 'vdv.id_ingreso', '=', 'aip.id')
    ->join('prod__productos as pp', 'aip.id_prod_producto', '=', 'pp.id')
    ->select(
        'vdv.id_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) AS total_venta_cantidad'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) AS cantidad_ingresada')
    )
    ->whereRaw('DATE(vr.created_at) <= CURDATE()')
    ->where('vr.id_sucursal',$id_sucursal)
    ->where('pp.activo', 1)
    ->where(DB::raw('LEFT(vr.cod,3)'), '=', 'ALM')
    ->whereRaw("
        DATE(vr.created_at) >= DATE_SUB(CURDATE(), INTERVAL 
            CASE 
                WHEN aip.envase = 'primario' THEN COALESCE(pp.tiempopedidoprimario, 0)
                WHEN aip.envase = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, 0)
                WHEN aip.envase = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, 0)
                ELSE 0
            END MONTH
        )
    ")
    ->groupBy('vdv.id_producto');

// Unión de ambas subconsultas
$ventasCombinadas = $ventaTienda
    ->unionAll($ventaAlmacen);

// Consulta final con agrupación y suma
$resultado = DB::table(DB::raw("({$ventasCombinadas->toSql()}) as ventas_combinadas"))
    ->mergeBindings($ventasCombinadas) // importante para que los bindings funcionen
    ->select(
        'id_producto',
        DB::raw('SUM(total_venta_cantidad) AS total_venta_cantidad'),
        DB::raw('SUM(cantidad_ingresada) AS cantidad_ingresada')
    )
    ->groupBy('id_producto')
    ->orderBy('id_producto')
    ->get();

        return $resultado;
    }

private function  listarControl($id_producto,$id_sucursal){

  
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
        'pl.id as id_linea',
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
        'tip.envase as envase',
        DB::raw("'Tienda' as tipo")
    )
        ->where('ass.id',$id_sucursal)
        ->where('pp.id', $id_producto);
        

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
        'pl.id as id_linea',
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
        'aip.envase as envase',
        DB::raw("'Almacen' as tipo")
    )
    ->where('ass.id',$id_sucursal)
    ->where('pp.id', $id_producto);

// Unión de tienda y almacén
$combinado = $gettionTienda->unionAll($gettionAlmacen);

// Consulta principal con agrupación
$resultado = DB::table(DB::raw("({$combinado->toSql()}) as sub"))
    ->mergeBindings($combinado)
    ->select(
        'sub.id_producto',
        'sub.id_linea',
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
        'sub.id_linea',
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

    private function promediostock($id_producto){

        $promedioStock = DB::table('sis_bitacora_stock as s')
    ->select('s.id_producto', DB::raw('IFNULL(AVG(s.stock), 0) as promedioStock'))
    ->where('s.id_producto', $id_producto)
    ->groupBy('s.id_producto')
    ->get();
    return $promedioStock;
    }

    private function alerta_query($id_linea){ 

    $today = Carbon::now()->toDateString();

// Subconsulta: tienda
$tienda = DB::table('ven__detalle_ventas as vdv')
    ->join('tda__ingreso_productos as tip', function ($join) {
        $join->on('tip.id', '=', 'vdv.id_ingreso')
             ->where(DB::raw('LEFT(vdv.codigo_tienda_almacen, 3)'), '=', 'TDA');
    })
    ->join('prod__productos as pp', 'pp.id', '=', 'vdv.id_producto')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('ven__recibos as vr', 'vr.id', '=', 'vdv.id_venta')
    ->where('pl.id', $id_linea)
    ->whereDate('vr.created_at', '<=', $today)
    ->whereRaw("
        DATE(vr.created_at) >= DATE_SUB(
            CURDATE(),
            INTERVAL 
                CASE 
                    WHEN tip.envase = 'primario' THEN COALESCE(pp.tiempopedidoprimario, 0)
                    WHEN tip.envase = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, 0)
                    WHEN tip.envase = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, 0)
                    ELSE 0
                END MONTH
        )
    ")
    ->groupBy('tip.id_prod_producto')
    ->select([
        'tip.id_prod_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) as total_veta'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) as cuanto_venta')
    ]);

// Subconsulta: almacen
$almacen = DB::table('ven__detalle_ventas as vdv')
    ->join('alm__ingreso_producto as aip', function ($join) {
        $join->on('aip.id', '=', 'vdv.id_ingreso')
             ->where(DB::raw('LEFT(vdv.codigo_tienda_almacen, 3)'), '=', 'ALM');
    })
    ->join('prod__productos as pp', 'pp.id', '=', 'vdv.id_producto')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('ven__recibos as vr', 'vr.id', '=', 'vdv.id_venta')
    ->where('pl.id', 1)
    ->whereDate('vr.created_at', '<=', $today)
    ->whereRaw("
        DATE(vr.created_at) >= DATE_SUB(
            CURDATE(),
            INTERVAL 
                CASE 
                    WHEN aip.envase = 'primario' THEN COALESCE(pp.tiempopedidoprimario, 0)
                    WHEN aip.envase = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, 0)
                    WHEN aip.envase = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, 0)
                    ELSE 0
                END MONTH
        )
    ")
    ->groupBy('aip.id_prod_producto')
    ->select([
        'aip.id_prod_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) as total_veta'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) as cuanto_venta')
    ]);

// Unión de ambas subconsultas con UNION ALL
    $resultado = $tienda
        ->unionAll($almacen)
        ->get();
    return $resultado;
    }

    public function getProducto_x_distribuidor(Request $request){
        $id_distribuidor=$request->id_distribuidor;
    // PRIMARIO
$primario = DB::table('prod__productos as pp')
    ->select([
        'pp.id as id_prod',
        'pl.id as id_linea',
        'pl.nombre as linea_nombre',
        'pp.codigoProducto',
        'pp.nombre as nom_prod',
        'dc.nom_a_facturar as distribuidor_nom',
        'pp.iddispenserprimario as id_dispenser',
        'pp.cantidadprimario as cantidad',
        'pp.tiempopedidoprimario as tiempo_pedido',
        'dis.nombre as dis_nom',
        'for_a.nombre as nom_for_farmaceutica',
        'pp.preciolistaprimario as preciolista',
        'pp.precioventaprimario as precioventa',
        DB::raw("'PRIMARIA' as tipo")
    ])
    ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
    ->join('dir__distribuidors as dd', function($join) {
        $join->whereRaw('FIND_IN_SET(pl.id, dd.id_linea_array)');
    })
    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
    ->join('prod__dispensers as dis', 'dis.id', '=', 'pp.iddispenserprimario')
    ->join('prod__forma_farmaceuticas as for_a', 'for_a.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('pp.activo', 1)
    ->where('pp.iddispenserprimario', '>', 0)
    ->where('dd.id', $id_distribuidor);

// SECUNDARIO
$secundario = DB::table('prod__productos as pp')
    ->select([
        'pp.id as id_prod',
        'pl.id as id_linea',
        'pl.nombre as linea_nombre',
        'pp.codigoProducto',
        'pp.nombre as nom_prod',
        'dc.nom_a_facturar as distribuidor_nom',
        'pp.iddispensersecundario as id_dispenser',
        'pp.cantidadsecundario as cantidad',
        'pp.tiempopedidosecundario as tiempo_pedido',
        'dis.nombre as dis_nom',
        'for_a.nombre as nom_for_farmaceutica',
        'pp.preciolistasecundario as preciolista',
        'pp.precioventasecundario as precioventa',
        
        DB::raw("'SECUNDARIO' as tipo")
    ])
    ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
    ->join('dir__distribuidors as dd', function($join) {
        $join->whereRaw('FIND_IN_SET(pl.id, dd.id_linea_array)');
    })
    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
    ->join('prod__dispensers as dis', 'dis.id', '=', 'pp.iddispensersecundario')
    ->join('prod__forma_farmaceuticas as for_a', 'for_a.id', '=', 'pp.idformafarmaceuticasecundario')
    ->where('pp.activo', 1)
    ->where('pp.iddispensersecundario', '>', 0)
    ->where('dd.id', $id_distribuidor);

// TERCIARIO
$terciario = DB::table('prod__productos as pp')
    ->select([
        'pp.id as id_prod',
        'pl.id as id_linea',
        'pl.nombre as linea_nombre',
        'pp.codigoProducto',
        'pp.nombre as nom_prod',
        'dc.nom_a_facturar as distribuidor_nom',
        'pp.iddispenserterciario as id_dispenser',
        'pp.cantidadterciario as cantidad',
        'pp.tiempopedidoterciario as tiempo_pedido',
        'dis.nombre as dis_nom',
        'for_a.nombre as nom_for_farmaceutica',
        'pp.preciolistaterciario as preciolista',
        'pp.precioventaterciario as precioventa',
        DB::raw("'TERCIARIO' as tipo")
    ])
    ->join('prod__lineas as pl', 'pp.idlinea', '=', 'pl.id')
    ->join('dir__distribuidors as dd', function($join) {
        $join->whereRaw('FIND_IN_SET(pl.id, dd.id_linea_array)');
    })
    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
    ->join('prod__dispensers as dis', 'dis.id', '=', 'pp.iddispenserterciario')
    ->join('prod__forma_farmaceuticas as for_a', 'for_a.id', '=', 'pp.idformafarmaceuticaterciario')
    ->where('pp.activo', 1)
    ->where('pp.iddispenserterciario', '>', 0)
    ->where('dd.id', $id_distribuidor);

// UNION ALL de las 3 consultas
$resultado = $primario->unionAll($secundario)->unionAll($terciario)->get();
return $resultado;
    
    }

    public function register_modal(Request $request){
        try {

       

         DB::beginTransaction();
         $arrayInferior_falso=$request->arrayInferior_falso;
         $arrayModalSuperiror_naranja=$request->arrayModalSuperiror_naranja;
         if(count($arrayInferior_falso)<=0&&count($arrayModalSuperiror_naranja)<=0){
            return 0;
         }
         
            $id_user=auth()->user()->id;
            $id_sucursal=$request->id_sucursal;
            $id_distribuidor=$request->id_distribuidor;
            $subTotal_modal_superior=floatval($request->subTotal_modal_superior);
            $sumatoriaBot=floatval($request->sumatoriaBot);
            $total_programacion=$subTotal_modal_superior+$sumatoriaBot;
            $fecha_pago=$request->fechaPago;
            $forma_pago=$request->selectFormaPago;
            $turno_pedido=$request->turno_pedido;
            $plazo_pedido=$request->plazoPago;
            $observacion=$request->observacion;
            $tipo=$request->tipo;
            $simbolo=$request->simbolo;

            $nuevoItem = new Inv_GestionStock();
            $nuevoItem->id_sucursal=$id_sucursal;
            $nuevoItem->id_usuario=$id_user;
            $nuevoItem->id_distribuidor=$id_distribuidor;
            $nuevoItem->total_programacion=$total_programacion;   
            $nuevoItem->fecha_pago=$fecha_pago;    
            $nuevoItem->forma_pago=$forma_pago; 
            $nuevoItem->turno_pedido=$turno_pedido;              
            $nuevoItem->plazo_pedido=$plazo_pedido;  
            $nuevoItem->observacion=$observacion;  
            $nuevoItem->tipo=$tipo; 
            $nuevoItem->simbolo=$simbolo;
            $nuevoItem->save();

               // Obtener el ID recién creado
             $id_nuevoItem = $nuevoItem->id;
      
             if(count($arrayModalSuperiror_naranja)>0){
            
            $bloque_superior = $request->arrayModalSuperiror_naranja;
           foreach ($bloque_superior as $item) {
           
            $lineas = $item['linea'];
            $id_producto = $item['id_producto'];
            $envase=$item['envase'];
            $ciclo_pedido = $item['ciclo'];
            $consumo_pedido = $item['consumo_mensual'];
            $plazo_medio = $item['plazo'];
            $consumo_dia_pedido = $item['consumo_dia'];
            $maximo_pedido = $item['stmax'];
            $actual_pedido = $item['stock_total'];
            $stock_pedido =$item['stpedido'];
            $cantidad_pedido =$item['dispedido'];
            $precio_pedido =$item['subtotal'];            

            $datos = [
                'id_gestion_stock' => $id_nuevoItem,
                'lineas' => $lineas,
                'id_producto' => $id_producto,
                'envase' => $envase,
                'ciclo_pedido' => $ciclo_pedido,
                'consumo_pedido' => $consumo_pedido,
                'plazo_medio' => $plazo_medio,
                'consumo_dia_pedido' => $consumo_dia_pedido,
                'maximo_pedido' => $maximo_pedido,
                'actual_pedido' => $actual_pedido,
                'stock_pedido' => $stock_pedido,
                'cantidad_pedido' => $cantidad_pedido,
                'precio_pedido' => $precio_pedido,
            ];
        
            DB::table('inv__pedido_gestion_stock')->insert($datos);
            }          
        } 
        
        if(count($arrayInferior_falso)>0){
             $bloque_inferior = $request->arrayInferior_falso;
              foreach ($bloque_inferior as $item) {
                $id_producto = $item['id_prod'];
                $envase = $item['tipo'];
                $cantidad_extra = $item['cantidadFalsa'];
                $precio_extra = $item['subTotal_falso'];

                $datos = [
                'id_gestion_stock' => $id_nuevoItem,
                'id_producto' => $id_producto, 
                'envase' => $envase,  
                'cantidad_extra' => $cantidad_extra,  
                'precio_extra' => $precio_extra,                
            ];
        
            DB::table('inv__tabla_extra_gestion_stock')->insert($datos);

              }
        }

         DB::commit(); 
        }
        catch (\Throwable $th) {
         return $th;
        }
    }

    /// llisato para stock cero 
public function saldocero($id_sucursal){
  
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
        'pp.codigo',
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
        'tip.envase as envase',
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
        'pp.codigo',
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
        'aip.envase as envase',
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
        'sub.codigo',
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
        'sub.codigo',
        'sub.tipo'
    )
    ->get();

    return $resultado;        

    }

    public function fechascero($id_producto,$id_sucursal) {
        $fecha = DB::table('sis_bitacora_stock as s')
    ->selectRaw('MAX(s.fecha_ingreso) as fecha')
    ->where('s.id_producto', $id_producto)
    ->where('s.stock', '<>', 0)
    ->where('s.id_sucursal', $id_sucursal)
    ->get();
        return $fecha;
    }

   public function diascero($id_producto, $id_sucursal, $fecha_inicial)
{
    $dias = DB::table('sis_bitacora_stock as s')
        ->where('s.id_producto', $id_producto)
        ->where('s.id_sucursal', $id_sucursal)
        ->whereBetween('s.fecha_ingreso', [
            DB::raw("DATE_ADD('$fecha_inicial', INTERVAL 1 DAY)"),
            DB::raw("CURRENT_DATE()")
        ])
        ->select(DB::raw('COUNT(s.stock) as dias'))
        ->get();

    return $dias;
}

public function prospecto($id_producto, $id_sucursal, $fecha_inicial){
    $perdido = DB::table('ven_prospectos as v')
    ->where('v.id_producto', $id_producto)
    ->where('v.id_sucursal', $id_sucursal)
    ->whereBetween('v.created_at', [
        DB::raw("DATE_ADD('$fecha_inicial', INTERVAL 1 DAY)"),
        DB::raw("CURRENT_DATE()")
    ])
    ->select(DB::raw('COUNT(v.id_producto) as perdido'))
    ->get();

return $perdido;
}

public function get_modal_saldo_cero(Request $request){
    $id_sucursal=$request->id_sucursal;
    $limitador=intval($request->limitador);
    $limter_=0;
    switch ($limitador) {
        case 1:
            $limter_=50;
        break;
        case 2:
            $limter_=100;
        break;
        case 3:
            $limter_=200;
        break;
        case 4:
            $limter_=300;
        break;
        default:
            $limter_=10;
        break;
    }
 
    $rspta = $this->saldocero($id_sucursal);
     //declaramos un array
        $data = Array();
        foreach ($rspta as $key => $value) {
           $id_producto = $value->id_producto;
           $codigo=$value->codigo;
           $envase=$value->envase;
            $stock = intval($value->stock_total);
            $linea = $value->nombre_linea;
           
            if(is_numeric($value->cantidad_dispenser_producto)){
                $producto = $value->nombre_producto." - ".$value->nombre_dis." X ".$value->cantidad_dispenser_producto." ".$value->nombre_forma_farmaceutica;
            }else{
                $producto = $value->nombre_producto." - ".$value->nombre_dis." ".$value->cantidad_dispenser_producto." ".$value->nombre_forma_farmaceutica;
            }
             if($stock == 0){
                $rspta1 = $this->fechascero($id_producto,$id_sucursal);  
                         
                foreach ($rspta1 as $key_2 => $value_2) {            
                    $fecha_inicial = $value_2->fecha;
                     $rspta2 = $this->diascero($id_producto, $id_sucursal, $fecha_inicial); 
                     foreach ($rspta2 as $key_3 => $value_3) {
                        $dias = $value_3->dias;
                        $rspta3 = $this->prospecto($id_producto, $id_sucursal, $fecha_inicial);
                            foreach ($rspta3 as $key_4 => $value_4) {
                            $perdido = $value_4->perdido;
                            //RESULTADO
                            $data[] = array(
                                "linea"=>$linea,
                                "codigo"=>$codigo,
                                "envase"=>$envase,
                                "producto"=>$producto,
                                "stock"=>$stock,
                                "dias"=>$dias,
                                "perdido"=>$perdido
                            );
                            }                       
                     }
                     
                     
                }
               
            }
        }
        if($limitador==5){
            return $data;
        }else{
        return array_slice($data, 0, $limter_);
        }      
}

}
