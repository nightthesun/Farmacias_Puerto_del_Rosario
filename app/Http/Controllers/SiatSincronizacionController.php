<?php

namespace App\Http\Controllers;

use App\Models\Siat_Sincronizacion;
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
       
            // Inicializar cURL
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
            dd($response);
            // return $response; 
    
         
             
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Error en la solicitud SOAP',
                'message' => $e->getMessage()
            ]);
        }   

    }
    
}
