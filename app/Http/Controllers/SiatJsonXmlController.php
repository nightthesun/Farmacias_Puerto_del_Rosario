<?php

namespace App\Http\Controllers;

use App\Helpers\CufHelper;
use App\Models\Siat_Json_xml;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiatJsonXmlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $index = DB::table('siat__json_xmls')
    ->select('*')
     ->orderBy('id', 'desc')
            ->get(); 
            return $index;            
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $nombre=$request->nomGesPrueba;
            $sector=$request->sector;
            $xmlString=$request->xmlGesPrueba;
            // VALIDAR XML
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlString);


        // ARRAY A JSON
        $jsonConvertido = json_encode(
            $xml,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

            $crear = new Siat_Json_xml();
            $crear->nombre = $nombre;
             $crear->sector = $sector;
              $crear->xml = $xmlString;
              $crear->json = $jsonConvertido;
              $crear->modalidad = $request->modalidad;
              $crear->codigoEmision=$request->selectEmisionGesPrueba;
            $crear->punto_venta=$request->selectPuntoVenPrueba;
            $crear->save();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siat_Json_xml $siat_Json_xml)
    {
        try {
            DB::beginTransaction();
            $nombre=$request->nomGesPrueba;
            $sector=$request->sector;
            $xmlString=$request->xmlGesPrueba;
            // VALIDAR XML
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlString);


        // ARRAY A JSON
        $jsonConvertido = json_encode(
            $xml,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

            $a =Siat_Json_xml::findOrFail($request->id);
            $a->nombre = $nombre;
             $a->sector = $sector;
              $a->xml = $xmlString;
              $a->json = $jsonConvertido;
            $a->modalidad = $request->modalidad;
            $a->codigoEmision=$request->selectEmisionGesPrueba;
            $a->punto_venta=$request->selectPuntoVenPrueba;

            $a->save();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
             $a = Siat_Json_xml::findOrFail($request->id);
             $a->delete();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
      
    }

    public function makePruebaSiat(Request $request)
    {
        $array=$request->array;
        $id=$request->id;

        if ($id==null||$id==0) {
            return "no existe elemento para consultar revise los datos siat";
        }
        $json_xmls = DB::table('siat__json_xmls as j')    
        ->select('*')
        ->where('j.id', $id)
        ->first(); 

        if (!$json_xmls) {
            return "no existe el id en la tabla json xml";            
        }

        $xml=$json_xmls->xml;
        //operacion
        
        $modalidad=$json_xmls->modalidad;//electronica o computarizada
        $sector=$json_xmls->sector;
        $codigoEmision_x2=$json_xmls->codigoEmision;
        $punto_venta_x2=$json_xmls->punto_venta;

        $a=DB::table('adm__credecial_correos')
        ->select('*')->where('id',1)->first();
        
        if (!$a) {
            return "La tabla de credenciales no tiene datos o el id no es el correspondiente";
        }

        $nit=$a->nit;
        $tele=$a->nro_celular;
        $nom_empresa=$a->nom_empresa;

        $c = DB::table('siat__configuracions')    
        ->select('*')
        ->where('id',1)        
        ->first();
 
        if (!$c) {
            return "La tabla siat__configuracions no tiene datos o no esta con el id igual a 1";
        }
        $codigo_sis=$c->cod_sis;
        $tipo_ambiente=$c->tipo_ambiente;
        $token_delegado=$c->token_delegado;        
        $vencimiento_token=$c->vencimiento_token;
        $name_firma=$c->name;
        $path_firma=$c->path;
         $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', 2)
        ->where('se.id',4)
        ->first(); 



         
        
        if (!$endPoints) {
            return "La tabla siat__endpoints no tiene los datos para que funcione esta parte";
        }

        $siat_data = DB::table('siat__emisors as e')
    ->join('siat__sucursals as s', 'e.id_siat_sucursal', '=', 's.id')
    ->join('siat__cuis as c', 'c.id', '=', 'e.id_cuis')
    ->join('siat__cufd as cc', 'cc.id', '=', 'e.id_cufd')
    ->select(
        'e.id_punto_venta as punto_venta',
        's.codigo_siat',
        'c.dato as cuis',
        'cc.dato as cufd',
        'cc.direccion',
        'cc.codigoControl'
    )
    ->where('e.estado', 1)
    ->where('s.codigo_siat', 0)
    ->where('e.id_punto_venta', 0)
    ->first();
    if (!$siat_data) {
        return "No existe datos en la tabla siat emisor o los ids no corresponden.";
    }
     $fechaHora = Carbon::now(); 
     $fechaFormateada = $fechaHora->format('YmdHisv'); // yyyyMMddHHmmssSSS 

    $puntoVenta=$siat_data->punto_venta;
    $codigo_siat=$siat_data->codigo_siat;   
    $cuis=$siat_data->cuis;
    $cufd=$siat_data->cufd;
    $direccion=$siat_data->direccion;
    $codigoControl=$siat_data->codigoControl;

  $cuf = CufHelper::generarCUF($nit, $fechaFormateada, 0, $modalidad, $codigoEmision_x2, 1, $sector, 1, $puntoVenta, $codigoControl);//<----------------------------------------6 cabecera
        $fechaEmision=$fechaHora->format('Y-m-d\TH:i:s.v');//<----------------------------------------11 cabecera
            
$datos_22 = simplexml_load_string($xml);
if ($datos_22 === false) {
    return "formato no valio xml";
}
       // dd($datos_22->cabecera->nitEmisor);
        $datos_22->cabecera->nitEmisor = $nit;
        $datos_22->cabecera->razonSocialEmisor = $nom_empresa;
        $datos_22->cabecera->telefono = $tele;
        $datos_22->cabecera->cuf = $cuf;
        $datos_22->cabecera->cufd = $cufd;
        $datos_22->cabecera->direccion = $direccion;        
        $datos_22->cabecera->codigoPuntoVenta = $punto_venta_x2;
        $datos_22->cabecera->fechaEmision = $fechaEmision;
        $datos_22->cabecera->nombreRazonSocial = 'Renzo bit';
        $datos_22->cabecera->codigoDocumentoSector = $sector;
        $xml_v2=$datos_22->asXML();


        $gzipped = gzencode($xml_v2);
        $archivo = base64_encode($gzipped); 
        // 14. Calcular el HASH SHA256
        $hashArchivo = hash('sha256', $gzipped);
        $cadena_url = $endPoints->Url;
        
        // Asignación de la URL y API key
        $wsdl = $cadena_url; 
        $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key
            // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes        
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                       <soapenv:Header/>
                       <soapenv:Body>
                          <siat:recepcionFactura>
                             <SolicitudServicioRecepcionFactura>
                                <codigoAmbiente>2</codigoAmbiente>
                                <codigoDocumentoSector>{$sector}</codigoDocumentoSector>
                                <codigoEmision>{$codigoEmision_x2}</codigoEmision>
                                <codigoModalidad>{$modalidad}</codigoModalidad>
                                <codigoPuntoVenta>{$punto_venta_x2}</codigoPuntoVenta>
                                <codigoSistema>{$codigo_sis}</codigoSistema>
                                <codigoSucursal>0</codigoSucursal>
                                <cufd>{$cufd}</cufd>
                                <cuis>{$cuis}</cuis>
                                <nit>{$nit}</nit>
                                <tipoFacturaDocumento>1</tipoFacturaDocumento>
                                <archivo>{$archivo}</archivo>
                                <fechaEnvio>{$fechaEmision}</fechaEnvio>
                                <hashArchivo>{$hashArchivo}</hashArchivo>
                             </SolicitudServicioRecepcionFactura>
                          </siat:recepcionFactura>
                       </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                 //   $datos_22 = simplexml_load_string($xml); 
            //dd($xmlData);
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
                    // Convertir la respuesta en un objeto SimpleXMLElement
                //    $xml = simplexml_load_string($response);                    
                    $respuesta=$response;
                  //  dd($respuesta);
                 return $respuesta;       
        
    }
}
