<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVentaVista;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use App\Helpers\converso_numero_a_texto;

class VenGestorVentaVistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sucursalId = $request->id_sucursal;
$codigo = $request->codigo_tienda_almacen;
$startDate = $request->startDate;
$endDate = $request->endDate;

        $buscararray=array();        
        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls=''; 
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                            u.name like '%".$valor."%' 
                                or dc.nom_a_facturar like '%".$valor."%' 
                                or dc.num_documento like '%".$valor."%'                              
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            u.name like '%".$valor."%' 
                                or dc.nom_a_facturar like '%".$valor."%' 
                                or dc.num_documento like '%".$valor."%'  
                          )" ;
                    }    
                }
                $ventas_show = DB::table('ven__recibos as vr')
                ->leftJoin('dir__clientes as dc', 'vr.id_cliente', '=', 'dc.id')
    ->join('users as u', 'u.id', '=', 'vr.id_usuario')
    ->join('adm__sucursals as ass','ass.id','=','vr.id_sucursal') 
    ->join('adm__departamentos as ad', 'ass.departamento', '=', 'ad.id')
    ->join('rrh__empleados as re','re.id','=','u.idempleado')            
    ->leftJoin('dir__personas as dp', function ($join) {
        $join->on('dp.id', '=', 'dc.id_per_emp')
             ->where('dc.tipo_per_emp', '=', 1);
    })
    ->leftJoin('dir__empresas as de', function ($join) {
        $join->on('de.id', '=', 'dc.id_per_emp')
             ->where('dc.tipo_per_emp', '=', 2);
    })
    ->select(
        'vr.id',
        'vr.id_sucursal',
        'vr.id_cliente',
        'vr.nro_comprobante_venta',
        'vr.total_venta',
        'vr.efectivo_venta',
        'vr.cambio_venta',
        'vr.descuento_venta',
        'vr.estado_venta',
        'vr.anulado',
        'vr.created_at',
        'vr.total_sin_des',
        DB::raw("CASE
                    WHEN vr.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN vr.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE NULL
                 END AS tipo_venta_reci_fac"),
        'vr.razon_social as nom_a_facturar',
        'vr.nro_doc as num_documento',
        'u.name',
        'vr.cod',
        'nro_ref as numero_referencia',
        DB::raw('UPPER(ass.razon_social) as razon_social'),
        DB::raw('UPPER(ass.direccion) as direccion'),
        DB::raw('UPPER(ass.ciudad) as ciudad'),
        DB::raw('UPPER(ad.nombre) as departamento'),
       
 
        DB::raw("DATE_FORMAT(vr.created_at, '%d/%m/%Y') as fecha_formateada"),
        DB::raw("DATE_FORMAT(vr.created_at, '%h:%i:%s %p') as hora_formateada"),
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 7 DAY), '%d/%m/%Y') as fecha_mas_siete"),
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 3 DAY), '%d/%m/%Y') as fecha_mas_tres"),
        DB::raw("UPPER(CONCAT(
            COALESCE(re.nombre, ''), ' ',
            COALESCE(re.papellido, ''), ' ',
            COALESCE(re.sapellido, '')
        )) as nombre_completo_empleado"),
         'dc.tipo_per_emp',
        DB::raw("CASE 
        WHEN dp.id IS NOT NULL THEN UPPER(CONCAT(COALESCE(dp.nombres, ''), ' ', COALESCE(dp.apellidos, '')))
        WHEN de.id IS NOT NULL THEN UPPER(de.razon_social)
        ELSE NULL
    END AS nombre_completo_cliente"),
    'dc.correo','vr.dosificacion_o_electronica' 
    )
    ->where('vr.id_sucursal', $sucursalId)
    ->where('vr.cod', $codigo)
    ->whereBetween(DB::raw('DATE(vr.created_at)'), [$startDate, $endDate])
    ->whereRaw($sqls)
    ->orderByDesc('vr.id')
    ->paginate(15);

            }   
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $ventas_show->total(),
                            'current_page'  =>    $ventas_show->currentPage(),
                            'per_page'      =>    $ventas_show->perPage(),
                            'last_page'     =>    $ventas_show->lastPage(),
                            'from'          =>    $ventas_show->firstItem(),
                            'to'            =>    $ventas_show->lastItem(),
                        ] ,
                    'ventas_show'=>$ventas_show,
            ]; 
        } else {
            $ventas_show = DB::table('ven__recibos as vr')
    ->leftJoin('dir__clientes as dc', 'vr.id_cliente', '=', 'dc.id')
    ->join('users as u', 'u.id', '=', 'vr.id_usuario')
    ->join('adm__sucursals as ass','ass.id','=','vr.id_sucursal')
    ->join('adm__departamentos as ad', 'ass.departamento', '=', 'ad.id') 
    ->join('rrh__empleados as re','re.id','=','u.idempleado') 
    ->leftJoin('dir__personas as dp', function ($join) {
        $join->on('dp.id', '=', 'dc.id_per_emp')
             ->where('dc.tipo_per_emp', '=', 1);
    })
    ->leftJoin('dir__empresas as de', function ($join) {
        $join->on('de.id', '=', 'dc.id_per_emp')
             ->where('dc.tipo_per_emp', '=', 2);
    })
    ->select(
        'vr.id',
        'vr.id_sucursal',
        'vr.id_cliente',
        'vr.nro_comprobante_venta',
        'vr.total_venta',
        'vr.efectivo_venta',
        'vr.cambio_venta',
        'vr.descuento_venta',
        'vr.estado_venta',
        'vr.anulado',
        'vr.created_at',
        'vr.total_sin_des',
        DB::raw("CASE
                    WHEN vr.tipo_venta_reci_fac = 1 THEN 'RECIBO'
                    WHEN vr.tipo_venta_reci_fac = 2 THEN 'FACTURA'
                    ELSE NULL
                 END AS tipo_venta_reci_fac"),
                 'vr.razon_social as nom_a_facturar',
                 'vr.nro_doc as num_documento',
        'u.name',
        'vr.cod',
        'nro_ref as numero_referencia',
        DB::raw('UPPER(ass.razon_social) as razon_social'),
        DB::raw('UPPER(ass.direccion) as direccion'),
        DB::raw('UPPER(ass.ciudad) as ciudad'),
        DB::raw('UPPER(ad.nombre) as departamento'),
        DB::raw("DATE_FORMAT(vr.created_at, '%d/%m/%Y') as fecha_formateada"),
        DB::raw("DATE_FORMAT(vr.created_at, '%h:%i:%s %p') as hora_formateada"),      
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 7 DAY), '%d/%m/%Y') as fecha_mas_siete"),
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 3 DAY), '%d/%m/%Y') as fecha_mas_tres"),
        DB::raw("UPPER(CONCAT(
            COALESCE(re.nombre, ''), ' ',
            COALESCE(re.papellido, ''), ' ',
            COALESCE(re.sapellido, '')
        )) as nombre_completo_empleado"),
         'dc.tipo_per_emp',
        DB::raw("CASE 
        WHEN dp.id IS NOT NULL THEN UPPER(CONCAT(COALESCE(dp.nombres, ''), ' ', COALESCE(dp.apellidos, '')))
        WHEN de.id IS NOT NULL THEN UPPER(de.razon_social)
        ELSE NULL
    END AS nombre_completo_cliente"),
    'dc.correo','vr.dosificacion_o_electronica'   
    )
    ->where('vr.id_sucursal', $sucursalId)
    ->where('vr.cod', $codigo)
    ->whereBetween(DB::raw('DATE(vr.created_at)'), [$startDate, $endDate])
   
    ->orderByDesc('vr.id')
    ->paginate(15);
    return 
    [
            'pagination'=>
                [
                    'total'         =>    $ventas_show->total(),
                    'current_page'  =>    $ventas_show->currentPage(),
                    'per_page'      =>    $ventas_show->perPage(),
                    'last_page'     =>    $ventas_show->lastPage(),
                    'from'          =>    $ventas_show->firstItem(),
                    'to'            =>    $ventas_show->lastItem(),
                ] ,
            'ventas_show'=>$ventas_show,
    ];
        }
        

   
    }


    public function re_imprecion(Request $request){

        try {
            $datoTexto = converso_numero_a_texto::convertirNumeroATexto($request->venta);
          //  $datoTexto = $this->convertirNumeroATexto($request->venta);

            $total_sin_des=$request->total_sin_des;
            $facturas_dosi = DB::table('ven__factura_dosi as vfd')
    ->join('dos__dosificacion as dd', 'vfd.id_dosificacion', '=', 'dd.id')
    ->where('vfd.id_venta', $request->id)
    ->select('vfd.*', 'dd.*') // Ajusta los campos que necesitas
    ->get();
           
                $idVenta = $request->id_venta;
                $datos_empresa = DB::table('adm__credecial_correos')
            ->select('nit', 'nom_empresa','actividad_economica','nro_celular')
            ->get();           
        
            $result = DB::table('ven__detalle_ventas as vdv')
            ->select(DB::raw('SUM(vdv.descuento) as suma_descuento'))
            ->where('vdv.id_venta', $idVenta)
            ->first();

            $resultado_descuento_2 = DB::table('ven__detalle_descuentos as vdd')
    ->select(DB::raw('ROUND(SUM(vdd.cantidad_descuento), 2) as resultado'))
    ->where('vdd.id_venta', $idVenta)
    ->where('vdd.tipo', 2)
    ->first();
    
             
            $sumaDescuento = $result ? number_format($result->suma_descuento, 2) : '0.00';

            $resultado_sumatoria=($total_sin_des-$sumaDescuento);
        
        $detalle_venta = DB::table('ven__detalle_ventas as vd')
            ->select(
                'vd.id_venta as id',
                'vd.cantidad_venta',
                DB::raw("
                    CASE 
                        WHEN tip.envase = 'primario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_1.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadprimario, ''), ' ', 
                            UPPER(COALESCE(ff_1.nombre, ''))
                        ) 
                        WHEN tip.envase = 'secundario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_2.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadsecundario, ''), ' ', 
                            UPPER(COALESCE(ff_2.nombre, ''))
                        ) 
                        WHEN tip.envase = 'terciario' THEN CONCAT(
                            UPPER(COALESCE(pp.nombre, '')), ' ', 
                            UPPER(COALESCE(pd_3.nombre, '')), ' X ', 
                            COALESCE(pp.cantidadterciario, ''), ' ', 
                            UPPER(COALESCE(ff_3.nombre, ''))
                        ) 
                        ELSE NULL 
                    END AS leyenda
                "),
                'vd.precio_venta as precio_unitario',
                DB::raw('(vd.cantidad_venta * vd.precio_venta) as tot'),
                'pp.codigo as cod_prod',
                DB::raw("DATE_FORMAT(tip.fecha_vencimiento, '%d/%m/%Y') as fecha_vencimiento"),
              
                'tip.lote',
                'pl.nombre as linea_nombre',
                'vd.descuento as descuento',
                'vd.id_detalle_descuento as id_detalle_descuento'

            )
            ->join('prod__productos as pp', 'vd.id_producto', '=', 'pp.id')
            
            ->join('prod__lineas as pl','pp.idlinea','=','pl.id')
            ->join('tda__ingreso_productos as tip', 'tip.id', '=', 'vd.id_ingreso')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->where('vd.id_venta', $idVenta)
            ->get();


            $respuesta_v2 = [
                'detalle_venta' => $detalle_venta,
                'datos_empresa' => $datos_empresa,
                'datoTexto_v2' => $datoTexto,
                'venta_con_descuento' => $resultado_sumatoria,
                'resultado_descuento_2' => $resultado_descuento_2,

            ];
        
            // Devolver la respuesta JSON
            return response()->json($respuesta_v2);
            
        } catch (\Throwable $th) {
           dd($th);
        }

    }

  /* 
   private function convertirNumeroATexto($numero) {
        $numberToWords = new NumberToWords();
    
        // Obtén el convertidor para español
        $numberTransformer = $numberToWords->getNumberTransformer('es');
    
        // Separa la parte entera y la parte decimal
        $partes = explode('.', number_format($numero, 2, '.', ''));
        $entero = intval($partes[0]);
        $decimal = intval($partes[1]);
    
        // Convierte la parte entera a texto
        $textoEntero = $numberTransformer->toWords($entero);
        $textoDecimal = $decimal . '/100 Bs';
    
        // Retorna la combinación de ambas partes
        return ucfirst($textoEntero) . ' ' . $textoDecimal;
    }
  */
   

    public function verCliente_x_venta(Request $request)
    {
    $ven_detalle_descuentos = DB::table('ven__detalle_descuentos as vdd')
    ->join('par_tipo_tabla as ptt', 'vdd.id_tabla', '=', 'ptt.id')
    ->join('par__descuentos as pdes', 'vdd.id_descuento', '=', 'pdes.id')
    ->select('vdd.id_venta as id','ptt.nombre as nom_tabla', 'pdes.nombre_descuento', 'vdd.cantidad_descuento','vdd.id_detalle_descuento')
    ->where('vdd.id_venta', $request->id_venta)   
    ->get();       
    return $ven_detalle_descuentos;       
    }

    public function desactivar(Request $request){
        //1=add, 2=delete, 3=create, 4=edit, 5=show
    // Truncar la tabla para eliminar todo su contenido
        try {         
           
            $eliminar = Ven_GestorVentaVista::findOrFail($request->id);
            $eliminar->anulado=1;
            $eliminar->save(); 
            $now = Carbon::now();
        DB::table('par__asignacion_descuento')->truncate();
        $datos = [
            'id_modulo' => $request->id_modulo,
            'id_sub_modulo' => $request->id_sub_modulo,
            'accion' => 2,
            'descripcion' => $request->des,          
            'user_id' =>auth()->user()->id, 
            'created_at'=>$now,
            'id_movimiento'=>$request->id        
        ];
    
        DB::table('log__sistema')->insert($datos);         
           
        } catch (\Throwable $th) {
           // DB::rollback();
            return response()->json(['error' => $th->getMessage()],500);
        }    
    }
   
}
