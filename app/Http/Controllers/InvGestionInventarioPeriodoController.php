<?php

namespace App\Http\Controllers;

use App\Models\Inv_gestionInventarioPeriodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvGestionInventarioPeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
 //   return $request->all();
 $tipo_inventario = $request->tipo_inventario;
  $ini=$request->ini;
        $fini=$request->fini;
 $id_tienda=$request->id_tienda;
$id_almacen=$request->id_almacen;
$id_sucursal=(int)$request->id_sucursal;
if ($id_tienda != null && $id_almacen == null) {
    $id_tienda =(int)$id_tienda;
   $sqls1 = "( i.id_tienda = $id_tienda )"; 
} else {
    $id_almacen =(int)$id_almacen;
   $sqls1 = "( i.id_almacen = $id_almacen )"; 
}

if ($request->estadoSelect=="N") {
    $sqls2 = "(i.estado = 'ACEPTADO')"; 
}else{
    $sqls2 = "(i.estado = 'ELIMINADO')";
}
if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                pl.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%' 
                                or i.turno like '%" . $valor . "%'
                               )";
                    } else {
                        $sqls .= "and (pl.nombre like '%" . $valor . "%' 
                        or u.name like '%" . $valor . "%' 
                        or i.turno like '%" . $valor . "%'
                       )";
                    }
                }
                $resultados = DB::table('inv_gestion_inventario_periodos as i')
    ->select(
        'i.id',
        'pl.nombre as nom_linea',
        'i.estado',
        DB::raw("
            CASE 
                WHEN i.tipo_inventario = 'D' THEN 'Diario'
                WHEN i.tipo_inventario = 'S' THEN 'Semanal'
                WHEN i.tipo_inventario = 'M' THEN 'Mensual'
                WHEN i.tipo_inventario = 'T' THEN 'Trimestral'
                ELSE 'Sin datos'
            END as tipo_inventario
        "),
        'i.updated_at as fecha_creacion',
        'u.name','i.turno'
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'i.id_linea')
    ->join('users as u', 'u.id', '=', 'i.id_usuario')    
    ->where('i.id_sucursal', $id_sucursal)
    ->whereRaw($sqls1)
    ->whereRaw($sqls2)
    ->whereRaw($sqls)
    ->where('i.tipo_inventario', $tipo_inventario)
    //->whereBetween(DB::raw('DATE(i.updated_at)'), [$ini, $fini])
    ->orderBy('i.id', 'desc')
    ->paginate(15);
                }
                return
            [
                'pagination' =>
                [
                    'total'         =>    $resultados->total(),
                    'current_page'  =>    $resultados->currentPage(),
                    'per_page'      =>    $resultados->perPage(),
                    'last_page'     =>    $resultados->lastPage(),
                    'from'          =>    $resultados->firstItem(),
                    'to'            =>    $resultados->lastItem(),
                ],
                'resultados' => $resultados,
            ];
            }  else{
                $resultados = DB::table('inv_gestion_inventario_periodos as i')
    ->select(
        'i.id',
        'pl.nombre as nom_linea',
        'i.estado',
        DB::raw("
            CASE 
                WHEN i.tipo_inventario = 'D' THEN 'Diario'
                WHEN i.tipo_inventario = 'S' THEN 'Semanal'
                WHEN i.tipo_inventario = 'M' THEN 'Mensual'
                WHEN i.tipo_inventario = 'T' THEN 'Trimestral'
                ELSE 'Sin datos'
            END as tipo_inventario
        "),
        'i.updated_at as fecha_creacion',
        'u.name','i.turno'
    )
    ->join('prod__lineas as pl', 'pl.id', '=', 'i.id_linea')
    ->join('users as u', 'u.id', '=', 'i.id_usuario')    
    ->where('i.id_sucursal', $id_sucursal)
    ->whereRaw($sqls1)
    ->whereRaw($sqls2)
    ->where('i.tipo_inventario', $tipo_inventario)
     ->whereBetween(DB::raw('DATE(i.updated_at)'), [$ini, $fini])
    ->orderBy('i.id', 'desc')
    ->paginate(15);

    return
            [
                'pagination' =>
                [
                    'total'         =>    $resultados->total(),
                    'current_page'  =>    $resultados->currentPage(),
                    'per_page'      =>    $resultados->perPage(),
                    'last_page'     =>    $resultados->lastPage(),
                    'from'          =>    $resultados->firstItem(),
                    'to'            =>    $resultados->lastItem(),
                ],
                'resultados' => $resultados,
            ];
            }  
    }  

    public function getProducto(Request $request){
        // Consulta 1 - Primario
        $id_linea=(int)$request->id_linea;
        $id_tienda=(int)$request->id_tienda;
        $id_almacen=(int)$request->id_almacen;

        if ($id_tienda!=null&&$id_almacen==null) {
          
            $primario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserprimario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('tip.envase', 'primario')
    ->where('pp.idlinea', $id_linea)
    ->where('tip.idtienda', $id_tienda)
    ->where('tip.stock_ingreso', '>',0)
     ->selectRaw('
        MIN(tip.id) AS id,
        MIN(tip.id_prod_producto) as id_prod_producto,
        tip.envase,
        SUM(tip.stock_ingreso) AS stock_ingreso,
        tip.fecha_vencimiento,
        tip.lote,
        tip.registro_sanitario,
        MIN(tip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );

// Consulta 2 - Secundario
$secundario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispensersecundario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticasecundario')
    ->where('tip.envase', 'secundario')
    ->where('pp.idlinea', $id_linea)
    ->where('tip.idtienda', $id_tienda)
    ->where('tip.stock_ingreso','>',0)
    ->selectRaw('
        MIN(tip.id) AS id,
        MIN(tip.id_prod_producto) as id_prod_producto,
        tip.envase,
        SUM(tip.stock_ingreso) AS stock_ingreso,
        tip.fecha_vencimiento,
        tip.lote,
        tip.registro_sanitario,
        MIN(tip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );

// Consulta 3 - Terciario
$terciario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserterciario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaterciario')
    ->where('tip.envase', 'terciario')
    ->where('pp.idlinea', $id_linea)
    ->where('tip.idtienda', $id_tienda)
    ->where('tip.stock_ingreso', '>',0)
    ->selectRaw('
        MIN(tip.id) AS id,
        MIN(tip.id_prod_producto) as id_prod_producto,
        tip.envase,
        SUM(tip.stock_ingreso) AS stock_ingreso,
        tip.fecha_vencimiento,
        tip.lote,
        tip.registro_sanitario,
        MIN(tip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );

// UNION ALL
$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->get();
  
    return $resultado; 
        }else{
            // --------------------
// PRIMARIO
// --------------------
$primario = DB::table('alm__ingreso_producto as aip')
    ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserprimario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('aip.envase', 'primario')
    ->where('pp.idlinea', $id_linea)
    ->where('aip.idalmacen', $id_almacen)
    ->selectRaw('
        MIN(aip.id) AS id,
        MIN(aip.id_prod_producto) as id_prod_producto,
        aip.envase,
        SUM(aip.stock_ingreso) AS stock_ingreso,
        aip.fecha_vencimiento,
        aip.lote,
        aip.registro_sanitario,
        MIN(aip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );
// --------------------
// SECUNDARIO
// --------------------
$secundario = DB::table('alm__ingreso_producto as aip')
    ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispensersecundario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticasecundario')
    ->where('aip.envase', 'secundario')
    ->where('pp.idlinea', $id_linea)
    ->where('aip.idalmacen', $id_almacen)
    ->where('aip.stock_ingreso', '>',0)
 ->selectRaw('
        MIN(aip.id) AS id,
        MIN(aip.id_prod_producto) as id_prod_producto,
        aip.envase,
        SUM(aip.stock_ingreso) AS stock_ingreso,
        aip.fecha_vencimiento,
        aip.lote,
        aip.registro_sanitario,
        MIN(aip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );

// --------------------
// TERCIARIO
// --------------------
$terciario = DB::table('alm__ingreso_producto as aip')
    ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserterciario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaterciario')
    ->where('aip.envase', 'terciario')
  ->where('pp.idlinea', $id_linea)
    ->where('aip.idalmacen', $id_almacen)
    ->where('aip.stock_ingreso', '>',0)
     ->selectRaw('
        MIN(aip.id) AS id,
        MIN(aip.id_prod_producto) as id_prod_producto,
        aip.envase,
        SUM(aip.stock_ingreso) AS stock_ingreso,
        aip.fecha_vencimiento,
        aip.lote,
        aip.registro_sanitario,
        MIN(aip.created_at) AS fecha_ingreso,
        pp.nombre AS nom_producto,
        pp.cantidadprimario AS cantidad_d,
        pd.nombre AS nom_dis,
        pff.nombre AS nom_forma_faraceutica
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre'
    );

// --------------------
// UNION ALL
// --------------------
$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->get();
    return $resultado;
        }



    }

    public function store(Request $request)
    {
        //1= Diario , 7=Semanal, 30=Mensual, 90=Trimestral
        try {
            DB::beginTransaction();
            //return $request->all();
            $id_tienda=(int)$request->id_tienda;
            $id_almacen=(int)$request->id_almacen;
            $id_linea=(int)$request->id_lineas;           
            if ($id_tienda!=null||$id_almacen==null) {
                $id_almacen=0;
            }else{
                $id_tienda=0;
            }
            $nuevo = new Inv_gestionInventarioPeriodo();
            $nuevo->id_sucursal=$request->id_sucursal;
            $nuevo->id_tienda=$id_tienda;
            $nuevo->id_almacen=$id_almacen;
            $nuevo->id_linea=$id_linea;
            $nuevo->estado="ACEPTADO";
            $nuevo->tipo_inventario=$request->tipo_inventario; 
            $nuevo->turno=$request->turno;
            $nuevo->id_usuario=auth()->user()->id;
            $nuevo->save();
            $id_nuevo=$nuevo->id;
            $arreglo=$request->array;
            foreach ($arreglo as $key => $value) {
                $cantidad_sis_detalle_inventario=$value['stock_ingreso'];                
                $cantidad_reg_detalle_inventario=$value['cantidad'];
                $diferencia = $cantidad_sis_detalle_inventario - $cantidad_reg_detalle_inventario;
                if ($diferencia == 0) {
                    $estado = "CORRECTO";
                }else{
                    if ($diferencia > 0) {
                        $estado = "FALTANTE";
                    }else{
                        $estado = "SOBRANTE";
                    }
                }

               $datos = [                         
                'id_inv_grestion_inventario' => $id_nuevo,
                'id_ingreso' => $value['id_ingreso'], 
                'id_producto' => $value['id_prod_producto'],   
                'envase' => $value['envase'], 
                'cantidad_sis_detalle_inventario' => $cantidad_sis_detalle_inventario, 
                'cantidad_reg_detalle_inventario' => $cantidad_reg_detalle_inventario, 
                'diferencia_detalle_inventario' => $diferencia, 
                'estado' => $estado, 
                'lote' =>  $value['lote'],
                'fecha_v' =>  $value['fecha_vencimiento'],              
            ];
                DB::table('inv_grestion_inventario_detalle')->insert($datos);
            }           

            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getLinea(){
        $lineas = DB::table('prod__lineas')
    ->select('id', 'nombre')
    ->where('activo', 1)
    ->get();

return $lineas;

    }

    public function changeEstado(Request $request){
        $dato=$request->dato;
        if ($dato==1) {
            $estado="ACEPTADO"; 
        } else {
            $estado="ELIMINADO";
        }
        
         $update = Inv_gestionInventarioPeriodo::findOrFail($request->id);
         $update->estado=$estado;
        $update->id_usuario=auth()->user()->id;
        $update->save();
    }
    
    public function getProductoDetalle(Request $request){
        $id=$request->id;
        $query1 = DB::table('inv_grestion_inventario_detalle as i')
    ->select(
        'i.envase',
        'i.cantidad_sis_detalle_inventario',
        'i.cantidad_reg_detalle_inventario',
        'i.diferencia_detalle_inventario',
        'i.estado',
        'i.fecha_v',
        'i.lote',
        'pp.nombre as nom_producto',
        'pp.cantidadprimario as cantidad_d',
        'pd.nombre as nom_dis',
        'pff.nombre as nom_forma_faraceutica'
    )
    ->join('prod__productos as pp', 'i.id_producto', '=', 'pp.id')
    ->join('prod__dispensers as pd', 'pp.iddispenserprimario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('i.envase', 'primario')
    ->where('i.id_inv_grestion_inventario', $id);

$query2 = clone $query1;
$query2->where('i.envase', 'secundario');

$query3 = clone $query1;
$query3->where('i.envase', 'terciario');

$inventario = $query1
    ->unionAll($query2)
    ->unionAll($query3)
    ->get();
    return $inventario;
    }
  
}
