<?php

namespace App\Http\Controllers;

use App\Models\Tda_ingresoProducto2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TdaIngresoProducto2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function update(Request $request, Tda_ingresoProducto2 $tda_ingresoProducto2)
    {
        //
    }
    public function listaTienda(Request $request){
        $iduserrolesuc = session('iduserrolesuc');
        $idsuc = session('idsuc');
        $id_user2 = session('id_user2'); 
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        
        if ($user_1 == 1 && $user_2 == 'admin') {
            // Uniendo ambas consultas para el administrador
          
    
            $resultadoConsulta = DB::table('tda__tiendas as tt')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
    ->select(
        'tt.id AS id_tienda',
        DB::raw('NULL AS id_almacen'),
        'tt.codigo',
        'ass.razon_social AS razon_social',
        'ass.nombre_comercial AS sucursal',
        'ass.cod AS codigoS',
        DB::raw("'Tienda' AS tipoCodigo"),
        'tt.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
    )
    ->where('ass.activo', 1)
    ->get();
    
            
            return $resultadoConsulta;
        } else {
            // Consultas para otros usuarios
            $asignaciones = DB::table('adm__asig_mas_sucursales')
                ->where('id_user_role_sucu', $id_user2)
                ->get();
            
            $codigos = [];
            $where1 = '';
            $where2 = '';
            
            if ($asignaciones->isNotEmpty()) {
                foreach ($asignaciones as $value) {
                    $codigos[] = "'" . $value->cod . "'";
                }
                $where1 = 'aa.codigo IN (' . implode(',', $codigos) . ')';
                $where2 = 'tda__tiendas.codigo IN (' . implode(',', $codigos) . ')';
            } else {
                $where1 = 'ass.id = ' . $idsuc;
                $where2 = 'ass.id = ' . $idsuc;
            }
          
            
            $resultadoConsulta = DB::table('tda__tiendas as tt')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->select(
                'tt.id AS id_tienda',
                DB::raw('NULL AS id_almacen'),
                'tt.codigo',
                'ass.nombre_comercial AS razon_social',
                'ass.razon_social AS sucursal',
                'ass.cod AS codigoS',
                DB::raw("'Tienda' AS tipoCodigo"),
                'tt.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)            
                ->whereRaw($where1)->get();
            
           
            return $resultadoConsulta;
        }
       }

}
