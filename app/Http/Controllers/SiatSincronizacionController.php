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
    public function does_sicronizacion1x1(Request $request){
    try {      
        $numero =$request->dato;
        if($numero==11){
            $query = DB::table('excel__emision')
            ->select('id_catalogo')->where('id_catalogo', 3)->get();
                if(count($query)<=0){
                    return response()->json([
                    'tipo' =>1,
                    'error' => "debe estar llena la tabla ",
                    'message' => "primero debe seleccionar el boton de Sincronizar ListaActividades DocumentoSector",          
                    ]);
                }
        }

// 1. Configuración SIAT
$configSiat = DB::table('siat__configuracions')
    ->select('cod_sis','tipo_ambiente','token_delegado','tipo_modalidad')->where('id', 1)->first();
if (!$configSiat) {
    return response()->json([
            'tipo' =>1,
            'error' => "Error base de datos",
            'message' => "no existe datos el la tabla configuracion",          
        ]);
}

// 2. Emisores habilitados
$emisores = DB::table('siat__cuis as a')
    ->join('siat__emisors as b', 'a.id', '=', 'b.id_cuis')
    ->join('siat__sucursals as c', 'c.id', '=', 'b.id_siat_sucursal')
    ->select('c.id as id_sucursal','c.nombre_suc_siat','b.id as id_emisor','c.codigo_siat as codigo_sucursal','b.id_punto_venta','a.dato as cuis')
    ->where('b.estado', 1)
    ->where('b.delete', 0)
    ->where('b.punto_venta_eliminado', 1)
    ->where('a.estado', 1)
    ->orderBy('c.codigo_siat', 'asc')
    ->get();
if (count($emisores)<=0) {
    return response()->json([
            'tipo' =>1,
            'error' => "Error base de datos",
            'message' => "no existe datos el la tabla emisor",          
        ]);
}

// 3. NIT del ERP
$configErp = DB::table('adm__config_erp')->select('nit')
    ->where('id', 1)->first();
if (!$configErp) {
    return response()->json([
            'tipo' =>1,
            'error' => "Error base de datos",
            'message' => "no existe datos el la tabla configuracion erp",          
        ]);
}

$codigoAmbiente = $configSiat->tipo_ambiente; 
          //  $codigoPuntoVenta = $request->codigoPuntoVenta; 
            $codigoSistema = $configSiat->cod_sis;
           // $codigoSucursal = $request->codigoSucursal;
           // $cuis = $request->cuis;
            $nit = $configErp->nit;
            $token_delegado=$configSiat->token_delegado;
            $modalidad=$configSiat->tipo_modalidad;

       //     $emisores = DB::table('siat__emisors as a')

//    ->select('a.id as id_emisor','a.id_siat_sucursal','a.id_punto_venta','b.dato as cuis')

            if($codigoAmbiente==1){
           //----------------produccion 
                return "sin datos.. error de ambiente";
            }else{
               
                  $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigoAmbiente))
        ->where('se.id',1)
        ->first(); 
            }
            
            $tiempoEspera = 30;
            $cadena_url=$endPoints->Url; 
        $wsdl = $cadena_url;       
        $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key

//---- pruebas ------------    for ($i=0; $i < 49; $i++) { 
  foreach ($emisores as $key => $value) {

    $id_sucursal=$value->id_sucursal;
    $nombre_suc_siat=$value->nombre_suc_siat;
    $id_emisor=$value->id_emisor;

   $codigoPuntoVenta=$value->id_punto_venta;
   $codigoSucursal=$value->codigo_sucursal;
   $cuis=$value->cuis;
   
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
    
   // return $response;
    // Verificar si hubo un error en cURL
    if (curl_errno($ch)) {
        $aa=throw new \Exception(curl_error($ch));
        return response()->json([
            'tipo' =>1,
            'error' => $aa,
            'message' =>  "error de lista numero ".$numero." sucursal: ".$nombre_suc_siat,          
        ]);
    }


    // Cerrar la sesión de cURL
    curl_close($ch);
   
    if($key==0){
    
     $respuesta_final= $this->actualizarSincroBD($numero,$response); 
  // return $respuesta_final;
        if ($respuesta_final!=0) {
           return response()->json([
            'tipo' =>1,
            'error' => $response,
            'message' =>  "error de al insertar datos registro numero: ".$numero,          
        ]);
        }    
    }
    
}    

 
return  response()->json([
            'tipo' =>0,
            'error' => "Sin error",
            'message' => "Sicronizacion hecha",          
        ]);;

    } catch (\Throwable $e) {
         return response()->json([
            'tipo' =>1,
            'error' => $e->getLine(),
            'message' =>  $e->getMessage(),          
        ]);
    }
   }


   private function actualizarSincroBD($n,$response){

     switch ($n) {

            case 1:            
               // Crear un objeto DOMDocument para formatear el XML
  $xml = simplexml_load_string($response);               
  // Usar XPath para encontrar el nodo <transaccion>
  $transaccion = $xml->xpath('//transaccion');
    if ($transaccion && isset($transaccion[0])) {    
        if ($transaccion[0]== 'true') {
            DB::table('excel__emision')
                ->where('id_catalogo', 15)
                ->delete();
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividades/codigoCaeb"); 
           $descripciones = $xml->xpath("//listaActividades/descripcion");
           $s1 = $xml->xpath("//listaActividades/tipoActividad");
           $s2 =null;
             $tamaño=count($codigos);
                   for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
                       $dato_14= html_entity_decode($s1[$i], ENT_QUOTES, 'UTF-8');                     
                        $dato_12 = (int) $dato_12; // Código como cadena);
                        $dato_13 = (string) $dato_13; // Entero
                        $dato_14 = (string) $dato_14; // Código como cadena);
                    DB::table('excel__emision')->insert([
                    'descripcion' => $dato_13,
                    'codigo' => $dato_12,
                    'id_catalogo' => 15,                    
                    's2' => $dato_14
                        ]);
                    }
                    return 0;
                
   }else{
    return 1;
   }
    }
    return 1;
     break;
//--------------------------------
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

// Convertir la fecha extraída a Carbon
$fecha1 = Carbon::parse($fechaTexto); // Convierte el string a una fecha Carbon
$fechaHoy = Carbon::now(); // Obtiene la fecha de hoy como Carbon
// Truncar a segundos
$fecha1Truncada = $fecha1->format('Y-m-d\TH:i:s');
$fecha2Truncada = $fechaHoy->format('Y-m-d\TH:i:s');
$minutos_1 = intval($fecha1->format('i')); // Extrae solo los minutos
$minutos_2 = intval($fechaHoy->format('i')); // Extrae solo los minutos
$respuesta_minutos = abs($minutos_1 - $minutos_2); // Convierte a positivo             

                        $time="";   
                        
                        
                            $data_query = Carbon::now()->format('Y-m-d H:i:s');     
                            if ($fecha1Truncada==$fecha2Truncada) {
                                return 0;
                            } else {
                                if ($respuesta_minutos == 0 || $respuesta_minutos == 1)  {
                                    return 0;
                                }else{
                                    return "Las fechas son diferentes.";
                                }                              
                            }
                           
                    } else {
                      return "error del siat de sincronización";
                    }    
               
            } else {   
                return "error en sicronización de de fecha y hora";
            }   
            break;   
//-------------------------------------------------
    case 3:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
               
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 3)
                ->delete();

            $codigos = $xml->xpath("//listaActividadesDocumentoSector/codigoDocumentoSector"); 
            $descripciones = "Sin tipo";
            $s1 = $xml->xpath("//listaActividadesDocumentoSector/tipoDocumentoSector"); 
           $s2 = $xml->xpath("//listaActividadesDocumentoSector/codigoActividad"); 
            $tamaño=count($codigos);
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
   // $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
    $dato_14 = html_entity_decode($s1[$i], ENT_QUOTES, 'UTF-8');
    $dato_15 = html_entity_decode($s2[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
   // $dato_13 = (string) $dato_13;
    $dato_14 = (string) $dato_14;
     $dato_15 = (string) $dato_15;  

   
        DB::table('excel__emision')->insert([
            'descripcion' => $descripciones,
            'codigo' => $dato_12,
            'id_catalogo' => 3,
            's1' => $dato_14,
            's2' => $dato_15,
        ]);
    
}
   
         return 0;  
                                      
        } else {
        return "error en sicronización de sector ";
        }    
   
} else {   
    return "error en sicronización de sector";
}  
            break;
            //------------------------------------------------------------------
case 4:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
               
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 11)
                ->delete();
   
            $codigos = null; 
            $descripciones =  $xml->xpath("//listaLeyendas/descripcionLeyenda"); 
           $s1 = null; 
            $s2 =null;
            $id_erp= $xml->xpath("//listaLeyendas/codigoActividad");
            $tamaño=count($id_erp);    
        
            for ($i = 0; $i < $tamaño; $i++) {

   // $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
    $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = $i+1;
    $dato_13 = (string) $dato_13;
    $dato_14 = (int) $dato_14;

    $existe = DB::table('excel__emision')
        ->where('id_catalogo', 11)
        ->where('descripcion', $dato_13)
        ->where('id_erp', $dato_14)     
        ->exists();

    if (!$existe) {
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 11,
            'id_erp' => $dato_14,
        ]);
    }
}
   
         return 0;  
                                      
        } else {
           return "error en sicronización de listaLeyendas ";
        }    
   
} else {   
    return "error en sicronización de listaLeyendas";
}  
  break;  
//----------------------------------------------------------------
case 5:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
               
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 5)
                ->delete();
        
            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
            $descripciones =$xml->xpath("//listaCodigos/descripcion");  
           $s1 = null; 
            $s2 =null;
            $id_erp=null;
            $tamaño=count($codigos);    
        
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
 //   $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
   // $dato_14 = (int) $dato_14;

    $existe = DB::table('excel__emision')
        ->where('id_catalogo', 5)
        ->where('descripcion', $dato_13)
        ->where('codigo', $dato_12)     
        ->exists();

    if (!$existe) {
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 5,
        
        ]);
    }
}       
            return 0;                          
        } else {
            return "error en sicronización de Sincronizar Lista Mensajes Servicios ";
        }    
   
} else {   
    return "error en sicronización de Sincronizar Lista Mensajes Servicios";
}  
  break;  
//----------------------------------------------------------------
case 6:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 16)
                ->delete();
       
             $codigos = $xml->xpath("//listaCodigos/codigoProducto");  
             $descripciones = $xml->xpath("//listaCodigos/descripcionProducto");  
           $s1 = null; 
            $s2 =null;
   
            $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
        $tamaño=count($codigos); 
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
    $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
    $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 16,
            'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarListaProductosServicios ";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarListaProductosServicios";
}  
  break;  
//----------------------------------------------------------------
case 7:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 6)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");
                            $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 6,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaEventosSignificativos";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaEventosSignificativos";
}  
  break;  
//----------------------------------------------------------------
case 8:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 4)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
                   
                    $codigos = $xml->xpath("//listaCodigos/codigoClasificador");   
                             $descripciones = $xml->xpath("//listaCodigos/descripcion");     
                            $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 4,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaMotivoAnulacion";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaMotivoAnulacion";
}  
  break;  
//----------------------------------------------------------------
case 9:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 10)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
                 $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion"); 
                            $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 10,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaPaisOrigen";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaPaisOrigen";
}  
  break;  
//----------------------------------------------------------------
case 10:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 7)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
                 $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion"); 
                            $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 7,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaPaisOrigen";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaPaisOrigen";
}  
  break;  
//----------------------------------------------------------------
case 11:
     
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                           
                $codigos = $xml->xpath("//listaCodigos/codigoClasificador");   
                $descripciones = $xml->xpath("//listaCodigos/descripcion");          
                           
                $tamaño=count($codigos);  
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');
    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
            DB::table('excel__emision')
    ->where('id_catalogo', 3)
    ->where('codigo', $dato_12)
    ->update([
        'descripcion' => $dato_13,
    ]);       
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoDocumentoSector";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoDocumentoSector";
}  
  break;  
//----------------------------------------------------------------
case 12:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 1)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
                
                $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                $descripciones = $xml->xpath("//listaCodigos/descripcion");  
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 1,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoEmision";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoEmision";
}  
  break; 
//----------------------------------------------------------------
case 13:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 14)
                ->delete();
                 // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripciones = $xml->xpath("//listaCodigos/descripcion"); 
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 14,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoHabitacion";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoHabitacion";
}  
  break; 
//----------------------------------------------------------------

case 14:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 8)
                ->delete();

                       $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");
           
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 8,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoMetodoPago";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoMetodoPago";
}  
  break; 
//----------------------------------------------------------------
case 15:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 9)
                ->delete();

                   
           
      $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripciones = $xml->xpath("//listaCodigos/descripcion"); 
                            
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 9,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoMoneda";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoMoneda";
}  
  break; 
//----------------------------------------------------------------
case 16:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 2)
                ->delete();

       
         $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");                     
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 2,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTiposFactura";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTiposFactura";
}  
  break; 
//---------------------------------------------------------------
case 17:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 13)
                ->delete();

       
         $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");                     
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 13,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaTipoPuntoVenta";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaTipoPuntoVenta";
}  
  break; 
//---------------------------------------------------------------
case 18:
             // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
          
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
                DB::table('excel__emision')
                ->where('id_catalogo', 12)
                ->delete();

       
         $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");                     
           
                $tamaño=count($codigos);  
       
          
           $s1 = null; 
            $s2 =null;   
     //       $id_erp=$xml->xpath("//listaCodigos/codigoActividad");
  
            for ($i = 0; $i < $tamaño; $i++) {

    $dato_12 = html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
    $dato_13 = html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
   // $dato_14 = html_entity_decode($id_erp[$i], ENT_QUOTES, 'UTF-8');

    $dato_12 = (int) $dato_12;
    $dato_13 = (string) $dato_13;
  //  $dato_14 = (int) $dato_14;
   
        DB::table('excel__emision')->insert([
            'descripcion' => $dato_13,
            'codigo' => $dato_12,
            'id_catalogo' => 12,
       //     'id_erp' => $dato_14,        
        ]);    
}       
            return 0;                          
        } else {
            return "error en sicronización de siat:sincronizarParametricaUnidadMedida";
        }    
   
} else {   
    return  "error en sicronización de siat:sincronizarParametricaUnidadMedida";
}  
  break; 
//---------------------------------------------------------------

     }

     }

    public function getDetalleSincro(){
        try {
            
          $data = DB::table('siat__sincronizacions')->select('*')->get();
            return $data;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function iniciarAutomatizacion(Request $request){
        try {
            $tareaActiva = DB::table('auto__sincronizacion')->where('id', 1)->first();
        $intentos=$tareaActiva->intentos;
        $intervalo_min=$tareaActiva->intervalo_min;
       
        if ($tareaActiva) {
        
            $datos_1 = DB::table('siat__configuracions')->where('id', 1)->first();
            $datos_2 = DB::table('adm__config_erp')->where('id', 1)->first();  
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
           DB::beginTransaction();
       
           $array=$request->array;
        if ($request->frecuencia_a==1) {
            $datos = [
                'hora' => $request->hora_a,
                'frecuencia' => $request->frecuencia_a,
                'intentos' => $request->intentos,
                'intervalo_min' => $request->intervalo_min,
                 'intervalo_seg' => $request->intervalo_seg,       
            ];   
        } else {
            $now = Carbon::now();          
            $nuevaFecha = $now->addDays($request->frecuencia_a);
            $datos = [
                'hora' => $request->hora_a,
                'frecuencia' => $request->frecuencia_a,
                'intentos' => $request->intentos,
                'intervalo_min' => $request->intervalo_min,
              'intervalo_seg' => $request->intervalo_seg,           
            ];  
        }  
        $a=DB::table('auto__sincronizacion')->where('id', 1)->exists();
        if ($a) {    
           
             
          
            foreach ($array as $key => $value) {
               
                  $d = [
                'estado' => $value['estado'],
                'prioridad' =>  $value['prioridad'],          
            ];  
                DB::table('siat__sincronizacions')->where('id', $value['id'])->update($d);
            }
            //siat__sincronizacions
             DB::table('auto__sincronizacion')->where('id', 1)->update($datos);
             DB::commit();
            return 0;
        }else{
            return 1; 
        }
         DB::commit();
       
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
            while ($numero <= 18) { // while ($numero <= 18) {
                   // Llamar al método privado dentro de la misma clase
    $xmlData = $this->endpoint($codigoAmbiente, $codigoPuntoVenta, $codigoSistema, $codigoSucursal, $cuis, $nit, $numero);
    $tiempoEspera = DB::table('siat__configuracions')
    ->where('id', 1)
    ->value('tiempo_espera'); // Obtiene directamente el valor de la columna
    if (is_null($tiempoEspera)) {
        $tiempoEspera = 30;
    }   
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
            $numero=0;//$numero=0;
       
            while ($numero <= 18) { // while ($numero <= 18) {
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
 
 
  $xml = simplexml_load_string($response);
               
  // Usar XPath para encontrar el nodo <transaccion>
  $transaccion = $xml->xpath('//transaccion');

    if ($transaccion && isset($transaccion[0])) {
    
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividades/codigoCaeb"); 
           $descripciones = $xml->xpath("//listaActividades/descripcion");
           $s1 = $xml->xpath("//listaActividades/tipoActividad");
            $data_query = $this->query_($n);   
           $tamaño=count($codigos);
            $tamañoBD=count($data_query);
          
              // dd(count($codigos)); //7  -- 3 
             
                $controlador_12=0;
               $existe_1=0;   
             $existe_2=0;  
                    foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {
                             $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }  
            
                  

                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO ACTIVIDADES:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
                       $dato_14= html_entity_decode($s1[$i], ENT_QUOTES, 'UTF-8');
                       
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8')." s1: ".$dato_14 ;
                    }
                    
               $respuesta=$cadenaA;
                } 
        } else {
            $respuesta=$response;
        }    
   
} else {   
   
    $respuesta=$response;
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

// Convertir la fecha extraída a Carbon
$fecha1 = Carbon::parse($fechaTexto); // Convierte el string a una fecha Carbon
$fechaHoy = Carbon::now(); // Obtiene la fecha de hoy como Carbon
// Truncar a segundos
$fecha1Truncada = $fecha1->format('Y-m-d\TH:i:s');
$fecha2Truncada = $fechaHoy->format('Y-m-d\TH:i:s');
$minutos_1 = intval($fecha1->format('i')); // Extrae solo los minutos
$minutos_2 = intval($fechaHoy->format('i')); // Extrae solo los minutos
$respuesta_minutos = abs($minutos_1 - $minutos_2); // Convierte a positivo             

                        $time="";   
                        
                        
                            $data_query = $this->query_($n);     
                            if ($fecha1Truncada==$fecha2Truncada) {
                                $time=0;
                            } else {
                                if ($respuesta_minutos == 0 || $respuesta_minutos == 1)  {
                                    $time=0;
                                }else{
                                    $time= "Las fechas son diferentes.";
                                }                              
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
            $tipoDocumentoSector = $xml->xpath("//listaActividadesDocumentoSector/tipoDocumentoSector"); 
           $codigoActividad = $xml->xpath("//listaActividadesDocumentoSector/codigoActividad"); 

            $tamaño=count($codigos);
            $data_query = $this->query_($n); 

             $controlador_12=0;              
             $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }  
                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO SECTOR:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($tipoDocumentoSector[$i], ENT_QUOTES, 'UTF-8');
                       $dato_14= html_entity_decode($codigoActividad[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." s1: ".mb_strtoupper($dato_13, 'UTF-8')." s2: ".$codigoActividad;
                    }
                    
               $respuesta=$cadenaA;
                }          
        } else {
            $respuesta=$response;
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


                            $controlador_12=0;              
  $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->id_erp==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }   
                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO LEYENDA:";
                    for ($i=0; $i <$tamaño_1 ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descrip[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." id_erp: ".$dato_12." descripcion: ".$dato_13;
                    }
                    
               $respuesta=$cadenaA;
                }                    
                                   
        } else {
            $respuesta=$response;
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
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");   
                                                                         
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n); 
                            
                             $controlador_12=0;
                $existe_1=0;   
             $existe_2=0; 

                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                         if ($data->codigo==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                } 
                 if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar MENSAJE SERVICIO:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                }       
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sicronización de mensaje servicio";
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
                            $tamaño=count($codigos_2);                                                                   
                            $data_query = $this->query_($n); 

                        $controlador_12=0;               
  $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos_2 as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {
                            $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                } 

                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO ACTIVIDADES DOCUMENTO SECTOR:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos_2[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      $dato_14= html_entity_decode($codigos_3[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_14, 'UTF-8')." id_erp: ".$dato_13;
                    }
                    
               $respuesta=$cadenaA;
                } 
     
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);      
                            $existe_1=0;   
             $existe_2=0;                 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                         $existe_1=1;                           
                            break;
                                     }
                                   
                                }
                                  if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                       
                             
                            }else{
                                  $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO EVENTO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error del siat de sincronizarParametricaEventosSignificativos";
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
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");                                              
                            $tamaño=count($codigos);    
                            $existe_1=0;   
             $existe_2=0;                                         
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0; 
                             
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO EVENTO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");  
                                                                          
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n); 
                            $existe_1=0;   
             $existe_2=0;                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $existe_1=1;                           
                            break;
                                     }
                                }
                                if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0; 
                             
                            }else{
                             $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO ORIGEN:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");   
                                                                          
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);   
                               $existe_1=0;   
             $existe_2=0;                          
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                              
                            }else{
                               $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO IDENTIDAD:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                              $descripcion = $xml->xpath("//listaCodigos/descripcion");   
                                                              
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                  $existe_1=0;   
             $existe_2=0;    
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                     $respuesta=0;  
                             
                            }else{
                             $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO SECTOR:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");  
                                                                           
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                            
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO EMISION:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                       $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;                           
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO HABILITACION:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                      $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                   $respuesta=0;
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO PAGO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                               $respuesta=0;
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO MONEDA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                           
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                     $existe_1=1;                           
                            break;
                                     }
                                }
                                 if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                  $respuesta=0;                             
                            }else{
                                   $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO DOCUMENTO FISCAL:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                      $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO PUNTO VENTA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                         $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                     $existe_1=1;                           
                            break;
                                     }
                                }
                                  if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;                           
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO UNIDAD DE MEDIDA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
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
