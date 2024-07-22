<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVentaVista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;

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
    ->join('rrh__empleados as re','re.id','=','u.idempleado')            
 
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
 
        DB::raw("DATE_FORMAT(vr.created_at, '%d/%m/%Y') as fecha_formateada"),
        DB::raw("DATE_FORMAT(vr.created_at, '%h:%i:%s %p') as hora_formateada"),
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 7 DAY), '%d/%m/%Y') as fecha_mas_siete"),
        DB::raw("UPPER(CONCAT(
            COALESCE(re.nombre, ''), ' ',
            COALESCE(re.papellido, ''), ' ',
            COALESCE(re.sapellido, '')
        )) as nombre_completo") 

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
    ->join('rrh__empleados as re','re.id','=','u.idempleado') 
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
        DB::raw("DATE_FORMAT(vr.created_at, '%d/%m/%Y') as fecha_formateada"),
        DB::raw("DATE_FORMAT(vr.created_at, '%h:%i:%s %p') as hora_formateada"),      
        DB::raw("DATE_FORMAT(DATE_ADD(vr.created_at, INTERVAL 7 DAY), '%d/%m/%Y') as fecha_mas_siete"),
        DB::raw("UPPER(CONCAT(
            COALESCE(re.nombre, ''), ' ',
            COALESCE(re.papellido, ''), ' ',
            COALESCE(re.sapellido, '')
        )) as nombre_completo")  
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
   
            $datoTexto = $this->convertirNumeroATexto($request->venta);

            if($request->tipofactura=="RECIBO"){
                $idVenta = $request->id_venta;
                $datos_empresa = DB::table('adm__credecial_correos')
            ->select('nit', 'nom_empresa')
            ->get();
        
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
                'vd.descuento as descuento'

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
                'datoTexto_v2' => $datoTexto
            ];
        
            // Devolver la respuesta JSON
            return response()->json($respuesta_v2);
            }else{
                if ($request->tipofactura=="FACTURA") {
                   dd("datos en contrucion");
                } else {
                    dd("error no exite datos");
                }
                
            }
        } catch (\Throwable $th) {
           dd($th);
        }

    }

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
   
}
