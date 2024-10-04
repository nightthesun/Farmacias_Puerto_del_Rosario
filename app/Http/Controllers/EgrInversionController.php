<?php

namespace App\Http\Controllers;

use App\Models\Egr_Inversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgrInversionController extends Controller
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
    public function update(Request $request, Egr_Inversion $egr_Inversion)
    {
        //
    }

   public function listarDistribuidor(){
    $distriibuidor =  DB::table('dir__distribuidors as dd')
    ->join('dir__clientes as dc', 'dd.id_cliente', '=', 'dc.id')
    ->leftJoin('dir__empresas as de', function($join) {
        $join->on('de.id', '=', 'dc.id_per_emp')
             ->where('dd.tipo_persona_empresa', '=', 2);
    })
    ->leftJoin('dir__personas as dp', function($join) {
        $join->on('dp.id', '=', 'dc.id_per_emp')
             ->where('dd.tipo_persona_empresa', '=', 1);
    })
    ->where('dd.estado', 1)  // Condición WHERE
    ->select('dd.id', 'dd.contacto', 'dd.nom_linea_array', 'dc.nom_a_facturar', 'dc.num_documento', 'dd.tipo_persona_empresa')
    ->selectRaw("
        CASE 
            WHEN de.id IS NOT NULL THEN UPPER(COALESCE(de.razon_social, ''))
            WHEN dp.id IS NOT NULL THEN UPPER(COALESCE(CONCAT(COALESCE(dp.nombres, ''), ' ', COALESCE(dp.apellidos, '')), ''))
            ELSE NULL 
        END AS nombre_1
    ")
    ->get();

    $moneda = DB::table('adm__credecial_correos as acc')
    ->join('adm__nacionalidads as an', 'acc.moneda', '=', 'an.id')
    ->select('acc.moneda', 'an.simbolo')
    ->get();

    return response()->json([
         'distriibuidor' => $distriibuidor,
         'moneda' => $moneda 
     ]);
 
   }
}
