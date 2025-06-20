<?php

namespace App\Http\Controllers;

use App\Models\Inv_GestionStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getGestorStockModal(Request $request){

        $arrayMostrar=[];
        $getStock=$this->get_totalventa();
        if (count($getStock)>0) {
            foreach ($getStock as $key => $value) {
                $id_producto = $value->id_producto;
                $total_venta = $value->total_venta_cantidad;
                //OBTENEMOS DATOS DEL METODO LISTARCONTROL
                 $rspta1 = $this->listarControl($id_producto);                
                 foreach ($rspta1 as $key => $value_2) {
                   $linea = $value_2->nombre_linea;
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
                        $color = "";
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
                        if ($stpedido < 0) {
                            $stpedido = 0;
                        }
                            //LISTADO FINAL
                            $arrayMostrar[] = [
                            'id_producto' => $id_producto,
                            'linea' => $linea,
                            'producto' => $producto,
                            'ciclo' => $ciclo,
                            'consumo_mensual' => $consumo_mensual,
                            'plazo' => $plazo,
                            'consumo_dia' => $consumo_dia,
                            'stmax' => $stmax,
                            'stmedio' => $stmedio,
                            'stock_total' => $stock_total,
                            'indicerot' => $indicerot,
                            'indicecober' => $indicecober,
                            'rentabilidad' => $rentabilidad,   
                            'color'=> $color                      
                            ];  
                    }
                 }
            
            }
            return $arrayMostrar;
        }else{
            return $arrayMostrar;
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
    
    private function get_totalventa(){
        $query = DB::table('ven__recibos as vr')
    ->join('ven__detalle_ventas as vdv', 'vdv.id_venta', '=', 'vr.id')
    ->join('tda__ingreso_productos as tip', 'vdv.id_ingreso', '=', 'tip.id')
    ->join('prod__productos as pp', 'tip.id_prod_producto', '=', 'pp.id')
    ->select(
        'vdv.id_producto',
        DB::raw('COALESCE(SUM(vdv.cantidad_venta), 0) as total_venta_cantidad'),
        DB::raw('COALESCE(COUNT(vdv.id_ingreso), 0) as cantidad_ingresada'),
        'pp.activo'
    )
    ->where('vr.id_sucursal', 1)
    ->where('pp.activo', 1)
    ->whereDate('vr.created_at', '<=', DB::raw('CURDATE()'))
    ->whereRaw("
        vr.created_at >= DATE_SUB(CURDATE(), INTERVAL 
            CASE 
                WHEN tip.envase = 'primario' THEN COALESCE(pp.tiempopedidoprimario, 0)
                WHEN tip.envase = 'secundario' THEN COALESCE(pp.tiempopedidosecundario, 0)
                WHEN tip.envase = 'terciario' THEN COALESCE(pp.tiempopedidoterciario, 0)
                ELSE 0
            END MONTH
        )
    ")
    ->groupBy('vdv.id_producto', 'pp.activo')
    ->orderByDesc('total_venta_cantidad')
    ->limit(50000)
    ->get();
    return $query;
    }

    private function  listarControl($id_producto){


$producto = DB::table('prod__productos as pp')
    ->join('tda__ingreso_productos as tip', 'tip.id_prod_producto', '=', 'pp.id')
    ->join('ges_pre__venta2s as gpv2', 'gpv2.id_table_ingreso_tienda_almacen', '=', 'tip.id')
    ->join('prod__dispensers as pd', function ($join) {
        $join->on(DB::raw('pd.id'), '=', DB::raw("CASE 
            WHEN tip.envase = 'primario' THEN pp.iddispenserprimario
            WHEN tip.envase = 'secundario' THEN pp.iddispensersecundario
            WHEN tip.envase = 'terciario' THEN pp.iddispenserterciario
            ELSE NULL 
        END"));
    })
    ->join('prod__forma_farmaceuticas as pff', function ($join) {
        $join->on(DB::raw('pff.id'), '=', DB::raw("CASE 
            WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
            WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
            WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
            ELSE NULL 
        END"));
    })
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->select(
        'pp.id',
        DB::raw('MAX(pl.nombre) as nombre_linea'),
        DB::raw('MAX(pl.tiempo_demora) as tiempo_demora'),
        DB::raw('MAX(pp.nombre) as nombre_producto'),

        DB::raw("MAX(CASE 
            WHEN tip.envase = 'primario' THEN pp.tiempopedidoprimario
            WHEN tip.envase = 'secundario' THEN pp.tiempopedidosecundario
            WHEN tip.envase = 'terciario' THEN pp.tiempopedidoterciario
            ELSE NULL 
        END) as tiempo_producto"),

        DB::raw('MAX(pd.nombre) as nombre_dis'),

        DB::raw("MAX(CASE 
            WHEN tip.envase = 'primario' THEN pp.cantidadprimario
            WHEN tip.envase = 'secundario' THEN pp.cantidadsecundario
            WHEN tip.envase = 'terciario' THEN pp.cantidadterciario
            ELSE NULL 
        END) as cantidad_dispenser_producto"),

        DB::raw('MAX(pff.nombre) as nombre_forma_farmaceutica'),

        DB::raw("MAX(CASE 
            WHEN tip.envase = 'primario' THEN pp.preciolistaprimario
            WHEN tip.envase = 'secundario' THEN pp.preciolistasecundario
            WHEN tip.envase = 'terciario' THEN pp.preciolistaterciario
            ELSE NULL 
        END) as precio_lista_producto"),

        DB::raw('SUM(tip.stock_ingreso) as stock_total'),
        DB::raw('AVG(gpv2.utilidad_neto_gespreventa) as utilidad_neta'),
        DB::raw('AVG(gpv2.costo_compra_gespreventa) as precio_unitario')
    )
    ->where('gpv2.tienda', 1)
    ->where('gpv2.almacen', 0)
    ->where('pp.id', $id_producto)
    ->groupBy('pp.id')
    ->get();

return $producto;

    }

    private function promediostock($id_producto){

        $promedioStock = DB::table('sis_bitacora_stock as s')
    ->select('s.id_producto', DB::raw('IFNULL(AVG(s.stock), 0) as promedioStock'))
    ->where('s.id_producto', $id_producto)
    ->groupBy('s.id_producto')
    ->limit(50000)
    ->get();
    return $promedioStock;
    }

}
