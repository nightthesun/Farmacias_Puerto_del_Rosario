<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatCuisCufdControlador extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = DB::table('siat__sucursals as ss')
        ->leftJoin('siat__cuis as sc', 'ss.id_cuis', '=', 'sc.id')
        ->leftJoin('siat__cufd as sf', 'ss.id_cufd', '=', 'sf.id')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'ss.id_sucursal')
        ->select(
            'ss.id',
            'ss.nombre_suc_siat',
            'ss.codigo_siat',
            'ss.id_sucursal',
            'sc.id as id_cuis',
            'sc.dato as cuis',
            'sc.fecha_vigencia as fecha_v_cuis',
            'sc.estado as estado_cuis',
            'sf.id as id_cufd',
            'sf.dato as cufd',
            'sf.fecha_vigencia as fecha_v_cufd',
            'sf.estado as estado_cufd',
            'ass.id as id_adm_sucursal',
            'ass.razon_social',
            'ass.direccion'
        )
        ->where('ss.estado', 1)
        ->paginate(15);
        return 
        [
                'pagination'=>
                    [
                        'total'         =>    $index->total(),
                        'current_page'  =>    $index->currentPage(),
                        'per_page'      =>    $index->perPage(),
                        'last_page'     =>    $index->lastPage(),
                        'from'          =>    $index->firstItem(),
                        'to'            =>    $index->lastItem(),
                    ] ,
                'index'=>$index,
        ];

    }

    public function siat_fig(){
        $siatConfiguracion = DB::table('siat__configuracions')->where('id', 1)->first();
        return $siatConfiguracion;
    } 




    
}
