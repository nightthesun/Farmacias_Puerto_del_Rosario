<?php

namespace App\Http\Controllers;

use App\Models\Siat_evento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PharData;
use ZipArchive;

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
        's.id as id_suc_siat'
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

                 try {
           
         DB::beginTransaction(); 
                $id=$request->id;
                $codigoMotivoEvento=$request->contingencia;
                $descripcion=$request->contingencia_descripcion_modal;

                $fechaHoraFinEvento = Carbon::parse($request->endDate_modal_1)->format('Y-m-d\TH:i:s.v');
                
                $id_cufd_modal=$request->id_cufd_modal;
                //id_sucursal_modal:1;
                $codigoSucursal=$request->punto_suc;
                $codigoPuntoVenta=$request->punto_venta;
                $fechaHoraInicioEvento = Carbon::parse($request->startDate_modal_1)->format('Y-m-d\TH:i:s.v');
                $id_suc_siat=$request->id_suc_siat;


$factura_siat = DB::table('ven__factura_siat')
        ->where('id',$id)
        ->select('zip_factura','codSector','tipo_emision','modalidad','tipoFacturaDoc','cuf')
        ->first();

        if (!$factura_siat) {
            return "No existe factura comprimida en sistema.";
        }
        $codSector=$factura_siat->codSector;
        $zip_factura=$factura_siat->zip_factura;  
        $tipo_emision_=$factura_siat->tipo_emision;
        $modalidad_=$factura_siat->modalidad;
        $tipoFacturaDoc_=$factura_siat->tipoFacturaDoc;

         

               
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

                     
$tablaCatalogo_siat = DB::table('siat__catalogo_lista_siat as c')
    ->join('excel__emision as e', function ($join) {
        $join->on('e.id_catalogo', '=', 'c.id_catalogo')
             ->on('e.descripcion', '=', 'c.descripcion');
    })
    ->join('siat__endpoints as s', function ($join) {
        $join->on('s.Descripcion', '=', 'c.descripcion');
    })
    ->select('c.id','c.id_catalogo','c.codigo','c.descripcion','e.s2','s.URL as url','s.modalidad')
    ->where('c.id_catalogo', 3)
    ->where('s.tipo', 2)
    ->first();

    if (!$tablaCatalogo_siat) {
       return "No existe datos en la tabla catalogo de lista siat revise que no tenag espacios o datos inicesarios, de la tabla emision  el espacio descripcion.";
    }

    if($tablaCatalogo_siat->s2==null||$tablaCatalogo_siat->s2==""){
        return "no existe datos en espacio s2 de la tabla emision.";
    }
    
    $escada=explode('x', $tablaCatalogo_siat->s2);
    $escada=array_filter($escada);
   $factura_siat_2 = 0;

foreach ($escada as $codigo) {
    if (DB::table('excel__emision')->where('codigo', $codigo)->exists()) {
        $factura_siat_2 = 1;
        break;
    }
}

if ($factura_siat_2==0) {
    return "no existe un codigo a sociado a la tabla emision";
}
 $url_s2=$tablaCatalogo_siat->url;
 $cuf=$factura_siat->cuf;

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
                                 
                  $codigoRecepcionEventoSignificativo = $xml->xpath('//codigoRecepcionEventoSignificativo');
              $codigo_1=  html_entity_decode($codigoRecepcionEventoSignificativo[0], ENT_QUOTES, 'UTF-8');

                $fechaEnvio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s.v');

                
// XML que viene de la base de datos
$xml = $zip_factura;

// Validar que exista XML
if(empty($xml)){
    return "XML vacío";
}


// Crear ZIP temporal.............................
$tmpZip = tempnam(sys_get_temp_dir(), 'siat_');
$nombreTar = storage_path('app/paquete.tar');

if (file_exists($nombreTar)) {
    unlink($nombreTar);
}

$tar = new PharData($nombreTar);
 $rutaTemporal = storage_path('app/temp/'.$cuf.'.xml');
  file_put_contents($rutaTemporal,$xml);

  $tar->addFile($rutaTemporal,$cuf.'.xml');

  //... parte dos
  $contenidoTar = file_get_contents($nombreTar);

$gzip = gzencode($contenidoTar, 9);

$rutaGz = storage_path('app/paquete.tar.gz');

file_put_contents($rutaGz, $gzip);

//parte 3
$hashArchivo = hash_file('sha256',$rutaGz);
//parte 4
$archivoBase64 = base64_encode(file_get_contents($rutaGz));
//parte 5
$archivo = $archivoBase64;
$hashArchivo = $hashArchivo;
//$cantidadFacturas = count($factur);
/*
foreach ($facturas as $factura) {

    $rutaTemporal = storage_path(
        'app/temp/'.$factura->cuf.'.xml'
    );

    file_put_contents(
        $rutaTemporal,
        $factura->xml
    );

    $tar->addFile(
        $rutaTemporal,
        $factura->cuf.'.xml'
    );
}
*/

//..........................

$cadena_url=$url_s2; 
         
        $wsdl = $cadena_url;
        // Asignación de la URL y API key
        $apikeyValue = 'TokenApi ' .$tokenDelegado; // Concatenar correctamente el valor del API key
        
            
         $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/"> 
            <soapenv:Header/>
            <soapenv:Body>
               <siat:recepcionPaqueteFactura>
                    <SolicitudServicioRecepcionPaquete>
                        <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                        <codigoDocumentoSector>{$codSector}</codigoDocumentoSector>
                        <codigoEmision>{$tipo_emision_}</codigoEmision>
                        <codigoModalidad>{$modalidad_}</codigoModalidad>
                        <!--Optional:-->
                        <codigoPuntoVenta>{$codigoPuntoVenta}</codigoPuntoVenta>
                        <codigoSistema>{$codigoSistema}</codigoSistema>
                        <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                        <cufd>{$cufd}</cufd>
                        <cuis>{$cuis}</cuis>
                        <nit>{$nit}</nit>
                        <tipoFacturaDocumento>{$tipoFacturaDoc_}</tipoFacturaDocumento>
                        <archivo>{$archivo}</archivo>
                        <fechaEnvio>{$fechaEnvio}</fechaEnvio>
                        <hashArchivo>{$hashArchivo}</hashArchivo>                        
                        <cantidadFacturas>1</cantidadFacturas>
                        <codigoEvento>{$codigo_1}</codigoEvento>
                  </SolicitudServicioRecepcionPaquete>
               </siat:recepcionPaqueteFactura>
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
                sleep(2);
                $codigoDescripcion = $xml->xpath('//codigoDescripcion');
                $codigoEstado = $xml->xpath('//codigoEstado');
                $codigoRecepcion = $xml->xpath('//codigoRecepcion');

                $data_load = [
                    'tipo_contigencia' => intval($codigoMotivoEvento),
                    'codDescripcion' =>  html_entity_decode($codigoDescripcion[0], ENT_QUOTES, 'UTF-8')." E",
                    'codEstado' =>  html_entity_decode($codigoEstado[0], ENT_QUOTES, 'UTF-8'), 
                    'codRecepcion' => html_entity_decode($codigoRecepcion[0], ENT_QUOTES, 'UTF-8') 

                ];                
                 DB::table('ven__factura_siat')->where('id', $id)->update($data_load);  

              $codigoRecepcion=  html_entity_decode($codigoRecepcion[0], ENT_QUOTES, 'UTF-8');
$contador_2=0;

                while($contador_2<=2){

                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                    <soapenv:Header/>
                    <soapenv:Body>
                    <siat:validacionRecepcionPaqueteFactura>
                        <SolicitudServicioValidacionRecepcionPaquete>
                            <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>
                            <codigoDocumentoSector>{$codSector}</codigoDocumentoSector>
                            <codigoEmision>{$tipo_emision_}</codigoEmision>
                            <codigoModalidad>{$modalidad_}</codigoModalidad>
                            <!--Optional:-->
                            <codigoPuntoVenta>{$codigoPuntoVenta}</codigoPuntoVenta>
                            <codigoSistema>{$codigoSistema}</codigoSistema>
                            <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                            <cufd>{$cufd}</cufd>
                            <cuis>{$cuis}</cuis>
                            <nit>{$nit}</nit>
                            <tipoFacturaDocumento>{$tipoFacturaDoc_}</tipoFacturaDocumento>
                        <codigoRecepcion>{$codigoRecepcion}</codigoRecepcion>                        
                    </SolicitudServicioValidacionRecepcionPaquete>
                    </siat:validacionRecepcionPaqueteFactura>
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
                        if($transaccion[0]== 'true'){
                        $contador_2=10;
                   
                            if ($transaccion && isset($transaccion[0])) {
                                if ($transaccion[0]== 'true') {
                                    $codigoDescripcion = $xml->xpath('//codigoDescripcion');
                                    $codigoEstado = $xml->xpath('//codigoEstado');
                                    $codigoRecepcion = $xml->xpath('//codigoRecepcion');

                                    $data_load = [
                                        'codDescripcion' =>  html_entity_decode($codigoDescripcion[0], ENT_QUOTES, 'UTF-8'),
                                        'codEstado' =>  html_entity_decode($codigoEstado[0], ENT_QUOTES, 'UTF-8'), 
                                        'codRecepcion' => html_entity_decode($codigoRecepcion[0], ENT_QUOTES, 'UTF-8') 
                                        ];                
                                        DB::table('ven__factura_siat')->where('id', $id)->update($data_load);  
                                            DB::commit();
                                           $files = Storage::files('temp');
                                                foreach ($files as $file) {
                                                    if (pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
                                                        Storage::delete($file);
                                                    }
                                                } 

                                            return 0; 
                                    }else{
                                          DB::commit();
                                        return $respuesta;
                                    }  
                            }else{
                                  DB::commit();
                                return $respuesta;
                            }
                        }else{
                        $contador_2++;
                        }                      
                }
            }else{
                  DB::commit();
                return $respuesta;
            }

         }else{
              DB::commit();
            return $respuesta;
         }

         
            } else {
                  DB::commit();
               return $respuesta;
            }                
    } else {         
        return $respuesta;
    } 
        

                 DB::commit();
              
            } catch (\Throwable $th) {
                return $th;
            }          

    }

    public function getEventoSiat(Request $request){
        try {
           
        
          $id_sucursal_siat=$request->id_sucursal;
          $id_emisor=$request->punto_ventar;
          $fechaEvento=$request->fechaEmision;


                $query_0_0 = DB::table('siat__sucursals')
    ->where('id', $id_sucursal_siat)
    ->select('codigo_siat')
    ->first();
    if(!$query_0_0){
        
         return response()->json([
                'error' => 'No existe la sucursal con id ".$id_sucursal_siat." o la tabla de sucursales no tiene datos.',  
                'message' => 0,            
                'nivel'=>2,
            ]);    
    }


                       $query_1 = DB::table('siat__emisors as e')
    ->leftJoin('siat__cuis as cuis', 'cuis.id', '=', 'e.id_cuis')
    ->leftJoin('siat__cufd as cufd', 'cufd.id', '=', 'e.id_cufd')
    ->where('e.id_siat_sucursal', $id_sucursal_siat)
    ->where('e.estado', 1)
    ->where('e.id', $id_emisor)
    ->where('e.delete', 0)
    ->select('cuis.dato as cuis','cufd.dato as cufd','e.id_punto_venta')
    ->first();
      if (!$query_1) {
        return response()->json([
                'error' => 'No exite el cuis o cufd para esta sucursal y punto de ventas.',   
                'message' => 0,           
                'nivel'=>2,
            ]);                 
                }
        
        $cuis=$query_1->cuis;
        $cufd=$query_1->cufd;
        $id_punto_venta=$query_1->id_punto_venta;

               $query_2 = DB::table('siat__configuracions as e')    
    ->where('e.id', 1)
    ->select('e.cod_sis','e.tipo_ambiente','e.token_delegado','e.tiempo_espera','e.tipo_modalidad')
    ->first();
    
    if (!$query_2) {
        return response()->json([
                'error' => 'La tabla de configuracion no tiene los datos para hacer esta operacion.', 
                'message' => 0,             
                'nivel'=>2,
            ]);      
        }

    $codigoSistema=$query_2->cod_sis;
    $codigoAmbiente=$query_2->tipo_ambiente;
    $tokenDelegado=$query_2->token_delegado;
    $tiempoEspera=$query_2->tiempo_espera;
    $tipoModalidad=$query_2->tipo_modalidad;
      $nit = DB::table('adm__credecial_correos')
        ->where('id',1)
        ->value('nit');

        if ($nit==null) {
            return response()->json([
                'error' => 'NIT sin configurar.',  
                'message' => 0,            
                'nivel'=>2,
            ]);        
        }

        $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigoAmbiente))
        ->where('se.id', 2)
        ->first(); 

                if (!$endPoints) {
                    return response()->json([
                        'error' => 'No existe la tabla de endpoints o el id fue cambiado ya que debe ser el 2 como id pivote',
                        'message' => 0,   
                        'nivel' => 2,
                    ]);
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
               <siat:consultaEventoSignificativo>
                    <SolicitudConsultaEvento>                    
                    <codigoAmbiente>{$codigoAmbiente}</codigoAmbiente>        
                    <codigoPuntoVenta>{$id_punto_venta}</codigoPuntoVenta>
                    <codigoSistema>{$codigoSistema}</codigoSistema>
                    <codigoSucursal>{$query_0_0->codigo_siat}</codigoSucursal>
                    <cufd>{$cufd}</cufd>
                    <cuis>{$cuis}</cuis>
                    <fechaEvento>{$fechaEvento}</fechaEvento>
                    <nit>{$nit}</nit>
                  </SolicitudConsultaEvento>
               </siat:consultaEventoSignificativo>
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
                     return response()->json([
                'error' => 'Error. '.$response,              
                'nivel'=>1,
            ]);    
}
 // Convertir la respuesta en un objeto SimpleXMLElement
                   $xml = simplexml_load_string($response);  
                 
              
                    // Usar XPath para encontrar el nodo <transaccion>
  $transaccion = $xml->xpath('//transaccion');
  if ($transaccion && isset($transaccion[0])) {  
     if ($transaccion[0]== 'true') {  
        
            return response()->json([
                'error' => 'Transacción exitosa',
                'message' => $response,
                'nivel'=>0,
            ]);
              
     }else{
        return response()->json([
                'error' => 'Error nivel 1.',  
                 'message' => $response,             
                'nivel'=>1,
            ]); 
     }
  }else{
     return response()->json([
                'error' => 'Error nivel 2.',  
                 'message' => $response,            
                'nivel'=>1,
            ]); 
  }


        } catch (\Throwable $th) {
            return $th;
        }

    



        $id_venta=$request->id_venta;
        $evento = DB::table('siat__eventos')
        ->where('id_venta', $id_venta)
        ->first();

        if ($evento) {
            return response()->json(['evento' => $evento, 'error' => 0]);
        } else {
            return response()->json(['message' => 'No se encontró el evento para la venta especificada.', 'error' => 1]);
        }
    }

public function getModalSucuralSiatPunto(Request $request){
    $entrada=$request->entrada;
    if ($entrada==1) {
        $datos = DB::table('siat__sucursals')
    ->select('id', 'nombre_suc_siat', 'codigo_siat')    
    ->get();
    return $datos;
    }

    if ($entrada==2) {
    $sucursal=$request->id;
     $datos = DB::table('siat__emisors')
    ->select('id','nombre', 'descripcion', 'id_punto_venta')
    ->where('id_siat_sucursal', $sucursal)
    ->where('estado', 1)
    ->where('punto_venta_eliminado', 1)
    ->where('delete', 0)
    ->get();
    return $datos;
    } 
    
}

public function getAutmo_select(){
    $query_1 = DB::table('siat__catalogo_lista_siat')
    ->selectRaw("
        MAX(CASE WHEN id_catalogo = 1 THEN codigo END) AS uno,
        MAX(CASE WHEN id_catalogo = 2 THEN codigo END) AS dos,
        MAX(CASE WHEN id_catalogo = 3 THEN codigo END) AS tres
    ")->first();
    $query_2 = DB::table('siat__configuracions')
    ->select('cod_sis', 'tipo_ambiente', 'tipo_modalidad')
    ->where('id', 1)
    ->first();

    return response()->json([
                'query_1' => $query_1,  
                'query_2' => $query_2, 
                
            ]); 
    
}

public function getModal_datos_adcionales(){
     $query_1= DB::table('excel__emision')
    ->select('*')
    ->where('id_catalogo','=',1)    
    ->get();

    $query_2= DB::table('excel__emision')
    ->select('*')
    ->where('id_catalogo','=',2)    
    ->get();

    
    $query_3= DB::table('excel__emision')
    ->select('*')
    ->where('id_catalogo','=',3)    
    ->get();
   

      return response()->json([
                'contigencia' => $query_1,  
                'tipoFacturaDoc' => $query_2,  
                'codsedtor' => $query_3,  
            ]); 
}

public function send_paquetes(Request $request){

                try {        
                    DB::beginTransaction();
                    $id=$request->id;
                    $query_1 = DB::table('evento__significativos')
                    ->where('id', $id)
                    ->first();

                    if (!$query_1)
                        return "No existe registro en la tabla de eventos";                    
                   
                    $ambiente=$query_1->ambiente;
                    $cafc=$query_1->cafc;//null
                    $cod_contigencia=$query_1->cod_contigencia;
                    $cod_punto_venta_siat=$query_1->cod_punto_venta_siat;
                    $cod_recep_even=$query_1->cod_recep_even;
                    $cod_sector=$query_1->cod_sector;
                    $cod_sucursal_siat=$query_1->cod_sucursal_siat;
                    $codigo_evento=$query_1->codigo_evento;
                    $creacion=$query_1->created_at;//"2026-06-25 10:50:00"
                    $descripcion=$query_1->descrip;
                    $estado=$query_1->estado;
                    $fechaF=$query_1->fechaF;//2026-06-25T11:27:00.000"
                    $fechaI=$query_1->fechaI;                 
                    $id_cufd=$query_1->id_cufd;
                    $id_cuis=$query_1->id_cuis;
                    $modalidad=$query_1->modalidad;
                    $tipo_factura=$query_1->tipo_factura;
                   
                     $factura_siat = DB::table('ven__factura_siat')
    ->where('codEstado', '<>', '908')
    ->where('sucursal_siat', $cod_sucursal_siat)
    ->where('punto_venta', $cod_punto_venta_siat)
    ->where('estado', 1)
    ->where('tipo_emision', $cod_contigencia)
    ->where('modalidad', $modalidad)
    ->where('tipoFacturaDoc', $cod_sector)
    ->where('ambiente', $ambiente)
    ->where('fechaEmision', '>=', $fechaI)
    ->where('fechaEmision', '<=', $fechaF)
    ->select([
        'id',
        'zip_factura',
        'codSector',
        'tipo_emision',
        'modalidad',
        'tipoFacturaDoc',
        'cuf'
    ])->get();

        if (count($factura_siat)<=0) {
            return "No existe factura con ese rango o datos enviados";
        }

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


    $tablaCatalogo_siat = DB::table('siat__catalogo_lista_siat as c')
    ->join('excel__emision as e', function ($join) {
        $join->on('e.id_catalogo', '=', 'c.id_catalogo')
             ->on('e.descripcion', '=', 'c.descripcion');
    })
    ->join('siat__endpoints as s', function ($join) {
        $join->on('s.Descripcion', '=', 'c.descripcion');
    })
    ->select('c.id','c.id_catalogo','c.codigo','c.descripcion','e.s2','s.URL as url','s.modalidad')
    ->where('c.id_catalogo', 3)
    ->where('s.tipo', 2)
    ->first();
    if (!$tablaCatalogo_siat) {
        return "no existe datos en la tabla, o id de catalogo deber ser 3 y endpoint el tipo debe ser 2";
    }
    
    if($tablaCatalogo_siat->s2==null||$tablaCatalogo_siat->s2==""){
        return "no existe datos en espacio s2 de la tabla emision.";
    }
    
    $escada=explode('x', $tablaCatalogo_siat->s2);
    $escada=array_filter($escada);
   $factura_siat_2 = 0;

foreach ($escada as $codigo) {
    if (DB::table('excel__emision')->where('codigo', $codigo)->exists()) {
        $factura_siat_2 = 1;
        break;
    }
}
if ($factura_siat_2==0) {
    return "no existe un codigo a sociado a la tabla emision";
}
 $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($ambiente))
        ->where('se.id', 2)
        ->first(); 

                if (!$endPoints) {
                    return "no exite la tabla o el id fue cambiado ya que debe ser el 2 como id pivote";
                }

        $cadena_url=$endPoints->Url; 

  

        $codigoRecepcionEventoSignificativo = $cod_recep_even;
       
        $fechaEnvio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s.v');

       // Crear ZIP temporal.............................
$tmpZip = tempnam(sys_get_temp_dir(), 'siat_');

$nombreTar = storage_path('app/paquete.tar');
$rutaGz    = storage_path('app/paquete.tar.gz');

if (file_exists($nombreTar)) {
    unlink($nombreTar);
}

if (file_exists($rutaGz)) {
    unlink($rutaGz);
}

$tar = new PharData($nombreTar);

foreach ($factura_siat as $v) {

    $xml = $v->zip_factura;
    $cuf = $v->cuf;

    if (empty($xml)) {
        continue;
    }

    $rutaTemporal = storage_path('app/temp/' . $cuf . '.xml');

    file_put_contents($rutaTemporal, $xml);

    $tar->addFile($rutaTemporal, $cuf . '.xml');

    // Eliminar el XML temporal
    unlink($rutaTemporal);
}

// Comprimir el TAR
$contenidoTar = file_get_contents($nombreTar);
$gzip = gzencode($contenidoTar, 9);
file_put_contents($rutaGz, $gzip);

// Hash SHA-256
$hashArchivo = hash_file('sha256', $rutaGz);

// Archivo Base64
$archivoBase64 = base64_encode(file_get_contents($rutaGz));

$archivo = $archivoBase64;
$cantidadFacturas = count($factura_siat);

  $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/"> 
            <soapenv:Header/>
            <soapenv:Body>
               <siat:recepcionPaqueteFactura>
                    <SolicitudServicioRecepcionPaquete>
                        <codigoAmbiente>{$ambiente}</codigoAmbiente>
                        <codigoDocumentoSector>{$cod_sector}</codigoDocumentoSector>
                        <codigoEmision>{$cod_contigencia}</codigoEmision>
                        <codigoModalidad>{$modalidad}</codigoModalidad>
                        <!--Optional:-->
                        <codigoPuntoVenta>{$cod_punto_venta_siat}</codigoPuntoVenta>
                        <codigoSistema>{$codigoSistema}</codigoSistema>
                        <codigoSucursal>{$cod_sucursal_siat}</codigoSucursal>
                        <cufd>{$cufd}</cufd>
                        <cuis>{$cuis}</cuis>
                        <nit>{$nit}</nit>
                        <tipoFacturaDocumento>{$tipoFacturaDoc_}</tipoFacturaDocumento>
                        <archivo>{$archivo}</archivo>
                        <fechaEnvio>{$fechaEnvio}</fechaEnvio>
                        <hashArchivo>{$hashArchivo}</hashArchivo>                        
                        <cantidadFacturas>{$cantidadFacturas}</cantidadFacturas>
                        <codigoEvento>{$codigoRecepcionEventoSignificativo}</codigoEvento>
                  </SolicitudServicioRecepcionPaquete>
               </siat:recepcionPaqueteFactura>
            </soapenv:Body>
         </soapenv:Envelope>
         EOD;
  return $archivo;



            //.........envio.................
                $cadena_url=$url_s2;         
                $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $apikeyValue = 'TokenApi ' .$tokenDelegado; // Concatenar correctamente el valor del API key
           
                    return $factura_siat;
                    
                    DB::commit();
                } catch (\Throwable $th) {
                    return $th;
                }
}


public function getEvento_significativo_paquete(Request $request){
   $estado = $request->a;

$query_3 = DB::table('evento__significativos')
    ->where('estado', $estado)
    ->get();
    return $query_3;
}

public function cambio_estado_evento(Request $request){
   try {
    DB::beginTransaction();
    DB::table('evento__significativos')
                ->where('id', $request->id)
                ->update(['estado' => 0 ]);
                     DB::commit();
                 return 0;       
   } catch (\Throwable $th) {
    return $th;
   }
}

public function sendEventoManual(Request $request){
    
    try {
                DB::beginTransaction();
          
                //datos de entrada
                $cafc=$request->cafc;
                 
              $fechaHoraFinEvento = Carbon::parse($request->fecha_fin)->format('Y-m-d\TH:i:s.v');
               $fechaHoraInicioEvento = Carbon::parse($request->fecha_ini)->format('Y-m-d\TH:i:s.v');
          
                $codigoMotivoEvento=$request->contigenciaDes_codigo_modal;
                $id_sucursal=$request->id_sucursal;
                $id_emisor=$request->punto_venta;
                $descripcion=$request->contingencia_descripcion_modal;

                $ambiente_m=$request->ambiente_m;
                $codSector_m=$request->codSector_m;
                $contigencia_m=$request->contigencia_m;//tipo fuera de linea 2 y contigencia 4 datos de entrada
                $modalidad_m=$request->modalidad_m;
                $tipoFacturaDoc_m=$request->tipoFacturaDoc_m;
                


                 $query_0_0 = DB::table('siat__sucursals')
    ->where('id', $id_sucursal)
    ->select('codigo_siat')
    ->first();
    if(!$query_0_0 ||$query_0_0==null){
        
         return 'No existe la sucursal con id '.$id_sucursal.' o la tabla de sucursales no tiene datos.';  
                
    }
                $codigoSucursal=$query_0_0->codigo_siat;

               $query_1 = DB::table('siat__emisors as e')
    ->leftJoin('siat__cuis as cuis', 'cuis.id', '=', 'e.id_cuis')
    ->leftJoin('siat__cufd as cufd', 'cufd.id', '=', 'e.id_cufd')
    ->where('e.id_siat_sucursal', $id_sucursal)
    ->where('e.estado', 1)
    ->where('e.id', $id_emisor)
    ->where('e.delete', 0)
    ->select('cuis.dato as cuis','cufd.dato as cufd','e.id_punto_venta','cuis.id as id_cuis','cufd.id as id_cufd')
    ->first();
   
                if (!$query_1||$query_1==null) {
                    return "no exite el cuis o cufd para esta sucursal y punto de venta";
                }
        
        $cuis=$query_1->cuis;
        $cufd=$query_1->cufd;    
        $id_cuis=$query_1->id_cuis;
        $id_cufd=$query_1->id_cufd;     
        $punto_venta=$query_1->id_punto_venta;     

              

        $fecha_22 = Carbon::parse($request->startDate_modal_1)->format('Y-m-d H:i:s');
    $cufdAnterior = DB::table('siat__cufd as c')
    ->select('c.dato as cufd_evento')
    ->where('c.id_emisor', $id_emisor)
    ->whereRaw('? BETWEEN c.created_at AND c.fecha_vigencia', [$fecha_22])
    ->first();

    if (!$cufdAnterior || $cufdAnterior==null) {
       return "No se encontro cufd en ese rango de fecha inicial de evento.";
    }
  $cufdAnterior= $cufdAnterior->cufd_evento;

       $query_2 = DB::table('siat__configuracions as e')    
    ->where('e.id', 1)
    ->select('e.cod_sis','e.tipo_ambiente','e.token_delegado','e.tiempo_espera','e.tipo_modalidad')
    ->first();
    
    if (!$query_2||$query_2==null) {
        return "La tabla de configuracion no tiene los datos para hacer esta operacion.";
        }

    $codigoSistema=$query_2->cod_sis;
    $codigoAmbiente=$query_2->tipo_ambiente;
    $tokenDelegado=$query_2->token_delegado;
    $tiempoEspera=$query_2->tiempo_espera;
    $tipoModalidad=$query_2->tipo_modalidad;

       
$tablaCatalogo_siat = DB::table('siat__catalogo_lista_siat as c')
    ->join('excel__emision as e', function ($join) {
        $join->on('e.id_catalogo', '=', 'c.id_catalogo')
             ->on('e.descripcion', '=', 'c.descripcion');
    })
    ->join('siat__endpoints as s', function ($join) {
        $join->on('s.Descripcion', '=', 'c.descripcion');
    })
    ->select('c.id','c.id_catalogo','c.codigo','c.descripcion','e.s2','s.URL as url','s.modalidad')
    ->where('c.id_catalogo', 3)
    ->where('s.tipo', 2)
    ->first();

    if (!$tablaCatalogo_siat) {
       return "No existe datos en la tabla catalogo de lista siat revise que no tenag espacios o datos inicesarios, de la tabla emision  el espacio descripcion.";
    }

    if($tablaCatalogo_siat->s2==null||$tablaCatalogo_siat->s2==""){
        return "no existe datos en espacio s2 de la tabla emision.";
    }
    $codSector=$tablaCatalogo_siat->codigo;
    $escada=explode('x', $tablaCatalogo_siat->s2);
    $escada=array_filter($escada);
   $factura_siat_2 = 0;

foreach ($escada as $codigo) {
    if (DB::table('excel__emision')->where('codigo', $codigo)->exists()) {
        $factura_siat_2 = 1;
        break;
    }
}

if ($factura_siat_2==0) {
    return "no existe un codigo a sociado a la tabla emision";
}
 $url_s2=$tablaCatalogo_siat->url;
        
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
/* 
$factura_siat = DB::table('ven__factura_siat')
    ->where('codEstado', '<>', '908')
    ->where('sucursal_siat', $codigoSucursal)
    ->where('punto_venta', $punto_venta)
    ->where('estado', 1)
    ->where('tipo_emision', $contigencia_m)
    ->where('modalidad', $modalidad_m)
    ->where('tipoFacturaDoc', $tipoFacturaDoc_m)
    ->where('ambiente', $ambiente_m)
    ->select([
        'zip_factura',
        'codSector',
        'tipo_emision',
        'modalidad',
        'tipoFacturaDoc',
        'cuf'
    ])
    ->get();

        if (count($factura_siat)<=0) {
            return "No existe factura comprimida en sistema.";
        }
        
*/
        
      

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
                    <codigoAmbiente>{$ambiente_m}</codigoAmbiente>
                    <codigoMotivoEvento>{$codigoMotivoEvento}</codigoMotivoEvento>
                    <codigoPuntoVenta>{$punto_venta}</codigoPuntoVenta>
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
                $codigoRecepcionEventoSignificativo = $xml->xpath('//codigoRecepcionEventoSignificativo');
                $codigo_1=  html_entity_decode($codigoRecepcionEventoSignificativo[0], ENT_QUOTES, 'UTF-8');
                $fechaEnvio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s.v');
                DB::table('evento__significativos')->insert([
    'codigo_evento' => $codigoMotivoEvento,
    'cod_recep_even' => $codigo_1,
    'descrip' => $descripcion,
    'fechaI' => $fechaHoraInicioEvento,
    'fechaF' => $fechaHoraFinEvento,
    'cod_sucursal_siat' => $codigoSucursal,
    'cod_punto_venta_siat' => $punto_venta,
    'cafc'=>$cafc,    
    'ambiente'=>$ambiente_m,
    'cod_sector'=> $codSector_m,
    'cod_contigencia'=> $contigencia_m,
    'tipo_factura'=>$tipoFacturaDoc_m,
    'modalidad' =>$modalidad_m, 
    'id_cuis' => $id_cuis,
    'id_cufd' => $id_cufd,           
    'created_at' => $fechaEnvio,
    'updated_at' => $fechaEnvio
]);
                                            DB::commit();
                                            return 0;
              /*
               Crear ZIP temporal.............................
                $tmpZip = tempnam(sys_get_temp_dir(), 'siat_');
                $nombreTar = storage_path('app/paquete.tar');
                if (file_exists($nombreTar)) {
                unlink($nombreTar);
                }
                $tar = new PharData($nombreTar);
                foreach ($factura_siat as $value) {
                    $xml = $value->zip_factura;
                    $cuf = $value->cuf;
                if (empty($xml)) {
                    continue; // o return según tu lógica
                }
                $rutaTemporal = storage_path('app/temp/'.$cuf.'.xml');
                file_put_contents($rutaTemporal, $xml);
                $tar->addFile($rutaTemporal,$cuf.'.xml');
                }
                $contenidoTar = file_get_contents($nombreTar);
                $gzip = gzencode($contenidoTar, 9);
                $rutaGz = storage_path('app/paquete.tar.gz');
                file_put_contents($rutaGz, $gzip);
                //parte 3
                $hashArchivo = hash_file('sha256',$rutaGz);
                //parte 4
                $archivoBase64 = base64_encode(file_get_contents($rutaGz));
                //parte 5
                $archivo = $archivoBase64;
                $hashArchivo = $hashArchivo;
                $cantidadFacturas = count($factura_siat);    
                
                
                //.........envio.................
                $cadena_url=$url_s2;         
                $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $apikeyValue = 'TokenApi ' .$tokenDelegado; // Concatenar correctamente el valor del API key
             $xmlData = <<<EOD
         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/"> 
            <soapenv:Header/>
            <soapenv:Body>
               <siat:recepcionPaqueteFactura>
                    <SolicitudServicioRecepcionPaquete>
                        <codigoAmbiente>{$ambiente_m}</codigoAmbiente>
                        <codigoDocumentoSector>{$codSector_m}</codigoDocumentoSector>
                        <codigoEmision>{$contigencia_m}</codigoEmision>
                        <codigoModalidad>{$modalidad_m}</codigoModalidad>
                        <!--Optional:-->
                        <codigoPuntoVenta>{$punto_venta}</codigoPuntoVenta>
                        <codigoSistema>{$codigoSistema}</codigoSistema>
                        <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                        <cufd>{$cufd}</cufd>
                        <cuis>{$cuis}</cuis>
                        <nit>{$nit}</nit>
                        <tipoFacturaDocumento>0</tipoFacturaDocumento>
                        <archivo>{$archivo}</archivo>
                        <fechaEnvio>{$fechaEnvio}</fechaEnvio>
                        <hashArchivo>{$hashArchivo}</hashArchivo>                        
                        <cantidadFacturas>{$cantidadFacturas}</cantidadFacturas>
                        <codigoEvento>{$codigo_1}</codigoEvento>
                  </SolicitudServicioRecepcionPaquete>
               </siat:recepcionPaqueteFactura>
            </soapenv:Body>
         </soapenv:Envelope>
         EOD;
         return $xmlData;
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
                        if (empty($response)) {
                            return("Error 2: ".$response);
                        }

                        // Convertir la respuesta en un objeto SimpleXMLElement
                        $xml = simplexml_load_string($response);   
                        $respuesta=$response;
                        return $respuesta;    
                         // Usar XPath para encontrar el nodo <transaccion>   
                        $transaccion = $xml->xpath('//transaccion');

                        if ($transaccion && isset($transaccion[0])) {
                            if ($transaccion[0]== 'true') {
                                sleep(2);
                                $codigoDescripcion = $xml->xpath('//codigoDescripcion');
                                $codigoEstado = $xml->xpath('//codigoEstado');
                                $codigoRecepcion = $xml->xpath('//codigoRecepcion');

                            }else{
                                return $respuesta;
                            }
                        }else{ 
                            return $respuesta;
                        }
               */                
             
                }else{
                return $respuesta;
            }
        }else {
            return $respuesta;
        }

//-------- 
 DB::commit();
 return $response;

    } catch (\Throwable $th) {
        return $th;
    }
}
    
}
