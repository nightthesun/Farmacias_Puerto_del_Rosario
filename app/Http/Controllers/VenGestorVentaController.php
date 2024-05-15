<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenGestorVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function get_sucusal(){
        
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;

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
                'gpv.id_lista','pppl.nombre as nombre_linea'
            )
            ->where('ass.id', $id_suc)
            ->where('gpv.listo_venta', 1)
            ->where('pl.id', $id_lista)
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
                'gpv.id_lista','pppl.nombre as nombre_linea'
            )
            ->where('ass.id', 1)
            ->where('gpv.listo_venta', $idsuc)
            ->get();
            return $resultados;    
        }

       
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

    /**
     * Display the specified resource.
     */
    public function show(Ven_GestorVenta $ven_GestorVenta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ven_GestorVenta $ven_GestorVenta)
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
        $resultado = DB::table('dir__clientes as dc')
        ->select('dc.id', 
                 'dc.nom_a_facturar', 
                 'dc.id_per_emp', 
                 'dc.num_documento', 
                 'dtd.id as id_tipo_doc',
                 'dtd.nombre_doc as tipo_doc_nombre',
                 'dtd.datos as tipo_doc_datos',
                 DB::raw("CONCAT(dp.nombres, ' ', dp.apellidos) AS nombre_completo"))
        ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
        ->join('dir__personas as dp', 'dc.id_per_emp', '=', 'dp.id')
        ->where('dc.tipo_per_emp', 1)
        ->where('dc.activo', 1)
        ->where('dc.num_documento',  $request->num_doc)
        ->unionAll(DB::table('dir__clientes as dc')
            ->select('dc.id', 
                     'dc.nom_a_facturar', 
                     'dc.id_per_emp', 
                     'dc.num_documento', 
                     'dtd.id as id_tipo_doc',
                     'dtd.nombre_doc as tipo_doc_nombre',
                     'dtd.datos as tipo_doc_datos',
                     'de.razon_social AS nombre_completo')
            ->join('dir__empresas as de', 'dc.id_per_emp', '=', 'de.id')
            ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
            ->where('dc.tipo_per_emp', 2)
            ->where('dc.activo', 1)
            ->where('dc.num_documento', $request->num_doc))
       
        ->first();
  
        return $resultado;
    }
    public function listarUsuarioRetorno(Request $request){
        $buscararray = array();
    
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                            
                            or dc.nom_a_facturar like '%" . $valor . "%' 
                            or dc.num_documento like '%" . $valor . "%' 
                         
                               )";
                    } else {
                        $sqls .= "and 
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento like '%" . $valor . "%' 
                       
                       
                       )";
                    }
                }
                //consulta---------------------------------------------------
                $subconsulta = DB::table('dir__clientes as dc')
                ->select('dc.id', 
                         'dc.nom_a_facturar', 
                         'dc.id_per_emp', 
                         'dc.num_documento', 
                         'dtd.id as id_tipo_doc',
                         'dtd.nombre_doc as tipo_doc_nombre',
                         'dtd.datos as tipo_doc_datos',
                         DB::raw("CONCAT(dp.nombres, ' ', dp.apellidos) AS nombre_completo"),
                         'dc.created_at as fecha')
                ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
                ->join('dir__personas as dp', 'dc.id_per_emp', '=', 'dp.id')
                ->where('dc.tipo_per_emp', 1)
                ->where('dc.activo', 1);
            
            $subconsultaEmpresas = DB::table('dir__clientes as dc')
                ->select('dc.id', 
                         'dc.nom_a_facturar', 
                         'dc.id_per_emp', 
                         'dc.num_documento', 
                         'dtd.id as id_tipo_doc',
                         'dtd.nombre_doc as tipo_doc_nombre',
                         'dtd.datos as tipo_doc_datos',
                         'de.razon_social AS nombre_completo',
                         'dc.created_at as fecha')
                ->join('dir__empresas as de', 'dc.id_per_emp', '=', 'de.id')
                ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
                ->where('dc.tipo_per_emp', 2)
                ->where('dc.activo', 1);
            
            $resultado = $subconsulta->unionAll($subconsultaEmpresas)
                ->orderBy('id', 'desc') // Ordenar por id de forma descendente
                ->take(30) // Limitar a 100 registros
                ->get();       
            }    
            return $resultado;
        } else{
            $subconsulta = DB::table('dir__clientes as dc')
    ->select('dc.id', 
             'dc.nom_a_facturar', 
             'dc.id_per_emp', 
             'dc.num_documento', 
             'dtd.id as id_tipo_doc',
             'dtd.nombre_doc as tipo_doc_nombre',
             'dtd.datos as tipo_doc_datos',
             DB::raw("CONCAT(dp.nombres, ' ', dp.apellidos) AS nombre_completo"),
             'dc.created_at as fecha')
    ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
    ->join('dir__personas as dp', 'dc.id_per_emp', '=', 'dp.id')
    ->where('dc.tipo_per_emp', 1)
    ->where('dc.activo', 1);

$subconsultaEmpresas = DB::table('dir__clientes as dc')
    ->select('dc.id', 
             'dc.nom_a_facturar', 
             'dc.id_per_emp', 
             'dc.num_documento', 
             'dtd.id as id_tipo_doc',
             'dtd.nombre_doc as tipo_doc_nombre',
             'dtd.datos as tipo_doc_datos',
             'de.razon_social AS nombre_completo',
             'dc.created_at as fecha')
    ->join('dir__empresas as de', 'dc.id_per_emp', '=', 'de.id')
    ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
    ->where('dc.tipo_per_emp', 2)
    ->where('dc.activo', 1);

$resultado = $subconsulta->unionAll($subconsultaEmpresas)
    ->orderBy('id', 'desc') // Ordenar por id de forma descendente
    ->take(100) // Limitar a 100 registros
    ->get();

return $resultado;
        }   
    }    
}
