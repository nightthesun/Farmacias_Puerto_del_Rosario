<?php

namespace App\Http\Controllers;

use App\Models\Siat_evento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $data= $request->inicial;

        switch ($data) {
            case 1:
                    $sucursal = $request->id_sucursal;
                    if($sucursal=='10000'){
                    $sqls="( ss.id <> 0)" ;
                    }else{
                    $sqls="( ss.id = '".(int)$sucursal."' )" ;
                    }
                                     
            break;
            case 2:
                    $num_factura = $request->num_factura;
                 $sqls="( f.numFactura = '".(int)$num_factura."' )" ;                    
            break;
            case 3:
                         $cuf = $request->cuf;
                 $sqls="( f.cuf = '".$cuf."' )" ;                    
            break;
            case 4:           
        $sector = $request->sector;
            $sqls="( f.codSector = '".(int)$sector."' )" ;                   
            break;
            case 5:           
         $estado = $request->estado;
                 $sqls="( f.codEstado = '".$estado."' )" ;                    
            break;
             case 6:           
         $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
              $sqls = "( f.fechaEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' )";
                
            break;
            
            default:
                 $sqls="( ss.id <> 0)" ;
                break;
        }

        $index = DB::table('ven__factura_siat as f')
    ->join('ven__recibos as r', 'r.id', '=', 'f.id_venta')
    ->join('adm__sucursals as ss', 'ss.id', '=', 'r.id_sucursal')
    ->leftJoin('siat__sucursals as s', 'r.id_sucursal', '=', 's.id_sucursal')
    ->join('excel__emision as e', function ($join) {
        $join->on('e.codigo', '=', 'f.codSector')
             ->where('e.id_catalogo', 3);
    })
    ->whereRaw($sqls)
    ->select(
        'f.id',
        'f.id_venta',
        'f.id_cufd',
        'f.id_cuis',
        'f.cuf',
        DB::raw('f.sucursal_siat as punto_suc'),
        'f.punto_venta',
        'f.numFactura',
        'f.fechaEmision',
        'f.codRecepcion',
        'f.codDescripcion',
        'f.codEstado',
        'f.codSector',
        'f.tipo_contigencia',
        DB::raw('r.id as id_venta'),
        'r.nro_doc',
        DB::raw('r.razon_social as nom_cliente'),
        DB::raw('ss.id as id_sucursal'),
        DB::raw('ss.razon_social as nomre_sucursal'),
        DB::raw('e.descripcion as sector_descripcion'),
        DB::raw('e.codigo as cod_sector'),
        's.id as id_suc_siat',
    )
  
    ->orderBy('f.id', 'desc')
    ->paginate(15);
         return   [
                    'pagination'=>
                        [
                            'total'         =>    $index->total(),
                            'current_page'  =>    $index->currentPage(),
                            'per_page'      =>    $index->perPage(),
                            'last_page'     =>    $index->lastPage(),
                            'from'          =>    $index->firstItem(),
                            'to'            =>    $index->lastItem(),
                        ] ,
                    'index'=>$index,
            ];
    }

    public function getEmisorAndSector(){

        $query_1 = DB::table('siat__emisors as e')
    ->join('siat__sucursals as s', 'e.id_siat_sucursal', '=', 's.id')
    ->join('adm__sucursals as ss', 'ss.id', '=', 's.id_sucursal')
    ->select(
        'e.id as id_emisor',
        'e.id_punto_venta',
        'e.nombre as nombre_emisor',
        'e.id_siat_sucursal',
        's.nombre_suc_siat',
        's.codigo_siat',
        'ss.razon_social',
        'ss.id as id_sucursal'
    )
    ->where('e.estado', 1)
    ->get();

    if (count($query_1)>0) {
        $error_1=1;
    }else{
        $error_1=0;
    }

    $query_2 = DB::table('excel__emision')
    ->select(
        'codigo',
        'id_catalogo',
        'descripcion'
    )
    ->where('id_catalogo', 3)
    ->get();
    if (count($query_2)>0) {
        $error_2=1;
    }else{
        $error_2=0;
    }

    return response()->json(['emisor_query_1' => $query_1, 'error_1' => $error_1,
                            'sector_query_2' => $query_2,'error_2' => $error_2                          
    ]);
   
    }

    public function getQueryModal_1(Request $request){
       
                
    $contigencia = DB::table('excel__emision')
            ->select('*')
            ->where('id_catalogo', 6)
            ->where('codigo', (int)$request->id_contigencia)
            ->first();

            $sucursal = DB::table('adm__sucursals as ass')
            ->join('siat__sucursals as ss', 'ass.id', '=', 'ss.id_sucursal')
            ->join('siat__emisors as e', 'ss.id_emisor', '=', 'e.id')
            ->select(
                'ass.razon_social',
                'ss.nombre_suc_siat'
            )
            ->where('ass.id', (int)$request->id_sucursal)
            ->where('ss.codigo_siat', (int)$request->suc)
            ->first();
        
    return response()->json(['contigencia' => $contigencia, 'sucursal' => $sucursal]);        
     
    }

    
    public function sendContingencia(Request $request){

            //    return $request->all();
            try {

                DB::beginTransaction();
                  $contigenciaDesCodigo=$request->contigenciaDes_codigo_modal;
                $codigoMotivoEvento=$request->contingencia;
                $descripcion=$request->contingencia_descripcion_modal;

                $fechaHoraFinEvento = Carbon::parse($request->endDate_modal_1)->format('Y-m-d\TH:i:s.v');
                
                $id_cufd_modal=$request->id_cufd_modal;
                //id_sucursal_modal:1;
                $codigoSucursal=$request->punto_suc;
                $codigoPuntoVenta=$request->punto_venta;
                $fechaHoraInicioEvento = Carbon::parse($request->startDate_modal_1)->format('Y-m-d\TH:i:s.v');
                $id_suc_siat=$request->id_suc_siat;

               
                $query_1 = DB::table('siat__emisors as e')
    ->leftJoin('siat__cuis as cuis', 'cuis.id', '=', 'e.id_cuis')
    ->leftJoin('siat__cufd as cufd', 'cufd.id', '=', 'e.id_cufd')
    ->where('e.id_siat_sucursal', $id_suc_siat)
    ->where('e.estado', 1)
    ->where('e.id_punto_venta', $codigoPuntoVenta)
    ->where('e.delete', 0)
    ->select('cuis.dato as cuis','cufd.dato as cufd')
    ->first();
                if (!$query_1) {
                    return "no exite el cuis o cufd para esta sucursal y punto de venta";
                }
        
        $cuis=$query_1->cuis;
        $cufd=$query_1->cufd;

       $query_2 = DB::table('siat__configuracions as e')    
    ->where('e.id', 1)
    ->select('e.cod_sis','e.tipo_ambiente','e.token_delegado','e.tiempo_espera','e.tipo_modalidad')
    ->first();
    
    if (!$query_2) {
        return "La tabla de configuracion no tiene los datos para hacer esta operacion.";
        }

    $codigoSistema=$query_2->cod_sis;
    $codigoAmbiente=$query_2->tipo_ambiente;
    $tokenDelegado=$query_2->token_delegado;
    $tiempoEspera=$query_2->tiempo_espera;
    $tipoModalidad=$query_2->tipo_modalidad;

        $cufdAnterior = DB::table('siat__cufd')
        ->where('id', $id_cufd_modal)
        ->value('dato');

        if ($cufdAnterior==null) {
        return "No existe el cufd o la tabla.";
        }
        
       $nit = DB::table('adm__credecial_correos')
        ->where('id',1)
        ->value('nit');

        if ($nit==null) {
        return "NIT sin configurar.";
        }

        $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigoAmbiente))
        ->where('se.id', 2)
        ->first(); 

                if (!$endPoints) {
                    return "no exite la tabla o el id fue cambiado ya que debe ser el 2 como id pivote";
                }

        $cadena_url=$endPoints->Url; 
         
        $wsdl = $cadena_url;
        // Asignación de la URL y API key
        $apikeyValue = 'TokenApi ' .$tokenDelegado; // Concatenar correctamente el valor del API key
                // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
    
        $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
            <soapenv:Header/>
            <soapenv:Body>
               <siat:registroEventoSignificativo>
                  <SolicitudEventoSignificativo>
                    <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                    <codigoMotivoEvento>{$codigoMotivoEvento}</codigoMotivoEvento>
                    <codigoPuntoVenta>{$codigoPuntoVenta}</codigoPuntoVenta>
                    <codigoSistema>{$codigoSistema}</codigoSistema>
                    <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                    <cufd>{$cufd}</cufd>
                    <cufdEvento>{$cufdAnterior}</cufdEvento>
                    <cuis>{$cuis}</cuis>
                    <descripcion>{$descripcion}</descripcion>
                    <fechaHoraFinEvento>{$fechaHoraFinEvento}</fechaHoraFinEvento>
                    <fechaHoraInicioEvento>{$fechaHoraInicioEvento}</fechaHoraInicioEvento>
                    <nit>{$nit}</nit>
                  </SolicitudEventoSignificativo>
               </siat:registroEventoSignificativo>
            </soapenv:Body>
         </soapenv:Envelope>
         EOD;
           $ch = curl_init();
            
                        // Configuración de la solicitud cURL
                        curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
                    
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: text/xml; charset=utf-8',
                            'SOAPAction: ""', // Si el SOAPAction es requerido, inclúyelo aquí
                            'apikey: ' . $apikeyValue // Incluye la API key con el valor correspondiente
                        ]);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
                        // Ejecutar la solicitud y obtener la respuesta
                        $response = curl_exec($ch);
                       
                        // Verificar si hubo un error en cURL
                        if (curl_errno($ch)) {
                            throw new \Exception(curl_error($ch));
                        }            
                        // Cerrar la sesión de cURL
                        curl_close($ch);
                if (empty($response)) {
    return("Error 2: ".$response);
}
    // Convertir la respuesta en un objeto SimpleXMLElement
        $xml = simplexml_load_string($response);   
        $respuesta=$response;
         // Usar XPath para encontrar el nodo <transaccion>    
        $transaccion = $xml->xpath('//transaccion');
         if ($transaccion && isset($transaccion[0])) {

            if ($transaccion[0]== 'true') {                     
                
                  //  $codigo_2 = $xml->xpath('//codigo');
                 //   $fechaVigencia= $xml->xpath('//fechaVigencia');
                 $codigoRecepcionEventoSignificativo=$xml->xpath('//codigoRecepcionEventoSignificativo');
                                
                    $dataa = simplexml_load_string($codigoRecepcionEventoSignificativo);   
                    return $codigoRecepcionEventoSignificativo;


            } else {
               return $respuesta;
            }                
    } else {         
        return $respuesta;
    } 
        return 0;

                 DB::commit();
            } catch (\Throwable $th) {
                return $th;
            }             

    }


    
}
