<?php

namespace App\Http\Controllers;

use App\Models\Siat_Homologacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatHomologacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                p.codigo like '%" . $valor . "%' 
                                or p.nombre like '%" . $valor . "%' 
                            or l.nombre like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (p.codigolike '%" . $valor . "%' 
                        or p.nombre like '%" . $valor . "%' 
                       or l.nombre like '%" . $valor . "%')";
                    }
                }
                //query
                $resultado = DB::table('prod__productos as p')
    ->select('p.id', 'p.codigo', 'p.nombre', 'p.codigoActividad', 'p.codigoProducto', 'e.descripcion', 'l.nombre as linea')
    ->join('excel__emision as e', function($join) {
        $join->on('p.codigoActividad', '=', 'e.id_erp')
             ->on('p.codigoProducto', '=', 'e.codigo');
    })
    ->join('prod__lineas as l', 'l.id', '=', 'p.idlinea')
    ->where('e.id_catalogo', '=', 16)
    ->whereRaw($sqls)
    ->paginate(10); 
            } 
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
            ];
        }else{
            $resultado = DB::table('prod__productos as p')
            ->select('p.id', 'p.codigo', 'p.nombre', 'p.codigoActividad', 'p.codigoProducto', 'e.descripcion', 'l.nombre as linea')
            ->join('excel__emision as e', function($join) {
                $join->on('p.codigoActividad', '=', 'e.id_erp')
                     ->on('p.codigoProducto', '=', 'e.codigo');
            })
            ->join('prod__lineas as l', 'l.id', '=', 'p.idlinea')
            ->where('e.id_catalogo', '=', 16)          
            ->paginate(10);  
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $resultado->total(),
                            'current_page'  =>    $resultado->currentPage(),
                            'per_page'      =>    $resultado->perPage(),
                            'last_page'     =>    $resultado->lastPage(),
                            'from'          =>    $resultado->firstItem(),
                            'to'            =>    $resultado->lastItem(),
                        ] ,
                    'resultado'=>$resultado,
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
            $array_enviar=$request->array_enviar;
            //return $array_enviar;
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
        
            foreach ($array_enviar as $key => $value) {
                $id=$value['id'];
                $data_load = [
                    'codigoActividad' => intval($request->codigoActividad),
                    'codigoProducto' => intval($request->codigoProducto)            
                ];
                DB::table('prod__productos')->where('id', $id)->update($data_load);               
            }
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 1,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>0,   
            ];
        
            DB::table('log__sistema')->insert($datos); 
            DB::commit();
        } catch (\Throwable $th) {
         return $th;
        }        
    }

  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siat_Homologacion $siat_Homologacion)
    {
        return $request->all();
    }

    public function getCodigoActividadProducto(){
        $homo = DB::table('excel__emision')
        ->where('id_catalogo', 16)
        ->get();
        return $homo;    
    }

    public function getProductoHomo(){
    $productos = DB::table('prod__productos')
    ->select('id', 'codigo', 'nombre')
    ->whereNull('codigoActividad')
    ->whereNull('codigoProducto')
    ->where('estado', 1)
    ->where('activo', 1)
    ->get();
return $productos;
    }

       public function desactivar(Request $request){
        try {
            DB::beginTransaction();
            $id=$request->id;
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $data_load = [
                'codigoActividad' => null,
                'codigoProducto' => null            
            ];
            DB::table('prod__productos')->where('id', $id)->update($data_load);   
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos); 
            DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }
       } 
}
