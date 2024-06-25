<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVenta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VenGestorVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    public function venta(Request $request){
        try {
               // Iniciar una transacción
               DB::beginTransaction();
               $user_1 = auth()->user()->id;
               $nomsucursal="";
               $iduserrolesuc = "";
               $idsuc = "";
               $name_user = ""; 
               if ($user_1==1) {
                $nomsucursal="SUCURSAL MAIN";
                $iduserrolesuc = 1;
               $idsuc = 1;
               $name_user = "administrador";
               }else{
                $iduserrolesuc = session('iduserrolesuc');
                $idsuc = session('idsuc');
                $id_user2 = session('id_user2'); 
               } 

               $num_documento = $request->input('num_documento');
               $data = [
                'title' => 'Factura de Venta',
                'num_documento' => $num_documento,
                'contenido' => 'Contenido de la factura...' // Aquí puedes agregar más datos necesarios para la factura
            ];
    
            // Convertir las dimensiones a puntos (1 mm = 2.83465 puntos)
            $width = 80 * 2.83465; // 80 mm a puntos
            $height = 500; // Suficientemente grande para permitir crecimiento
    
            // Definir el tamaño del papel
            $customPaper = array(0, 0, $width, $height);
    
            $pdf = Pdf::loadView('factura.recibo', $data)
                ->setPaper($customPaper, 'portrait');
               
    
            return $pdf->stream('ticket.pdf');
    

        } catch (\Throwable $th) {
              return response()->json(['error' => $th->getMessage()],500);
        }
      

       
       // return $pdf->inline('factura.ticket');

       // return $pdf->download('mi_archivo.pdf');
       //return $pdf->inline('cxc_pdf', compact('fecha','fechaCarta'));
        //$pdf = \PDF::loadView('reports.pdf.carta',compact('cadenaDL1','cadenaBS1','fechaC','fechaH','nameCxc','cxcCarta','totalS','cadenaDL','cadenaBS','arraycxcCarta','formatter'))
      
        
    
  
        
    
    }

    public function get_sucusal(){
        
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        //dd(session()->all());
        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }

              // Obtener todos los registros donde id_sucursal es 1
          // Obtener todos los registros donde id_sucursal es 1 usando el constructor de consultas
        $sucursales = DB::table('adm__sucursal_listas')->where('id_sucursal', $idsuc)->first();
   
       
        if ($sucursales) {
            // si tiene  es con lista 
            $id_suc=$sucursales->id_sucursal;
            $id_lista=$sucursales->id_lista; 
            $resultados = DB::table('ges_pre__venta_listas as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__registro_pre_x_lists as prp', 'prp.id', '=', 'gpv.id_lista')
            ->join('prod__listas as pl', 'pl.id', '=', 'prp.id_lista')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
                'tip.id as id_ingreso',
                DB::raw("
                    CASE 
                        WHEN tip.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, ''))
                        WHEN tip.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, ''))
                        WHEN tip.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, ''))
                        ELSE NULL
                    END AS leyenda
                "),
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11'
            )
            ->where('ass.id', $id_suc)
            ->where('gpv.listo_venta', 1)
            ->where('pl.id', $id_lista)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->get(); 
            return $resultados;
       
        } else{
            // lista por defecto 
            $resultados = DB::table('ges_pre__venta2s as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
   
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
                'tip.id as id_ingreso',
                DB::raw("
                    CASE 
                        WHEN tip.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, ''))
                        WHEN tip.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, ''))
                        WHEN tip.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, ''))
                        ELSE NULL
                    END AS leyenda
                "),
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11'
               
            )
            ->where('ass.id', $idsuc)
            ->where('gpv.listo_venta', 1)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->get();
            return $resultados;    
        }

       
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ven_GestorVenta $ven_GestorVenta)
    {
        //
    }
    public function listarUsuario(Request $request){
        $clientes = DB::table('dir__clientes as dc')
        ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
        ->leftJoin('dir__personas as dp', function ($join) {
            $join->on('dp.id', '=', 'dc.id_per_emp')
                 ->where('dc.tipo_per_emp', '=', 1);
        })
        ->leftJoin('dir__empresas as de', function ($join) {
            $join->on('de.id', '=', 'dc.id_per_emp')
                 ->where('dc.tipo_per_emp', '=', 2);
        })
        ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
        ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
        ->leftJoin('par__asignacion_descuento as pad2', function($join) {
            $join->on('pad2.id_descuento', '=', 'pd2.id')
                 ->where('pad2.id_sucursal', '=', 1);
        })
        ->where('dc.activo', 1)
        ->where('dc.num_documento', $request->num_doc)
 
        ->select([
            'dc.id',
            'dc.nom_a_facturar',
            'dc.id_per_emp',
            'dc.num_documento',
            'dtd.id as id_tipo_doc',
            'dtd.nombre_doc as tipo_doc_nombre',
            'dtd.datos as tipo_doc_datos',
            DB::raw('CASE 
                        WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                        WHEN de.id IS NOT NULL THEN de.razon_social
                        ELSE NULL
                    END as nombre_completo'),
            'dc.created_at as fecha',
            DB::raw('CASE 
                        WHEN pd2.desc_num = 1 THEN "#"
                        WHEN pd2.desc_num = 2 THEN "%"
                        ELSE NULL
                    END as tipo_num_des'),
            'pd2.monto_descuento',
            'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11'
        ])        
       
        ->first();
  
        return $clientes;
    }

    public function listarUsuarioRetorno(Request $request)
{
    $buscararray = array();
    if (!empty($request->buscar)) {
        $buscararray = explode(" ", $request->buscar);
        $valor = sizeof($buscararray);
        if ($valor > 0) {
            $sqls = '';
            foreach ($buscararray as $key => $valor) {
                // Corrección: Se verifica si $sqls está vacío antes de añadir la primera condición
                if (empty($sqls)) {
                    $sqls = "(
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento like '%" . $valor . "%'
                    )";
                } else {
                    // Corrección: Se añade un paréntesis de apertura y se corrige la concatenación
                    $sqls .= " and (
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento like '%" . $valor . "%'
                    )";
                }
            }
            
            $clientes = DB::table('dir__clientes as dc')
                ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
                ->leftJoin('dir__personas as dp', function ($join) {
                    $join->on('dp.id', '=', 'dc.id_per_emp')
                         ->where('dc.tipo_per_emp', '=', 1);
                })
                ->leftJoin('dir__empresas as de', function ($join) {
                    $join->on('de.id', '=', 'dc.id_per_emp')
                         ->where('dc.tipo_per_emp', '=', 2);
                })
                ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
                ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
                ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                    $join->on('pad2.id_descuento', '=', 'pd2.id')
                         ->where('pad2.id_sucursal', '=', 1);
                })
                ->where('dc.activo', 1)
                ->whereRaw($sqls)
                ->take(10)
                ->orderBy('id', 'desc')
                ->select([
                    'dc.id',
                    'dc.nom_a_facturar',
                    'dc.id_per_emp',
                    'dc.num_documento',
                    'dtd.id as id_tipo_doc',
                    'dtd.nombre_doc as tipo_doc_nombre',
                    'dtd.datos as tipo_doc_datos',
                    DB::raw('CASE 
                                WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                                WHEN de.id IS NOT NULL THEN de.razon_social
                                ELSE NULL
                            END as nombre_completo'),
                    'dc.created_at as fecha',
                    DB::raw('CASE 
                                WHEN pd2.desc_num = 1 THEN "#"
                                WHEN pd2.desc_num = 2 THEN "%"
                                ELSE NULL
                            END as tipo_num_des'),
                    'pd2.monto_descuento',
                    'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11'
                ])
                ->get();

            return $clientes;
        }
    } else {
        // Si no hay parámetro de búsqueda, se ejecuta esta parte del código
        $clientes = DB::table('dir__clientes as dc')
            ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
            ->leftJoin('dir__personas as dp', function ($join) {
                $join->on('dp.id', '=', 'dc.id_per_emp')
                     ->where('dc.tipo_per_emp', '=', 1);
            })
            ->leftJoin('dir__empresas as de', function ($join) {
                $join->on('de.id', '=', 'dc.id_per_emp')
                     ->where('dc.tipo_per_emp', '=', 2);
            })
            ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
            ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
            ->where('dc.activo', 1)
            ->orderBy('id', 'desc')
            ->take(40)
            ->select([
                'dc.id',
                'dc.nom_a_facturar',
                'dc.id_per_emp',
                'dc.num_documento',
                'dtd.id as id_tipo_doc',
                'dtd.nombre_doc as tipo_doc_nombre',
                'dtd.datos as tipo_doc_datos',
                DB::raw('CASE 
                            WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                            WHEN de.id IS NOT NULL THEN de.razon_social
                            ELSE NULL
                        END as nombre_completo'),
                'dc.created_at as fecha',
                DB::raw('CASE 
                            WHEN pd2.desc_num = 1 THEN "#"
                            WHEN pd2.desc_num = 2 THEN "%"
                            ELSE NULL
                        END as tipo_num_des'),
                'pd2.monto_descuento',
                'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11'
            ])
            ->get();

        return $clientes;
    }
}

    

    public function listarDescuentos_listas(Request $request){

        $user_1 = auth()->user()->id;   

        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }
       // Consulta optimizada
        $validador = DB::table('par__asignacion_descuento')
        ->where('id_sucursal', $idsuc)
        ->where(DB::raw('LEFT(cod, 3)'), 'TDA')
        ->where('personalizado', 1)
        ->exists();

        // Verificar el resultado
        if ($validador) {
            $descuento =  DB::table('par__asignacion_descuento as pad1')
        ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
        ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
        ->select(
            'pad1.id_descuento as id',
            'pad1.cod',
            'pd.nombre_descuento',
            'ptt.id as id_tabla',
            'ptt.nombre as nombre_tabla',
            'pad1.personalizado as per'
        )
        ->where('pd.activo', 1)
        ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
        ->where('pad1.id_sucursal', $idsuc)
        ->where('pad1.personalizado','=',1)
        ->get();  
        
            } else {
                $descuento = DB::table('par__asignacion_descuento AS pad1')
                ->join('par__descuentos AS pd', 'pd.id', '=', 'pad1.id_descuento')
                ->join('par_tipo_tabla AS ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
                ->select(
                    'pad1.id_descuento as id',
                    'pad1.cod',
                    'pd.nombre_descuento',
                    'ptt.id as id_tabla',
                    'ptt.nombre as nombre_tabla',
                    'pad1.personalizado as per'
                )
                ->where('pd.activo', 1)
                ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
                ->where('pad1.id_sucursal', $idsuc)
                ->where('pad1.personalizado', '=', 0)->get();       
  
            }

            $lista = DB::table('adm__sucursal_listas as asl')
            ->join('prod__listas as pl', 'asl.id_lista', '=', 'pl.id')
            ->select('pl.id', 'pl.nombre_lista')
            ->where('pl.activo', '=',1)
            ->where('asl.id_sucursal','=',$idsuc)
            ->first();
          
    return response()->json(['descuento' => $descuento, 'lista' => $lista,'id_descuento_x'=>$idsuc]);
    }


    public function listarDescuento_Tipo_tabla(){
        $user_1 = auth()->user()->id;   

        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }
         // Consulta optimizada
         $validador = DB::table('par__asignacion_descuento')
         ->where('id_sucursal', $idsuc)
         ->where(DB::raw('LEFT(cod, 3)'), 'TDA')
         ->where('personalizado', 1)
         ->exists();
          // Verificar el resultado
        if ($validador) {
            $descuento = DB::table('par__asignacion_descuento as pad1')
            ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
            ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
            ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
            ->leftJoin('par__cliente_producto as pcli', 'pcli.id_descuento', '=', 'pd.id')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
            ->leftJoin('par__personalizado as pp2','pp2.id_descuento','=','pd.id') 
            ->select(
                'pad1.id_descuento as id',
                'pad1.cod',
                'pd.nombre_descuento',
                DB::raw("
                    CASE
                        WHEN pd.desc_num = 1 THEN '#'
                        WHEN pd.desc_num = 2 THEN '%'
                        ELSE NULL
                    END AS tipo_num_des
                "),
                'pd.monto_descuento',
                'ptt.id as id_nom_tabla',
                'ptt.nombre as nombre_tabla',
                DB::raw("
                    CASE
                        WHEN pcp.es_cantidad_es_monto = 1 THEN '#'
                        WHEN pcp.es_cantidad_es_monto = 2 THEN 'BS'
                        ELSE NULL
                    END AS tipo_can_valor
                "),
                DB::raw("
                    CASE
                        WHEN pcp.regla = 1 THEN '<'
                        WHEN pcp.regla = 2 THEN '>'
                        WHEN pcp.regla = 3 THEN '='
                        ELSE NULL
                    END AS regla
                "),
                'pcp.cantidad_valor',
                'pcli.id_cliente_p',
                'ppd.id_prod',
                'pcp.id as id_tabla_cant_valor',
                'pcli.id as id_tabla_cliente',
                'ppd.id as id_tabla_prod',
                'pp2.id as id_perso',
                DB::raw("
                CASE
                WHEN ptt.id=1 THEN '0'
                WHEN ptt.id=2 THEN 	pcp.id
                WHEN ptt.id=3 THEN 	pcli.id
                WHEN ptt.id=4 THEN ppd.id
                WHEN ptt.id=5 THEN pp2.id
                WHEN ptt.id=6 THEN 'f' 
            ELSE NULL
              END as id_tablas_x
                "),
                'pad1.personalizado as per'
            )
            ->where('pd.activo', 1)
            ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
            ->where('pad1.id_sucursal', $idsuc)
            ->where('pad1.personalizado','=',1)
            ->get();
        
           
            return response()->json(['descuento' => $descuento, 'validador' => $validador]);
        }else{
            $descuento = DB::table('par__asignacion_descuento as pad1')
            ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
            ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
            ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
            ->leftJoin('par__cliente_producto as pcli', 'pcli.id_descuento', '=', 'pd.id')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
            ->leftJoin('par__personalizado as pp2','pp2.id_descuento','=','pd.id') 
            ->select(
                'pad1.id_descuento as id',
                'pad1.cod',
                'pd.nombre_descuento',
                DB::raw("
                    CASE
                        WHEN pd.desc_num = 1 THEN '#'
                        WHEN pd.desc_num = 2 THEN '%'
                        ELSE NULL
                    END AS tipo_num_des
                "),
                'pd.monto_descuento',
                'ptt.id as id_nom_tabla',
                'ptt.nombre as nombre_tabla',
                DB::raw("
                    CASE
                        WHEN pcp.es_cantidad_es_monto = 1 THEN '#'
                        WHEN pcp.es_cantidad_es_monto = 2 THEN 'BS'
                        ELSE NULL
                    END AS tipo_can_valor
                "),
                DB::raw("
                    CASE
                        WHEN pcp.regla = 1 THEN '<'
                        WHEN pcp.regla = 2 THEN '>'
                        WHEN pcp.regla = 3 THEN '='
                        ELSE NULL
                    END AS regla
                "),
                'pcp.cantidad_valor',
                'pcli.id_cliente_p',
                'ppd.id_prod',
                'pcp.id as id_tabla_cant_valor',
                'pcli.id as id_tabla_cliente',
                'ppd.id as id_tabla_prod',
                'pp2.id as id_perso',
                DB::raw("
                CASE
                WHEN ptt.id=1 THEN '0'
                WHEN ptt.id=2 THEN 	pcp.id
                WHEN ptt.id=3 THEN 	pcli.id
                WHEN ptt.id=4 THEN ppd.id
                WHEN ptt.id=5 THEN pp2.id
                WHEN ptt.id=6 THEN 'f' 
            ELSE NULL
              END as id_tablas_x
                "),
                'pad1.personalizado as per'
            )
            ->where('pd.activo', 1)
            ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
            ->where('pad1.id_sucursal', $idsuc)
            ->get();
        
            return response()->json(['descuento' => $descuento, 'validador' => $validador]);
        }
       

    }
    
}
