<?php

namespace App\Http\Controllers;

use App\Models\Caja_Creacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaCreacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                cc.nombre_caja like '%" . $valor . "%' 
                                or cc.codigo like '%" . $valor . "%'                                                         
                               )";
                    } else {
                        $sqls .= "and (cc.nombre_caja like '%" . $valor . "%' 
                        or cc.codigo like '%" . $valor . "%'          
                       )";
                    }
                }
                //cosnsulta---
                $resultado = DB::table('caja__creacions as cc')
                ->join('users as u', 'u.id', '=', 'cc.id_usuario_modifica')
                ->select(
                    'cc.id',
                    'cc.codigo',
                    'cc.nombre_caja',
                    'cc.id_sucursal',
                    'cc.id_users as array_user',
                    'cc.monto_caja',
                    'cc.moneda',
                    'cc.estado',
                    DB::raw('GREATEST(cc.created_at, cc.updated_at) as fecha_mas_reciente'),
                    'u.name'
                )
                ->whereRaw($sqls) 
                ->where('cc.id_sucursal', $request->id_sucursal)
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
            $resultado = DB::table('caja__creacions as cc')
                ->join('users as u', 'u.id', '=', 'cc.id_usuario_modifica')
                ->select(
                    'cc.id',
                    'cc.codigo',
                    'cc.nombre_caja',
                    'cc.id_sucursal',
                    'cc.id_users as array_user',
                    'cc.monto_caja',
                    'cc.moneda',
                    'cc.estado',
                    DB::raw('GREATEST(cc.created_at, cc.updated_at) as fecha_mas_reciente'),
                    'u.name'
                )             
                ->where('cc.id_sucursal', $request->id_sucursal)
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
            $ultimoId = DB::table('caja__creacions')->where('id_sucursal',$request->id_sucursal)->latest('id')->value('id');
            $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $indiceAleatorio = random_int(0, strlen($caracteres) - 1);
            $codigo="";
           
            if (is_null($ultimoId)) {
                $codigo="caja-0"."-".$indiceAleatorio;
            } else {
                $codigo="caja-".$ultimoId."-".$indiceAleatorio;            
            }
            $moneda_v1 = DB::table('adm__credecial_correos as acc')
            ->join('adm__nacionalidads as an', 'an.id', '=', 'acc.moneda')
            ->select('an.simbolo')
            ->limit(1)
            ->value('simbolo');           
            $cadena = implode(',', $request->array_id_v);
            $crear = new Caja_Creacion();
            $crear->codigo=$codigo;
            $crear->nombre_caja=$request->nombre_caja;
            $crear->id_sucursal=$request->id_sucursal;
            $crear->id_users=$cadena;
            $crear->monto_caja=$request->monto_caja;
            $crear->moneda=$moneda_v1;
            $crear->estado=1;
            $crear->id_usuario_registra=auth()->user()->id;
            $crear->id_usuario_modifica=auth()->user()->id;
            $crear->save();
            DB::commit();
            
        } catch (\Throwable $th) {
            //throw $th;
           return $th; 
        }
    }

    public function desactivar(Request $request)
    {
        $update = Caja_Creacion::findOrFail($request->id);
       
        $update->estado = 0;       
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }
 
    public function activar(Request $request)
    {   
        $update = Caja_Creacion::findOrFail($request->id);
    
        $update->estado = 1;    
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Creacion $caja_Creacion)
    {
        $cadena = implode(',', $request->array_id_v);
        $actualizar = Caja_Creacion::findOrFail($request->id);   
        $actualizar->id_usuario_modifica=auth()->user()->id;    
        $actualizar->nombre_caja=$request->nombre_caja; 
        $actualizar->id_users=$cadena;
        $actualizar->monto_caja=$request->monto_caja;
        $actualizar->save();
    }
}
