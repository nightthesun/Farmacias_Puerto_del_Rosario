<?php

namespace App\Http\Controllers;

use App\Models\Dir_Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray = array();
        $limite=$request->limite;
        if ($limite==0) {
            $limite=999999999;
        }
        ///persona
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
                    $proveedores = DB::table('dir__proveedors as dp')
    ->join('dir__clientes as dc', 'dc.id', '=', 'dp.id_cliente')
    ->join('dir__personas as dpe', 'dpe.id', '=', 'dc.id_per_emp')
    ->join('users as u', function($join) {
        $join->on('u.id', '=', DB::raw('COALESCE(dp.id_usuario_modifica, dp.id_usuario_registra)'));
    })
    ->select(
        'dp.id',
        'dc.id as id_cliente',
        'dc.num_documento',
        'dp.datos_adicionales',
        'dp.estado',
        DB::raw('GREATEST(dp.created_at, dp.updated_at) as fecha_mas_reciente'),
        'dc.correo',
        'dc.nom_a_facturar',
        DB::raw("CONCAT(
            COALESCE(dc.pais, ''),
            ' ',
            COALESCE(dc.ciudad, ''),
            ' ',
            COALESCE(dc.direccion, '')
        ) as ubicacion"),
        'dc.telefono',
        DB::raw("CONCAT(COALESCE(dpe.nombres, ''), ' ', COALESCE(dpe.apellidos, '')) as name_all"),
        'u.name'
    )
    ->where('dp.tipo_persona_empresa', 1)
    ->whereRaw($sqls)
        ->orderByDesc('dp.id')
        ->limit($limite)
        ->paginate(20);  
                }  
                return 
                    ['pagination' =>
                        [
                            'total'         =>    $proveedores->total(),
                            'current_page'  =>    $proveedores->currentPage(),
                            'per_page'      =>    $proveedores->perPage(),
                            'last_page'     =>    $proveedores->lastPage(),
                            'from'          =>    $proveedores->firstItem(),
                            'to'            =>    $proveedores->lastItem(),
                        ],
                        'proveedores' => $proveedores,
                    ];    
            }  else{
                $proveedores = DB::table('dir__proveedors as dp')
    ->join('dir__clientes as dc', 'dc.id', '=', 'dp.id_cliente')
    ->join('dir__personas as dpe', 'dpe.id', '=', 'dc.id_per_emp')
    ->join('users as u', function($join) {
        $join->on('u.id', '=', DB::raw('COALESCE(dp.id_usuario_modifica, dp.id_usuario_registra)'));
    })
    ->select(
        'dp.id',
        'dc.id as id_cliente',
        'dc.num_documento',
        'dp.datos_adicionales',
        'dp.estado',
        DB::raw('GREATEST(dp.created_at, dp.updated_at) as fecha_mas_reciente'),
        'dc.correo',
        'dc.nom_a_facturar',
        DB::raw("CONCAT(
            COALESCE(dc.pais, ''),
            ' ',
            COALESCE(dc.ciudad, ''),
            ' ',
            COALESCE(dc.direccion, '')
        ) as ubicacion"),
        'dc.telefono',
        DB::raw("CONCAT(COALESCE(dpe.nombres, ''), ' ', COALESCE(dpe.apellidos, '')) as name_all"),
        'u.name'
    )
    ->where('dp.tipo_persona_empresa', 1) 
        ->orderByDesc('dp.id')
        ->limit($limite)
        ->paginate(20);
        return 
        ['pagination' =>
            [
                'total'         =>    $proveedores->total(),
                'current_page'  =>    $proveedores->currentPage(),
                'per_page'      =>    $proveedores->perPage(),
                'last_page'     =>    $proveedores->lastPage(),
                'from'          =>    $proveedores->firstItem(),
                'to'            =>    $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores,
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
                        $proveedores =  DB::table('dir__proveedors as dp')
                        ->join('dir__clientes as dc', 'dc.id', '=', 'dp.id_cliente')
                        ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
                        ->join('users as u', function($join) {
                            $join->on('u.id', '=', DB::raw('COALESCE(dp.id_usuario_modifica, dp.id_usuario_registra)'));
                        })
                        ->select(
                            'dp.id',
                            'dc.id as id_cliente',
                            'dc.num_documento',
                            'dp.datos_adicionales',
                            'dp.estado',
                            DB::raw('GREATEST(dp.created_at, dp.updated_at) as fecha_mas_reciente'),
                            'dc.correo',
                            'dc.nom_a_facturar',
                            DB::raw("CONCAT(
                                COALESCE(dc.pais, ''),
                                ' ',
                                COALESCE(dc.ciudad, ''),
                                ' ',
                                COALESCE(dc.direccion, '')
                            ) as ubicacion"),
                            'dc.telefono',
                            'de.razon_social as name_all',
                            'u.name'
                        )
                        ->where('dp.tipo_persona_empresa', 2)
        ->whereRaw($sqls)
            ->orderByDesc('dp.id')
            ->limit($limite)
            ->paginate(20);  
                    }  
                    return 
                        ['pagination' =>
                            [
                                'total'         =>    $proveedores->total(),
                                'current_page'  =>    $proveedores->currentPage(),
                                'per_page'      =>    $proveedores->perPage(),
                                'last_page'     =>    $proveedores->lastPage(),
                                'from'          =>    $proveedores->firstItem(),
                                'to'            =>    $proveedores->lastItem(),
                            ],
                            'proveedores' => $proveedores,
                        ];    
                }  else{
                    $proveedores =  DB::table('dir__proveedors as dp')
                    ->join('dir__clientes as dc', 'dc.id', '=', 'dp.id_cliente')
                    ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
                    ->join('users as u', function($join) {
                        $join->on('u.id', '=', DB::raw('COALESCE(dp.id_usuario_modifica, dp.id_usuario_registra)'));
                    })
                    ->select(
                        'dp.id',
                        'dc.id as id_cliente',
                        'dc.num_documento',
                        'dp.datos_adicionales',
                        'dp.estado',
                        DB::raw('GREATEST(dp.created_at, dp.updated_at) as fecha_mas_reciente'),
                        'dc.correo',
                        'dc.nom_a_facturar',
                        DB::raw("CONCAT(
                            COALESCE(dc.pais, ''),
                            ' ',
                            COALESCE(dc.ciudad, ''),
                            ' ',
                            COALESCE(dc.direccion, '')
                        ) as ubicacion"),
                        'dc.telefono',
                        'de.razon_social as name_all',
                        'u.name'
                    )
                    ->where('dp.tipo_persona_empresa', 2)
   
        ->orderByDesc('dp.id')
        ->limit($limite)
        ->paginate(20); 
            return 
            ['pagination' =>
                [
                    'total'         =>    $proveedores->total(),
                    'current_page'  =>    $proveedores->currentPage(),
                    'per_page'      =>    $proveedores->perPage(),
                    'last_page'     =>    $proveedores->lastPage(),
                    'from'          =>    $proveedores->firstItem(),
                    'to'            =>    $proveedores->lastItem(),
                ],
                'proveedores' => $proveedores,
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
        $crear = new Dir_Proveedor();
        $crear->datos_adicionales=$request->datos_adicionales;
        $crear->id_cliente=$request->id_cliente;
        $crear->tipo_persona_empresa=$request->selectTipo;      
        $crear->id_usuario_registra=auth()->user()->id;      
        $crear->save();
      //  return DB::commit();
        DB::commit();    
       } catch (\Throwable $th) {
        return $th;
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Proveedor $dir_Proveedor)
    {
        try {
            DB::beginTransaction();
            $e =Dir_Proveedor::find($request->id_transaccion);
            $e->datos_adicionales=$request->datos_adicionales;
            $e->id_cliente=$request->id_cliente;
            $e->tipo_persona_empresa=$request->selectTipo;      
            $e->id_usuario_modifica=auth()->user()->id;      
            $e->save();
          //  return DB::commit();
            DB::commit();    
           } catch (\Throwable $th) {
            return $th;
           }
    }

    public function cliente(Request $request){

        if ($request->tipo_per_emp==1) {
            $clientes = DB::table('dir__clientes as dc')
            ->join('dir__personas as dp', 'dp.id', '=', 'dc.id_per_emp')
            ->select(
                'dc.id',
                'dc.telefono',
                'dc.direccion',
                'dc.nom_a_facturar',
                'dc.pais',
                'dc.ciudad',
                'dc.num_documento',
                DB::raw("CONCAT(COALESCE(dp.nombres, ''), ' ', COALESCE(dp.apellidos, '')) as name_all"),
                'dp.documento_identidad as dato',
                'dp.complemento as doc'
            )
            ->where('dc.tipo_per_emp', 1)
            ->where('dc.activo', 1)
            ->orderByDesc('dc.id')
            ->get();
            return $clientes;
        }
        else{
            if ($request->tipo_per_emp==2) {
                $clientes = DB::table('dir__clientes as dc')
                ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
                ->select(
                    'dc.id',
                    'dc.telefono',
                    'dc.direccion',
                    'dc.nom_a_facturar',
                    'dc.pais',
                    'dc.ciudad',
                    'dc.num_documento',
                    'de.razon_social as name_all',
                    'de.nit as dato',
                    DB::raw("'Emp' as doc")
                )
                ->where('dc.tipo_per_emp', 2)
                ->where('dc.activo', 1)
                ->orderByDesc('dc.id')
                ->get();
                return $clientes;
            }else{
                dd("error...");
            }
        }
    }

    public function desactivar(Request $request)
    {
        $update = Dir_Proveedor::findOrFail($request->id);
        $update->estado = 0;       
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {   
        $update = Dir_Proveedor::findOrFail($request->id);
        $update->estado = 1;    
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }
}
