<?php

namespace App\Http\Controllers;

use App\Models\Caja_AperturaCierre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaAperturaCierreController extends Controller
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
    public function show(Caja_AperturaCierre $caja_AperturaCierre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Caja_AperturaCierre $caja_AperturaCierre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_AperturaCierre $caja_AperturaCierre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja_AperturaCierre $caja_AperturaCierre)
    {
        //
    }

    public function verificador_moneda_sistemas(Request $request){
        
        $moneda = DB::table('adm__credecial_correos')
        ->where('id', 1)
        ->value('moneda');

// Asigna directamente el valor de $moneda a $data_1
$data_1 = $moneda;
        if ($data_1==0||$data_1==null||$data_1=="") { 
                      
            return response()->json([                        
                'moneda' => 0,
                'listaMoneda' => null, 
            ]);
        }else {
         
            $monedas_2 =  DB::table('caja__monedas')
            ->select('id', 'tipo_corte', 'valor', 'unidad', 'unidad_entera', DB::raw('0.00 AS valor_default'),DB::raw('0 AS input'))
            ->where('id_nacionalidad_pais', 1)
            ->where('activo', 1)
            ->get();
           
            return response()->json([                        
                'moneda' => $moneda,
                'listaMoneda' => $monedas_2, 
            ]);
        }
    }
  
}
