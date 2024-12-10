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

        $bus = $request->id_sucursal;
        $ok="OK";
     
        if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id; 
            $where = "(cac.id_sucursal = '$bus' and cc.estado_caja <> '$ok' and ca.id_usuario = $user)";            
        } else {
            $where = "(cac.id_sucursal = '$bus' and cc.estado_caja <> '$ok')"; 
        }

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
                'cc.id_arqueo_mod',
                'cac.turno_caja',
                'cac.total_arqueo_caja as total_apertura',
                'cc_2.codigo',
                'cc_2.nombre_caja',
                'cc_2.moneda',
              
               'u.name','cc.estado_caja',
               'cm.id as id_mod_v2','cm.monto_dif as monto_v2',
               'cm.estado as estado_v2','cm.motivo as motivo_v2',
               'cm.id_usuario_registra','cm.id_usuario_modifica',
               'cm.numero_edicion', 
               DB::raw('GREATEST(cm.created_at, cm.updated_at) as fecha_mas_reciente')  
                ])
               // ->where('cc.estado_caja', '<>', 'OK')
               // ->where('cac.id_sucursal', $request->id_sucursal)
               ->whereRaw($where)
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
                'cc.id_arqueo_mod',
                'cac.turno_caja',
                'cac.total_arqueo_caja as total_apertura',
                'cc_2.codigo',
                'cc_2.nombre_caja',
                'cc_2.moneda',
               'u.name','cc.estado_caja', 
               'cm.id as id_mod_v2','cm.monto_dif as monto_v2',
               'cm.estado as estado_v2','cm.motivo as motivo_v2',
               'cm.id_usuario_registra','cm.id_usuario_modifica',
               'cm.numero_edicion',
               DB::raw('GREATEST(cm.created_at, cm.updated_at) as fecha_mas_reciente') 
            ])
              // ->where('cc.estado_caja', '<>', 'OK')
               // ->where('cac.id_sucursal', $request->id_sucursal)
               ->whereRaw($where)
     
            ->whereBetween(DB::raw('DATE(cc.created_at)'), [$ini, $fini])  
                      
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
           
            $datos = [
                'id_usuario' => auth()->user()->id,
                'total_arqueo' => $request->total_arqueo_caja,                       
                'cantidad_billete' => $request->cantidadBilletes,  
                'total_billete' => $request->totalBilletas, 
                'cantidad_moneda' => $request->cantidadMonedas, 
                'total_moneda' => $request->totalMonedas, 
                'tipo_moneda' => $request->moneda_s1                      
            ];    
           
            $id = DB::table('caja__arqueo')->insertGetId($datos);               
            foreach ($request->input as $key => $value) {                       
                $datos_2 = [                            
                    'id_arqueo' => $id,                       
                    'id_moneda' => $key,  
                    'cantidad' => $value                                              
                ];  
                DB::table('caja__arqueo_array')->insert($datos_2);
            }  

            $crear = new Caja_Modificacion();
            $crear->id_cierre=$request->id_cierre_modal;
            $crear->monto_dif=$request->diferencia_modal;
            $crear->estado=$request->estado_modal;
            $crear->motivo=$request->textArea_modal;   
            $crear->id_usuario_registra=auth()->user()->id;
            $crear->id_usuario_modifica=auth()->user()->id;
            $crear->save();
            $operacion_total_arqueo_caja=0;
            $operacion_diferencia_caja=0;
            $operacion_estado_caja=0;
            $query = DB::table('caja__cierre as cc')->where('cc.id', $request->id_cierre_modal)->first();
  
            if ($query) {
                if ($request->estado_modal=='Faltante') { 
                    $operacion_total_arqueo_caja=$query->total_arqueo_caja + $request->diferencia_modal;           
                    $operacion_diferencia_caja=$request->diferencia_modal - $query->diferencia_caja;
                    $operacion_estado_caja='Corregido';
                }
                if ($request->estado_modal=='Sobrante') {
                    $operacion_total_arqueo_caja=$query->total_arqueo_caja - $request->diferencia_modal;           
                    $operacion_diferencia_caja=$request->diferencia_modal - $query->diferencia_caja;
                    $operacion_estado_caja='Corregido';
                }
            } else {
                return "No se encontró ningún registro  la tabla cierre de caja.";
            }

          //  if ($operacion_diferencia_caja!=0){
          //      return "Error de diferencia contacte al administrador de base de datos.";
          //  }

            $datos_3 = [                            
                'total_arqueo_caja' => $operacion_total_arqueo_caja,                       
                'diferencia_caja' => $operacion_diferencia_caja,  
                'estado_caja' => $operacion_estado_caja,
                'id_arqueo_mod' => $id                                              
            ];    
          
            DB::table('caja__cierre as cc')
    ->where('cc.id', $request->id_cierre_modal)
    ->update($datos_3);

            DB::commit();
        } catch (\Throwable $th) {
        return $th;
        }       
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja_Modificacion $caja_Modificacion, Request $request)
    {
        try {            
            $query1 = DB::table('caja__arqueo_array as caa')
            ->join('caja__monedas as ca', 'ca.id', '=', 'caa.id_moneda')
            ->select('caa.cantidad', 'ca.tipo_corte', 'ca.valor', 'ca.unidad', 'ca.unidad_entera')
            ->where('caa.id_arqueo', $request->id_arqueo)
            ->get();
        
            $query2 = DB::table('caja__arqueo as ca')
            ->join('adm__nacionalidads as an', 'an.id', '=', 'ca.tipo_moneda')
            ->join('users as u', 'u.id', '=', 'ca.id_usuario')
            ->select('ca.id', 'ca.total_arqueo', 'ca.cantidad_billete', 'ca.total_billete', 'ca.cantidad_moneda', 
                'ca.total_moneda', 'an.simbolo', 'u.name')
            ->where('ca.id',  $request->id_arqueo)
            ->get();            
        
            return response()->json([                        
                'array_arqueo' => $query1,
                'arqueo' => $query2, 
            ]);

        } catch (\Throwable $th) {
           return $th;
        }
      


    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Modificacion $caja_Modificacion)
    {
        //
    }

}
