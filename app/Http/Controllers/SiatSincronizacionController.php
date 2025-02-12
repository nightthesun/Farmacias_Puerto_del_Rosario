<?php

namespace App\Http\Controllers;

use App\Models\Siat_Sincronizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatSincronizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function activarModo(Request $request){
        try {
            DB::beginTransaction();
            if ($request->selectAutoManual==1||$request->selectAutoManual==2) {
                # code...
            }
            $datos = [
                'sincro_auto_manual' => $request->selectAutoManual,                
            ];        
            DB::table('siat__configuracions')
            ->where('id', 1) // Primero se filtra el registro
            ->update($datos); // Luego se actualiza       
    
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
}
