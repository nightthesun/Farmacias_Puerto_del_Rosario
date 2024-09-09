<?php

namespace App\Http\Controllers;

use App\Models\Caja_EntradaSalida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaEntradaSalidaController extends Controller
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
                    'id_entrada_salida' => $id,                       
                    'id_moneda' => $key,  
                    'cantidad' => $value                                              
                ];  
                DB::table('caja__entrada_salida_array')->insert($datos_2);
            } 

                $entrada_salida=new Caja_EntradaSalida();             
                $entrada_salida->id_sucursal= $request->id_sucursal;
                $entrada_salida->id_arqueo= $id;
                $entrada_salida->valor= $request->total_arqueo_caja;
                $entrada_salida->observacion= $request->obs;           
                $entrada_salida->mensaje= $request->mensaje;
                $entrada_salida->entrada_salida= $request->entrada_salida;                
                $entrada_salida->id_apertura_cierre= $request->id_apertura_cierre;
                $entrada_salida->save();
            DB::commit();
            return DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }
    }
}