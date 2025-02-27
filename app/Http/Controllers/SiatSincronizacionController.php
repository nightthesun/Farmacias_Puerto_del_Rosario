<?php

namespace App\Http\Controllers;

use App\Models\Siat_Sincronizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SiatSincronizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    public function iniciarAutomatizacion(Request $request){
        try {
            $tareaActiva = DB::table('auto__sincronizacion')->where('id', 1)->first();
        $intentos=$tareaActiva->intentos;
        $intervalo_min=$tareaActiva->intervalo_min;
       
        if ($tareaActiva) {
        
            $datos_1 = DB::table('siat__configuracions')->where('id', 1)->first();
            $datos_2 = DB::table('adm__credecial_correos')->where('id', 1)->first();  
                $configuracion = DB::table('siat__emisors as s')
                ->join('siat__sucursals as ss', 'ss.id', '=', 's.id_siat_sucursal')
                ->join('siat__cuis as c', 'c.id', '=', 's.id_cuis')
                ->where('s.estado', 1)->where('c.estado', 1)
                ->select('s.id','s.nombre as nombre_emisor','s.id_punto_venta as punto_venta','ss.nombre_suc_siat','ss.codigo_siat','c.dato')->get();
               
                    $tamaño=count($configuracion);
                    foreach ($configuracion as $key => $value) { 
                        $n=1;   
                        while ($n <= $intentos) {
                            $a= $this->sincronizar_m_a_v2($datos_1->tipo_ambiente,$datos_1->token_delegado,$datos_1->cod_sis,$datos_2->nit,$value->punto_venta,$value->codigo_siat,$value->dato);                         
                            if ($a==0) {
                        $n=$intentos+1;
                                $tamaño--;
                            }else{
                                $n++;
                            }                         
                        }                  
                    }
                
                if ($tamaño==0) {
                    return $tamaño;
                }else{
                    return 1;
                }

           
          
        }
        
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function activarModo(Request $request){
        try {
            DB::beginTransaction();
            
                if (intval($request->selectAutoManual)==1) {
                    $datos = [
                        'activo' => 0,                
                    ];                   
                } 
                if (intval($request->selectAutoManual)==2) {
                        $datos = [
                            'activo' => 1,                                         
                        ];              
                    } 
            DB::table('auto__sincronizacion')->where('id', 1)->update($datos);               
            $sincronizacion = DB::table('auto__sincronizacion')->where('id', 1)->first();
            if ($sincronizacion->activo==1) {
                  Artisan::call('schedule:run');
                //Aquí puedes agregar la lógica para programar la tarea
                // en un horario específico.
                //$this->programarTarea();
            }
          
            DB::commit();
        } catch (\Throwable $th) {
          return $th;
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

   public function cambiarConfiguracion(Request $request){
    try {
        if ($request->frecuencia_a==1) {
            $datos = [
                'hora' => $request->hora_a,
                'frecuencia' => $request->frecuencia_a,
                'intentos' => $request->intentos,
                'intervalo_min' => $request->intervalo_min,
                'fecha_siguiente' => null,
                'fecha_ini' => null,           
            ];   
        } else {
            $now = Carbon::now();          
            $nuevaFecha = $now->addDays($request->frecuencia_a);
            $datos = [
                'hora' => $request->hora_a,
                'frecuencia' => $request->frecuencia_a,
                'intentos' => $request->intentos,
                'intervalo_min' => $request->intervalo_min,
                'fecha_siguiente' => $nuevaFecha,
                'fecha_ini' => $now,           
            ];  
        }  
        $a=DB::table('auto__sincronizacion')->where('id', 1)->exists();
        if ($a) {
            DB::table('auto__sincronizacion')->where('id', 1)->update($datos);
            return "1";
        }else{
            return "0"; 
        }
       
    } catch (\Throwable $th) {
        return $th;
    }    
   }

    public function auto_sincro(){
        $sincronizacion = DB::table('auto__sincronizacion')->where('id', 1)->first();
        return $sincronizacion;
    }

    public function sincronizacion_v2()
    {
        try {
            Artisan::call('schedule:run'); // Ejecuta las tareas programadas en Laravel

            return response()->json(['mensaje' => 'Sincronización ejecutada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la sincronización: ' . $e->getMessage()], 500);
        }
    }

    public function sincronizar_m_a_v2($codigoAmbiente,$token_delegado,$codigoSistema,$nit,$codigoPuntoVenta,$codigoSucursal,$cuis){
        try {
         //codigoAmbiente='+me.tipo_ambiente+'&codigoPuntoVenta='+me.codigoPuntoVenta_Modal+'&codigoSistema='+me.cod_sis+'&codigoSucursal='+me.codigo_siat_modal+'&cuis='+me.cuis_modal+'&nit='+me.nit+'&token_delegado='+me.token_delegado;                        

            $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigoAmbiente))
        ->where('se.id',1)
        ->get(); 
    
        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;
            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key
       
            $numero=0;
            while ($numero <= 18) {
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
            $codigoSucursal = $request->codigoSucursal;
            $cuis = $request->cuis;
            $nit = $request->nit; 
            $numero=0;
            while ($numero <= 18) {
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
                case 3:                              
                        $xmlData = <<<EOD
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                            <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarListaActividadesDocumentoSector>
                                    <SolicitudSincronizacion>
                                        <codigoAmbiente>$codigoAmbiente</codigoAmbiente>          
                                        <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                        <codigoSistema>$codigoSistema</codigoSistema>
                                        <codigoSucursal>$codigoSucursal</codigoSucursal>
                                        <cuis>$cuis</cuis>
                                        <nit>$nit</nit>
                                        </SolicitudSincronizacion>
                                </siat:sincronizarListaActividadesDocumentoSector>
                            </soapenv:Body>
                        </soapenv:Envelope>
                        EOD;
                        break;    
                    case 4:                              
                            $xmlData = <<<EOD
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                                <soapenv:Header/>
                                    <soapenv:Body>
                                        <siat:sincronizarListaLeyendasFactura>
                                        <SolicitudSincronizacion>
                                        <codigoAmbiente>$codigoAmbiente</codigoAmbiente>           
                                        <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                        <codigoSistema>$codigoSistema</codigoSistema>
                                        <codigoSucursal>$codigoSucursal</codigoSucursal>
                                        <cuis>$cuis</cuis>
                                        <nit>$nit</nit>
                                        </SolicitudSincronizacion>
                                    </siat:sincronizarListaLeyendasFactura>
                                </soapenv:Body>
                            </soapenv:Envelope>
                            EOD;
                    break;  
                    case 5:                              
                        $xmlData = <<<EOD
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                            <soapenv:Header/>
                                <soapenv:Body>
                                    <siat:sincronizarListaMensajesServicios>
                                    <SolicitudSincronizacion>
                                    <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                    <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                    <codigoSistema>$codigoSistema</codigoSistema>
                                    <codigoSucursal>$codigoSucursal</codigoSucursal>
                                    <cuis>$cuis</cuis>
                                    <nit>$nit</nit>
                                    </SolicitudSincronizacion>
                                </siat:sincronizarListaMensajesServicios>
                            </soapenv:Body>
                        </soapenv:Envelope>
                        EOD;
                    break;
                case 6:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarListaProductosServicios>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarListaProductosServicios>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;      
                case 7:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaEventosSignificativos>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaEventosSignificativos>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;  
                case 8:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaMotivoAnulacion>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaMotivoAnulacion>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 9:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaPaisOrigen>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaPaisOrigen>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 10:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoDocumentoIdentidad>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoDocumentoIdentidad>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 11:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoDocumentoSector>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoDocumentoSector>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 12:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoEmision>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>           
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoEmision>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 13:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoHabitacion>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoHabitacion>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 14:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoMetodoPago>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoMetodoPago>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 15:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoMoneda>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoMoneda>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 16:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTiposFactura>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTiposFactura>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 17:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoPuntoVenta>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoPuntoVenta>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 18:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaUnidadMedida>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaUnidadMedida>
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
                break;
            case 3:   
                $query = DB::table('excel__emision')->where('id_catalogo', 3)->get();
                break;
            case 4:
                $query = DB::table('excel__emision')->where('id_catalogo', 11)->whereNotNull('id_erp')->get();
                break;
            case 5:
                $query = DB::table('excel__emision')->where('id_catalogo', 5)->get();
                break; 
            case 6:
                $query = DB::table('excel__emision')->where('id_catalogo', 16)->get();
                break;   
            case 7:
                $query = DB::table('excel__emision')->where('id_catalogo', 6)->get();
                break;
            case 8:
                $query = DB::table('excel__emision')->where('id_catalogo', 4)->get();
                break; 
            case 9:
                $query = DB::table('excel__emision')->where('id_catalogo', 10)->get();
                break; 
            case 10:
                $query = DB::table('excel__emision')->where('id_catalogo', 7)->get();
                break;
            case 11:
                $query = DB::table('excel__emision')->where('id_catalogo', 3)->whereNull('id_erp')->get();
                break;   
            case 12:
                $query = DB::table('excel__emision')->where('id_catalogo', 1)->get();
                break;  
            case 13:
                $query = DB::table('excel__emision')->where('id_catalogo', 14)->get();
                break;
            case 14:
                $query = DB::table('excel__emision')->where('id_catalogo', 8)->get();
                break;  
            case 15:
                $query = DB::table('excel__emision')->where('id_catalogo', 9)->get();
                break;
            case 16:
                $query = DB::table('excel__emision')->where('id_catalogo', 2)->get();
                break;
            case 17:
                $query = DB::table('excel__emision')->where('id_catalogo', 13)->get();
                break;  
            case 18:
                $query = DB::table('excel__emision')->where('id_catalogo', 12)->get();
                break;                                                 
            default:
                      
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
                        $respuesta=$response;
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
            $respuesta=$response;
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

    //dd($fechaCarbonFormat." --- ".$data_query);
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
            
            case 3:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
             
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividadesDocumentoSector/codigoDocumentoSector"); 
            $tamaño=count($codigos);
            $data_query = $this->query_($n);  
            foreach ($codigos as  $codigo) {
               foreach ($data_query as  $query) {
                $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    if ($dato==$query->codigo) {
                       $tamaño--;
                    }
               }
            }
                if ($tamaño===0) {
                    $respuesta=$tamaño;
                }else{
                    $respuesta="error de tamaño del siat de sector";  
                }             
        } else {
            $respuesta="error del siat de sector";
        }    
   
} else {   
    $respuesta="error en sicronización de sector";
}  
            break;

            case 4:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaLeyendas/codigoActividad");                         
                            $descrip = $xml->xpath("//listaLeyendas/descripcionLeyenda");                           
                            $tamaño_1=count($codigos); 
                            $tamaño_2=count($descrip);                     
                            $data_query = $this->query_($n);  
                   
                            if(count($data_query)===$tamaño_1 && count($data_query)===$tamaño_2){
                                $respuesta=0;
                            }else{
                                $respuesta="error del siat de leyenda de factura"; 
                            }        
        } else {
            $respuesta="error del siat de leyenda de factura";
        }    
   
} else {   
    $respuesta="error en sicronización de leyenda de factura";}  
            break;

            case 5:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaLeyendas/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño lista mensaje servicio"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de tamaño lista mensaje servicio";
        }    
   
} else {   
    $respuesta="error en sicronización de leyenda de factura";
}  
            break;
            case 6:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoActividad");        
                            $codigos_2 = $xml->xpath("//listaCodigos/codigoProducto");    
                            $codigos_3 = $xml->xpath("//listaCodigos/descripcionProducto");                                            
                            $tamaño=count($codigos);                                                                   
                            $data_query = $this->query_($n); 
                           
                            if (count($data_query)==$tamaño) {
                                $respuesta=0;
                            }else{
                                $respuesta="error del siat de tamaño sincronizarListaProductosServicios";  
                            }        
        } else {
            $respuesta="error del siat de sincronizarListaProductosServicios";
        }    
   
} else {   
    $respuesta="error en sicronización de sincronizarListaProductosServicios";
}  
            break;
            case 7:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaEventosSignificativos"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaEventosSignificativos";
        }    
   
} else {   
    $respuesta="error en sicronización de leyenda de factura";
}  
            break;
            case 8:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaMotivoAnulacion"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaMotivoAnulacion";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaMotivoAnulacion";
}  
            break;
            case 9:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaPaisOrigen"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaPaisOrigen";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaPaisOrigen";
}  
            break;
            case 10:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoDocumentoIdentidad"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoDocumentoIdentidad";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoDocumentoIdentidad";
}  
            break;
            case 11:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoDocumentoSector"; 
                             
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoDocumentoSector";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoDocumentoSector";
}  
            break;
            case 12:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoEmision";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoEmision";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoEmision";
}  
            break;
            case 13:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoHabitacion";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoHabitacion";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoHabitacion";
}  
            break;
            case 14:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoMetodoPago";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoMetodoPago";
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoMetodoPago";
}  
            break;
            case 15:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoMoneda";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoMoneda";
        }       
} else {   
    $respuesta="error en sincronizarParametricaTipoMoneda";
}  
            break;
            case 16:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTiposFactura";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTiposFactura";
        }       
} else {   
    $respuesta="error en sincronizarParametricaTiposFactura";
}  
            break;
            case 17:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaTipoPuntoVenta";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaTipoPuntoVenta";
        }       
} else {   
    $respuesta="error en sincronizarParametricaTipoPuntoVenta";
}  
            break;
            case 18:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                       
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");                                                 
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                       
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $tamaño--;
                                     }
                                }
                             }
                            if($tamaño!=0){
                                $respuesta="error del siat de tamaño sincronizarParametricaUnidadMedida";                              
                            }else{
                                $respuesta=0;
                            }        
        } else {
            $respuesta="error del siat de sincronizarParametricaUnidadMedida";
        }       
} else {   
    $respuesta="error en sincronizarParametricaUnidadMedida";
}  
            break;
            default:
                # code...
                break;

                


        }
        return $respuesta;
      
    }

   
    
}
