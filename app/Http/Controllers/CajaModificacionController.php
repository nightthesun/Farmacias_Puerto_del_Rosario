<?php

namespace App\Http\Controllers;

use App\Models\Caja_Modificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaModificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ini=$request->ini;
        $fini=$request->fini;

        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                cc_2.codigo like '%" . $valor . "%' 
                                or u.name like '%" . $valor . "%'
                                or cc_2.nombre_caja like '%" . $valor . "%' 
                                               
                               )";
                    } else {
                        $sqls .= "and (cc_2.codigo like '%" . $valor . "%' 
                        or u.name like '%" . $valor . "%'
                        or cc_2.nombre_caja like '%" . $valor . "%' 
             
                       )";
                    }
                }
                //consulta 
                $resultado = DB::table('caja__cierre as cc')
                ->join('caja__arqueo as ca', 'ca.id', '=', 'cc.id_arqueo')
                ->join('caja__apertura_cierres as cac', 'cc.id_apertura', '=', 'cac.id')
                ->join('caja__creacions as cc_2', 'cc_2.id', '=', 'cac.id_caja')
                ->join('users as u', 'u.id', '=', 'ca.id_usuario')
                ->leftJoin('caja__modificacions as cm', 'cm.id_cierre','=','cc.id')
                ->select([
                    'cc.id',
                'cc.id_apertura',
                'cc.id_arqueo',
                'cc.total_caja as total_cierre',
                'cc.diferencia_caja',
                'cc.estado_caja',
                'cc.created_at',
                'cac.turno_caja',
                'cac.total_arqueo_caja as total_apertura',
                'cc_2.codigo',
                'cc_2.nombre_caja',
                'cc_2.moneda',
               'u.name','cc.estado_caja','cm.id as id_mod_v2'
                  
                ])
                ->where('cc.estado_caja', '<>', 'OK')
                ->where('cac.id_sucursal', $request->id_sucursal)
                ->whereRaw($sqls)               
                ->orderByDesc('cc.id')
           
                ->paginate(15);    
            }
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ];      
        } else {
            $resultado = DB::table('caja__cierre as cc')
            ->join('caja__arqueo as ca', 'ca.id', '=', 'cc.id_arqueo')
            ->join('caja__apertura_cierres as cac', 'cc.id_apertura', '=', 'cac.id')
            ->join('caja__creacions as cc_2', 'cc_2.id', '=', 'cac.id_caja')
            ->join('users as u', 'u.id', '=', 'ca.id_usuario')
            ->leftJoin('caja__modificacions as cm', 'cm.id_cierre','=','cc.id')           
            ->select([
                'cc.id',
                'cc.id_apertura',
                'cc.id_arqueo',
                'cc.total_caja as total_cierre',
                'cc.diferencia_caja',
                'cc.estado_caja',
                'cc.created_at',
                'cac.turno_caja',
                'cac.total_arqueo_caja as total_apertura',
                'cc_2.codigo',
                'cc_2.nombre_caja',
                'cc_2.moneda',
               'u.name','cc.estado_caja','cm.id as id_mod_v2'
            ])
            ->where('cc.estado_caja', '<>', 'OK')
            ->whereBetween(DB::raw('DATE(cc.created_at)'), [$ini, $fini])  
            ->where('cac.id_sucursal', $request->id_sucursal)                         
            ->orderByDesc('cc.id')       
            ->paginate(15); 
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ];    
        }
     
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $crear = new Caja_Modificacion();
            $crear->id_cierre=$request->id_cierre_modal;
            $crear->monto_dif=$request->diferencia_modal;
            $crear->estado=$request->estado_modal;
            $crear->motivo=$request->textArea_modal;   
            $crear->id_usuario_registra->auth()->user()->id;
            $crear->id_usuario_modifica->auth()->user()->id;
            DB::commit();
        } catch (\Throwable $th) {
        return $th;
        }       
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja_Modificacion $caja_Modificacion)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Modificacion $caja_Modificacion)
    {
        //
    }

}
