<?php

namespace App\Http\Controllers;

use App\Models\Ven_prospecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenProspectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
         if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
             if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                               pp.nombre like '%" . $valor . "%' 
                                or pl.nombre like '%" . $valor . "%'  
                                or pp.codigo like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (pp.nombre '%" . $valor . "%' 
                         or pl.nombre like '%" . $valor . "%'  
                        or pp.codigo like  '%" . $valor . "%' 
                      )";
                    }
                }
                $prospectos = DB::table('ven_prospectos as vp')
    ->join('users as u', 'u.id', '=', 'vp.id_usuario')
    ->join('prod__productos as pp', 'pp.id', '=', 'vp.id_producto')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'vp.id_sucursal')
    ->select(
        'vp.id',
        'u.name as nom_user',
        'pl.nombre as nom_linea',
        'pp.nombre as nom_prod',
        'pp.id as id_producto',
        'pp.codigo as codigoProducto',
        'vp.envase',
        'vp.descripcion',
        'vp.activo',
        'ass.id as id_sucursal',
        'ass.razon_social',
        'vp.updated_at'
    )
    ->where('vp.id_sucursal', $request->id_sucursal)
    ->whereRaw($sqls)
   // ->whereBetween(DB::raw('DATE(vp.created_at)'), [$startDate, $endDate])
    ->orderBy('vp.id', 'desc')
    ->paginate(15);

             }    
               return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $prospectos->total(),
                            'current_page'  =>    $prospectos->currentPage(),
                            'per_page'      =>    $prospectos->perPage(),
                            'last_page'     =>    $prospectos->lastPage(),
                            'from'          =>    $prospectos->firstItem(),
                            'to'            =>    $prospectos->lastItem(),
                        ] ,
                    'prospectos'=>$prospectos,
            ];
         } else {
  $prospectos = DB::table('ven_prospectos as vp')
    ->join('users as u', 'u.id', '=', 'vp.id_usuario')
    ->join('prod__productos as pp', 'pp.id', '=', 'vp.id_producto')
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'vp.id_sucursal')
    ->select(
        'vp.id',
        'u.name as nom_user',
        'pl.nombre as nom_linea',
        'pp.nombre as nom_prod',
        'pp.id as id_producto',
        'pp.codigo as codigoProducto',
        'vp.envase',
        'vp.descripcion',
        'vp.activo',
        'ass.id as id_sucursal',
        'ass.razon_social',
        'vp.updated_at'
    )
    ->where('vp.id_sucursal', $request->id_sucursal)
   // ->whereRaw($sqls)
    ->whereBetween(DB::raw('DATE(vp.created_at)'), [$startDate, $endDate])
    ->orderBy('vp.id', 'desc')
    ->paginate(15);
     return 
    [
            'pagination'=>
                [
                    'total'         =>    $prospectos->total(),
                    'current_page'  =>    $prospectos->currentPage(),
                    'per_page'      =>    $prospectos->perPage(),
                    'last_page'     =>    $prospectos->lastPage(),
                    'from'          =>    $prospectos->firstItem(),
                    'to'            =>    $prospectos->lastItem(),
                ] ,
            'prospectos'=>$prospectos,
    ];
         }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
         DB::beginTransaction();         
            $id_user=auth()->user()->id;
            $nuevoItem = new Ven_prospecto();
            $nuevoItem->id_usuario=$id_user;
            $nuevoItem->id_producto=$request->id_producto;
            $nuevoItem->envase=$request->envase;
            $nuevoItem->descripcion=$request->descripcion;  
            $nuevoItem->id_sucursal=$request->id_sucursal;
        
            $nuevoItem->save();
            DB::commit(); 
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ven_prospecto $ven_prospecto)
    {
        try {
            DB::beginTransaction(); 
            $id_user=auth()->user()->id;
           $update=Ven_prospecto::find($request->id);
           $update->descripcion=$request->descripcion;
           $update->id_usuario=$id_user; 
           $update->save();
            DB::commit();    
        } catch (\Throwable $th) {
            return $th;
        }          
    }

      public function desactivar(Request $request)
    {         
        $update = Ven_prospecto::findOrFail($request->id);
        $update->activo = 0;           
        $update->save();                    
    }

    public function activar(Request $request)
    {
        $update = Ven_prospecto::findOrFail($request->id);
        $update->activo = 1;           
        $update->save();         
    }

    
}
