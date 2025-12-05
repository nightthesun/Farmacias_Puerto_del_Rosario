<?php
namespace App\HelperServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class gestionStock{
     public static function getGestorStockModal($id_sucursal,$tipo){
        $arrayMostrar=[];
        $arrayLinea=[];
        $importe_total =0;          
       
        $getStock = self::get_totalventa($id_sucursal,$tipo);

        if (count($getStock)>0) {
            foreach ($getStock as $key => $value) {
                $id_producto = $value->id_producto;
                $total_venta = $value->total_venta_cantidad;
                $envase = $value->envase;   
                //OBTENEMOS DATOS DEL METODO LISTARCONTROL
               
                  $rspta1 = self::listarControl($id_producto,$id_sucursal,$envase,$tipo);
                 if (count($rspta1)>0) {
                    foreach ($rspta1 as $key => $value_2) {
                   $linea = $value_2->nombre_linea;
                   $id_linea=$value_2->id_linea;
                  
                if(is_numeric($value_2->cantidad_dispenser_producto)){
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_forma_farmaceutica." X ".$value_2->nombre_dis." ".$value_2->nombre_forma_farmaceutica;
                    $dis = $value_2->cantidad_dispenser_producto;
                }else{
                    $producto = $value_2->nombre_producto." - ".$value_2->nombre_forma_farmaceutica." ".$value_2->nombre_dis." ".$value_2->nombre_forma_farmaceutica;
                    $dis=1;
                }
                $precio_lista = $value_2->precio_lista_producto;
                $stock_total = $value_2->stock_total;
                $utilidad_neta = $value_2->utilidad_neta;
                $ciclo = $value_2->tiempo_producto;
                $plazo = $value_2->tiempo_demora;                  
                

                 //OBTENEMOS EL STOCK PROMEDIO
                 $rspta2 = self::promediostock($id_producto,$envase,$id_sucursal,$tipo);                
    
                    foreach ($rspta2 as $key => $value_3) {
                         $promstock = $value_3->promedioStock;
                             if($promstock>0){
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
                                $arrayLinea[] = ['id_linea'=> $id_linea,'color'=>'amarillo','linea'=>$linea];
                            }else{
                                if($stock_total <= $alerta && $stock_total > $minimo){
                                    $color = 2;
                                $arrayLinea[] = ['id_linea'=> $id_linea,'color'=>'naranja','linea'=>$linea];
                                }
                            }
                        }
                        if ($stpedido < 0) {
                            $stpedido = 0;
                        }
                            //LISTADO FINAL
                           //LISTADO FINAL
                            $dispedido=round($stpedido / $dis);
                            $subtotal=$precio_lista * $dispedido;
                            $importe_total = $importe_total + $subtotal;
                            if ($color>=0&&$color<=2) {

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
                            'color'=> $color,
                          
                            'dispedido'=>round($dispedido),
                            'precio_lista'=>$precio_lista,  
                            'subtotal'=>$subtotal,
                            'envase'=>$envase,
                            'importe_total'=>$importe_total,
                            'id_linea'=>$id_linea,
                                           
                            ];  
                            }                           
                            }                        
                    }
                 }
                 }                  
            } 
         
             return response()->json([
                'arrayMostrar' => $arrayMostrar,
                'arrayLinea' => $arrayLinea      
            ]);
        }   else{
             return response()->json([
                'arrayMostrar' => $arrayMostrar,
                'arrayLinea' => $arrayLinea      
            ]);
        }        

    } 

/******************************************************/    
private static function get_totalventa($id_sucursal,$tipo){ 
      if ($tipo==1||$id_sucursal==0) {    
        $where = "(pp.activo=1)";  
      }else {
        $where = "(vr.id_sucursal='$id_sucursal' and pp.activo=1)";
      }
    $hoy = Carbon::now()->toDateString();
// Subconsulta: venta_tienda
$ventaTienda = DB::table('ven__recibos as vr')
    ->join('ven__detalle_ventas as vdv', 'vdv.id_venta', '=', 'vr.id')
    ->join('tda__ingreso_productos as tip', 'vdv.id_ingreso', '=', 'tip.id')
    ->join('prod__productos as pp', 'tip.id_prod_producto', '=', 'pp.id')
    ->select(
        'vdv.id_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) AS total_venta_cantidad'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) AS cantidad_ingresada'),'tip.envase as envase'
    )    
    ->whereRaw('DATE(vr.created_at) <= CURDATE()')
    ->whereRaw($where)    
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
   ->groupBy('vdv.id_producto', 'tip.envase');
// Subconsulta: venta_almacen
$ventaAlmacen = DB::table('ven__recibos as vr')
    ->join('ven__detalle_ventas as vdv', 'vdv.id_venta', '=', 'vr.id')
    ->join('alm__ingreso_producto as aip', 'vdv.id_ingreso', '=', 'aip.id')
    ->join('prod__productos as pp', 'aip.id_prod_producto', '=', 'pp.id')
    ->select(
        'vdv.id_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) AS total_venta_cantidad'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) AS cantidad_ingresada'),'aip.envase as envase'
    )
    ->whereRaw('DATE(vr.created_at) <= CURDATE()')
   ->whereRaw($where)    
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
   ->groupBy('vdv.id_producto', 'aip.envase');
// Unión de ambas subconsultas
$ventasCombinadas = $ventaTienda
    ->unionAll($ventaAlmacen);
// Consulta final con agrupación y suma
$resultado = DB::table(DB::raw("({$ventasCombinadas->toSql()}) as ventas_combinadas"))
    ->mergeBindings($ventasCombinadas) // importante para que los bindings funcionen
     ->select('id_producto','envase',
        DB::raw('SUM(total_venta_cantidad) AS total_venta_cantidad'),
        DB::raw('SUM(cantidad_ingresada) AS cantidad_ingresada')
    )
    ->groupBy('id_producto', 'envase')
    ->orderBy('id_producto')
    ->get();
        return $resultado;
    }
/******************************************************/   

private static function  listarControl($id_producto,$id_sucursal,$envase,$tipo){
    if ($tipo==1||$id_sucursal==0) {    
        $where = "(pp.id = '$id_producto')";  
      }else {
        $where = "(ass.id='$id_sucursal' and pp.id = '$id_producto')";       
      }
// tip.envase='$envase'
// Subconsulta gettion_tienda stock_total
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
    ->whereRaw($where)
     
    ->where('tip.envase', $envase);
        

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
    ->whereRaw($where)

     ->where('aip.envase', $envase);
    

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
/******************************************************/   
 private static function promediostock($id_producto,$envase,$id_sucursal,$tipo){

            if ($tipo==1||$id_sucursal==0) {    
                $where = "(s.id_producto = '$id_producto')";  
            }else {
                $where = "(s.id_sucursal='$id_sucursal' and s.id_producto = '$id_producto')";       
            }
        $stockMedio = DB::table('adm__credecial_correos as a')
    ->select('a.stock_medio')->first(); 
    
        if ($stockMedio->stock_medio==0||$stockMedio->stock_medio==1) {
             $promedioStock = DB::table('sis_bitacora_stock as s')
            ->select('s.id_producto', DB::raw('IFNULL(AVG(s.stock), 0) as promedioStock'))           
            ->where('s.envase', $envase)   
            ->whereRaw($where)
            ->groupBy('s.id_producto')
            ->get();
        return $promedioStock;        
        }else{
            $promedioStock = DB::table('sis__bitacora_stock_v2 as s')
    ->select('s.id_producto', DB::raw('(s.suma / s.contador) as promedioStock'))    
    ->where('s.envase', $envase)
    ->whereRaw($where)
    ->get();
        return $promedioStock;            
        }      
    }
}
