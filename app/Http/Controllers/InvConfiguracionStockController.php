<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvConfiguracionStockController extends Controller
{
     
    public function get_tabla_accion_stock(){

        $id_user=auth()->user()->id;
        $name_user=auth()->user()->name;
        $fechaHoy = Carbon::now()->format('Y-m-d');
              
              if ($id_user==1||$name_user=='admin') {
                $idsucursal=1;               
              }else{
                $idsucursal=session('idsuc');             
              }  

              $sucursalX=DB::table('adm__sucursals as s')
              ->select('s.id')
              ->where('s.activo', 1)
              ->where('s.id', $idsucursal)->get();
              
              if (count($sucursalX)<=0) {
                return 0;
              }else{
                     $fechaHoy = Carbon::now()->format('Y-m-d');
        $fechaMenos3 = Carbon::now()->subDays(3)->format('Y-m-d');  
 
    $data = DB::table('log__tabla_accion_stock as ltas')
    ->join('users as u', 'u.id', '=', 'ltas.id_user')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'ltas.id_sucursal')
    ->select(
        'u.name',
        'ass.razon_social',
        DB::raw("
            CASE
                WHEN ltas.tipo_tabla = 0 THEN 'POR DEFECTO'
                WHEN ltas.tipo_tabla = 1 THEN 'TABLA 1'
                WHEN ltas.tipo_tabla = 2 THEN 'TABLA 2'
                ELSE 'SIN RESULTADO'
            END AS tipo
        "),
        DB::raw("
            CASE
                WHEN ltas.accion = 0 THEN 'CIERRE DE CAJA'
                WHEN ltas.accion = 1 THEN 'CONFIGURACION MANUAL'
                WHEN ltas.accion = 3 THEN 'OTROS'
                ELSE 'SIN RESULTADO'
            END AS accion
        "),
        'ltas.fecha',
        'ltas.hora'
    )
    ->where('ltas.id_sucursal', $idsucursal)
    ->whereBetween('ltas.fecha', [$fechaMenos3, $fechaHoy])
    ->orderByDesc('ltas.id')
    ->get();
    return $data;
              }
    }

    public function storeBitacora_user_x_sucursal(Request $request){
        try {
              DB::beginTransaction();
              $fechaHoy = Carbon::now()->format('Y-m-d');
              $data_sucursal=$request->data_sucursal;
              $tipoTabla=$request->tipoTabla;
              if ($data_sucursal==0) {
                 $id_user=auth()->user()->id;
              $name_user=auth()->user()->name;
              
              $hora=Carbon::now()->format('H:i:s');
              if ($id_user==1||$name_user=='admin') {
                $idsucursal=1;
                $nomsucursal="usuario admin";
              }else{
                $idsucursal=session('idsuc');
                $nomsucursal=session('nomsucursal');
              }            
              // $table->tinyInteger('accion')->comment('1->modulo configuracion manual,2=otros 3....., 0=cierre de caja');
                $data_2=[
                    'id_user' => $id_user,
                    'id_sucursal' => $idsucursal,
                    'tipo_tabla' => $tipoTabla,
                    'fecha' => $fechaHoy,
                    'hora' => $hora,
                    'accion' =>1   
                ];
                 DB::table('log__tabla_accion_stock')->insert($data_2);  

                  $generarstocks=$this->generarstocks($idsucursal);
                //  return $generarstocks;
                    foreach ($generarstocks as $key => $value) {                  

            $datos_3=[
                'id_producto' => $value->id_producto,
                'stock' => $value->stock_total,
                'fecha_ingreso' => $fechaHoy, 
                'id_sucursal' => $idsucursal,
                'envase' => $value->envase,         
            ];
            
           DB::table('sis_bitacora_stock')->insert($datos_3);   
                    } 
              }else{
                $idsucursal=$data_sucursal;
                 $generarstocks=$this->generarstocks($idsucursal);
                    foreach ($generarstocks as $key => $value) {                  

            $datos_3=[
                'id_producto' => $value->id_producto,
                'stock' => $value->stock_total,
                'fecha_ingreso' => $fechaHoy, 
                'id_sucursal' => $idsucursal,
                'envase' => $value->envase,         
            ];
            
           DB::table('sis_bitacora_stock')->insert($datos_3);   
                    }    
              }
            
             
              DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }
    }    






    private function  generarstocks($id_sucursal){  
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
