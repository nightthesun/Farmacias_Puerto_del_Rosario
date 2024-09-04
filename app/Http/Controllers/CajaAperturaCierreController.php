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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::commit();
        
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
    }

  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_AperturaCierre $caja_AperturaCierre)
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

    public function cajaAnteriror(Request $request){
        $ultimoRegistro = DB::table('caja__apertura_cierres')
        ->select('turno_caja', 'tipo_caja_c_a', 'total_caja', 'estado_caja')
        ->where('id_sucursal', $request->id_sucursal)    
        ->orderBy('created_at', 'desc')
        ->first();
        if($ultimoRegistro==null){
          $ultimoRegistro=0;  
        }     
       return $ultimoRegistro;      
    }  
}
