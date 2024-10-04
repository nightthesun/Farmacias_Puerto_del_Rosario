<?php

namespace App\Http\Controllers;

use App\Models\Dir_Distribuidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirDistribuidorController extends Controller
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
        if ($request->tipo==1) {
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
                                   )";
                        } else {
                            $sqls .= "and (
                            dc.nom_a_facturar like '%" . $valor . "%' 
                            or dc.num_documento     like '%" . $valor . "%'     
                           )";
                        }
                    }
                    //consulta
                    $distribuidor = DB::table('dir__distribuidors as dd')
                    ->select(
                        'dd.id',
                        'dd.contacto',
                        'dd.id_linea_array',
                        'dd.nom_linea_array',
                        'dd.estado',
                        DB::raw('GREATEST(dd.created_at, dd.updated_at) AS fecha_mas_reciente'),
                        'dc.correo',
                        'dc.nom_a_facturar',
                        'dc.num_documento',
                        'dc.telefono',
                        'u.name',
                        'dd.id_cliente'
                    )
                    ->join('dir__clientes as dc', 'dc.id', '=', 'dd.id_cliente')
                    ->join('dir__personas as dpe', 'dpe.id', '=', 'dc.id_per_emp')
                    ->join('users as u', DB::raw('COALESCE(dd.id_usuario_modifica, dd.id_usuario_registra)'), '=', 'u.id')
                    ->where('dd.tipo_persona_empresa', 1)
                    ->whereRaw($sqls)
        ->orderByDesc('dd.id')
        ->limit($limite)
        ->paginate(20);  
                }  
                return 
                    ['pagination' =>
                        [
                            'total'         =>    $distribuidor->total(),
                            'current_page'  =>    $distribuidor->currentPage(),
                            'per_page'      =>    $distribuidor->perPage(),
                            'last_page'     =>    $distribuidor->lastPage(),
                            'from'          =>    $distribuidor->firstItem(),
                            'to'            =>    $distribuidor->lastItem(),
                        ],
                        'distribuidor' => $distribuidor,
                    ];    
            }  else{
                $distribuidor = DB::table('dir__distribuidors as dd')
                    ->select(
                        'dd.id',
                        'dd.contacto',
                        'dd.id_linea_array',
                        'dd.nom_linea_array',
                        'dd.estado',
                        DB::raw('GREATEST(dd.created_at, dd.updated_at) AS fecha_mas_reciente'),
                        'dc.correo',
                        'dc.nom_a_facturar',
                        'dc.num_documento',
                        'dc.telefono',
                        'u.name',
                        'dd.id_cliente'
                    )
                    ->join('dir__clientes as dc', 'dc.id', '=', 'dd.id_cliente')
                    ->join('dir__personas as dpe', 'dpe.id', '=', 'dc.id_per_emp')
                    ->join('users as u', DB::raw('COALESCE(dd.id_usuario_modifica, dd.id_usuario_registra)'), '=', 'u.id')
                    ->where('dd.tipo_persona_empresa', 1)                    
        ->orderByDesc('dd.id')
        ->limit($limite)
        ->paginate(20);
        return 
        ['pagination' =>
            [
                'total'         =>    $distribuidor->total(),
                'current_page'  =>    $distribuidor->currentPage(),
                'per_page'      =>    $distribuidor->perPage(),
                'last_page'     =>    $distribuidor->lastPage(),
                'from'          =>    $distribuidor->firstItem(),
                'to'            =>    $distribuidor->lastItem(),
            ],
            'distribuidor' => $distribuidor,
        ];    
            }
        } else {
            /// empresa
            if ($request->tipo==2) {
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
                                       )";
                            } else {
                                $sqls .= "and (
                                dc.nom_a_facturar like '%" . $valor . "%' 
                                    or dc.num_documento     like '%" . $valor . "%'   
                               )";
                            }
                        }
                        //consulta
                        $distribuidor =  DB::table('dir__distribuidors as dd')
                        ->select(
                            'dd.id',
                            'dd.contacto',
                            'dd.id_linea_array',
                            'dd.nom_linea_array',
                            'dd.estado',
                            DB::raw('GREATEST(dd.created_at, dd.updated_at) AS fecha_mas_reciente'),
                            'dc.correo',
                            'dc.nom_a_facturar',
                            'dc.num_documento',
                            'dc.telefono',
                            'u.name',
                            'dd.id_cliente'
                        )
                        ->join('dir__clientes as dc', 'dc.id', '=', 'dd.id_cliente')
                        ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
                        ->join('users as u', DB::raw('COALESCE(dd.id_usuario_modifica, dd.id_usuario_registra)'), '=', 'u.id')
                        ->where('dd.tipo_persona_empresa', 2)
        ->whereRaw($sqls)
            ->orderByDesc('dd.id')
            ->limit($limite)
            ->paginate(20);  
                    }  
                    return 
                        ['pagination' =>
                            [
                                'total'         =>    $distribuidor->total(),
                                'current_page'  =>    $distribuidor->currentPage(),
                                'per_page'      =>    $distribuidor->perPage(),
                                'last_page'     =>    $distribuidor->lastPage(),
                                'from'          =>    $distribuidor->firstItem(),
                                'to'            =>    $distribuidor->lastItem(),
                            ],
                            'distribuidor' => $distribuidor,
                        ];    
                }  else{
                    $distribuidor =  DB::table('dir__distribuidors as dd')
                    ->select(
                        'dd.id',
                        'dd.contacto',
                        'dd.id_linea_array',
                        'dd.nom_linea_array',
                        'dd.estado',
                        DB::raw('GREATEST(dd.created_at, dd.updated_at) AS fecha_mas_reciente'),
                        'dc.correo',
                        'dc.nom_a_facturar',
                        'dc.num_documento',
                        'dc.telefono',
                        'u.name',
                        'dd.id_cliente'
                    )
                    ->join('dir__clientes as dc', 'dc.id', '=', 'dd.id_cliente')
                    ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
                    ->join('users as u', DB::raw('COALESCE(dd.id_usuario_modifica, dd.id_usuario_registra)'), '=', 'u.id')
                    ->where('dd.tipo_persona_empresa', 2)
   
        ->orderByDesc('dd.id')
        ->limit($limite)
        ->paginate(20); 
            return 
            ['pagination' =>
                [
                    'total'         =>    $distribuidor->total(),
                    'current_page'  =>    $distribuidor->currentPage(),
                    'per_page'      =>    $distribuidor->perPage(),
                    'last_page'     =>    $distribuidor->lastPage(),
                    'from'          =>    $distribuidor->firstItem(),
                    'to'            =>    $distribuidor->lastItem(),
                ],
                'distribuidor' => $distribuidor,
            ];    
                }
            }else{
                dd("error...");
            }

        }       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $crear = new Dir_Distribuidor();
            $crear->contacto=$request->contacto;
            $crear->id_cliente=$request->id_cliente;
            $crear->id_linea_array=$request->ids_linea;
            $crear->nom_linea_array=$request->linea_nom;            
            $crear->tipo_persona_empresa=$request->selectTipo;     
            $crear->id_usuario_registra=auth()->user()->id;      
            $crear->save();
        
            DB::commit();    
           } catch (\Throwable $th) {
            return $th;
           }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Distribuidor $dir_Distribuidor)
    {
   
        try {
            DB::beginTransaction();
            $e =Dir_Distribuidor::find($request->id_distribuidor);
            $e->contacto=$request->contacto;
            $e->id_cliente=$request->id_cliente;
            $e->id_linea_array=$request->ids_linea;
            $e->nom_linea_array=$request->linea_nom;     
            $e->tipo_persona_empresa=$request->selectTipo;      
            $e->id_usuario_modifica=auth()->user()->id;      
            $e->save();
           //return DB::commit();
            DB::commit();    
           } catch (\Throwable $th) {
            return $th;
           }
    }


    public function listarLinea(){
        $lineas = DB::table('prod__lineas')
            ->select('id', 'codigo',  DB::raw('UPPER(nombre) as nombre'))
            ->where('idrubro', 1)
            ->where('activo', 1)
            ->orderBy('id', 'desc')
            ->get();
        return $lineas;
    }

    public function desactivar(Request $request)
    {
        $update = Dir_Distribuidor::findOrFail($request->id);
        $update->estado = 0;       
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {   
        $update = Dir_Distribuidor::findOrFail($request->id);
        $update->estado = 1;    
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }
}
