<?php

namespace App\Http\Controllers;

use App\Models\Ven_Trasferencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenTrasferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id;           
            $where = "(r.id_sucursal = $request->id_sucursal AND r.id_usuario = $user)";                    
        } else {
            $where = "(r.id_sucursal = $request->id_sucursal)";  
        }

        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';
                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                               t.num_tar_o_boleto like '%" . $valor . "%' 
                                or t.tipo like '%" . $valor . "%'                                 
                               )";
                    } else {
                        $sqls .= "and (t.num_tar_o_boleto '%" . $valor . "%' 
                        or t.tipo like  '%" . $valor . "%' 
                      )";
                    }
                }
                //query
                $resultado = DB::table('ven__trasferencias as t')      
    ->select(
        't.id',
        't.id_venta',
        't.tipo',
        't.num_tar_o_boleto',
        't.mas_datos',
        't.id_banco',
        't.contador',
        'r.nro_comprobante_venta',
        'r.anulado',
        'r.created_at as venta_fecha',
        't.updated_at as impre_fecha',
        'r.id_apertura',
        'u.super_usuario',
        DB::raw("CASE 
                    WHEN r.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN r.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE 'DESCONOCIDO'
                 END as tipo_venta"),
        'r.razon_social',
        'r.monto_apagar',
        'u.name as user_name',
        's.razon_social as nombre_sucursal',
        's.ciudad','bb.nombre as nombre_banco',
        's.direccion','nac.simbolo','em.nombre as nom_empleado'
    )
    ->join('ven__recibos as r', 'r.id', '=', 't.id_venta')
    ->join('users as u', 'u.id', '=', 'r.id_usuario')
    ->join('rrh__empleados as em', 'em.id', '=', 'u.idempleado') 
    ->join('adm__sucursals as s', 's.id', '=', 'r.id_sucursal')  
    ->leftJoin('adm__bancos as bb', 'bb.id', '=', 't.id_banco')  
    ->leftJoin('adm__nacionalidads as nac', 'nac.id', '=', 'r.moneda')      
    ->whereRaw($where)
    ->whereRaw($sqls)    
    ->orderByDesc('t.id')
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
        } else {
            $resultado = DB::table('ven__trasferencias as t')      
            ->select(
                't.id',
                't.id_venta',
                't.tipo',
                't.num_tar_o_boleto',
                't.mas_datos',
                't.id_banco',
                't.contador',
                'r.nro_comprobante_venta',
                'r.anulado',
                'r.created_at as venta_fecha',
                't.updated_at as impre_fecha',
                'r.id_apertura',
                'u.super_usuario',
                DB::raw("CASE 
                            WHEN r.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                            WHEN r.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                            ELSE 'DESCONOCIDO'
                         END as tipo_venta"),
                'r.razon_social',
                'r.monto_apagar',
                'u.name as user_name',
                's.razon_social as nombre_sucursal',
                's.ciudad','bb.nombre as nombre_banco',
                's.direccion','nac.simbolo','em.nombre as nom_empleado'
            )
            ->join('ven__recibos as r', 'r.id', '=', 't.id_venta')
            ->join('users as u', 'u.id', '=', 'r.id_usuario')
            ->join('rrh__empleados as em', 'em.id', '=', 'u.idempleado')            
            ->join('adm__sucursals as s', 's.id', '=', 'r.id_sucursal')  
            ->leftJoin('adm__bancos as bb', 'bb.id', '=', 't.id_banco')  
            ->leftJoin('adm__nacionalidads as nac', 'nac.id', '=', 'r.moneda')     
            ->whereRaw($where)            
            ->orderByDesc('t.id')
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
    public function show(Ven_Trasferencia $ven_Trasferencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Ven_Trasferencia $ven_Trasferencia)
    {
        try {
            DB::beginTransaction();
          
            //data es  el valor para imprimir un datos si es 2 es para lote de datos
            $fechaHora = Carbon::now(); // Se usará automáticamente el formato correcto
           
            
                DB::table('ven__trasferencias as t')   
                ->join('ven__recibos as r', 'r.id', '=', 't.id_venta')
                ->where('r.id_apertura', $request->id_apertura) 
                ->update(['t.contador' => 0,'t.updated_at' => $fechaHora]);
                     
     
            DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ven_Trasferencia $ven_Trasferencia)
    {
        try {
            DB::beginTransaction();
          
            //data es  el valor para imprimir un datos si es 2 es para lote de datos
            $fechaHora = Carbon::now(); // Se usará automáticamente el formato correcto
            $idCierre = DB::table('caja__apertura_cierres')
            ->where('id', $request->id_apertura)
            ->value('id_cierre');
            if ($idCierre===0 || $idCierre===null) {
                return "DEBE CERRAR LA CAJA ANTES DE HACER ESTA ACCIÓN O LA APERTURA NO EXISTE.";
            }
            
            if ($request->data==1) {
                $data = Ven_Trasferencia::findOrFail($request->id);
                $data->contador=0;
                $data->updated_at=$fechaHora;             
                $data->save();
            }
            
            if ($request->data==2) {
               
                DB::table('ven__trasferencias as t')   
                ->join('ven__recibos as r', 'r.id', '=', 't.id_venta')
                ->where('r.id_apertura', $request->id_apertura) 
                ->update(['t.contador' => 0,'t.updated_at' => $fechaHora]);
            }           
     
            DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }        
    }

}
