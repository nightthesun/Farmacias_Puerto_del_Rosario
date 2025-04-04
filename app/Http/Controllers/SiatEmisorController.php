<?php

namespace App\Http\Controllers;

use App\Models\Siat_Emisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class SiatEmisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        $index = DB::table('siat__emisors as se')
        ->leftJoin('siat__sucursals as ss', 'ss.id', '=', 'se.id_siat_sucursal')
        ->leftJoin('caja__creacions as sc', 'sc.id', '=', 'se.id_caja')
        ->leftJoin('excel__emision as ee', function ($join) {
            $join->on('ee.codigo', '=', 'se.tipo')
                 ->where('ee.id_catalogo', 13);
        })
        ->leftJoin('siat__cuis as cui', 'cui.id', '=', 'se.id_cuis')
        ->leftJoin('siat__cufd as cuf', 'cuf.id', '=', 'se.id_cufd')
        ->join('users as u', 'u.id', '=', 'se.id_usuario_modifica')
        ->select(
            'se.id',
            'se.nombre',
            'se.descripcion',
            'se.tipo',
            'se.id_punto_venta',
            'se.estado',
            'ss.nombre_suc_siat',
            'ss.codigo_siat',
            'sc.nombre_caja',
            'ee.descripcion as descripcion_tipo',
            'se.id_cuis', 
            'cui.dato as cuis',
            'cui.fecha_vigencia as fecha_cuis',
            'cui.estado as cuis_estado',
            'se.id_cufd',
            'cuf.dato as cufd',
            'cuf.fecha_vigencia as fecha_cufd',
            'cuf.estado as cufd_estado',
            'se.id_caja'
        )
        ->where('se.id_siat_sucursal', $request->id)       
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

    public function registrarPuntoV(Request $request){

        try {
            $endPoints = DB::table('siat__endpoints as se')    
            ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
            ->where('se.tipo', $request->codigoAmbiente)
            ->where('se.id', 2)
            ->get();
            $cadena_url=$endPoints[0]->Url; 
            $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $wsdl = $cadena_url; 
                $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key
    
             // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
        
             $codigoAmbiente = $request->codigoAmbiente;
             $codigoModalidad = $request->codigoModalidad;
             $codigoSistema =  $request->codigoSistema;
             $codigoSucursal = $request->codigoSucursal;
             $codigoTipoPuntoVenta = $request->codigoTipoPuntoVenta;
             $cuis = $request->cuis;
             $descripcion = $request->descripcion;
             $nit = $request->nit;
             $nombrePuntoVenta = $request->nombrePuntoVenta;
             
             $xmlData = <<<EOD
             <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                <soapenv:Header/>
                <soapenv:Body>
                   <siat:registroPuntoVenta>
                      <SolicitudRegistroPuntoVenta>
                         <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                         <codigoModalidad>{$codigoModalidad}</codigoModalidad>
                         <codigoSistema>{$codigoSistema}</codigoSistema>
                         <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                         <codigoTipoPuntoVenta>{$codigoTipoPuntoVenta}</codigoTipoPuntoVenta>
                         <cuis>{$cuis}</cuis>
                         <descripcion>{$descripcion}</descripcion>
                         <nit>{$nit}</nit>
                         <nombrePuntoVenta>{$nombrePuntoVenta}</nombrePuntoVenta>
                      </SolicitudRegistroPuntoVenta>
                   </siat:registroPuntoVenta>
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
               // dd($response);               
                return $response; 
                
                /*
                $soapResponse2 = <<<XML
               <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                   <soap:Body>
                       <ns2:registroPuntoVentaResponse xmlns:ns2="https://siat.impuestos.gob.bo/">
                           <RespuestaRegistroPuntoVenta>
                               <codigoPuntoVenta>2</codigoPuntoVenta>
                               <transaccion>true</transaccion>
                           </RespuestaRegistroPuntoVenta>
                       </ns2:registroPuntoVentaResponse>
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Iniciar una transacción
           DB::beginTransaction();
          $crear = new Siat_Emisor();
          $crear->nombre=$request->nombre;
          $crear->descripcion=$request->descripcion;
          $crear->id_siat_sucursal=$request->id_siat_sucursal;
          $crear->tipo=$request->tipo;
          $crear->id_caja = $request->id_caja;   
          $crear->id_punto_venta=$request->id_punto_venta;      
          $crear->id_usuario_registra=auth()->user()->id;
          $crear->id_usuario_modifica=auth()->user()->id;
          $crear->save();
       
          DB::commit();
      } catch (\Throwable $th) {
         return $th;
      }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siat_Emisor $siat_Emisor)
    {
        //
    }    

    public function siat_sucursal(Request $request){

        $resultado = DB::table('siat__sucursals as ss')
        ->leftJoin('siat__emisors as se', 'se.id', '=', 'ss.id_emisor')
        ->leftJoin('siat__cuis as sc', 'se.id_cuis', '=', 'sc.id')
        ->leftJoin('siat__cufd as sf', 'se.id_cufd', '=', 'sf.id')
        ->select(
            'ss.id',
            'ss.nombre_suc_siat',
            'ss.codigo_siat',
            'se.id_cuis',
            'se.id_cufd',
            'sc.dato',
            'sc.fecha_vigencia'
        )
        ->where('ss.estado', 1)
        ->where('sc.estado', 1)
     
        ->get();      
 
    return $resultado;
    }

    public function listar_caja(Request $request){
        $resultado = DB::table('caja__creacions as ca')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'ca.id_sucursal')
    ->join('siat__sucursals as ss', 'ss.id_sucursal', '=', 'ca.id_sucursal')
    ->leftJoin('siat__emisors as se', 'se.id_caja', '=', 'ca.id')
    ->select(
        'ca.id',
        'ca.nombre_caja',
        'ass.razon_social',
        'ss.nombre_suc_siat',
        'ss.codigo_siat',
        'se.estado'    
    )
    ->where('ss.estado', 1)
    ->where('ass.activo', 1)
    ->where('ca.estado', 1)    
    ->where('ss.codigo_siat', $request->id)
    ->whereNull('se.id_caja') // Filtra los valores donde se.id_caja es NULL
    ->get();
 
    return $resultado; 
    }

    public function update_caja(Request $request){
    
    try {        
        DB::beginTransaction();
        $actualizar = Siat_Emisor::findOrFail($request->id);
        $actualizar->id_caja = $request->id_caja;       
        $actualizar->updated_at=auth()->user()->id;
        $actualizar->save();
        DB::commit();
        
    } catch (\Throwable $th) {
            return $th;
            //throw $th;
        }

    }

    public function modificar_nul_caja(Request $request){    
        try {
            DB::beginTransaction();
    
            $actualizar = Siat_Emisor::findOrFail($request->id);
            $actualizar->id_caja = null;
            $actualizar->save();
    
            DB::commit();
          
        } catch (\Exception $e) {        
            return $e;
        }   
    }      

    public function consultar_PV_siat(Request $request){

        
         
   $endPoints = DB::table('siat__endpoints as se')    
            ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
            ->where('se.tipo', $request->codigoAmbiente)
            ->where('se.id', 2)
            ->get();
            
            $cadena_url=$endPoints[0]->Url; 
            $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $wsdl = $cadena_url; 
                $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key
    
             // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
        
        $codigoAmbiente = $request->codigoAmbiente;
        $codigoSistema =  $request->codigoSistema;
        $codigoSucursal = $request->codigoSucursal;      
        $cuis = $request->cuis;       
        $nit = $request->nit;
         
        $xmlData = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
    <soapenv:Header/>
    <soapenv:Body>
        <siat:consultaPuntoVenta>
            <SolicitudConsultaPuntoVenta> 
                <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                <codigoSistema>{$codigoSistema}</codigoSistema>
                <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                <cuis>{$cuis}</cuis>
                <nit>{$nit}</nit>
            </SolicitudConsultaPuntoVenta>
        </siat:consultaPuntoVenta>
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
                    'apikey: ' .$apikeyValue // Incluye la API key con el valor correspondiente
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

    public function cerrarPV(Request $request){
        
        try {
            DB::beginTransaction();    
          //contraseña para cambiar
            $pass="123";
           
        if ($request->passsCmbio==$pass) {
            $endPoints = DB::table('siat__endpoints as se')    
            ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
            ->where('se.tipo', $request->codigoAmbiente)
            ->where('se.id', 2)
            ->get();
            
            $cadena_url=$endPoints[0]->Url; 
            $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $wsdl = $cadena_url; 
                $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key
    
             // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes
        
        $codigoAmbiente = $request->codigoAmbiente;
        $codigoSistema =  $request->codigoSistema;
        $codigoSucursal = $request->codigoSucursal;  
        $codigoPuntoVenta = $request->codigoPuntoVenta;    
        $cuis = $request->cuis;       
        $nit = $request->nit;
         
        $xmlData = <<<EOD
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
           <soapenv:Header/>
           <soapenv:Body>
              <siat:cierrePuntoVenta>
                 <SolicitudCierrePuntoVenta>
                    <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                    <codigoPuntoVenta>{$codigoPuntoVenta}</codigoPuntoVenta>
                    <codigoSistema>{$codigoSistema}</codigoSistema>
                    <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                    <cuis>{$cuis}</cuis>
                    <nit>{$nit}</nit>
                 </SolicitudCierrePuntoVenta>
              </siat:cierrePuntoVenta>
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
                    'apikey: ' .$apikeyValue // Incluye la API key con el valor correspondiente
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

            /* 
                $soapResponse2 = <<<XML
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <ns2:cierrePuntoVentaResponse xmlns:ns2="https://siat.impuestos.gob.bo/">
            <RespuestaCierrePuntoVenta>
                <codigoPuntoVenta>3</codigoPuntoVenta>
                <transaccion>true</transaccion>
            </RespuestaCierrePuntoVenta>
        </ns2:cierrePuntoVentaResponse>
    </soap:Body>
</soap:Envelope>
XML;              
                return $soapResponse2;
            */
          
        } else {
            return "error";
        }    
            DB::commit();          
        } catch (\Exception $e) {        
            return $e;
        }  
    }

    public function eliminarPV(Request $request){
        try {
            DB::beginTransaction();  
            $fechaActual = SupportCarbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$request->id,   
            ];        
            DB::table('log__sistema')->insert($datos);
            $emi = Siat_Emisor::findOrFail($request->id);
            $emi->estado=0;
            $emi->save();
       DB::commit();    
        } catch (\Throwable $th) {
           return $th;
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
                
            $actualizar = Siat_Emisor::findOrFail($id);
            $actualizar->id_cuis = $id_cuis;    
            $actualizar->save();
            
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 3,
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

       public function eliminar_operaciones(Request $request){
        try {
            DB::beginTransaction();
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;         
            $id_cuis=$request->id_cuis;        
            $id_emisor=$request->id_emisor;
            $id_cufd=$request->id_cufd;
              // Si el registro existe, actualizar el dato (cuis)
        DB::table('siat__cuis')->where('id', $id_cuis)->update(['estado' =>0,'id_emisor'=>$id]);
        DB::table('siat__cufd')->where('id', $id_cufd)->update(['estado' =>0,'id_emisor'=>$id]);     
        DB::table('siat__emisors')->where('id', $id)->update(['id_cuis' =>null,'id_cufd'=>null]);    
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
