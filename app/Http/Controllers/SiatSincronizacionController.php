<?php

namespace App\Http\Controllers;

use App\Models\Siat_Sincronizacion;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatSincronizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function activarModo(Request $request){
        try {
            DB::beginTransaction();
            if ($request->selectAutoManual==1||$request->selectAutoManual==2) {
                $datos = [
                    'sincro_auto_manual' => $request->selectAutoManual,                
                ];        
                DB::table('siat__configuracions')
                ->where('id', 1) // Primero se filtra el registro
                ->update($datos); // Luego se actualiza   
            } else {
                return "dato de entrada erroneo";
            }             
    
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function emisor(){
        $emisores = DB::table('siat__emisors as s')
    ->join('siat__sucursals as ss', 'ss.id', '=', 's.id_siat_sucursal')
    ->join('siat__cuis as c', 'c.id', '=', 's.id_cuis')
    ->join('adm__sucursals as sss', 'sss.id', '=', 'ss.id_sucursal')
    ->select(
        's.id',
        's.nombre as nombre_emisor',
        's.id_siat_sucursal as codigoSucursal',
        's.id_punto_venta as codigoPuntoVenta',
        'c.dato as cuis',
        'ss.nombre_suc_siat',
        'ss.codigo_siat',
        'sss.razon_social'
    )
    ->where('s.estado', 1)
    ->whereNotNull('s.id_cuis')
    ->get();
    return $emisores;
    }

    public function auto_sincro(){
        $sincronizacion = DB::table('auto__sincronizacion')->where('id', 1)->first();
        return $sincronizacion;
    }

    public function sincronizar_m_a(Request $request){
        try {
         //codigoAmbiente='+me.tipo_ambiente+'&codigoPuntoVenta='+me.codigoPuntoVenta_Modal+'&codigoSistema='+me.cod_sis+'&codigoSucursal='+me.codigo_siat_modal+'&cuis='+me.cuis_modal+'&nit='+me.nit+'&token_delegado='+me.token_delegado;                        
    
            $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($request->codigoAmbiente))
        ->where('se.id',1)
        ->get(); 
    
        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;
            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$request->token_delegado; // Concatenar correctamente el valor del API key

            $codigoAmbiente = $request->codigoAmbiente; 
            $codigoPuntoVenta = $request->codigoPuntoVenta; 
            $codigoSistema = $request->codigoSistema;
            $codigoSucursal = $request->codigoPuntoVenta;
            $cuis = $request->cuis;
            $nit = $request->nit; 
            $numero=0;
            while ($numero <= 2) {
                   // Llamar al método privado dentro de la misma clase
    $xmlData = $this->endpoint($codigoAmbiente, $codigoPuntoVenta, $codigoSistema, $codigoSucursal, $cuis, $nit, $numero);
              
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
    
    $respuesta_final= $this->operacionSincro($numero,$response);   
  
    if ($respuesta_final!=0) {
        return $respuesta_final;
    } 
                $numero++;
            }
            return 0; 
          
             
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Error en la solicitud SOAP',
                'message' => $e->getMessage()
            ]);
        }   

    }

    public function endpoint($codigoAmbiente, $codigoPuntoVenta, $codigoSistema, $codigoSucursal, $cuis, $nit, $n) {
        $xmlData = null;
        switch ($n) {
            case 0:
                $xmlData = <<<EOD
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                    <soapenv:Header/>
                    <soapenv:Body>
                        <siat:verificarComunicacion/>
                    </soapenv:Body>
                </soapenv:Envelope>
                EOD;
                break;
            case 1:
                $xmlData = <<<EOD
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                   <soapenv:Header/>
                   <soapenv:Body>
                      <siat:sincronizarActividades>
                         <SolicitudSincronizacion> 
                            <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                            <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                            <codigoSistema>$codigoSistema</codigoSistema>
                            <codigoSucursal>$codigoSucursal</codigoSucursal>
                            <cuis>$cuis</cuis>
                            <nit>$nit</nit>
                         </SolicitudSincronizacion>
                      </siat:sincronizarActividades>
                   </soapenv:Body>
                </soapenv:Envelope>
                EOD;
                break;
            case 2:
                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <siat:sincronizarFechaHora>
                                <SolicitudSincronizacion>
                                    <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                    <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                    <codigoSistema>$codigoSistema</codigoSistema>
                                    <codigoSucursal>$codigoSucursal</codigoSucursal>
                                    <cuis>$cuis</cuis>
                                    <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarFechaHora>
                        </soapenv:Body>
                        </soapenv:Envelope>
                    EOD;
                    break;
            default:
                return "Opción no válida";
        }

        return $xmlData;
    }

    public function query_($n){
        $query="";
        switch ($n) {
            case 1:
                $query = DB::table('excel__emision')->where('id_catalogo', 15)->get();
                break;
            case 2:
                $query = Carbon::now()->format('Y-m-d H:i:s');
            default:
                # code...
                break;
        }
        return $query;
    }

    public function operacionSincro($n,$response){
        $respuesta="";
        switch ($n) {
            case 0:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
                if ($transaccion && isset($transaccion[0])) {
                    if ($transaccion[0]== 'true') {                     
                            $respuesta=0;
                    } else {
                        $respuesta="error no hay respuesta con el siat sincronización";
                    }                
            } else {   
                $respuesta="error en sicronización con siat";
            }   
             break;   
            case 1:
               // Crear un objeto DOMDocument para formatear el XML
//$dom = new DOMDocument();
//$dom->preserveWhiteSpace = false;
//$dom->formatOutput = true;
//$dom->loadXML($response);

// Mostrar el XML formateado
//header('Content-Type: text/xml');
//echo $dom->saveXML();
  // Convertir la respuesta en un objeto SimpleXMLElement
$xml = simplexml_load_string($response);

// Usar XPath para encontrar el nodo <transaccion>
$transaccion = $xml->xpath('//transaccion');

if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividades/codigoCaeb"); 
            $tamaño=count($codigos);
                        
                $data_query = $this->query_($n);            
                foreach ($data_query as $data) {            
                    foreach ($codigos as $codigo) {
                        $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                        if ($data->codigo==$dato) {
                            $tamaño=$tamaño-1;
                        }                  
                    }
                }
                $respuesta=$tamaño;
        } else {
            $respuesta="error del siat de sincronización";
        }    
   
} else {   
    $respuesta="error en sicronización de actividad";
}   
                break;
            
            case 2:
                 // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
             
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
             
                if ($transaccion && isset($transaccion[0])) {
                    if ($transaccion[0]== 'true') {
                        // Extraer y decodificar las descripciones de actividades
                        $codigos = $xml->xpath("//fechaHora"); 
                       // Obtener el primer resultado y convertirlo a string
                        $fechaTexto = (string) $codigos[0];
                        // Convertir la fecha a Carbon
                        $fechaCarbon = Carbon::parse($fechaTexto); 
                        $time="";        
                            $data_query = $this->query_($n);   
    // Formatear la fecha del XML también sin milisegundos
    $fechaCarbonFormat = $fechaCarbon->format('Y-m-d H:i:s'); 
             
        if ($fechaCarbonFormat==$data_query) {
                                $time=0;
                            } else {
                                $time= "Las fechas son diferentes.";
                            }
                            $respuesta=$time;
                    } else {
                        $respuesta="error del siat de sincronización";
                    }    
               
            } else {   
                $respuesta="error en sicronización de de fecha y hora";
            }   

            break;        

            default:
                # code...
                break;
        }
        return $respuesta;
      
    }
    
}
