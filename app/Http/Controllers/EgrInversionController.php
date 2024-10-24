<?php

namespace App\Http\Controllers;

use App\Models\Egr_Inversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgrInversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();
        ///persona
        $limite=$request->limite;
        if ($limite==0) {
            $limite=999999999;
        }
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(                                
                                dc.nom_a_facturar like '%" . $valor . "%' 
                                or dc.num_documento     like '%" . $valor . "%'
                                 or ei.nro_comprobante     like '%" . $valor . "%'                                                                 
                               )";
                    } else {
                        $sqls .= "and (
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento     like '%" . $valor . "%'    
                         or ei.nro_comprobante     like '%" . $valor . "%'  
                       )";
                    }
                }
                //----
                $resultado = DB::table('egr__inversions as ei')
                        ->select('ei.id','ei.id_distribuidor','ei.tipo_comprabante as id_tipo_comprabante','ei.estado',DB::raw("CASE 
                        WHEN ei.tipo_comprabante = 1 THEN 'Recibo'
                        WHEN ei.tipo_comprabante = 2 THEN 'Factura'
                        ELSE NULL 
                        END AS tipo_comprabante"),
                        'ei.total','ei.simbolo','u.name',
                        DB::raw('GREATEST(ei.created_at, ei.updated_at) AS fecha_mas_reciente'),
                        'ei.descripcion',
                        'dc.num_documento','ei.nro_comprobante','dc.nom_a_facturar',
                        DB::raw("CASE 
                        WHEN de.id IS NOT NULL THEN UPPER(COALESCE(de.razon_social, ''))
                        WHEN dp.id IS NOT NULL THEN UPPER(COALESCE(CONCAT(
                        COALESCE(dp.nombres, ''),
                        ' ',
                        COALESCE(dp.apellidos, '')
                        ), ''))
                        ELSE NULL 
                        END AS nombre_1")
                        )
                    ->join('dir__distribuidors as dd', 'dd.id', '=', 'ei.id_distribuidor')
                    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
                    ->leftJoin('dir__empresas as de', function($join) {
                    $join->on('de.id', '=', 'dc.id_per_emp')
                    ->where('dd.tipo_persona_empresa', '=', 2);
                    })
                    ->leftJoin('dir__personas as dp', function($join) {
                    $join->on('dp.id', '=', 'dc.id_per_emp')
                    ->where('dd.tipo_persona_empresa', '=', 1);
                    })
                    ->join('users as u', DB::raw('COALESCE(ei.id_usuario_modifica, ei.id_usuario_registra)'), '=', 'u.id')
                 
                    ->where('ei.id_sucursal', '=', $request->id_sucursal)
                    ->whereRaw($sqls)
                    ->orderByDesc('ei.id')
                    ->limit($limite)
                    ->paginate(20);  
            } 
            return 
                    ['pagination' =>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ],
                        'resultado' => $resultado,
                    ];                
        } else {
            $resultado = DB::table('egr__inversions as ei')
            ->select('ei.id','ei.id_distribuidor','ei.tipo_comprabante as id_tipo_comprabante','ei.estado',DB::raw("CASE 
            WHEN ei.tipo_comprabante = 1 THEN 'Recibo'
            WHEN ei.tipo_comprabante = 2 THEN 'Factura'
            ELSE NULL 
            END AS tipo_comprabante"),
            'ei.total','ei.simbolo','u.name',
            DB::raw('GREATEST(ei.created_at, ei.updated_at) AS fecha_mas_reciente'),
            'ei.descripcion',
            'dc.num_documento','ei.nro_comprobante','dc.nom_a_facturar',
            DB::raw("CASE 
            WHEN de.id IS NOT NULL THEN UPPER(COALESCE(de.razon_social, ''))
            WHEN dp.id IS NOT NULL THEN UPPER(COALESCE(CONCAT(
            COALESCE(dp.nombres, ''),
            ' ',
            COALESCE(dp.apellidos, '')
            ), ''))
            ELSE NULL 
            END AS nombre_1")
            )
        ->join('dir__distribuidors as dd', 'dd.id', '=', 'ei.id_distribuidor')
        ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
        ->leftJoin('dir__empresas as de', function($join) {
        $join->on('de.id', '=', 'dc.id_per_emp')
        ->where('dd.tipo_persona_empresa', '=', 2);
        })
        ->leftJoin('dir__personas as dp', function($join) {
        $join->on('dp.id', '=', 'dc.id_per_emp')
        ->where('dd.tipo_persona_empresa', '=', 1);
        })
        ->join('users as u', DB::raw('COALESCE(ei.id_usuario_modifica, ei.id_usuario_registra)'), '=', 'u.id')
   
        ->where('ei.id_sucursal', '=', $request->id_sucursal)
        ->orderByDesc('ei.id')
        ->limit($limite)
        ->paginate(20);
        return 
                    ['pagination' =>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ],
                        'resultado' => $resultado,
                    ];   
        }

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // 1= recibo, 2 = factura
       try {
        DB::beginTransaction();
        $crear = new Egr_Inversion();
        $crear->id_distribuidor=$request->id_dis;
        $crear->tipo_persona_empresa=$request->tipo_persona_empresa;
        $crear->tipo_comprabante=$request->tipo_comprabante;
        $crear->nro_comprobante=$request->nro_comprobante;            
        $crear->total=$request->total;     
        $crear->simbolo=$request->simbolo;    
        $crear->id_sucursal=$request->id_sucursal;    
        $crear->total=$request->total;    
        $crear->descripcion=$request->descripcion;
        $crear->id_usuario_registra=auth()->user()->id;  
        $crear->id_apertura=$request->id_apertura_cierre;  
        $crear->save();
       // return DB::commit();   
        DB::commit();    
       } catch (\Throwable $th) {
        return $th;
       }
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Egr_Inversion $egr_Inversion)
    {
        try {
            DB::beginTransaction();
            $e =Egr_Inversion::find($request->id);
            $e->id_distribuidor=$request->id_dis;
            $e->tipo_persona_empresa=$request->tipo_persona_empresa;
            $e->tipo_comprabante=$request->tipo_comprabante;
            $e->nro_comprobante=$request->nro_comprobante;            
            $e->total=$request->total;     
 
            $e->id_sucursal=$request->id_sucursal;    
            $e->total=$request->total;    
            $e->descripcion=$request->descripcion;
            $e->id_usuario_modifica=auth()->user()->id;  
            $e->save();
           //return DB::commit();
            DB::commit();    
           } catch (\Throwable $th) {
            return $th;
           }
    }

   public function listarDistribuidor(){

    $distriibuidor =  DB::table('dir__distribuidors as dd')
    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
    ->leftJoin('dir__empresas as de', function($join) {
        $join->on('de.id', '=', 'dc.id_per_emp')
             ->where('dd.tipo_persona_empresa', '=', 2);
    })
    ->leftJoin('dir__personas as dp', function($join) {
        $join->on('dp.id', '=', 'dc.id_per_emp')
             ->where('dd.tipo_persona_empresa', '=', 1);
    })
    ->where('dd.estado', 1)  // Condición WHERE
    ->select('dd.id', 'dd.contacto', 'dd.nom_linea_array', 'dc.nom_a_facturar', 'dc.num_documento', 'dd.tipo_persona_empresa')
    ->selectRaw("
        CASE 
            WHEN de.id IS NOT NULL THEN UPPER(COALESCE(de.razon_social, ''))
            WHEN dp.id IS NOT NULL THEN UPPER(COALESCE(CONCAT(COALESCE(dp.nombres, ''), ' ', COALESCE(dp.apellidos, '')), ''))
            ELSE NULL 
        END AS nombre_1
    ")
    ->selectRaw("
       CASE 
        WHEN dd.tipo_persona_empresa = 1 THEN 'Persona'
        WHEN dd.tipo_persona_empresa = 2 THEN 'Empresa'
        ELSE NULL 
    END AS tipo
    ")

    ->get();

    $moneda = DB::table('adm__credecial_correos as acc')
    ->join('adm__nacionalidads as an', 'acc.moneda', '=', 'an.id')
    ->select('acc.moneda', 'an.simbolo')
    ->get();

    return response()->json([
         'distribuidor' => $distriibuidor,
         'moneda' => $moneda 
     ]);
 
   }

   public function desactivar(Request $request)
   {
       $update = Egr_Inversion::findOrFail($request->id);
       $update->estado = 0;       
       $update->id_usuario_modifica=auth()->user()->id;
       $update->save();
   }

   public function activar(Request $request)
   {   
       $update = Egr_Inversion::findOrFail($request->id);
       $update->estado = 1;    
       $update->id_usuario_modifica=auth()->user()->id;
       $update->save();
   }
   
}
