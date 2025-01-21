<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        ->leftJoin('siat__cuis as sc', 'ss.id_cuis', '=', 'sc.id')
        ->leftJoin('siat__cufd as sf', 'ss.id_cufd', '=', 'sf.id')
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
            'ass.direccion'
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
           // dd($response);
            return $response;
           
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json([
                'error' => 'Error en la solicitud SOAP',
                'message' => $e->getMessage()
            ]);
        }       
    }


  
}
