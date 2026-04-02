<?php

namespace App\Http\Controllers;

use App\Models\inv_GestionInventarioPeriodo_uno;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvGestionInventarioPeriodoUnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    $sqls2 = "(i.estado = 1)"; 
}else{
    $sqls2 = "(i.estado = 0)";
}
if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                i.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%' 
                                or i.motivo like '%" . $valor . "%'
                               )";
                    } else {
                        $sqls .= "and (i.nombre like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%' 
                                or i.motivo like '%" . $valor . "%'
                       )";
                    }
                }
               $resultados = DB::table('inv__gestion_inventario_periodo_unos as i')
    ->select(
        'i.id',
        'i.nombre',
        'i.motivo',
        'i.estado',
        'i.enproceso',
        'i.id_tabla_dos_momentanio',
        'i.id_bloqueo',
        DB::raw("
            CASE 
                WHEN i.tipo_inventario = 'D' THEN 'Diario'
                WHEN i.tipo_inventario = 'G' THEN 'Global'
                ELSE 'Sin datos'
            END as tipo_inventario
        "),
        'i.updated_at as fecha_creacion',
        'u.name','ii.id as id_dos','ii.id_linea','ii.turno','ii.fecha_ini','l.nombre as nombre_linea',
       DB::raw("
            CASE 
                WHEN ii.turno = 1 THEN 'Mañana'
                WHEN ii.turno = 2 THEN 'Tarde'
                WHEN ii.turno = 3 THEN 'Completo'
                ELSE 'Error'
            END as tuno_nombre
        ")
    )    
    ->join('users as u', 'u.id', '=', 'i.id_usuario_registra')    
    ->leftJoin('inv__gestion_inventario_periodo_linea_dos as ii', 'ii.id', '=', 'i.id_tabla_dos_momentanio') 
    ->leftJoin('prod__lineas as l', 'l.id','=','ii.id_linea')   
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
}else{
      $resultados = DB::table('inv__gestion_inventario_periodo_unos as i')
    ->select(
        'i.id',
        'i.nombre',
        'i.motivo',
        'i.estado',
        'i.enproceso',
        'i.id_tabla_dos_momentanio',
        'i.id_bloqueo',
        DB::raw("
            CASE 
                WHEN i.tipo_inventario = 'D' THEN 'Diario'
                WHEN i.tipo_inventario = 'G' THEN 'Global'
                ELSE 'Sin datos'
            END as tipo_inventario
        "),
        'i.updated_at as fecha_creacion',
       'u.name','ii.id as id_dos','ii.id_linea','ii.turno','ii.fecha_ini','l.nombre as nombre_linea',
       DB::raw("
            CASE 
                WHEN ii.turno = 1 THEN 'Mañana'
                WHEN ii.turno = 2 THEN 'Tarde'
                WHEN ii.turno = 3 THEN 'Completo'
                ELSE 'Error'
            END as tuno_nombre
        ")
    )    
    ->join('users as u', 'u.id', '=', 'i.id_usuario_registra')   
     ->leftJoin('inv__gestion_inventario_periodo_linea_dos as ii', 'ii.id', '=', 'i.id_tabla_dos_momentanio')     
    ->leftJoin('prod__lineas as l', 'l.id','=','ii.id_linea') 
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

public function store_2(Request $request){
 //1= Diario , 7=Semanal, 30=Mensual, 90=Trimestral
        try {
            DB::beginTransaction();
            //return $request->all();
            $id_tienda=(int)$request->id_tienda;
            $id_almacen=(int)$request->id_almacen;
           // $id_linea=(int)$request->id_lineas;           tipo_inventario
           
            
            $nuevo = new inv_GestionInventarioPeriodo_uno();
            $nuevo->nombre=$request->nombre_0;  
             $nuevo->motivo=$request->motivo_0; 

            $nuevo->id_sucursal=$request->id_sucursal;
            $nuevo->id_tienda=$id_tienda;
            $nuevo->id_almacen=$id_almacen;       

            $nuevo->tipo_inventario=$request->tipo_inventario; 
            $nuevo->id_usuario_registra=auth()->user()->id;                    
            $nuevo->save();
                  

            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
}

     public function store(Request $request)
    {
        //1= Diario , 7=Semanal, 30=Mensual, 90=Trimestral
        try {
            DB::beginTransaction();
            //return $request->all();
            //$id_tienda=(int)$request->id_tienda;
           // $id_almacen=(int)$request->id_almacen;
           // $id_linea=(int)$request->id_lineas;           
           
          
       
               
            $id_nuevo=$request->id_dos;
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
                'id_gestion_inv_periodo_dos' => $request->id_tabla_dos_momentanio,
                'id_ingreso' => $value['id_ingreso'], 
                'id_producto' => $value['id_prod_producto'],   
                'envase' => $value['envase'], 
                'cantidad_sis_detalle_inventario' => $cantidad_sis_detalle_inventario, 
                'cantidad_reg_detalle_inventario' => $cantidad_reg_detalle_inventario, 
                'diferencia_detalle_inventario' => $diferencia, 
                'estado' => $estado, 
                'lote' =>  $value['lote'],
                'fecha_v' =>  $value['fecha_vencimiento'],      
                'observacion' => "Comentar",         
            ];
                DB::table('inv__gestion_inventario_periodo_detalle_tres')->insert($datos);
            }   
          
               $update = inv_GestionInventarioPeriodo_uno::findOrFail($request->id_index);
$update->enproceso = 2;
$update->save();  
   

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
        
        
         $update = inv_GestionInventarioPeriodo_uno::findOrFail($request->id);
         $update->estado=$dato;        
        $update->save();
    }


public function bloquear_linea(Request $request){   
    try {
           DB::beginTransaction();
      $fechaHoy = new DateTime();
$fechaHoy->format('Y-m-d');                  
            $existe = DB::table('inv__gestion_inventario_bloqueo_sucursal')
    ->where('id_inventario', $request->id_index)
    ->where('id_sucursal', $request->id_sucursal)
    ->where('id', $request->id_bloqueo_1)    
    ->where('activo', 1)    
    ->exists();
    if ($existe) {
   // return 1;
       return response()->json([
                'estado' => 1,
                'enviar' => 0,   
                'error' => 'sin error'        
            ]);
} else {
$datos = [                         
                'id_inventario' => $request->id_index,
            'id_linea' => $request->id_lineas, 
                'id_sucursal' => $request->id_sucursal, 
                'id_usuario' => auth()->user()->id, 
                'activo' => $request->inicio, 
                'fecha_ini' => $fechaHoy, 
                'fecha_fin' => $fechaHoy,             
                'observacion' =>'sin datos',                      
            ];
     $id_bloqueo =DB::table('inv__gestion_inventario_bloqueo_sucursal')->insertGetId($datos);
     $enviar_fecha="";
     if ($request->tipo_inventario=="D") {
      $enviar_fecha=$request->fecha_0_0;
     }else{
    $enviar_fecha=$fechaHoy;  
     }
$datos = [                         
                'id_gestion_inv_periodo_uno' => (int)$request->id_index,
            'id_linea' => (int)$request->id_lineas, 
                'turno' => (int)$request->turno, 
                'fecha_ini' => $enviar_fecha,                                      
            ];
               $id_data = DB::table('inv__gestion_inventario_periodo_linea_dos')->insertGetId($datos);

$update = inv_GestionInventarioPeriodo_uno::findOrFail($request->id_index);
$update->enproceso = 1;
$update->id_tabla_dos_momentanio = $id_data;
$update->id_bloqueo = $id_bloqueo;
$update->save(); 
}

     DB::commit();
           //  return 0;
           return response()->json([
                'estado' => 0,
                'enviar' => $id_data,   
                'error' => 'Sin error'        
            ]);
    } catch (\Throwable $th) {
         DB::rollBack();
        return response()->json([
                'estado' => 2,
                'enviar' => 0,   
                 'error' => $th->getMessage()      
            ]);
    }
  
}

 public function getProducto(Request $request){
        // Consulta 1 - Primario
        $id_linea=(int)$request->id_linea;
        $id_tienda=(int)$request->id_tienda;
        $id_almacen=(int)$request->id_almacen;
        $peridoSelect=$request->peridoSelect;
        $fecha=$request->fecha_0_0;
        $turno=(int)$request->turno;
        $id_sucursal=(int)$request->id_sucursal;

        if ($id_tienda!=null&&$id_almacen==null) {
            if ($peridoSelect=="D") {       
             $sqls = "(tip.stock_ingreso > 0 AND DATE(tip.created_at) <= '$fecha')"; 
             
        } else {
               $sqls = "(tip.stock_ingreso > 0)";
        }
          
            $primario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserprimario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('tip.envase', 'primario')
    ->where('pp.idlinea', $id_linea)
     ->whereRaw($sqls)
    ->where('tip.idtienda', $id_tienda)
    
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
        pff.nombre AS nom_forma_faraceutica,
        tip.codigo_imprecion
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'tip.codigo_imprecion'
    );

// Consulta 2 - Secundario
$secundario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispensersecundario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticasecundario')
    ->where('tip.envase', 'secundario')
    ->where('pp.idlinea', $id_linea)
     ->whereRaw($sqls)
    ->where('tip.idtienda', $id_tienda)
  
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
        pff.nombre AS nom_forma_faraceutica,
        tip.codigo_imprecion
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'tip.codigo_imprecion'
    );

// Consulta 3 - Terciario
$terciario = DB::table('tda__ingreso_productos as tip')
    ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserterciario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaterciario')
    ->where('tip.envase', 'terciario')
    ->where('pp.idlinea', $id_linea)
     ->whereRaw($sqls)
    ->where('tip.idtienda', $id_tienda)

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
        pff.nombre AS nom_forma_faraceutica,
        tip.codigo_imprecion
    ')->groupBy(
        'tip.envase',
        'tip.fecha_vencimiento',
        'tip.lote',
        'tip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'tip.codigo_imprecion'
    );

// UNION ALL
$resultado = $primario
    ->unionAll($secundario)
    ->unionAll($terciario)
    ->get();
     if ($peridoSelect=="D") {  

       foreach ($resultado as $key => $value) {
    $cantidad = DB::table('ven__detalle_ventas as vd')
        ->join('ven__recibos as v', 'v.id', '=', 'vd.id_venta')
        ->join('caja__apertura_cierres as c', 'c.id', '=', 'v.id_apertura')
        ->where('vd.id_ingreso', $value->id)
        ->where('vd.id_producto', $value->id_prod_producto)
        ->where('vd.id_linea', $id_linea)
        ->where('vd.envase', $value->envase)
        ->where('v.id_sucursal', $id_sucursal)
        ->where('c.turno_caja', $turno)
        ->where('v.anulado', 0)
        ->whereDate('v.created_at', '=', $fecha)
        ->sum('vd.cantidad_venta');

    // calcular stock restante
    $resultado[$key]->stock_ingreso = $value->stock_ingreso - $cantidad;
}     
     return $resultado;
             
        } else {
            return $resultado; 
        }
  
    
        }else{
            if ($peridoSelect=="D") {       
             $sqls = "(aip.stock_ingreso > 0 AND DATE(aip.created_at) <= '$fecha')"; 
        } else {
               $sqls = "(aip.stock_ingreso > 0)";
        }
            // --------------------
// PRIMARIO
// --------------------
$primario = DB::table('alm__ingreso_producto as aip')
    ->join('prod__productos as pp', 'pp.id', '=', 'aip.id_prod_producto')
    ->join('prod__dispensers as pd', 'pp.iddispenserprimario', '=', 'pd.id')
    ->join('prod__forma_farmaceuticas as pff', 'pff.id', '=', 'pp.idformafarmaceuticaprimario')
    ->where('aip.envase', 'primario')
    ->where('pp.idlinea', $id_linea)
     ->whereRaw($sqls)
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
        pff.nombre AS nom_forma_faraceutica,
        aip.codigo_imprecion
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'aip.codigo_imprecion'
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
     ->whereRaw($sqls)
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
        pff.nombre AS nom_forma_faraceutica,
        aip.codigo_imprecion
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'aip.codigo_imprecion'
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
   ->whereRaw($sqls)
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
        pff.nombre AS nom_forma_faraceutica,
        aip.codigo_imprecion
    ')->groupBy(
        'aip.envase',
        'aip.fecha_vencimiento',
        'aip.lote',
        'aip.registro_sanitario',
        'pp.nombre',
        'pp.cantidadprimario',
        'pd.nombre',
        'pff.nombre',
        'aip.codigo_imprecion'
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


    public function getBloqueo_1(Request $request){
        $existe = DB::table('inv__gestion_inventario_bloqueo_sucursal')
    ->where('id_linea', $request->id_linea)
    ->where('id_sucursal', $request->id_sucursal)
    ->where('id', $request->id_bloqueo_1)
    ->exists();
    if ($existe) {
    return 1;
} else {
    return 0;
}
    }

    public function getTabla_dos(Request $request){
        $datos = DB::table('inv__gestion_inventario_periodo_linea_dos')
    ->where('id_gestion_inv_periodo_uno', $request->id)
    ->first();
    return $datos;
    }

    public function getTabla_tres(Request $request){
            $datos = DB::table('inv__gestion_inventario_periodo_detalle_tres as i')
    ->select(
        'i.id',
        'i.id_gestion_inv_periodo_dos',
        'i.id_ingreso',
        'i.id_producto',
        'i.envase',
        'i.cantidad_sis_detalle_inventario',
        'i.cantidad_reg_detalle_inventario',
        'i.diferencia_detalle_inventario',
        'i.estado',
        'i.lote',
        'i.fecha_v',
        'i.observacion',
        'tip.stock_ingreso as cantidad_ingresada_t',
        'aip.stock_ingreso as cantidad_ingresada_a',
        DB::raw("
            CASE
                WHEN i.envase = 'primario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_1.nombre,''),' x ',COALESCE(p.cantidadprimario,''),' ',COALESCE(ff_1.nombre,''))
                WHEN i.envase = 'secundario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_2.nombre,''),' x ',COALESCE(p.cantidadsecundario,''),' ',COALESCE(ff_2.nombre,''))
                WHEN i.envase = 'terciario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_3.nombre,''),' x ',COALESCE(p.cantidadterciario,''),' ',COALESCE(ff_3.nombre,''))
                ELSE NULL
            END AS leyenda
        "),
        'l.nombre as nom_linea'
    )
    ->leftJoin('tda__ingreso_productos as tip', 'tip.id', '=', 'i.id_ingreso')
    ->leftJoin('alm__ingreso_producto as aip', 'aip.id', '=', 'i.id_ingreso')
    ->join('prod__productos as p', 'p.id', '=', 'i.id_producto')
    ->join('prod__lineas as l', 'l.id', '=', 'p.idlinea')
    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'p.iddispenserprimario')
    ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'p.iddispensersecundario')
    ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'p.iddispenserterciario')
    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'p.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'p.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'p.idformafarmaceuticaterciario')
    ->where('i.id_gestion_inv_periodo_dos', $request->id)
    ->where('i.diferencia_detalle_inventario', '<>', 0)
    ->where('i.bloqueado','=',0)
    ->get();
    return $datos;
    }
 

    public function end_proceso(Request $request){

 try {
     DB::beginTransaction();
    $id_nuevo=$request->id_dos;
    $arreglo=$request->array;
            foreach ($arreglo as $key => $value) {
        $observacion = $value['observacion'];                

        $datos = [                         
            'observacion' => $observacion,     
            'bloqueado'=>1                  
        ];

        DB::table('inv__gestion_inventario_periodo_detalle_tres')
            ->where('id_gestion_inv_periodo_dos', $id_nuevo)
            ->where('id_ingreso', $value['id_ingreso'])
            ->update($datos);
    }   

    DB::table('inv__gestion_inventario_periodo_unos')
        ->where('id', $request->id_index)
        ->update([
            'enproceso' => 3
        ]);

    DB::table('inv__gestion_inventario_bloqueo_sucursal')
        ->where('id', $request->id_bloqueo)    
        ->delete();  


             DB::commit();
             return 0;
 } catch (\Throwable $th) {
    return $th;
 }
    }

    public function end_proceso_continue(Request $request){
    try {
         DB::beginTransaction();

            DB::table('inv__gestion_inventario_periodo_unos')
        ->where('id', $request->id_index)
        ->update([
            'enproceso' => $request->tipo,
            'id_tabla_dos_momentanio'=>null,
             'id_bloqueo'=>null
        ]);
         DB::commit();
             return 0;
    } catch (\Throwable $th) {
        return $th;
    }
           

    }


    public function get_modal_data(Request $request){
        $id=$request->id;
        $consulta_1 = DB::table('inv__gestion_inventario_periodo_unos as i')
    ->join('users as u', 'u.id', '=', 'i.id_usuario_registra')
    ->select(
        'i.nombre',
        'i.motivo',
        DB::raw("
            CASE
                WHEN i.enproceso = 0 THEN 'Sin acción'
                WHEN i.enproceso = 1 THEN 'Incompleto'
                WHEN i.enproceso = 2 THEN 'Con registros'
                WHEN i.enproceso = 3 THEN 'Terminado'
                WHEN i.enproceso = 4 THEN 'Consolidado'
                ELSE NULL
            END AS enproceso
        "),
        'u.name',
        DB::raw("
            CASE
                WHEN i.tipo_inventario = 'D' THEN 'Diario'
                WHEN i.tipo_inventario = 'G' THEN 'General'
                ELSE NULL
            END AS tipo_inventario
        "),
        'i.id_bloqueo',
        'i.created_at'
    )
    ->where('i.id', $id)
    ->first();

    $consulta_2 = DB::table('inv__gestion_inventario_periodo_detalle_tres as i')
    ->join('prod__productos as p', 'p.id', '=', 'i.id_producto')
    ->join('inv__gestion_inventario_periodo_linea_dos as ii', 'ii.id', '=', 'i.id_gestion_inv_periodo_dos')
    ->join('prod__lineas as l', 'l.id', '=', 'ii.id_linea')

    ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'p.iddispenserprimario')
    ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'p.iddispensersecundario')
    ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'p.iddispenserterciario')

    ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'p.idformafarmaceuticaprimario')
    ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'p.idformafarmaceuticasecundario')
    ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'p.idformafarmaceuticaterciario')

    ->where('ii.id_gestion_inv_periodo_uno', $id)

    ->select([
        'i.id_ingreso',
        'i.envase',
        'i.cantidad_sis_detalle_inventario',
        'i.cantidad_reg_detalle_inventario',
        'i.diferencia_detalle_inventario',
        'i.estado',
        'i.lote',
        'i.fecha_v',
        'i.observacion',

        DB::raw("
            CASE 
                WHEN COUNT(*) OVER(
                    PARTITION BY i.id_ingreso, i.cantidad_sis_detalle_inventario, i.envase, l.id, i.lote
                ) > 1 
                THEN 'REPETIDO'
                ELSE 'OK'
            END AS repetido
        "),

        DB::raw("
            CASE
                WHEN i.envase = 'primario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_1.nombre,''),' x ',COALESCE(p.cantidadprimario,''),' ',COALESCE(ff_1.nombre,''))
                WHEN i.envase = 'secundario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_2.nombre,''),' x ',COALESCE(p.cantidadsecundario,''),' ',COALESCE(ff_2.nombre,''))
                WHEN i.envase = 'terciario' THEN CONCAT(COALESCE(p.nombre,''),' ',COALESCE(pd_3.nombre,''),' x ',COALESCE(p.cantidadterciario,''),' ',COALESCE(ff_3.nombre,''))
                ELSE NULL
            END AS leyenda
        "),

        'ii.id as id_dos',

        DB::raw("
            CASE
                WHEN ii.turno = 1 THEN 'Mañana'
                WHEN ii.turno = 2 THEN 'Tarde'
                WHEN ii.turno = 3 THEN 'Completo'
                ELSE NULL
            END AS turno
        "),

        'ii.fecha_ini',
        'l.nombre as nom_linea'
    ])

    ->get();
return response()->json([
                'query_1' => $consulta_1,
                'query_2' => $consulta_2       
            ]);

    }
}
