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
    public function index()
    {
        //
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
        $crear->id_persona=$request->id_persona;
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
     * Display the specified resource.
     */
    public function show(Dir_Proveedor $dir_Proveedor)
    {
        //
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Proveedor $dir_Proveedor)
    {
        //
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
                ->get();
                return $clientes;
            }else{
                dd("error...");
            }
        }
    }

    
}
