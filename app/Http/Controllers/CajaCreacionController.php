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
                                u.name like '%" . $valor . "%' 
                                or cac.estado_caja like '%" . $valor . "%' 
                            or cac.id like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (u.name like '%" . $valor . "%' 
                        or cac.estado_caja like '%" . $valor . "%' 
                       or cac.id like '%" . $valor . "%' 
                       )";
                    }
                }
                //cosnsulta---
                $result = DB::table('caja__creacions as cc')
                ->where('cc.id_sucursal', 1)
                ->get();
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
            $crear->save();
            DB::commit();
            
        } catch (\Throwable $th) {
            //throw $th;
           return $th; 
        }
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Creacion $caja_Creacion)
    {
        //
    }
}
