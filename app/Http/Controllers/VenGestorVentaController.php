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
                            dc.id like '%" . $valor . "%' 
                            or dc.nom_a_facturar like '%" . $valor . "%' 
                            or dc.num_documento like '%" . $valor . "%' 
                         
                               )";
                    } else {
                        $sqls .= "and (dc.id like '%" . $valor . "%' 
                        or dc.nom_a_facturar like '%" . $valor . "%' 
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
