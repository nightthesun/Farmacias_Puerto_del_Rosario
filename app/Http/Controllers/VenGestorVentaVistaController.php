<?php

namespace App\Http\Controllers;

use App\Models\Ven_GestorVentaVista;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use App\Helpers\converso_numero_a_texto;
use App\Models\Alm_IngresoProducto;
use App\Models\Inv_AjustePositivo;
use App\Models\Tda_IngresoProducto;
use Illuminate\Database\Schema\IndexDefinition;
use Ramsey\Uuid\Type\Integer;

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
$id_caja = $request->id_caja;
//->where('vr.id_sucursal', $sucursalId)
    //->where('vr.cod', $codigo)
if (auth()->user()->super_usuario == 0) {
    $user = auth()->user()->id; 
    $where = "(vr.id_sucursal = $sucursalId and vr.cod = $codigo and vr.id_usuario = $user)";            
} else {
    $where = "(vr.id_sucursal = $sucursalId and vr.cod = $codigo)";   
}

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
    ->join('caja__apertura_cierres as cac','vr.id_apertura','=','cac.id')          
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
    'dc.correo','vr.dosificacion_o_electronica','vr.tipo_venta','vr.monto_vale','vr.monto_apagar' 
    )
    //->where('vr.id_sucursal', $sucursalId)
    //->where('vr.cod', $codigo)
    ->whereRaw($where)
    //->whereBetween(DB::raw('DATE(vr.created_at)'), [$startDate, $endDate])
    ->whereRaw($sqls)
    ->where('cac.id_caja',$id_caja)
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
    ->join('caja__apertura_cierres as cac','vr.id_apertura','=','cac.id') 
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
    'dc.correo','vr.dosificacion_o_electronica','vr.tipo_venta','vr.monto_vale','vr.monto_apagar'    
    )
    ->where('vr.id_sucursal', $sucursalId)
    ->where('vr.cod', $codigo)
    ->where('cac.id_caja',$id_caja)
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

    public function get_operacion_general(Request $request){

    /*
    tipo_venta 1 4  2,3
  
  select sum(r.total_venta) as total from ven__recibos r
  join caja__apertura_cierres c on r.id_apertura=c.id
  where c.id_caja=1 and r.tipo_venta =2 or r.tipo_venta = 3
  
  select sum(e.valor) as total from caja__entrada_salidas e
  join caja__apertura_cierres c on e.entrada_salida=c.id
  where c.id_caja=1 and e.entrada_salida=1
    */
  $id_sucursal=$request->id_sucursal;
   $id_caja=$request->id_caja;
   $startDate = $request->startDate;
$endDate = $request->endDate;
 $where_1 = "( r.tipo_venta = 1 or r.tipo_venta = 4)";  
 $where_2 = "( r.tipo_venta = 2 or r.tipo_venta = 3)"; 

  $entrada = DB::table('caja__entrada_salidas as e') 
              ->join('caja__apertura_cierres as c', 'e.entrada_salida', '=', 'c.id')
              ->selectRaw('sum(e.valor) as total')
    ->where('e.id_sucursal', '=' ,$id_sucursal)  
     ->where('c.id_caja', '=' ,$id_caja)  
   ->where('e.entrada_salida', '=' ,1)   
  ->whereBetween(DB::raw('DATE(e.created_at)'), [$startDate, $endDate])
    ->value('total');

    
  $salida = DB::table('caja__entrada_salidas as e') 
              ->join('caja__apertura_cierres as c', 'e.entrada_salida', '=', 'c.id')
              ->selectRaw('sum(e.valor) as total')
    ->where('e.id_sucursal', '=' ,$id_sucursal)  
     ->where('c.id_caja', '=' ,$id_caja)  
   ->where('e.entrada_salida', '=' ,2)   
  ->whereBetween(DB::raw('DATE(e.created_at)'), [$startDate, $endDate])
    ->value('total');

              $efectivo = DB::table('ven__recibos as r') 
              ->join('caja__apertura_cierres as c', 'r.id_apertura', '=', 'c.id')
              ->selectRaw('sum(r.total_venta) as total')
    ->where('r.id_sucursal', '=' ,$id_sucursal)  
     ->where('c.id_caja', '=' ,$id_caja)  
    ->where('r.anulado', '=' ,0)
    ->whereRaw($where_1)
  ->whereBetween(DB::raw('DATE(r.created_at)'), [$startDate, $endDate])
    ->value('total');

    $digital = DB::table('ven__recibos as r') 
              ->join('caja__apertura_cierres as c', 'r.id_apertura', '=', 'c.id')
              ->selectRaw('sum(r.total_venta) as total')
    ->where('r.id_sucursal', '=' ,$id_sucursal)  
     ->where('c.id_caja', '=' ,$id_caja)  
    ->where('r.anulado', '=' ,0)
    ->whereRaw($where_2)
  ->whereBetween(DB::raw('DATE(r.created_at)'), [$startDate, $endDate])
    ->value('total');

     $total = DB::table('ven__recibos as r') 
              ->join('caja__apertura_cierres as c', 'r.id_apertura', '=', 'c.id')
              ->selectRaw('sum(r.total_venta) as total')
    ->where('r.id_sucursal', '=' ,$id_sucursal)  
     ->where('c.id_caja', '=' ,$id_caja)  
    ->where('r.anulado', '=' ,0)
   
  ->whereBetween(DB::raw('DATE(r.created_at)'), [$startDate, $endDate])
    ->value('total');

    if ($entrada==null) {
        $entrada=0;
    }
if ($salida==null) {
        $salida=0;
    }
    if ($efectivo==null) {
        $efectivo=0;
    }
    if ($digital==null) {
        $digital=0;
    }
    if ($total==null) {
        $total=0;
    }
    $operacion=($total+$entrada)-$salida;
     $operacion=number_format($operacion,2,'.','');
   
   // entrada'=> number_format($entrada,2,'.','')'
return response()->json([
                        'entrada' => $entrada,
                        'salida' => $salida,
                        'efectivo'=>$efectivo,
                        'digital'=>$digital,
                        'total'=>$total,
                        'operacion'=>$operacion
                        ]);
    
   
    }

    public function get_tipo_caja(Request $request){
   
  $id_sucursal=$request->id_sucursal;
   $query = DB::table('caja__creacions') 
    ->where('id_sucursal', '=' ,$id_sucursal)  
    ->where('estado', '=' ,1)  
    ->select('id','codigo','nombre_caja','tipo_caja',
     DB::raw("CASE 
        WHEN tipo_caja = 1 THEN 'Caja normal'
        WHEN tipo_caja = 2 THEN 'Caja modificada'
        ELSE 'Caja sin configurar'
    END AS tipo_caja"),
    )->get();
    if(count($query)>0){
    return $query;
    }else{
        return 0;
    }      

    }
    

    public function re_imprecion(Request $request){

        try {
         

            $datoTexto = converso_numero_a_texto::convertirNumeroATexto($request->venta);
          //  $datoTexto = $this->convertirNumeroATexto($request->venta);

            $total_sin_des=$request->total_sin_des;
            $facturas_dosi = DB::table('ven__factura_dosi as vfd')
    ->join('dos__dosificacion as dd', 'vfd.id_dosificacion', '=', 'dd.id')
    ->where('vfd.id_venta', '=' ,$request->id_venta)  
    ->select(
        'vfd.numero_factura',
        'vfd.codigo_control',
        'vfd.estado_factura',
        'dd.nro_autorizacion',
        DB::raw("DATE_FORMAT(dd.fecha_e, '%d-%m-%Y') as fecha_e") // Formato dd-mm-yyyy
    ) ->get();
     
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
    
    $descuento_final = DB::table('ven__detalle_descuentos')
    ->where('id_venta', $idVenta)
    ->where('id_detalle_descuento', 0)
    ->sum('cantidad_descuento');

            $sumaDescuento = $result ? number_format($result->suma_descuento, 2) : '0.00';

            $resultado_sumatoria=($total_sin_des-$sumaDescuento);
            $total_literal = converso_numero_a_texto::convertirNumeroATexto($request->venta);
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
                DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
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
            
            if ($request->tipofactura=="FACTURA"&&$request->plana_ticket==2) {
                 // Ruta de la imagen en la carpeta public
                $path = public_path('img/favicon.png');    
                // Codificar la imagen en base64
                $imageData = base64_encode(file_get_contents($path));    
                // Obtener el tipo MIME de la imagen
                $mimeType = mime_content_type($path);
                // Crear la cadena base64 con el prefijo data
                $base64Image = 'data:' . $mimeType . ';base64,' . $imageData;
                $respuesta_v2 = [
                    'detalle_venta' => $detalle_venta,
                    'datos_empresa' => $datos_empresa,
                    'datoTexto_v2' => $datoTexto,
                    'venta_con_descuento' => $resultado_sumatoria,
                    'resultado_descuento_2' => $resultado_descuento_2,
                    'facturas_dosi' => $facturas_dosi,
                    'descuento_final' => $descuento_final,
                    'total_literal' => $total_literal,
                    'base64' => $base64Image    
                ];
            }else{
                $respuesta_v2 = [
                    'detalle_venta' => $detalle_venta,
                    'datos_empresa' => $datos_empresa,
                    'datoTexto_v2' => $datoTexto,
                    'venta_con_descuento' => $resultado_sumatoria,
                    'resultado_descuento_2' => $resultado_descuento_2,
                    'facturas_dosi' => $facturas_dosi,
                    'descuento_final' => $descuento_final,
                    'total_literal' => $total_literal,
               
                ];
            }
            
       
           
            
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
            DB::beginTransaction(); 


    $tipo =$request->dosificacion_o_electronica;
    $id=$request->id;
    $query_1 = DB::table('ven__recibos as r')    
                ->where('r.id', $id)
                ->select('*')
                ->first(); 

                if ($query_1==null){
                    return response()->json([
                        'msn' => "No existe la venta.",
                        'error' => 1,
                        ]);
                } 
                
                $id_sucursal=$query_1->id_sucursal;   
 
                if ($tipo==3) {
                     $siat = DB::table('ven__factura_siat')    
                ->where('id_venta', $id)
                ->select('estado','enviado')
                ->first();
                    if ($siat==null){
                        return response()->json([
                        'msn' => "No existe factura",
                        'error' => 1,
                    ]);
                    }                    
                     
                    if ($siat->estado==0) {
                        return response()->json([
                        'msn' => "La factura debe estar anulada en sistema del siat",
                        'error' => 1,
                    ]);
                    }
                    if ($siat->enviado==0) {
                       
                        return response()->json([
                            'msn' => "La factura no esta enviada al siat",
                        'error' => 1,
                        ]);
                    }                 
                }

                $datos_venta_detalle = DB::table('ven__detalle_ventas as p')    
                ->where('p.id_venta', $id)
                ->join('prod__lineas as l' ,'p.id_linea', '=','l.id')
                ->join('prod__productos as pp' ,'pp.id', '=','p.id_producto')   
                ->select('p.id_ingreso','p.id_producto','p.id_linea','p.cantidad_venta','p.codigo_tienda_almacen','p.envase','l.nombre as nom_linea','pp.codigo as cod_prod','pp.nombre as nom_prod')
                ->get();

                if (count($datos_venta_detalle)<=0) {
                    return response()->json([
                        'msn' => "no existe datos de la venta",
                        'error' => 1,
                    ]);
                }
          
               

                foreach ($datos_venta_detalle as $key => $value) {

                $cantidad_venta=$value->cantidad_venta;
                $cod_prod=$value->cod_prod;
                $codigo_tienda_almacen=$value->codigo_tienda_almacen; //"TDA000"
                $envase=$value->envase;
                $id_ingreso=$value->id_ingreso;
                $id_linea=$value->id_linea;
                $id_producto=$value->id_producto;
                $nom_linea=$value->nom_linea;
                $nom_prod=$value->nom_prod;
                  

                   $pivote = DB::table('pivot__modulo_tienda_almacens as p')
    ->leftJoin('alm__almacens as aa', function ($join) use ($codigo_tienda_almacen) {
        $join->on('aa.id', '=', 'p.id_tienda_almacen')
             ->where('aa.codigo', '=', $codigo_tienda_almacen);
    })

    ->leftJoin('tda__tiendas as tt', function ($join) use ($codigo_tienda_almacen) {
        $join->on('tt.id', '=', 'p.id_tienda_almacen')
             ->where('tt.codigo', '=', $codigo_tienda_almacen);
    })

    ->leftJoin('alm__ingreso_producto as aip', function ($join) use ($id_producto, $envase) {
        $join->on('aip.id', '=', 'p.id_ingreso')
             ->where('aip.id_prod_producto', '=', $id_producto)
             ->where('aip.envase', '=', $envase);
    })

    ->leftJoin('tda__ingreso_productos as tip', function ($join) use ($id_producto, $envase) {
        $join->on('tip.id', '=', 'p.id_ingreso')
             ->where('tip.id_prod_producto', '=', $id_producto)
             ->where('tip.envase', '=', $envase);
    })

    ->where('p.id_ingreso', $id_ingreso)

    ->select(
        'p.id as id_pivote',
        'p.tipo',
        'p.id_tienda_almacen',

        DB::raw("
            CASE
                WHEN aa.idsucursal IS NOT NULL THEN aa.idsucursal
                WHEN tt.idsucursal IS NOT NULL THEN tt.idsucursal
                ELSE NULL
            END AS idsucursal
        "),

        DB::raw("
            CASE
                WHEN aa.idsucursal IS NOT NULL THEN aip.stock_ingreso
                WHEN tt.idsucursal IS NOT NULL THEN tip.stock_ingreso
                ELSE NULL
            END AS stock_ingreso
        "),

        DB::raw("
            CASE
                WHEN aa.idsucursal IS NOT NULL THEN aip.lote
                WHEN tt.idsucursal IS NOT NULL THEN tip.lote
                ELSE NULL
            END AS lote
        "),

        DB::raw("
            CASE
                WHEN aa.idsucursal IS NOT NULL THEN aip.fecha_vencimiento
                WHEN tt.idsucursal IS NOT NULL THEN tip.fecha_vencimiento
                ELSE NULL
            END AS fecha_vencimiento
        "),

        DB::raw("
            CASE
                WHEN aa.idsucursal IS NOT NULL THEN aip.created_at
                WHEN tt.idsucursal IS NOT NULL THEN tip.created_at
                ELSE NULL
            END AS fecha_ingreso
        ")
    )
    ->first();


        
                $now = Carbon::now();
                $operacion = $pivote->stock_ingreso+$value->cantidad_venta;
              $fecha_ingreso=$pivote->fecha_ingreso;
              $fecha_vencimiento=$pivote->fecha_vencimiento;
              $lote=$pivote->lote;
              $tipo=$pivote->tipo;
                    $ajuste=new Inv_AjustePositivo();
                    $ajuste->id_usuario = auth()->user()->id;
                    $ajuste->usuario = auth()->user()->name;
                    $ajuste->id_usuario_registra = auth()->user()->id;
                    $ajuste->id_tipo=4;
                    $ajuste->id_sucursal=$id_sucursal;
                    $ajuste->id_producto_linea=$id_producto;
                    $ajuste->codigo=$cod_prod;
                    $ajuste->linea=$nom_linea;
                    $ajuste->producto=$nom_prod;
                    $ajuste->cantidad=$cantidad_venta;
                    $ajuste->stock=$cantidad_venta;
                    $ajuste->fecha_ingreso=$fecha_ingreso;
                    $ajuste->lote=$lote;
                    $ajuste->fecha_vencimiento=$fecha_vencimiento;
                    $ajuste->descripcion="por anulacion de productos"; 
                    $ajuste->id_usuario_modifica = auth()->user()->id;  
                    $ajuste->cod=$codigo_tienda_almacen;           
                    $ajuste->id_ingreso=$id_ingreso;
                    $ajuste->leyenda = $nom_prod." - Envase: ".$envase;
                    $ajuste->save();

              if($tipo=='TDA'){              
                    $ingreso=Tda_IngresoProducto::find($id_ingreso);
                    $ingreso->stock_ingreso=$operacion;
                    $ingreso->save();              
                }
                if($tipo=='ALM'){
                    $ingreso=Alm_IngresoProducto::find($id_ingreso);
                    $ingreso->stock_ingreso=$operacion;
                    $ingreso->save();                      
                }
                }

                //fin de bucle============================================
                $eliminar = Ven_GestorVentaVista::findOrFail($id);
                $eliminar->anulado=1;
                $eliminar->save(); 

         //   DB::table('par__asignacion_descuento')->truncate();
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
               DB::commit();
               return response()->json([
            'msn' => "Accion realizada con exito",
            'error' => 0,
        ]);
           
        } catch (\Throwable $th) {
           DB::rollback();
            return response()->json([
            'msn' => $th,
            'error' => 1,
        ]);
        }    
    }

    public function factura_dosificacion(Request $request){
        $dosificacion_fac =  DB::table('ven__factura_dosi as vfd')
        ->select('vfd.numero_factura', 'vfd.codigo_control', 'dd.nro_autorizacion', DB::raw('1 as key_0'),'vfd.estado_factura','dd.estado')
        ->join('dos__dosificacion as dd', 'vfd.id_dosificacion', '=', 'dd.id')
        ->where('vfd.id_venta', $request->id)
        ->first();

         $siat =  DB::table('ven__factura_siat as f')
        ->select('f.numFactura','f.cuf as codigoControl','f.codEstado','f.estado','enviado')    
        ->where('f.id_venta', $request->id)
        ->first();

        return response()->json([
            'fac_dosi' => $dosificacion_fac,
            'fac_siat' => $siat,
        ]);
    }
   

    public function get_data_factura_siat(Request $request){

    try {
        $id_catalogo=2;
        $id_catalogo_3=3;
        $id_catalogo_2=11;
        $idVenta=$request->id_venta;
      
           $venta = DB::table('ven__recibos as r')
           ->select('s.direccion as dir_suc','s.razon_social as nom_suc',
            'f.fechaEmision','r.nro_doc','r.razon_social as nom_facturar',
            'r.total_sin_des','r.descuento_venta','r.total_venta','f.cuf as cod_auto',
            'f.numFactura','r.efectivo_venta','r.cambio_venta','r.id_cliente',
            'f.tipoFacturaDoc','f.id_leyenda','f.punto_venta','f.sucursal_siat','r.monto_vale as git_card',
            'n.simbolo','r.anulado','e.descripcion as nom_tipofacturaDocumento','ee.descripcion as leyenda','eee.descripcion as tipoEmision')
           ->join('ven__factura_siat as f', 'r.id','=','f.id_venta')
           ->join('adm__sucursals as s', 's.id','=','r.id_sucursal')   
            ->leftJoin('adm__nacionalidads as n', 'n.id','=','r.moneda')           
    ->leftJoin('excel__emision as e', function ($join) use ($id_catalogo) {
        $join->on('e.codigo', '=', 'f.tipoFacturaDoc')
             ->where('e.id_catalogo', '=', $id_catalogo);
    })
    ->leftJoin('excel__emision as ee', function ($join) use ($id_catalogo_2) {
        $join->on('ee.codigo', '=', 'f.tipoFacturaDoc')
             ->where('ee.id_catalogo', '=', $id_catalogo_2);
    })
    ->leftJoin('excel__emision as eee', function ($join) use ($id_catalogo_3) {
        $join->on('eee.codigo', '=', 'f.codSector')
             ->where('eee.id_catalogo', '=', $id_catalogo_3);
    })
    ->where('r.id',$idVenta)
    ->first();

    if ($venta==null) {
        return response()->json([
            'error' => 1,
            'msn' => "no existe la venta",
            'total_literal' =>'cero',
            'venta'=> null,
            'detalle_venta'=>null,
             'empresa'=> null,
            'data_siat'=> null,
            'descuento_detalle_venta'=>0,
            'qr'=>null,
            'credito_fiscal' => null,
            'factura_' => null
        ]);
    }
 

      $detalle_venta = DB::table('ven__detalle_ventas as vd')
            ->select(
                'vd.id_venta as id',
                'vd.cantidad_venta as cant',
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
                    END AS descrip
                "),
                DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
             "), 
                'vd.precio_venta as p_u',
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

            if (count($detalle_venta)<=0) {
                return response()->json([
            'error' => 1,
            'msn' => "no exite detalle de venta",
            'total_literal' =>'cero',
            'venta'=> null,
            'detalle_venta'=>null,
             'empresa'=> null,
            'data_siat'=> null,
            'descuento_detalle_venta'=>0,
            'qr'=>null,
            'credito_fiscal' => null,
            'factura_' => null
        ]);
            }
    $descuento_detalle_venta =0;
    foreach ($detalle_venta as $key => $value) {
        $descuento_detalle_venta = $descuento_detalle_venta+$value->descuento;
    }

  $total_literal = converso_numero_a_texto::convertirNumeroATexto($venta->total_venta);

    $data_emp =  DB::table('adm__credecial_correos')
        ->select('nro_celular','nit','nom_empresa')    
        ->where('id', 1)
        ->first();
        if($data_emp==null){
    return response()->json([
            'error' => 1,
            'msn' => "no existe datos en la tabla de credenciales",
            'total_literal' =>'cero',
            'venta'=> null,
            'detalle_venta'=>null,
             'empresa'=> null,
            'data_siat'=> null,
            'descuento_detalle_venta'=>0,
            'qr'=>null,
            'credito_fiscal' => null,
            'factura_' => null
        ]);
        }

        $data_siat =  DB::table('siat__configuracions')
        ->select('tipo_ambiente','url_QR','tipo_modalidad')    
        ->where('id', 1)
        ->first();
        if($data_siat==null){
    return response()->json([
            'error' => 1,
            'msn' => "no existe datos en la tabla de configuracion",
            'total_literal' =>'cero',
            'venta'=> null,
            'detalle_venta'=>null,
             'empresa'=> null,
            'data_siat'=> null,
            'descuento_detalle_venta'=>0,          
            'qr'=>null,
            'credito_fiscal' => null,
            'factura_' => null
        ]);
        }
 //  https://pilotosiat.impuestos.gob.bo/consulta/QR?nit={nit_emisor}&cuf={cuf}&numero={nro_factura}&t={formato 1=rollo/ 2= A4 carta default= 2}    
$t_xd=2;
$url_xd=$data_siat->url_QR;
$numeroDocumento = $venta->nro_doc;
$num_auto=$venta->cod_auto;
$numeroFactura=$venta->numFactura;
$url = str_replace(
    ['{nit_emisor}', '{cuf}', '{nro_factura}','{formato 1=rollo/ 2= A4 carta default= 2}'],
    [$numeroDocumento, $num_auto, $numeroFactura, $t_xd],
    $url_xd
);

        $texto = $venta->nom_tipofacturaDocumento;
        $resultado = str_replace("FACTURA", "", strtoupper($texto));

         $texto_2= strtoupper($venta->tipoEmision);
        if ($texto_2=="FACTURA COMPRA-VENTA"|| "FACTURA COMPRA VENTA" || "COMPRA-VENTA" || "COMPRA VENTA") {
           $cadena_2="FACTURA";
        }else{
            $cadena_2=$texto_2;
        }

return response()->json([
            'error' => 0,
            'msn' => "Sin problema",
            'total_literal' =>$total_literal,
            'venta'=> $venta,
            'detalle_venta'=> $detalle_venta,
            'empresa'=> $data_emp,
            'data_siat'=>$data_siat,
            'descuento_detalle_venta'=>$descuento_detalle_venta,
            'qr'=>$url,
            'credito_fiscal' => $resultado,
            'factura_' => $cadena_2
        ]);


         
    } catch (\Throwable $th) {
      return response()->json([
            'error' => 1,
            'msn' => $th,
            'total_literal' =>'cero',
            'venta'=> null,
            'detalle_venta'=>null,
            'empresa'=> null,
            'data_siat'=> null,
            'descuento_detalle_venta'=>0,
            'qr'=>null,
            'credito_fiscal' => null,
            'factura_' => null
        ]);
    }

    }
}
