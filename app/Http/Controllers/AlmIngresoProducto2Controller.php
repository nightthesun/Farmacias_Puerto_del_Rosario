<?php

namespace App\Http\Controllers;

use App\Models\Alm_IngresoProducto2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class AlmIngresoProducto2Controller extends Controller
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
    public function show(Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alm_IngresoProducto2 $alm_IngresoProducto2)
    {
        //
    }

   public function listaAlmacen(Request $request){
    $iduserrolesuc = session('iduserrolesuc');
    $idsuc = session('idsuc');
    $id_user2 = session('id_user2'); 
    $user_1 = auth()->user()->id;
    $user_2 = auth()->user()->name;
    
    if ($user_1 == 1 && $user_2 == 'admin') {
        // Uniendo ambas consultas para el administrador
      

        $resultadoConsulta = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(
                DB::raw('NULL as id_tienda'),
                'aa.id as id_almacen',
                'aa.codigo',
                'aa.nombre_almacen as razon_social',
                'ass.razon_social as sucursal',
                'ass.cod as codigoS',
                DB::raw('"Almacen" as tipoCodigo'),
                'aa.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)->get();

        
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
      
        
        $resultadoConsulta = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(
                DB::raw('NULL as id_tienda'),
                'aa.id as id_almacen',
                'aa.codigo',
                'aa.nombre_almacen as razon_social',
                'ass.razon_social as sucursal',
                'ass.cod as codigoS',
                DB::raw('"Almacen" as tipoCodigo'),
                'aa.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
            )
            ->where('ass.activo', 1)
            ->whereRaw($where1)->get();
        
       
        return $resultadoConsulta;
    }
   }
}
