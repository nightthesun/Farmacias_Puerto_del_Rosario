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
    public function index(Request $request)
    {
        $buscararray = array();       
        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                u.name like '%" . $valor . "%' 
                                ces.mensaje like '%" . $valor . "%' 
                            or ces.id like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (u.name like '%" . $valor . "%' 
                       ces.mensaje like '%" . $valor . "%' 
                       or ces.id like '%" . $valor . "%' 
                       )";
                    }
                }
                // consulta
               $respuesta= DB::table('caja__entrada_salidas as ces')
               ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
               ->join('adm__nacionalidads as aa', 'aa.id', '=', 'ca.tipo_moneda')
               ->join('users as u', 'u.id', '=', 'ca.id_usuario')
               ->select(
                   'ces.id',
                   'ces.id_arqueo as num_arqueo',
                   'ces.valor',
                   'ces.observacion',
                   'ces.mensaje',
                   'ces.created_at',
                   'u.name',
                   'ca.cantidad_billete',
                   'ca.total_billete',
                   'ca.cantidad_moneda',
                   'ca.tipo_moneda',
                   'ca.total_moneda',
                   'aa.pais as nombre_moneda',
                   'aa.simbolo'
               )
               ->where('ces.id_sucursal', $request->id_sucursal)
               ->where('ces.entrada_salida', $request->entrada_salida)
               ->whereRaw($sqls)
               ->orderByDesc('ces.id')
                ->paginate(15);
            }
            return     
            [
                'pagination'=>
                    [
                        'total'         =>    $respuesta->total(),
                        'current_page'  =>    $respuesta->currentPage(),
                        'per_page'      =>    $respuesta->perPage(),
                        'last_page'     =>    $respuesta->lastPage(),
                        'from'          =>    $respuesta->firstItem(),
                        'to'            =>    $respuesta->lastItem(),
                    ] ,
                'respuesta'=>$respuesta,
        ];  
        } else {
            $respuesta= DB::table('caja__entrada_salidas as ces')
               ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
               ->join('adm__nacionalidads as aa', 'aa.id', '=', 'ca.tipo_moneda')
               ->join('users as u', 'u.id', '=', 'ca.id_usuario')
               ->select(
                   'ces.id',
                   'ces.id_arqueo as num_arqueo',
                   'ces.valor',
                   'ces.observacion',
                   'ces.mensaje',
                   'ces.created_at',
                   'u.name',
                   'ca.cantidad_billete',
                   'ca.total_billete',
                   'ca.cantidad_moneda',
                   'ca.tipo_moneda',
                   'ca.total_moneda',
                   'aa.pais as nombre_moneda',
                   'aa.simbolo'
               )
               ->where('ces.id_sucursal', $request->id_sucursal)
               ->where('ces.entrada_salida', $request->entrada_salida)            
               ->orderByDesc('ces.id')
                ->paginate(15);
                return     
                [
                    'pagination'=>
                        [
                            'total'         =>    $respuesta->total(),
                            'current_page'  =>    $respuesta->currentPage(),
                            'per_page'      =>    $respuesta->perPage(),
                            'last_page'     =>    $respuesta->lastPage(),
                            'from'          =>    $respuesta->firstItem(),
                            'to'            =>    $respuesta->lastItem(),
                        ] ,
                    'respuesta'=>$respuesta,
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