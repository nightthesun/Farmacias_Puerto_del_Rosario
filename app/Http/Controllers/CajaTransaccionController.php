<?php

namespace App\Http\Controllers;

use App\Models\Caja_Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaTransaccionController extends Controller
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caja_Transaccion $caja_Transaccion)
    {
        //
    }

    public function get_cuenta_salida(Request $request){
        $user=auth()->user()->id;
        $cuentasBancos = DB::table('adm__cuenta_banco as acb')
        ->join('adm__bancos as ab', 'ab.id', '=', 'acb.id_banco')
        ->select('acb.id', 'acb.nombre as nom_cuenta', 'acb.nro_cuenta', 'ab.nombre as nom_banco')
        ->where('acb.estado', 1)
        ->get();       

    
        $cajaEntradasSalidas = DB::table('caja__entrada_salidas as ces')
    ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
    ->join('adm__nacionalidads as na', 'na.id', '=', 'ca.tipo_moneda')
    ->select('ces.id', 'ces.id_arqueo', 'ces.valor', 'ces.observacion', 'ces.mensaje', 'na.simbolo')
    ->where('ces.entrada_salida', 2)
    ->where('ca.id_usuario', $user)
    ->where('ces.transaccion', 0)
    ->where('ces.id_sucursal', $request->id_sucursal)
    ->get();
        return response()->json([                      
            'cuentasBancos' => $cuentasBancos,   
            'cajaEntradasSalidas' => $cajaEntradasSalidas
         ]);
    }     
}
