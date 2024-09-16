<?php

namespace App\Http\Controllers;

use App\Models\Caja_EntradaSalida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                               or ces.mensaje like '%" . $valor . "%' 
                            or ces.id like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (u.name like '%" . $valor . "%' 
                       or ces.mensaje like '%" . $valor . "%' 
                       or ces.id like '%" . $valor . "%' 
                       )";
                    }
                }
                // consulta
               $respuesta= DB::table('caja__entrada_salidas as ces')
               ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
               ->join('adm__nacionalidads as aa', 'aa.id', '=', 'ca.tipo_moneda')
               ->join('users as u', 'u.id', '=', 'ca.id_usuario')
               ->join('adm__sucursals AS ass', 'ass.id', '=', 'ces.id_sucursal')
               ->join('adm__departamentos AS ad', 'ad.id', '=', 'ass.departamento')
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
                   'aa.simbolo',
                   'ass.razon_social',
                   'ass.direccion',
                   'ces.entrada_salida as cadena_A',
                   DB::raw('CONCAT(ad.nombre, " - ", ass.ciudad) AS dir')

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
               ->join('adm__sucursals AS ass', 'ass.id', '=', 'ces.id_sucursal')
                ->join('adm__departamentos AS ad', 'ad.id', '=', 'ass.departamento')
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
                   'aa.simbolo',
                   'ass.razon_social',
                   'ass.direccion',
                   'ces.entrada_salida as cadena_A',
                   DB::raw('CONCAT(ad.nombre, " - ", ass.ciudad) AS dir')
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
                    'id_arqueo' => $id,                       
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
                $id_ss =$entrada_salida->id;
                $fechaCreacion = $entrada_salida->created_at;
// Separar la fecha y la hora
$soloFecha = $fechaCreacion->format('Y-m-d'); 
$soloHora = $fechaCreacion->format('H:i:s');  
$sucu = DB::table('adm__sucursals as ass')
    ->join('adm__departamentos as ad', 'ad.id', '=', 'ass.departamento')
    ->select('ass.id', 'ass.tipo', 'ass.direccion', 'ass.ciudad', 'ad.nombre','ass.razon_social')
    ->where('ass.id', 1)
    ->first();
            DB::commit();
            return response()->json([
                      
                'id' => $id_ss,   
                'id_arqueo' => $id,            
                'soloFecha' => $soloFecha, 
                'soloHora' => $soloHora,   
                'mensaje' => strtoupper($request->mensaje),
                'observacion' => strtoupper($request->obs),
                'valor' =>$request->total_arqueo_caja,
                'simbolo' => $request->simbolo,
                'titulo' => $sucu,
                'user' => auth()->user()->name

             ]);
     
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function validatePassword(Request $request)
    {
        try {
         // Validar que el campo 'password' esté presente
        $pass=$request->password;
      
        // Obtener al usuario autenticado
        $user = auth()->user()->password;
        // Comparar la contraseña ingresada con la almacenada en la base de datos
    if (Hash::check($pass, $user)) {
        return 1;
    } else {
        return 2;
    }
        } catch (\Throwable $th) {
            return $th;
        }
    
    }

    public function monedaModal_vista(Request $request){
       
        $resultado = DB::table('caja__entrada_salida_array as caa')
    ->join('caja__monedas as cm', 'caa.id_moneda', '=', 'cm.id')
    ->select('caa.id_moneda as id','cm.unidad_entera', 'cm.unidad', 'cm.tipo_corte', 'cm.valor', 'caa.cantidad')
    ->where('caa.id_arqueo','=',$request->id_arqueo)
    ->get();
  
    return $resultado;

    }
}