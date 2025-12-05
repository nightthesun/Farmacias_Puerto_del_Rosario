<?php

namespace App\Http\Controllers;

use App\HelperServices\demandaDiariaEcu;
use App\HelperServices\gestionStock;
use App\Models\Inv_Auto_ttr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvAutoTtrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getGoToRunOperacionAuto(Request $request){

        $id_producto=30;        
        $tiempo_compra_1=1;
        $tiempo_compra_2=2;
        $tiempo_traspaso_1=0;
        $tiempo_traspaso_2=0;
        $tiempo_traslado_1=0;
        $tiempo_traslado_2=0;
        $tiempo_recepcion_1=0; 
        $tiempo_recepcion_2=0;

        $inicio_ecuacionDemandaDiaria=demandaDiariaEcu::inicio_ecuacionDemandaDiaria(
            $id_producto,
    $tiempo_compra_1, $tiempo_compra_2,
    $tiempo_traspaso_1, $tiempo_traspaso_2,
    $tiempo_traslado_1, $tiempo_traslado_2,
    $tiempo_recepcion_1, $tiempo_recepcion_2
        );

$inicio_gestionStock=gestionStock::getGestorStockModal(0,1);
        dd($inicio_gestionStock);
        
$demandaDiaria = round($inicio_ecuacionDemandaDiaria, 2); //
        dd($demandaDiaria);

        


    }
    

    public function getTablaConfig_tg(){        
        try {
             $configTraspaso = DB::table('log__config_traspaso')
        ->where('id', 1)
        ->first();

        $configGestion = DB::table('log__config_gestion_stock')
        ->where('id', 1)
        ->first();

        return response()->json(['configTraspaso' => $configTraspaso,'configGestion'=>$configGestion]);
        } catch (\Throwable $th) {
            return $th;
        }       
    }
   
}
