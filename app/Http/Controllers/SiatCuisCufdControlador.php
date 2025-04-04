<?php

namespace App\Http\Controllers;

use App\Models\Siat_Emisor;
use App\Models\Siat_Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use SoapClient;
use SoapHeader;

class SiatCuisCufdControlador extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = DB::table('siat__sucursals as ss')
    ->leftJoin('siat__emisors as se', 'se.id', '=', 'ss.id_emisor')
    ->leftJoin('siat__cuis as sc', 'se.id_cuis', '=', 'sc.id')
    ->leftJoin('siat__cufd as sf', 'se.id_cufd', '=', 'sf.id')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'ss.id_sucursal')
    ->select(
        'ss.id',
        'ss.nombre_suc_siat',
        'ss.codigo_siat',
        'ss.id_sucursal',
        'sc.id as id_cuis', 
        'sc.dato as cuis',
        'sc.fecha_vigencia as fecha_v_cuis',
        'sc.estado as estado_cuis',
        'sf.id as id_cufd',
        'sf.dato as cufd',
        'sf.fecha_vigencia as fecha_v_cufd',
        'sf.estado as estado_cufd',
        'ass.id as id_adm_sucursal',
        'ass.razon_social',
        'ass.direccion',
        'ss.id_emisor'
    )
    ->where('ss.estado', 1)
   
        ->paginate(15);
        return 
        [
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

    public function siat_fig(){
        $siatConfiguracion = DB::table('siat__configuracions')->where('id', 1)->first();
        return $siatConfiguracion;
    }
    

    public function solicitarCudf(Request $request){
        try {
   
            $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($request->codigo_ambiente))
        ->where('se.id',$request->endpoint)
        ->get(); 
        
        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;
            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key
        
        // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes        
        $xmlData = <<<EOD
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
            <soapenv:Header/>
            <soapenv:Body>
                <siat:cufd>
                    <SolicitudCufd>
                        <codigoAmbiente>{$request->codigo_ambiente}</codigoAmbiente>
                        <codigoModalidad>{$request->modalidad}</codigoModalidad>              
                        <codigoPuntoVenta>{$request->punto_venta}</codigoPuntoVenta>
                        <codigoSistema>{$request->codigo_sistema}</codigoSistema>
                        <codigoSucursal>{$request->codigo_siat}</codigoSucursal>
                        <cuis>{$request->cuis}</cuis>
                        <nit>{$request->nit}</nit>
                    </SolicitudCufd>
                </siat:cufd>
            </soapenv:Body>
        </soapenv:Envelope>
        EOD;
        $tiempoEspera = DB::table('siat__configuracions')
        ->where('id', 1)
        ->value('tiempo_espera'); // Obtiene directamente el valor de la columna
        if (is_null($tiempoEspera)) {
            $tiempoEspera = 30;
        } 
            // Inicializar cURL
            $ch = curl_init();

            // Configuración de la solicitud cURL
            curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
            //  Agregar timeout (en segundos)
            curl_setopt($ch, CURLOPT_TIMEOUT, $tiempoEspera); // Espera máximo 30 segundos para la respuesta completa
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Espera máximo 10 segundos para conectarse
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
                $cadena_22=curl_error($ch);
                return "Error: ".$cadena_22;
               // throw new \Exception(curl_error($ch));
            }
          
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code != 200) {
    switch ($http_code) {
         
        case 500:
            return "500 Internal Server Error” - Es un error genérico que indica que el servidor encontró una condición inesperada y no puede cumplir con la solicitud. El servidor te dice que hay algo mal, pero no está seguro de cuál es el problema.";
            break;        
        case 501:
            return "501 Not Implemented” - El servidor no admite el método de solicitud o no tiene la capacidad de cumplir con la solicitud.";
            break;
        case 502:
            return "502 Bad Gateway - Este error indica que el servidor actuó como puerta de enlace o proxy y recibió una respuesta no válida del servidor ascendente. Esta es la descripción oficial, pero hay varios factores que pueden causar este error.";
            break;
        case 503:
            return "503 Service Unavailable - El servidor no puede manejar la solicitud. Esto suele ser una condición temporal causada por una sobrecarga o mantenimiento continuo en el servidor.";
            break;
        case 504:
            return "504 “Gateway Timeout” - El servidor actuó como puerta de enlace y no recibió una respuesta oportuna del servidor ascendente. En la mayoría de los casos, este error es causado por scripts PHP que no terminan a tiempo y exceden el límite de tiempo de espera.";
            break;
        case 505:
            return "505 “HTTP Version Not Supported” - El servidor no es compatible con la versión HTTP utilizada en la solicitud.";
            break;
        case 506:
            return "506 Variant Also Negotiates - Este error ocurre cuando el cliente y el servidor entran en Negociación de Contenido Transparente, que permite al cliente recuperar la mejor variante de un recurso cuando el servidor soporta múltiples versiones.";
            break;
        case 507:
            return "507 Insufficient Storage (WebDAV) - El servidor no puede almacenar la representación requerida para completar la solicitud.";
            break;
        case 508:
            return "508 Loop Detected (WebDAV) - El servidor detectó un bucle infinito al procesar la solicitud.";
            break;  
        case 510:
            return "510 Not Extended - Se requieren más extensiones a la solicitud para que el servidor la cumpla. Este código ya no está disponible.";
            break;
        case 511:
            return "511 Network Authentication Required - Esta respuesta se envía cuando necesitas ser autenticado para que la red pueda enviar tu solicitud a un servidor. Más comúnmente, se ve al intentar usar una red Wi-Fi, y debe aceptar sus Términos de acuerdo.";
            break;           
           
        default:
        return "Error 1: ".$http_code;
            break;
    }
   
}
            // Cerrar la sesión de cURL
            curl_close($ch);
            // Verificar respuesta SOAP
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
                    $respuesta=0;
                    $codigo_2 = $xml->xpath('//codigo');
                    $fechaVigencia= $xml->xpath('//fechaVigencia');
                    $direccion= $xml->xpath('//direccion');
                    $codigoControl= $xml->xpath('//codigoControl');
                    
                    $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
               
                    $actualizar = Siat_Emisor::findOrFail($request->id_emisor);
               
                    $existe_cufd = DB::table('siat__cufd')->where('dato', $request->cufd)->first();

                    if ($existe_cufd) {     
                                        
                        DB::table('siat__cufd')->where('dato', $request->cufd)->update(['estado' =>0,'id_emisor' =>$request->id_emisor]);
                        $datos_2=[                        
                            'dato' => $codigo_2[0],
                            'fecha_vigencia' => $fechaVigencia[0],
                            'created_at' => $fechaActual,
                            'id_emisor' => $request->id_emisor,
                            'codigoControl' => $codigoControl[0],
                            'direccion' => $direccion[0]                            
                        ];
                        $id_cufd = DB::table('siat__cufd')->insertGetId($datos_2);

                    } else {             
                       
                        $datos_2=[                        
                            'dato' => $codigo_2[0],
                            'fecha_vigencia' => $fechaVigencia[0],
                            'created_at' => $fechaActual,
                            'id_emisor' => $request->id_emisor,
                            'codigoControl' => $codigoControl[0],
                            'direccion' => $direccion[0]    
                        ];
                        $id_cufd = DB::table('siat__cufd')->insertGetId($datos_2);
                    }
                    $actualizar->id_cufd = $id_cufd;    
                    $actualizar->save();              
                   
                    return $respuesta;
            } else {
                $codigo_2 = $xml->xpath('//codigo');
                $descripcion = DB::table('excel__emision')
                ->where('id_catalogo', 5)
                ->where('codigo', 902)
                ->value('descripcion'); // Obtiene directamente el valor de la columna
                if (is_null($descripcion)) {
                    return "Error siat ".$respuesta;
                } else{
                   return $descripcion; 
                }
            }                
    } else {         
        return $respuesta;
    } 
            
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Error en la solicitud SOAP',
                'message' => $e->getMessage()
            ]);
        }       
    }

    public function solicitarCuis(Request $request){

        try {
            $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($request->codigo_ambiente))
        ->where('se.id',$request->cuis_end)
        ->get(); 
    
        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;
            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key

         // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
         $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
            <soapenv:Header/>
            <soapenv:Body>
               <siat:cuis>
                  <SolicitudCuis>
                     <codigoAmbiente>{$request->codigo_ambiente}</codigoAmbiente>
                     <codigoModalidad>{$request->modalidad}</codigoModalidad>
                     <codigoPuntoVenta>{$request->emisor}</codigoPuntoVenta>
                     <codigoSistema>{$request->codigo_sistema}</codigoSistema>
                     <codigoSucursal>{$request->codigo_siat}</codigoSucursal>
                     <nit>{$request->nit}</nit>
                  </SolicitudCuis>
               </siat:cuis>
            </soapenv:Body>
         </soapenv:Envelope>
         EOD;
         $tiempoEspera = DB::table('siat__configuracions')
         ->where('id', 1)
         ->value('tiempo_espera'); // Obtiene directamente el valor de la columna
         if (is_null($tiempoEspera)) {
             $tiempoEspera = 30;
         } 
            // Inicializar cURL
            $ch = curl_init();

            // Configuración de la solicitud cURL
            curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
            curl_setopt($ch, CURLOPT_POST, 1);            
  curl_setopt($ch, CURLOPT_TIMEOUT, $tiempoEspera); // Espera máximo 30 segundos para la respuesta completa
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Espera máximo 10 segundos para conectarse
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
                $cadena_22=curl_error($ch);
                return "Error: ".$cadena_22;
               // throw new \Exception(curl_error($ch));
            }
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                switch ($http_code) {
                     
                    case 500:
                        return "500 Internal Server Error” - Es un error genérico que indica que el servidor encontró una condición inesperada y no puede cumplir con la solicitud. El servidor te dice que hay algo mal, pero no está seguro de cuál es el problema.";
                        break;        
                    case 501:
                        return "501 Not Implemented” - El servidor no admite el método de solicitud o no tiene la capacidad de cumplir con la solicitud.";
                        break;
                    case 502:
                        return "502 Bad Gateway - Este error indica que el servidor actuó como puerta de enlace o proxy y recibió una respuesta no válida del servidor ascendente. Esta es la descripción oficial, pero hay varios factores que pueden causar este error.";
                        break;
                    case 503:
                        return "503 Service Unavailable - El servidor no puede manejar la solicitud. Esto suele ser una condición temporal causada por una sobrecarga o mantenimiento continuo en el servidor.";
                        break;
                    case 504:
                        return "504 “Gateway Timeout” - El servidor actuó como puerta de enlace y no recibió una respuesta oportuna del servidor ascendente. En la mayoría de los casos, este error es causado por scripts PHP que no terminan a tiempo y exceden el límite de tiempo de espera.";
                        break;
                    case 505:
                        return "505 “HTTP Version Not Supported” - El servidor no es compatible con la versión HTTP utilizada en la solicitud.";
                        break;
                    case 506:
                        return "506 Variant Also Negotiates - Este error ocurre cuando el cliente y el servidor entran en Negociación de Contenido Transparente, que permite al cliente recuperar la mejor variante de un recurso cuando el servidor soporta múltiples versiones.";
                        break;
                    case 507:
                        return "507 Insufficient Storage (WebDAV) - El servidor no puede almacenar la representación requerida para completar la solicitud.";
                        break;
                    case 508:
                        return "508 Loop Detected (WebDAV) - El servidor detectó un bucle infinito al procesar la solicitud.";
                        break;  
                    case 510:
                        return "510 Not Extended - Se requieren más extensiones a la solicitud para que el servidor la cumpla. Este código ya no está disponible.";
                        break;
                    case 511:
                        return "511 Network Authentication Required - Esta respuesta se envía cuando necesitas ser autenticado para que la red pueda enviar tu solicitud a un servidor. Más comúnmente, se ve al intentar usar una red Wi-Fi, y debe aceptar sus Términos de acuerdo.";
                        break;           
                       
                    default:
                    return "Error 1: ".$http_code;
                        break;
                }
               
            }
            // Cerrar la sesión de cURL
            curl_close($ch);
        
             return $response; 
            
         /** 
          *   $soapResponse2 = <<<XML
            * <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              *  <soap:Body>
                *   <ns2:cuisResponse xmlns:ns2="https://siat.impuestos.gob.bo/">
                   *   <RespuestaCuis>
                  *       <codigo>271F1913</codigo>
                 *        <fechaVigencia>2026-01-27T12:15:22.082-04:00</fechaVigencia>
                *         <transaccion>true</transaccion>
               *       </RespuestaCuis>
              *     </ns2:cuisResponse>
             *   </soap:Body>
            * </soap:Envelope>
           *  XML;
           *  return $soapResponse2;
         */  
         
             
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Error en la solicitud SOAP',
                'message' => $e->getMessage()
            ]);
        }       
    }

   public function crear_cuis(Request $request){
    
    try {
        DB::beginTransaction();
        $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
        $id = $request->id;        
        $cuis = $request->cuis;
        $fecha = $request->fecha;  

        $datos_2=[
            'dato' => $cuis,
            'fecha_vigencia' => $fecha,
            'created_at' => $fechaActual,
        ];
        $id_cuis = DB::table('siat__cuis')->insertGetId($datos_2);

        $crear = new Siat_Emisor();
        $crear->nombre="Punto de venta 0";
        $crear->descripcion="sucursal";
        $crear->id_siat_sucursal=$id;
        $crear->tipo=1000;
        $crear->id_punto_venta=0;      
        $crear->id_usuario_registra=auth()->user()->id;
        $crear->id_usuario_modifica=auth()->user()->id;
        $crear->id_cuis=$id_cuis;
        $crear->save();
        
        $actualizar = Siat_Sucursal::findOrFail($id);
        $actualizar->id_emisor = $crear->id;    
        $actualizar->save();
        
        $datos = [
            'id_modulo' => $request->id_modulo,
            'id_sub_modulo' => $request->id_sub_modulo,
            'accion' => 2,
            'descripcion' => $request->des,          
            'user_id' =>auth()->user()->id, 
            'created_at'=>$fechaActual,
            'id_movimiento'=>$id_cuis,   
        ];    
        DB::table('log__sistema')->insert($datos);

        DB::commit();
    } catch (\Throwable $th) {
       return $th;
    }
   }

   public function get_cancelar_operacion(Request $request){
        
    try {
        
        $emisores = DB::table('siat__emisors')
        ->where('id_siat_sucursal', $request->id)
        ->where('id_punto_venta', '<>', 0)
        ->where('estado', 1)
        ->pluck('id');
    
        if (count($emisores)>0) {
            return "error_1";
        } else {      
            
            $endPoints = DB::table('siat__endpoints as se')    
            ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
            ->where('se.tipo', intval($request->codigo_ambiente))
            ->where('se.id',$request->cuis_end)
            ->get();            
         

        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;

            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key

         // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
        //en punto de venta Solo se envía cuando la transacción se realiza utilizando un punto de venta. Caso contrario enviar 0.
         
         $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
            <soapenv:Header/>
            <soapenv:Body>
               <siat:cierreOperacionesSistema>
                  <SolicitudOperaciones>
                     <codigoAmbiente>$request->codigo_ambiente</codigoAmbiente>
                     <codigoModalidad>$request->modalidad</codigoModalidad>
                     <!--Optional:-->
                     <codigoPuntoVenta>$request->emisor</codigoPuntoVenta>
                     <codigoSistema>$request->codigo_sistema</codigoSistema>
                     <codigoSucursal>$request->codigo_siat</codigoSucursal>
                     <cuis>$request->cuis</cuis>
                     <nit>$request->nit</nit>
                  </SolicitudOperaciones>
               </siat:cierreOperacionesSistema>
            </soapenv:Body>
         </soapenv:Envelope>
         EOD;
         $tiempoEspera = DB::table('siat__configuracions')
         ->where('id', 1)
         ->value('tiempo_espera'); // Obtiene directamente el valor de la columna
         if (is_null($tiempoEspera)) {
             $tiempoEspera = 30;
         } 
            // Inicializar cURL
            $ch = curl_init();

            // Configuración de la solicitud cURL
            curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $tiempoEspera); // Espera máximo 30 segundos para la respuesta completa
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Espera máximo 10 segundos para conectarse
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
                $cadena_22=curl_error($ch);
                return "Error: ".$cadena_22;
               // throw new \Exception(curl_error($ch));
            }
                     
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                switch ($http_code) {
                     
                    case 500:
                        return "500 Internal Server Error” - Es un error genérico que indica que el servidor encontró una condición inesperada y no puede cumplir con la solicitud. El servidor te dice que hay algo mal, pero no está seguro de cuál es el problema.";
                        break;        
                    case 501:
                        return "501 Not Implemented” - El servidor no admite el método de solicitud o no tiene la capacidad de cumplir con la solicitud.";
                        break;
                    case 502:
                        return "502 Bad Gateway - Este error indica que el servidor actuó como puerta de enlace o proxy y recibió una respuesta no válida del servidor ascendente. Esta es la descripción oficial, pero hay varios factores que pueden causar este error.";
                        break;
                    case 503:
                        return "503 Service Unavailable - El servidor no puede manejar la solicitud. Esto suele ser una condición temporal causada por una sobrecarga o mantenimiento continuo en el servidor.";
                        break;
                    case 504:
                        return "504 “Gateway Timeout” - El servidor actuó como puerta de enlace y no recibió una respuesta oportuna del servidor ascendente. En la mayoría de los casos, este error es causado por scripts PHP que no terminan a tiempo y exceden el límite de tiempo de espera.";
                        break;
                    case 505:
                        return "505 “HTTP Version Not Supported” - El servidor no es compatible con la versión HTTP utilizada en la solicitud.";
                        break;
                    case 506:
                        return "506 Variant Also Negotiates - Este error ocurre cuando el cliente y el servidor entran en Negociación de Contenido Transparente, que permite al cliente recuperar la mejor variante de un recurso cuando el servidor soporta múltiples versiones.";
                        break;
                    case 507:
                        return "507 Insufficient Storage (WebDAV) - El servidor no puede almacenar la representación requerida para completar la solicitud.";
                        break;
                    case 508:
                        return "508 Loop Detected (WebDAV) - El servidor detectó un bucle infinito al procesar la solicitud.";
                        break;  
                    case 510:
                        return "510 Not Extended - Se requieren más extensiones a la solicitud para que el servidor la cumpla. Este código ya no está disponible.";
                        break;
                    case 511:
                        return "511 Network Authentication Required - Esta respuesta se envía cuando necesitas ser autenticado para que la red pueda enviar tu solicitud a un servidor. Más comúnmente, se ve al intentar usar una red Wi-Fi, y debe aceptar sus Términos de acuerdo.";
                        break;           
                       
                    default:
                    return "Error 1: ".$http_code;
                        break;
                }
               
            }
             // Cerrar la sesión de cURL
             curl_close($ch);
          
            return $response; 
                     
        }    
        /*
           $soapResponse2 = <<<XML
             <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                <ns2:cierreOperacionesSistemaResponse xmlns:ns2="https://siat.impuestos.gob.bo/">
                    <RespuestaCierreSistemas><codigoSistema>7CB3E3D13E3F5D0D6422E06</codigoSistema>
                    <transaccion>true</transaccion>
                    </RespuestaCierreSistemas>
                </ns2:cierreOperacionesSistemaResponse>
                </soap:Body>
             </soap:Envelope>
             XML;
             return $soapResponse2;
        */ 
              
    }  catch (\Exception $e) {
        // Manejo de excepciones
        return response()->json([
            'error' => 'Error en la solicitud SOAP',
            'message' => $e->getMessage()
        ]);
    } 
   }

   public function eliminar_operaciones(Request $request){
    try {
        DB::beginTransaction();
        $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
        $id = $request->id;         
        $id_cuis=$request->id_cuis;        
        $id_emisor=$request->id_emisor;
        $id_cufd=$request->id_cufd;
          // Si el registro existe, actualizar el dato (cuis)
    DB::table('siat__cuis')->where('id', $id_cuis)->update(['estado' =>0,'id_emisor' =>$id_emisor ]);
    DB::table('siat__cufd')->where('id', $id_cufd)->update(['estado' =>0,'id_emisor' =>$id_emisor]);
    DB::table('siat__emisors')->where('id', $id_emisor)->update(['estado' =>0,'descripcion'=>'sucursal eliminada']);
    DB::table('siat__sucursals')->where('id', $id)->update(['id_emisor' =>null]);
        
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


   public function perdirCufd_all(Request $request){
    try {
           
        $datos_1 = DB::table('siat__configuracions')->where('id', 1)->first();
        $datos_2 = DB::table('adm__credecial_correos')->where('id', 1)->first();  
        $nit=$datos_2->nit;
            $configuracion = DB::table('siat__emisors as s')
            ->join('siat__sucursals as ss', 'ss.id', '=', 's.id_siat_sucursal')
            ->join('siat__cuis as c', 'c.id', '=', 's.id_cuis')
            ->leftJoin('siat__cufd as cu', 'cu.id', '=', 's.id_cufd')
            ->where('s.estado', 1)->where('c.estado', 1)
            ->select('s.id','s.nombre as nombre_emisor','s.id_punto_venta as punto_venta','ss.nombre_suc_siat','ss.codigo_siat','c.dato','cu.dato as cufd','cu.id as cufd_id')->get();
            
                $tamaño=count($configuracion);
              
                foreach ($configuracion as $key => $value) { 
                    $endPoints = DB::table('siat__endpoints as se')    
                    ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
                    ->where('se.tipo', intval($datos_1->tipo_ambiente))
                    ->where('se.id',3)
                    ->get(); 
                 
                    
                    $cadena_url=$endPoints[0]->Url; 
                    $wsdl = $cadena_url;
                        // Asignación de la URL y API key
                        $wsdl = $cadena_url; 
                        $apikeyValue = 'TokenApi ' .$datos_1->token_delegado; // Concatenar correctamente el valor del API key
                    
                    // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes        
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <siat:cufd>
                                <SolicitudCufd>
                                    <codigoAmbiente>{$datos_1->tipo_ambiente}</codigoAmbiente>
                                    <codigoModalidad>{$datos_1->tipo_modalidad}</codigoModalidad>              
                                    <codigoPuntoVenta>{$value->punto_venta}</codigoPuntoVenta>
                                    <codigoSistema>{$datos_1->cod_sis}</codigoSistema>
                                    <codigoSucursal>{$value->codigo_siat}</codigoSucursal>
                                    <cuis>{$value->dato}</cuis>
                                    <nit>{$nit}</nit>
                                </SolicitudCufd>
                            </siat:cufd>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                    $tiempoEspera = DB::table('siat__configuracions')
                    ->where('id', 1)
                    ->value('tiempo_espera'); // Obtiene directamente el valor de la columna
                    if (is_null($tiempoEspera)) {
                        $tiempoEspera = 30;
                    } 
            
                        // Inicializar cURL
                        $ch = curl_init();
            
                        // Configuración de la solicitud cURL
                        curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
                        curl_setopt($ch, CURLOPT_TIMEOUT, $tiempoEspera); // Espera máximo 30 segundos para la respuesta completa
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Espera máximo 10 segundos para conectarse
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
                $cadena_22=curl_error($ch);
                return "Error: ".$cadena_22;
               // throw new \Exception(curl_error($ch));
            }
            
                        // Cerrar la sesión de cURL
                        curl_close($ch);
            
                  
                    $xml = simplexml_load_string($response);
            
                    $respuesta=$response;
                  
                    // Usar XPath para encontrar el nodo <transaccion>    
                    $transaccion = $xml->xpath('//transaccion');
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {                     
                                $respuesta=0;
                                $codigo_2 = $xml->xpath('//codigo');
                                $fechaVigencia= $xml->xpath('//fechaVigencia');
                                $direccion= $xml->xpath('//direccion');
                                $codigoControl= $xml->xpath('//codigoControl');                                
                                $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
                           
                                $actualizar = Siat_Emisor::findOrFail($value->id);
                               
                                $existe_cufd = DB::table('siat__cufd')->where('dato', $value->cufd)->first();
                            
                                if ($existe_cufd) {     
                                                    
                                    DB::table('siat__cufd')->where('dato', $value->cufd)->where('id',$value->cufd_id)->update(['estado' =>0,'id_emisor' =>$value->id]);
                                    $datos_3=[                        
                                        'dato' => $codigo_2[0],
                                        'fecha_vigencia' => $fechaVigencia[0],
                                        'created_at' => $fechaActual,
                                        'id_emisor' => $value->id,
                                        'codigoControl' => $codigoControl[0],
                                        'direccion' => $direccion[0] 
                                    ];
                                    $id_cufd = DB::table('siat__cufd')->insertGetId($datos_3);
            
                                } else {             
                                   
                                    $datos_3=[                        
                                        'dato' => $codigo_2[0],
                                        'fecha_vigencia' => $fechaVigencia[0],
                                        'created_at' => $fechaActual,
                                        'id_emisor' => $value->id,
                                        'codigoControl' => $codigoControl[0],
                                        'direccion' => $direccion[0] 
                                    ];
                                    $id_cufd = DB::table('siat__cufd')->insertGetId($datos_3);
                                }
                                $actualizar->id_cufd = $id_cufd;    
                                $actualizar->save();              
                                $tamaño--;
                               
                        }              
                }             
                }
            
            if ($tamaño==0) {
                return $tamaño;
            }else{
                return 1;
            }
    
    } catch (\Throwable $th) {
        return $th;
    }
}
}