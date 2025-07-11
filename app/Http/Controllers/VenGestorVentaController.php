<?php

namespace App\Http\Controllers;

use App\Helpers\operacionDosificacion;
use App\Models\Tda_ingresoProducto2;
use App\Models\Ven_GestorVenta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\converso_numero_a_texto;
use App\Helpers\CufHelper;
use Exception;
use Illuminate\Support\Facades\Storage;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

use SimpleXMLElement;

class VenGestorVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    private function verComunicacion($ambiente,$token_delegado){
      
        switch ($ambiente) {
            case 1:
                
                return 1;
            break;

            case 2:
                $endPoints = DB::table('siat__endpoints as se')    
                    ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
                    ->where('se.tipo', intval($ambiente))
                    ->where('se.id',4)
                    ->first();
                
                if ($endPoints) {
                    $cadena_url = $endPoints->Url;
                 
                    $wsdl = $cadena_url;
                        // Asignación de la URL y API key
                        $wsdl = $cadena_url; 
                        $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key
                    
                    // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes        
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <siat:verificarComunicacion/>
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
                    // Convertir la respuesta en un objeto SimpleXMLElement
                    $xml = simplexml_load_string($response);                    
                    $respuesta=$response;

                   // Usar XPath para encontrar el nodo <transaccion>    
                   $transaccion = $xml->xpath('//transaccion');
                   if ($transaccion && isset($transaccion[0])) {
                    if ($transaccion[0]== 'true') { 
                        return 1;
                        }else{
                            return 10;
                        }
                    }                   
                }else{
                    return 0;
                }    
                          
            break;
            
            default:
                return 0;
            break;
        }
         
    }

    public function ventaFacturaSiat(Request $request){
        try {
          
            DB::beginTransaction();
            return $request->all();
            $fechaHora = Carbon::now(); // Se usará automáticamente el formato correcto
            $arrayEstado_dosificacion_facctura=$request->arrayEstado_dosificacion_facctura;
            $arrayQuery_siat_=$request->arrayQuery_siat_;
            $arrayProRecibo=$request->arrayProRecibo;
            
            //-----verifica el tipo de emision 
            $comunicacion = $this->verComunicacion($arrayEstado_dosificacion_facctura['tipo_ambiente'],$arrayEstado_dosificacion_facctura['token_delegado']);
                  
            switch ($comunicacion) {
                case 0:
                    return "Error con la consulta... contacte al administrador";
                break;
                
                case 1:
                    $queryEmision = DB::table('excel__emision')
                    ->where('id_catalogo', 1)
                    ->where('codigo', 1)
                    ->first();
                    if (!$queryEmision) {
                        return "Error de emisión";
                    }                   
                break;
                case 10:
                    //--------------------modulo de facturas si respuestas  o contigencia
                    $queryEmision = DB::table('excel__emision')
                    ->where('id_catalogo', 1)
                    ->where('codigo', 2)
                    ->first();
                    if (!$queryEmision) {
                        return "Error de emisión";
                    }  
                break;    
                default:
                  return "Error con la consulta... contacte al administrador";
                break;
            }
        
            
        $querytipoSector = DB::table('excel__emision')
            ->where('id_catalogo', 3)
            ->where('codigo', 1)
            ->first();
            if(!$querytipoSector){
                return "Tipo de catalogo no existe";
            }

            $queryNumeroFactura = DB::table('ven__factura_siat')
            ->orderBy('id', 'desc')->limit(1)->value('numero_factura');
            if($queryNumeroFactura){
            $numero__=$queryNumeroFactura;
            }else{ $numero__=1; }
            //----------------CUF      
            $codigoSistema=$arrayEstado_dosificacion_facctura['cod_sis']; 
            // Obtener la fecha actual con milisegundos
            $fechaFormateada = $fechaHora->format('YmdHisv'); // yyyyMMddHHmmssSSS 
            $nitEmisor = $arrayEstado_dosificacion_facctura['nit']; //<----------------------------------------1 cabecera
            $sucursal = $arrayQuery_siat_['id_sucursal_siat'];//<----------------------------------------8 cabecera
            $modalidad = $arrayEstado_dosificacion_facctura['tipo_modalidad'];
            $tipoEmision = $queryEmision->codigo;
            $token_delegado= $arrayEstado_dosificacion_facctura['token_delegado'];
            $tipoFactura = $request->id_tipo_doc;//<----------------------------------------13 cabecera
            $tipoDocumentoSector = $querytipoSector->codigo;
            $numeroFactura = $numero__;//<----------------------------------------5 cabecera
            $puntoVenta = $arrayQuery_siat_['punto_venta'];//<----------------------------------------10 cabecera
            $codigoControl = $arrayQuery_siat_['codigo_control_cufd']; // Este valor lo obtienes del WebService de la SIN              
        
            $cuf = CufHelper::generarCUF($nitEmisor, $fechaFormateada, $sucursal, $modalidad, $tipoEmision, $tipoFactura, $tipoDocumentoSector, $numeroFactura, $puntoVenta, $codigoControl);//<----------------------------------------6 cabecera
            //----------------------
            
            $razonSocialEmisor=$request->arrayEstado_dosificacion_facctura['emisor_razon_soc'];////<----------------------------------------2 cabecera         
            $id_sucursal_sistemas__=$arrayQuery_siat_['id_sucursal_sistemas'];
            $departamento = DB::table('adm__sucursals as s')
            ->join('adm__departamentos as d', 'd.id', '=', 's.departamento')
            ->select('s.razon_social', 's.telefonos', 's.direccion', 'd.nombre')
            ->where('s.id', $id_sucursal_sistemas__)
            ->first();
         
            if(!$departamento){
                return "Error de departamento";
            }
            $municipio=$departamento->nombre;//<----------------------------------------3 cabecera
            $telefono=$arrayEstado_dosificacion_facctura['nro_celular'];//<----------------------------------------4 cabecera
            $cufd=$arrayQuery_siat_['cufd'];//<----------------------------------------7 cabecera
            $cuis=$arrayQuery_siat_['cuis'];
          
            $direccion=$arrayQuery_siat_['direccion_cufd'];//<----------------------------------------9 cabecera
            $fechaEmision=$fechaHora->format('Y-m-d\TH:i:s.v');//<----------------------------------------11 cabecera
            $nombreRazonSocial=$request->nom_a_facturar;//<----------------------------------------12 cabecera
            $complemento_siat=$request->complemento_siat;//---- revisar si es null //<----------------------------------------15 cabecera
            $codigoCliente=$request->cliente_id;//---- revisar si es null //<----------------------------------------16 cabecera
            $numeroDocumento=$request->num_documento;//<----------------------------------------14 cabecera         
            $codigoMetodoPago=$request->tipoPago;//<----------------------------------------17 cabecera
            if($codigoMetodoPago=="2"){
                if($request->numeroTarjeta==null || $request->numeroTarjeta==''){
                    //<----------------------------------------18 casos nulo cabecera
                    $numeroTarjeta_sis='<numeroTarjeta xsi:nil="true"/>';
                } else{ 
                    $numeroTarjeta_op = $this->ofuscarTarjeta($request->numeroTarjeta); //<----------------------------------------18 filtrar informacion cabecera
                    if($numeroTarjeta_op==null){
                    $numeroTarjeta_sis='<numeroTarjeta xsi:nil="true"/>'; 
                    }else{
                    $numeroTarjeta_sis="<numeroTarjeta>{$numeroTarjeta_op}</numeroTarjeta>";  
                    }
                }
            }else{
                $numeroTarjeta_sis='<numeroTarjeta xsi:nil="true"/>';
            }
                   
          
            $montoTotal=number_format($request->total_venta, 2, '.', '');  //<----------------------------------------19 cabecera 
            $montoTotalSujetoIva=number_format($request->total_venta, 2, '.', '');  //<----------------------------------------20 cabecera 
            $moneda_siat_x = DB::table('excel__emision')
                ->where('id_catalogo', 9)->where('id_erp', 1)->first();              
            if(!$moneda_siat_x){
                return "no exite configuracion de moneda contacte al administrador";
            }    
            $codigoMoneda=$moneda_siat_x->codigo;//<----------------------------------------21 cabecera 
            $tipoCambio=$moneda_siat_x->codigo;//<----------------------------------------22 cabecera 
            $montoTotalMoneda=$montoTotal;
            $montoTotalSujetoIva_real=$request->importe_fiscal;
            $montoTotal_real=$request->monto_a_pagar;
            $montoTotalMoneda_real=$request->monto_a_pagar;
            $descuentoAdicional=number_format($request->descuento_a_total, 2, '.', '');
            if ($request->descuento_a_total==null||$request->descuento_a_total==""||$request->descuento_a_total==" "||$request->descuento_a_total==0) {
                $descuentoAdicional=0;
            } else {
                $descuentoAdicional=number_format($request->descuento_a_total, 2, '.', '');
            }
            
            if($request->gift_value==null||$request->gift_value==""||$request->gift_value==" "||$request->gift_value==0){
                $montoGiftCard=0;
            }else{
                $montoGiftCard=number_format($request->gift_value, 2, '.', '');  
            }
            $codigoExcepcion=0;//<---------------solo cuando Solo cuando se desee autorizar al SIN el registro de una factura emitida a un NIT inválido se debe enviar el valor de uno (1) en el mismo .
            $valor_ca=$arrayProRecibo[0]['codigoActividad'];
            $leyenda = DB::table('excel__emision')
    ->where('id_erp', $valor_ca)
    ->where('id_catalogo', 11)
    ->limit(1)
    ->value('descripcion');
      
  
          
            $id_user2 = session('id_user2'); 
            $user_nom=auth()->user()->id;
            $nombreCompletoObj = DB::table('rrh__empleados as re')
            ->join('users as u', 're.id', '=', 'u.idempleado')
            ->where('u.id', $user_nom)
            ->value(DB::raw('UPPER(re.nombre)'));
         
            //==================================================
            //------------------etapa 4-------------------------
            //==================================================
            $tipo_ambiente=$arrayEstado_dosificacion_facctura['tipo_ambiente'];
            $endPoints = DB::table('siat__endpoints as se')    
            ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
            ->where('se.tipo', intval($tipo_ambiente))
            ->where('se.id',4)
            ->get(); 
         
            $codigoDocumentoSectorQuery = DB::table('excel__emision')
    ->where('id_catalogo', 3)
    ->where('codigo', 1)// como es factura compra venta se usa esa 
    ->first();

    $tipoFacturaDocumento = DB::table('excel__emision')
    ->where('id_catalogo', 2)
    ->where('codigo', 1)// como es factura compra venta se usa esa 
    ->first();
    $docFactura=$tipoFacturaDocumento->codigo;
    $docFacturaDescrip=$tipoFacturaDocumento->descripcion;
   
            $codigoDocumentoSector=$codigoDocumentoSectorQuery->codigo;// configurar depedeniendo de laf actura comoe s compra y venta es 1            
          //  return $request->all();
            $cadena_url=$endPoints[0]->Url; 
         
 

            $wsdl = $cadena_url;
                // Asignación de la URL y API key
                $wsdl = $cadena_url; 
                $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key
// Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes      
//    <codigoPuntoVenta>0</codigoPuntoVenta>  <codigoPuntoVenta xsi:nil="true"/>   <complemento xsi:nil="true"/> <numeroTarjeta xsi:nil="true"/>  <montoGiftCard xsi:nil="true"/>
if ($complemento_siat == null) {
    $complemento_siat_sis = '<complemento xsi:nil="true"/>';
} else {
    $complemento_siat_sis = "<complemento>{$complemento_siat}</complemento>";
}




    

$detalles = ''; // Aquí se guardarán todos los detalles

foreach ($arrayProRecibo as $item) {
    // Convertir cantidad a entero
    $cantidad_array = intval($item['cant']);
    
    // Convertir precios a float
    $precioUnitario = (float)$item['p_u'];
    $montoDescuento = (float)$item['descuento'];
 
    // Calcular subtotal y luego formatear
    $subTotalCalculado = ($cantidad_array * $precioUnitario) - $montoDescuento;
    $subTotal = number_format($subTotalCalculado, 2, '.', '');

    $detalles .= '
<detalle>
    <actividadEconomica>' . $item['codigoActividad'] . '</actividadEconomica>
    <codigoProductoSin>' . $item['codigoProducto'] . '</codigoProductoSin>
    <codigoProducto>' . $item['cod_pro'] . '</codigoProducto>
    <descripcion>' . $item['descrip'] . '</descripcion>
    <cantidad>' . $item['cant'] . '</cantidad>
    <unidadMedida>' . $item['id_unidad_me'] . '</unidadMedida>
    <precioUnitario>' . $precioUnitario . '</precioUnitario>
    <montoDescuento>' . $montoDescuento . '</montoDescuento>
    <subTotal>' . $subTotal . '</subTotal>
    <numeroSerie xsi:nil="true"/>
    <numeroImei xsi:nil="true"/>        
</detalle>';
}



$factura = <<<EOD
<facturaElectronicaCompraVenta xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="facturaElectronicaCompraVenta.xsd">
    <cabecera>
        <nitEmisor>{$nitEmisor}</nitEmisor>
        <razonSocialEmisor>{$razonSocialEmisor}</razonSocialEmisor>
        <municipio>{$municipio}</municipio>
        <telefono>{$telefono}</telefono>
        <numeroFactura>{$numeroFactura}</numeroFactura>
        <cuf>{$cuf}</cuf>
        <cufd>{$cufd}</cufd>
        <codigoSucursal>{$sucursal}</codigoSucursal>
        <direccion>{$direccion}</direccion>
        <codigoPuntoVenta>{$puntoVenta}</codigoPuntoVenta>       
        <fechaEmision>{$fechaEmision}</fechaEmision>
        <nombreRazonSocial>{$nombreRazonSocial}</nombreRazonSocial>
        <codigoTipoDocumentoIdentidad>{$tipoFactura}</codigoTipoDocumentoIdentidad>
        <numeroDocumento>{$numeroDocumento}</numeroDocumento>
        {$complemento_siat_sis}
        <codigoCliente>{$codigoCliente}</codigoCliente>
        <codigoMetodoPago>{$codigoMetodoPago}</codigoMetodoPago>
        {$numeroTarjeta_sis}
        <montoTotal>{$montoTotal_real}</montoTotal>
        <montoTotalSujetoIva>{$montoTotalSujetoIva_real}</montoTotalSujetoIva>
        <codigoMoneda>{$codigoMoneda}</codigoMoneda>
        <tipoCambio>{$tipoCambio}</tipoCambio>
        <montoTotalMoneda>{$montoTotalMoneda_real}</montoTotalMoneda>
        <montoGiftCard>{$montoGiftCard}</montoGiftCard>      
        <descuentoAdicional>{$descuentoAdicional}</descuentoAdicional>
        <codigoExcepcion>{$codigoExcepcion}</codigoExcepcion>
        <cafc xsi:nil="true"/>
        <leyenda>{$leyenda}</leyenda>
        <usuario>{$nombreCompletoObj}</usuario>
        <codigoDocumentoSector>{$codigoDocumentoSector}</codigoDocumentoSector>
    </cabecera>
    {$detalles}
  </facturaElectronicaCompraVenta>
EOD;
//$xmlString = view('factura_template', $factura)->render(); 
 // 2. Firma digital con .p12


 // 1. Obtener archivo .p12 desde storage/app/firma
 $archivoP12 = collect(Storage::files('firma'))->first();
 if (!$archivoP12) {
     throw new \Exception('No se encontró ningún archivo .p12 en storage/app/firma');
 }
 
 // 2. Obtener clave y desencriptarla
 $claveEncriptada = $arrayEstado_dosificacion_facctura['password'];
 $claveReducida = preg_replace('/^.{2}(.*).{3}$/', '$1', $claveEncriptada);
 $claveP12 = Crypt::decrypt($claveReducida);
 
 // 3. Leer contenido del archivo .p12
 $contenidoP12 = Storage::get($archivoP12);
 
 // 4. Extraer claves y certificado
 $certs = [];
 if (!openssl_pkcs12_read($contenidoP12, $certs, $claveP12)) {
     throw new \Exception("No se pudo leer el archivo .p12 o la clave es incorrecta.");
 }
 $privateKey = $certs['pkey'];
 $publicCert = $certs['cert'];
 
 // 5. Cargar el XML
 $doc = new \DOMDocument();
 $doc->preserveWhiteSpace = false;
 $doc->formatOutput = false;
 $doc->loadXML($factura);
 
 // 6. Crear la firma digital
 $objDSig = new XMLSecurityDSig();
 // Cambiar método de canonicalización a Exclusivo con Comentarios
 $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N_COMMENTS);

 
 // 7. Agregar referencia con algoritmos correctos
 $objDSig->addReference(
     $doc,
     XMLSecurityDSig::SHA256,
    [
        'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
        'http://www.w3.org/2001/10/xml-exc-c14n#WithComments'
    ],
    [
        'uri' => '',
        'force_uri' => true
    ]
 );
 
 // 8. Crear clave RSA SHA256
 $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
 $objKey->loadKey($privateKey, false);
 
 // 9. Firmar con la clave privada
 $objDSig->sign($objKey);
 
 // 10. Incluir certificado público en la firma
 $objDSig->add509Cert($publicCert);
 
 // 11. Agregar firma al XML
 $objDSig->appendSignature($doc->documentElement);
 
 // 12. Guardar el XML firmado
 $xmlFirmado = $doc->saveXML();

 // 13. GZIP + Base64
 $gzipped = gzencode($xmlFirmado);
 $archivo = base64_encode($gzipped);
 
 // 14. Calcular el HASH SHA256
 $hashArchivo = hash('sha256', $gzipped);
 
 // 15. Enviar al SIAT
 $soap_llamada = $this->enviarFactura_siat(
     $tipo_ambiente, $token_delegado, $codigoDocumentoSector, $tipoEmision,
     $modalidad, $puntoVenta, $codigoSistema, $sucursal,
     $cufd, $cuis, $nitEmisor, $docFactura, $archivo, $fechaEmision, $hashArchivo
 );
 
 return $soap_llamada;

 $xml = simplexml_load_string($soap_llamada);
    // Usar XPath para encontrar el nodo <transaccion>    
    $transaccion = $xml->xpath('//transaccion');
    if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {  
         
            $codigoRecepcion = $xml->xpath('//codigoRecepcion');
            $codigoDescripcion= $xml->xpath('//codigoDescripcion');

        $insertarVenta_v= $this->insertarVenta($codigoCliente,$total_venta,$efectivo_venta,$cambio_venta,$descuento_venta,$total_sin_des,$dato_tipo,$codigo_tienda_almacen_0
    ,$id_lista_v2,$numero_referencia,$num_documento,$nom_a_facturar,$estado_dosificacion_facctura,$id_apertura_cierre,$tipo_venta,
    $monto_vale,$monto_apagar,$moneda,$arrayDescuentoOperacion,$arrayDesatlleVenta,$numeroTarjeta,$cadenaOtros,$tipoBanco,$id_cufd,$id_cuis
    ,$cuf,$id_credenciales,$sucursal_siat,$punto_venta,$direccion,$municipio, $numFactura, $fechaEmision, $xml, $id_leyenda, $codRecepcion);
                
        }else{
            return $soap_llamada;
        }       
    }else{
        return $soap_llamada;
    }      
            DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function venta(Request $request){
      
        try {
               // Iniciar una transacción
               $num_factura="";
               $fechaHoy = Carbon::now()->format('Y-m-d');
               DB::beginTransaction();
               $user_1 = auth()->user()->id;
               $nomsucursal="";
               $iduserrolesuc = "";
               $idsuc = "";
               $name_user = ""; 
               if ($user_1==1) {
                $valor = '1';
                return response()->json(['data' => $valor]);
               }else{
                $nomsucursal= session('nomsucursal');
                $iduserrolesuc = session('iduserrolesuc');
                $idsuc = session('idsuc');
                $id_user2 = session('id_user2'); 
               } 
                
               $ultimoComprobante = DB::table('ven__recibos')
    ->orderBy('contador', 'desc')
    ->value('contador');

    $credencial = DB::table('adm__credecial_correos')
    ->select('nro_celular', 'nom_empresa', 'actividad_economica','nit','moneda')
    ->get();
    
$nombre_e = $credencial[0]->nom_empresa;
$num_e = $credencial[0]->nro_celular;       
$actividad_economica = strtoupper($credencial[0]->actividad_economica);
$nit_2=$credencial[0]->nit;
$moneda=$credencial[0]->moneda;
    $currentDateTime = Carbon::now();
if (is_null($ultimoComprobante)) {
    // La tabla está vacía, iniciar con 1    
    $contador_2 = 1;
} else {
    // Incrementar el último número de comprobante
    $contador_2 = $ultimoComprobante + 1;
}

$year = strval($currentDateTime->year); 
$contadorCadena=strval($contador_2);
$controlador_2_1=$year.$contadorCadena;

$num_documento = $request->input('num_documento');
$nom_a_facturar = strtoupper($request->input('nom_a_facturar'));
$numero_referencia = $num_e;
$nombre_empresa = strtoupper($nombre_e); 

 //-------------- facturacion dosificacion
 if ($request->TipoComprobate==1) {
    $estado_dosificacion_facctura=0;
 }else{
    if ($request->TipoComprobate==2) {
        $estado_dosificacion_facctura=$request->estado_dosificacion_facctura;
    }else{
        dd("error... de entrada de tipod e factura recibo");
    }
 }


    // datos para cargar 
        $total_venta=$request->total_venta;
        $efectivo_venta=$request->efectivo_venta;
        $cambio_venta=$request->cambio_venta;
        $descuento_venta=$request->descuento_venta;
        $total_sin_des= $total_venta+$descuento_venta;
        $dato_tipo=intval($request->TipoComprobate);
        $codigo_tienda_almacen_0=$request->codigo_tienda_almacen_0;
        $id_lista_v2=$request->id_lista_v2;
        $cliente_id=$request->cliente_id;
        $id_apertura_cierre=$request->id_apertura_cierre;
        $monto_apagar=$request->monto_a_pagar;
        $monto_vale =$request->gift_value;
        $tipo_venta= (integer)$request->tipo_pago_Qr_con_tar;
    $data_recibo = [
        'id_sucursal' => $idsuc,
        'id_cliente' => $cliente_id,
        'id_usuario'=>$id_user2,
        'nro_comprobante_venta' => $controlador_2_1,
        'total_venta' => $total_venta,
        'efectivo_venta' => $efectivo_venta, 
        'cambio_venta' => $cambio_venta,       
        'descuento_venta' => $descuento_venta,
        'created_at' => $currentDateTime,
        'updated_at' => $currentDateTime,
        'total_sin_des' => $total_sin_des,
        'tipo_venta_reci_fac' => $dato_tipo,
        'contador'=> $contador_2,
        'cod'=> $codigo_tienda_almacen_0,
        'id_lista'=>$id_lista_v2,
        'nro_ref'=>$numero_referencia,
        'nro_doc'=> $num_documento,
        'razon_social'=>$nom_a_facturar,
        'dosificacion_o_electronica'=>$estado_dosificacion_facctura,
        'id_apertura'=>$id_apertura_cierre,
        'tipo_venta'=>$tipo_venta,
        'monto_vale'=>$monto_vale,
        'monto_apagar'=>$monto_apagar,
        'moneda'=>$moneda,
       ];   
      
       $id_recibo = DB::table('ven__recibos')->insertGetId($data_recibo);
      
    
    
     $num_auto="";
     $cod_autorizacion="";
     $fecha_e_1="";
     $fecha_e_2="";
     
       if ($estado_dosificacion_facctura==2&&$request->TipoComprobate==2) {

        $id_dosificacaion_1=$request->id_dosificacaion_1;
        $num_auto=$request->nro_autorizacion_1;  
        $Llave_Dosificacion=$request->dosificacion_1;               
        $fecha_e_1=$request->fecha_e_1;            
        $n_ini_facturacion_1=$request->n_ini_facturacion_1;     
        $n_fin_facturacion_1=$request->n_fin_facturacion_1;  
        $n_act_facturacion_1=$request->n_act_facturacion_1;  
        $fecha_e_2 = Carbon::createFromFormat('Y-m-d', $fecha_e_1)->format('d-m-Y');
        $ultimo_factura_dosi = DB::table('ven__factura_dosi')
        ->orderBy('numero_factura', 'desc')
        ->value('numero_factura');
        if (is_null($ultimo_factura_dosi)) {
            // La tabla está vacía, iniciar con 1    
            $num_factura = $n_ini_facturacion_1;
        } else {
            // Incrementar el último número de comprobante
            $num_factura = $ultimo_factura_dosi + 1;
            if($num_factura>999999999999){
                $error_logitud=12;
                return $error_logitud;
            }
        }
        
        $fecha_transaccion = Carbon::createFromFormat('Y-m-d', $fechaHoy)->format('Ymd');
        //return "*-----*".$Llave_Dosificacion." ".$num_auto." ".$num_factura." ".$num_documento." ".$fecha_transaccion." ".$total_venta; 
        $cod_autorizacion= operacionDosificacion::operacion($Llave_Dosificacion, $num_auto,$num_factura,$num_documento,$fecha_transaccion,$total_venta); 
        
        $data_fac_dosi = [
            'id_venta' => $id_recibo,         
            'id_dosificacion'=>$id_dosificacaion_1,
            'numero_factura' => $num_factura,
            'total' => $total_venta,
            'codigo_control' => $cod_autorizacion,             
           ];  

           
            DB::table('ven__factura_dosi')->insert($data_fac_dosi);
      

     
    }
    
       /////// detalle_descuento
       $descuento_final_2=0;
       $bloque_descuento = $request->arrayDescuentoOperacion;
       foreach ($bloque_descuento as $item) {
        $id_tabla = $item['id_tabla'];
        $id_descuento = $item['id_descuento'];
        $cantidad_descuento = $item['cantidad_descuento'];
        $tipo_num_des =$item['id_contador'];
        $tipo_2=$item['tipo'];
        $data_descuento = [
            'id_venta' => $id_recibo,
            'id_tabla' => $id_tabla,  
            'id_descuento' => $id_descuento,
            'cantidad_descuento' => $cantidad_descuento, 
            'id_detalle_descuento'=> $tipo_num_des,
            'tipo' => $tipo_2      
           ];    
           DB::table('ven__detalle_descuentos')->insert($data_descuento);
           if($tipo_2==2&&$tipo_num_des==0){
            $descuento_final_2=$descuento_final_2+$cantidad_descuento;
           }
          
       }  
        /////// detalle_venta
        //$es_lista si es lita esta por default 0 pero si es lista es 1
        $bloque_venta_detalle=$request->arrayDesatlleVenta;
        foreach ($bloque_venta_detalle as $item) {
            $id_contador=$item['id_contador'];
            $es_lista=$item['es_lista'];
            $id_ges_pre=$item['id_ges_pre'];
            $id_ingreso=$item['id_ingreso'];
            $id_producto=$item['id_producto'];
            $id_linea=$item['id_linea'];
            $precio_venta=$item['precio_venta'];
            $cantidad_venta=$item['cantidad_venta'];
            $codigo_tienda_almacen=$item['codigo_tienda_almacen'];
            $descuento=$item['descuento'];
            $data_det_venta = [
                'id_detalle_descuento'=>$id_contador,
                'id_venta' => $id_recibo,          
                'es_lista' => $es_lista,
                'id_ges_pre' => $id_ges_pre,
                'id_ingreso' => $id_ingreso, 
                'id_producto' => $id_producto,       
                'id_linea' => $id_linea,    
                'precio_venta' => $precio_venta,
                'cantidad_venta' => $cantidad_venta,
                'codigo_tienda_almacen'=>$codigo_tienda_almacen,
                 'descuento'=>$descuento,
               ];  
            DB::table('ven__detalle_ventas')->insert($data_det_venta);

           // Si todo sale bien, confirmar la transacción
           $update = Tda_ingresoProducto2::find($id_ingreso);
           $cantidad_v3=$update->stock_ingreso;
           $update->stock_ingreso = $cantidad_v3-$cantidad_venta;
           $update->save();    
        }
        $validador_1="";
        $valor_2=0;
        $numeroTarjeta="";
       
        switch ($tipo_venta) {
            case 2:
            $validador_1="QR";
            $numeroTarjeta=$request->numeroTarjeta;
            $valor_2=1;
                break;
            case 3:
            $validador_1="TRJ";      
          
            if($request->numeroTarjeta==null || $request->numeroTarjeta==''){
                $numeroTarjeta=$request->numeroTarjeta;  
            } else{ 
                $numeroTarjeta = $this->ofuscarTarjeta($request->numeroTarjeta); 
            } 
            $valor_2=1;
                break;
            case 0:
                $validador_1="ERROR";
                $numeroTarjeta=0;
                break;    
            default:
                $validador_1="ERROR";
                $numeroTarjeta=0;
                break;
        }   

        if($valor_2==1){
            $data_transaccion = [
                'id_venta'=>$id_recibo,
                'tipo' => $validador_1,          
                'num_tar_o_boleto' => $numeroTarjeta,
                'mas_datos' => $request->cadenaOtros,  
                'id_banco' => $request->tipoBanco           
               ];  
            DB::table('ven__trasferencias')->insert($data_transaccion);
        }   
        
    DB::commit();
        // Llamar a create_recibo
        //$reciboData = $this->create_recibo($idsuc,$id_user2,$nuevoComprobante,$nomsucursal,$num_documento,$currentDateTime,$nom_a_facturar,$numero_referencia,$request->arrayProRecibo,$total_sin_des,$descuento_venta, $total_venta,$efectivo_venta,$cambio_venta);
            // Devolver una respuesta JSON con un mensaje de éxito
          $array_recibo = $request->arrayProRecibo;
          

        $sucursal_0 =  DB::table('adm__sucursals as ass')
    ->join('adm__departamentos as ad', 'ass.departamento', '=', 'ad.id')
    ->select('ass.razon_social', 'ass.telefonos', 'ass.nit', 'ass.direccion', 'ass.ciudad', 'ad.nombre as departamento')
    ->where('ass.id', $idsuc)
    ->first();
    $razonSocial_su = $sucursal_0->razon_social;
    $telefonos_su = $sucursal_0->telefonos;
    $nit_su = $sucursal_0->nit;
    $direccion_su = $sucursal_0->direccion;
    $ciudad_su = $sucursal_0->ciudad;
    $departamento_su = $sucursal_0->departamento;
        //  $direccion = DB::table('adm__sucursals')
        //  ->where('id', $idsuc)
        //  ->value('direccion'); 
  
      $nombreCompletoObj = DB::table('rrh__empleados as re')
          ->join('users as u', 're.id', '=', 'u.idempleado')
          ->where('u.id', $id_user2)
          ->select(DB::raw("CONCAT(
              COALESCE(re.nombre, ''), ' ',
              COALESCE(re.papellido, ''), ' ',
              COALESCE(re.sapellido, '')
          ) as nombre_completo"))
          ->first();
          $nombreCompleto = $nombreCompletoObj ? $nombreCompletoObj->nombre_completo : '';
              //datos para hacer factura vista
              $ciudad_su_1 = strtoupper($ciudad_su);
              $departamento_su_1 = strtoupper($departamento_su);       
        $nombre_negocio = strtoupper($nomsucursal);      
        $direccionMayusculas=strtoupper($direccion_su);     
        $fecha = $currentDateTime->format('d/m/Y');
       // $hora = $currentDateTime->format('H:i:s'); 
       $hora = $currentDateTime->format('h:i:s A');
       $fecha_7 = $currentDateTime->addDays(7);     
        $fechaMas7Dias = $fecha_7->format('d/m/Y');
        $nombreCompleto_1=strtoupper($nombreCompleto);
        
        
        $total_literal = converso_numero_a_texto::convertirNumeroATexto($total_venta);
        return response()->json([
           // 'idsuc' => $idsuc,
           // 'id_user2' => $id_user2,           
           'nomsucursal' => $nombre_negocio,
           'direccionMayusculas' => $direccionMayusculas, 
           'nuevoComprobante' => $controlador_2_1,          
            'fecha' => $fecha,
            'hora' => $hora, 
            'num_documento' => $num_documento,
            'nom_a_facturar' => $nom_a_facturar,
            //'currentDateTime' => $currentDateTime,
           'array_recibo' => $array_recibo,
           'total_sin_des' => $total_sin_des,                
            'efectivo_venta' => $efectivo_venta,
            'total_venta' =>$total_venta,
            'descuento_venta' => $descuento_venta,
            'cambio_venta' => $cambio_venta,
            'fechaMas7Dias' => $fechaMas7Dias,
            'numero_referencia' => $numero_referencia,
            'nombreCompleto_1' => $nombreCompleto_1,
            'tipocom'=> $dato_tipo,
            'nombre_empresa' => $nombre_empresa,  
            'ciudad_su_1' => $ciudad_su_1, 
            'departamento_su_1' => $departamento_su_1,
            'descuento_final_2' => $descuento_final_2,
            
            'nit_2' =>$nit_2,
            'actividad_economica' => $actividad_economica,
            'num_auto' => $num_auto,
            'cod_autorizacion' => $cod_autorizacion,
            'fecha_e_2' => $fecha_e_2,
            'numero_factura' => $num_factura,
            'cliente_id' => $cliente_id,
            'total_literal' => $total_literal,

            'id_apertura'=>$id_apertura_cierre,
        'tipo_venta'=>$tipo_venta,
        'monto_vale'=>$monto_vale,
        'monto_apagar'=>$monto_apagar
        ]);


    } catch (\Throwable $th) {
            // Revertir la transacción en caso de error
        DB::rollback();
 
              return response()->json(['error' => $th->getMessage()],500);
        }
      
    }


    public function get_producto_bloque(Request $request){

        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        //dd(session()->all());
        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
         }
        // Obtener todos los registros donde id_sucursal es 1
        // Obtener todos los registros donde id_sucursal es 1 usando el constructor de consultas
        $sucursales = DB::table('adm__sucursal_listas')->where('id_sucursal', $idsuc)->first();
        $pro=$request->producto;      

        // Dividimos la cadena por el espacio
        $a = explode(' ', $pro);  
        $partes = $a[0];
   
        if ($sucursales) {
            // si tiene  es con lista 
            $id_suc=$sucursales->id_sucursal;
            $id_lista=$sucursales->id_lista; 
            $resultados = DB::table('ges_pre__venta_listas as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__registro_pre_x_lists as prp', 'prp.id', '=', 'gpv.id_lista')
            ->join('prod__listas as pl', 'pl.id', '=', 'prp.id_lista')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tt.codigo as codigo_tienda_almacen' ,
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
                'pp.nombre as prod_name',
                'tip.id as id_ingreso',
                DB::raw("
                CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS leyenda
            "),
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
             "),  
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
                    WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
                    WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
                    ELSE NULL
                END AS id_unidad_medida
             "), 
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11','pppl.id as id_linea',
     'pd2.id as id_descuento',
            'pd2.id_tipo_tabla as id_tabla','tip.prioridad_caducidad','pp.codigoActividad','pp.codigoProducto'  
            )
            ->where('ass.id', $id_suc)
            ->where('gpv.listo_venta', 1)
            ->where('pl.id', $id_lista)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
            ->where('pp.nombre', 'like', '%' . $partes . '%')
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->orderBy('tip.prioridad_caducidad', 'desc')
            ->get(); 
            return $resultados;
       
        } else{
            // lista por defecto 
            $resultados = DB::table('ges_pre__venta2s as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
   
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tt.codigo as codigo_tienda_almacen' ,
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
                'pp.nombre as prod_name',
                'tip.id as id_ingreso',
                DB::raw("
                CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS leyenda
            "),
            DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
             "),  
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
                    WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
                    WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
                    ELSE NULL
                END AS id_unidad_medida
             "),
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11','pppl.id as id_linea',
    'pd2.id as id_descuento',
    'pd2.id_tipo_tabla as id_tabla','tip.prioridad_caducidad','pp.codigoActividad','pp.codigoProducto'         
            )
            ->where('ass.id', $idsuc)
            ->where('gpv.listo_venta', 1)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
            ->where('pp.nombre', 'like', '%' . $partes . '%')
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->orderBy('tip.prioridad_caducidad', 'desc')
            ->get();
    
            return $resultados;    
        }

    }    


    public function get_sucusal(){
        
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        $user_rubro = auth()->user()->rubro_x_usuario;
        if($user_rubro==null || $user_rubro==""){
            return "000";
        }
        $idrubro = explode(',', $user_rubro);
        //dd(session()->all());
        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }

              // Obtener todos los registros donde id_sucursal es 1
          // Obtener todos los registros donde id_sucursal es 1 usando el constructor de consultas
        $sucursales = DB::table('adm__sucursal_listas')->where('id_sucursal', $idsuc)->first();
   
       
        if ($sucursales) {
            // si tiene  es con lista 
            $id_suc=$sucursales->id_sucursal;
            $id_lista=$sucursales->id_lista; 
            $resultados = DB::table('ges_pre__venta_listas as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__registro_pre_x_lists as prp', 'prp.id', '=', 'gpv.id_lista')
            ->join('prod__listas as pl', 'pl.id', '=', 'prp.id_lista')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tt.codigo as codigo_tienda_almacen' ,
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
             'pp.nombre as prod_name',
                'tip.id as id_ingreso',
                DB::raw("
                CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS leyenda
            "),
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
             "),
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
                    WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
                    WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
                    ELSE NULL
                END AS id_unidad_medida
             "),   
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11','pppl.id as id_linea',
     'pd2.id as id_descuento',
            'pd2.id_tipo_tabla as id_tabla','tip.prioridad_caducidad','pp.codigoActividad','pp.codigoProducto','pp.idrubro'
            )
            ->where('ass.id', $id_suc)
            ->where('gpv.listo_venta', 1)
            ->where('pl.id', $id_lista)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
            ->where('tip.stock_ingreso','>',0)
            ->whereIn('pp.idrubro', $idrubro)
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->orderBy('tip.prioridad_caducidad', 'desc')
            ->get(); 
            return $resultados;
       
        } else{
            // lista por defecto 
            $resultados = DB::table('ges_pre__venta2s as gpv')
            ->join('pivot__modulo_tienda_almacens as pivot', 'pivot.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
            ->join('tda__ingreso_productos as tip', 'pivot.id_ingreso', '=', 'tip.id')
            ->join('tda__tiendas as tt', 'tt.id', '=', 'tip.idtienda')
            ->join('prod__productos as pp', 'pp.id', '=', 'tip.id_prod_producto')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
            ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
            ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
            ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
            ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
            ->join('prod__lineas as pppl','pppl.id','=','pp.idlinea')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_prod', '=', 'pp.id')
            ->leftJoin('par__descuentos as pd2', 'ppd.id_descuento', '=', 'pd2.id')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
   
            ->select(
                'gpv.id as id',
                'gpv.precio_lista_gespreventa',
                'gpv.precio_venta_gespreventa',
                'gpv.cantidad_envase_gespreventa',
                'gpv.costo_compra_gespreventa',
                'gpv.margen_20p_gespreventa',
                'gpv.margen_30p_gespreventa',
                'gpv.utilidad_bruta_gespreventa',
                'gpv.utilidad_neto_gespreventa',
                'tt.codigo as codigo_tienda_almacen' ,
                'tip.envase',
                'tip.cantidad',
                'tip.stock_ingreso',
                'tip.fecha_vencimiento',
                'tip.lote',
                'pp.codigo as codigo_prod',
              'pp.nombre as prod_name',
                'tip.id as id_ingreso',
                DB::raw("
                CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS leyenda
            "),
            DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN UPPER(CONCAT(COALESCE(ff_1.nombre, '')))
                    WHEN tip.envase = 'secundario' THEN UPPER(CONCAT(COALESCE(ff_2.nombre, '')))
                    WHEN tip.envase = 'terciario' THEN UPPER(CONCAT(COALESCE(ff_3.nombre, '')))
                    ELSE NULL
                END AS unidad_medida
             "),  
             DB::raw("
             CASE 
                    WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
                    WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
                    WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
                    ELSE NULL
                END AS id_unidad_medida
             "),
                'gpv.id_lista','pppl.nombre as nombre_linea','pp.id as id_prod',
                DB::raw('DATEDIFF(fecha_vencimiento, NOW()) AS dias'),
                DB::raw("CASE 
                WHEN pd2.desc_num = 1 THEN '#'
                WHEN pd2.desc_num = 2 THEN '%'
                ELSE NULL
            END as tipo_num_des"),
    'pd2.monto_descuento',
    'pd2.activo as descuento_activo',
    'pad2.id_sucursal as id_11','pppl.id as id_linea',
    'pd2.id as id_descuento',
    'pd2.id_tipo_tabla as id_tabla','tip.prioridad_caducidad','pp.codigoActividad','pp.codigoProducto','pp.idrubro'         
            )
            ->where('ass.id', $idsuc)
            ->where('gpv.listo_venta', 1)
            ->where('pp.activo', 1)
            ->where('tip.activo', 1)
                 ->where('tip.stock_ingreso','>',0)
            ->whereIn('pp.idrubro', $idrubro)
            ->where('tip.fecha_vencimiento', '>=', DB::raw('CURDATE()'))
            ->orderBy('tip.prioridad_caducidad', 'desc')
            ->get();
            return $resultados;    
        }

       
    }
   
 
   
    public function listarUsuario(Request $request){
        $clientes = DB::table('dir__clientes as dc')
        ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
        ->leftJoin('dir__personas as dp', function ($join) {
            $join->on('dp.id', '=', 'dc.id_per_emp')
                 ->where('dc.tipo_per_emp', '=', 1);
        })
        ->leftJoin('dir__empresas as de', function ($join) {
            $join->on('de.id', '=', 'dc.id_per_emp')
                 ->where('dc.tipo_per_emp', '=', 2);
        })
        ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
        ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
        ->leftJoin('par__asignacion_descuento as pad2', function($join) {
            $join->on('pad2.id_descuento', '=', 'pd2.id')
                 ->where('pad2.id_sucursal', '=', 1);
        })
        ->where('dc.activo', 1)
        ->where('dc.num_documento', $request->num_doc)
 
        ->select([
            'dc.id',
            'dc.nom_a_facturar',
            'dc.id_per_emp',
            'dc.num_documento',
            'dtd.id as id_tipo_doc',
            'dtd.nombre_doc as tipo_doc_nombre',
            'dtd.datos as tipo_doc_datos',
            DB::raw('CASE 
                        WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                        WHEN de.id IS NOT NULL THEN de.razon_social
                        ELSE NULL
                    END as nombre_completo'),
            'dc.created_at as fecha',
            DB::raw('CASE 
                        WHEN pd2.desc_num = 1 THEN "#"
                        WHEN pd2.desc_num = 2 THEN "%"
                        ELSE NULL
                    END as tipo_num_des'),
            'pd2.monto_descuento',
            'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11',
            'pd2.id as id_descuento',
            'pd2.id_tipo_tabla as id_tabla',
            'dp.complemento as complemento_s'
        ])        
       
        ->first();
  
        return $clientes;
    }

    public function listarUsuarioRetorno(Request $request)
{
    $buscararray = array();
    if (!empty($request->buscar)) {
        $buscararray = explode(" ", $request->buscar);
        $valor = sizeof($buscararray);
        if ($valor > 0) {
            $sqls = '';
            foreach ($buscararray as $key => $valor) {
                // Corrección: Se verifica si $sqls está vacío antes de añadir la primera condición
                if (empty($sqls)) {
                    $sqls = "(
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento like '%" . $valor . "%'
                    )";
                } else {
                    // Corrección: Se añade un paréntesis de apertura y se corrige la concatenación
                    $sqls .= " and (
                        dc.nom_a_facturar like '%" . $valor . "%' 
                        or dc.num_documento like '%" . $valor . "%'
                    )";
                }
            }
            
            $clientes = DB::table('dir__clientes as dc')
                ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
                ->leftJoin('dir__personas as dp', function ($join) {
                    $join->on('dp.id', '=', 'dc.id_per_emp')
                         ->where('dc.tipo_per_emp', '=', 1);
                })
                ->leftJoin('dir__empresas as de', function ($join) {
                    $join->on('de.id', '=', 'dc.id_per_emp')
                         ->where('dc.tipo_per_emp', '=', 2);
                })
                ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
                ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
                ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                    $join->on('pad2.id_descuento', '=', 'pd2.id')
                         ->where('pad2.id_sucursal', '=', 1);
                })
                ->where('dc.activo', 1)
                ->whereRaw($sqls)
                ->take(10)
                ->orderBy('id', 'desc')
                ->select([
                    'dc.id',
                    'dc.nom_a_facturar',
                    'dc.id_per_emp',
                    'dc.num_documento',
                    'dtd.id as id_tipo_doc',
                    'dtd.nombre_doc as tipo_doc_nombre',
                    'dtd.datos as tipo_doc_datos',
                    DB::raw('CASE 
                                WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                                WHEN de.id IS NOT NULL THEN de.razon_social
                                ELSE NULL
                            END as nombre_completo'),
                    'dc.created_at as fecha',
                    DB::raw('CASE 
                                WHEN pd2.desc_num = 1 THEN "#"
                                WHEN pd2.desc_num = 2 THEN "%"
                                ELSE NULL
                            END as tipo_num_des'),
                    'pd2.monto_descuento',
                    'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11',
            'pd2.id as id_descuento',
            'pd2.id_tipo_tabla as id_tabla'
                ])
                ->get();

            return $clientes;
        }
    } else {
        // Si no hay parámetro de búsqueda, se ejecuta esta parte del código
        $clientes = DB::table('dir__clientes as dc')
            ->join('dir__tipo_doc as dtd', 'dc.id_tipo_doc', '=', 'dtd.id')
            ->leftJoin('dir__personas as dp', function ($join) {
                $join->on('dp.id', '=', 'dc.id_per_emp')
                     ->where('dc.tipo_per_emp', '=', 1);
            })
            ->leftJoin('dir__empresas as de', function ($join) {
                $join->on('de.id', '=', 'dc.id_per_emp')
                     ->where('dc.tipo_per_emp', '=', 2);
            })
            ->leftJoin('par__cliente_producto as pcp', 'pcp.id_cliente_p', '=', 'dc.id')
            ->leftJoin('par__descuentos as pd2', 'pd2.id', '=', 'pcp.id_descuento')
            ->leftJoin('par__asignacion_descuento as pad2', function($join) {
                $join->on('pad2.id_descuento', '=', 'pd2.id')
                     ->where('pad2.id_sucursal', '=', 1);
            })
            ->where('dc.activo', 1)
            ->orderBy('id', 'desc')
            ->take(40)
            ->select([
                'dc.id',
                'dc.nom_a_facturar',
                'dc.id_per_emp',
                'dc.num_documento',
                'dtd.id as id_tipo_doc',
                'dtd.nombre_doc as tipo_doc_nombre',
                'dtd.datos as tipo_doc_datos',
                DB::raw('CASE 
                            WHEN dp.id IS NOT NULL THEN CONCAT(dp.nombres, " ", dp.apellidos)
                            WHEN de.id IS NOT NULL THEN de.razon_social
                            ELSE NULL
                        END as nombre_completo'),
                'dc.created_at as fecha',
                DB::raw('CASE 
                            WHEN pd2.desc_num = 1 THEN "#"
                            WHEN pd2.desc_num = 2 THEN "%"
                            ELSE NULL
                        END as tipo_num_des'),
                'pd2.monto_descuento',
                'pd2.activo as descuento_activo',
            'pad2.id_sucursal as id_11',
            'pd2.id as id_descuento',
            'pd2.id_tipo_tabla as id_tabla'
            ])
            ->get();

        return $clientes;
    }
}

    

    public function listarDescuentos_listas(Request $request){

        $user_1 = auth()->user()->id;   

        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }
       // Consulta optimizada
        $validador = DB::table('par__asignacion_descuento')
        ->where('id_sucursal', $idsuc)
        ->where(DB::raw('LEFT(cod, 3)'), 'TDA')
        ->where('personalizado', 1)
        ->exists();

        // Verificar el resultado
        if ($validador) {
            $descuento =  DB::table('par__asignacion_descuento as pad1')
        ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
        ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
        ->select(
            'pad1.id_descuento as id',
            'pad1.cod',
            'pd.nombre_descuento',
            'ptt.id as id_tabla',
            'ptt.nombre as nombre_tabla',
            'pad1.personalizado as per'
        )
        ->where('pd.activo', 1)
        ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
        ->where('pad1.id_sucursal', $idsuc)
        ->where('pad1.personalizado','=',1)
        ->get();  
        
            } else {
                $descuento = DB::table('par__asignacion_descuento AS pad1')
                ->join('par__descuentos AS pd', 'pd.id', '=', 'pad1.id_descuento')
                ->join('par_tipo_tabla AS ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
                ->select(
                    'pad1.id_descuento as id',
                    'pad1.cod',
                    'pd.nombre_descuento',
                    'ptt.id as id_tabla',
                    'ptt.nombre as nombre_tabla',
                    'pad1.personalizado as per'
                )
                ->where('pd.activo', 1)
                ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
                ->where('pad1.id_sucursal', $idsuc)
                ->where('pad1.personalizado', '=', 0)->get();       
  
            }

            $lista = DB::table('adm__sucursal_listas as asl')
            ->join('prod__listas as pl', 'asl.id_lista', '=', 'pl.id')
            ->select('pl.id', 'pl.nombre_lista')
            ->where('pl.activo', '=',1)
            ->where('asl.id_sucursal','=',$idsuc)
            ->first();
          
    return response()->json(['descuento' => $descuento, 'lista' => $lista,'id_descuento_x'=>$idsuc]);
    }


    public function listarDescuento_Tipo_tabla(){
        $user_1 = auth()->user()->id;   

        if ($user_1==1) {
            $idsuc=1;
        }else{
            $iduserrolesuc = session('iduserrolesuc');
            $idsuc = session('idsuc');
            $id_user2 = session('id_user2'); 
        }
         // Consulta optimizada
         $validador = DB::table('par__asignacion_descuento')
         ->where('id_sucursal', $idsuc)
         ->where(DB::raw('LEFT(cod, 3)'), 'TDA')
         ->where('personalizado', 1)
         ->exists();
          // Verificar el resultado
        if ($validador) {
            $descuento = DB::table('par__asignacion_descuento as pad1')
            ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
            ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
            ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
            ->leftJoin('par__cliente_producto as pcli', 'pcli.id_descuento', '=', 'pd.id')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
            ->leftJoin('par__personalizado as pp2','pp2.id_descuento','=','pd.id') 
            ->select(
                'pad1.id_descuento as id',
                'pad1.cod',
                'pd.nombre_descuento',
                DB::raw("
                    CASE
                        WHEN pd.desc_num = 1 THEN '#'
                        WHEN pd.desc_num = 2 THEN '%'
                        ELSE NULL
                    END AS tipo_num_des
                "),
                'pd.monto_descuento',
                'ptt.id as id_nom_tabla',
                'ptt.nombre as nombre_tabla',
                DB::raw("
                    CASE
                        WHEN pcp.es_cantidad_es_monto = 1 THEN '#'
                        WHEN pcp.es_cantidad_es_monto = 2 THEN 'BS'
                        ELSE NULL
                    END AS tipo_can_valor
                "),
                DB::raw("
                    CASE
                        WHEN pcp.regla = 1 THEN '<'
                        WHEN pcp.regla = 2 THEN '>'
                        WHEN pcp.regla = 3 THEN '='
                        ELSE NULL
                    END AS regla
                "),
                'pcp.cantidad_valor',
                'pcli.id_cliente_p',
                'ppd.id_prod',
                'pcp.id as id_tabla_cant_valor',
                'pcli.id as id_tabla_cliente',
                'ppd.id as id_tabla_prod',
                'pp2.id as id_perso',
                DB::raw("
                CASE
                WHEN ptt.id=1 THEN '0'
                WHEN ptt.id=2 THEN 	pcp.id
                WHEN ptt.id=3 THEN 	pcli.id
                WHEN ptt.id=4 THEN ppd.id
                WHEN ptt.id=5 THEN pp2.id
                WHEN ptt.id=6 THEN 'f' 
            ELSE NULL
              END as id_tablas_x
                "),
                'pad1.personalizado as per'
            )
            ->where('pd.activo', 1)
            ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
            ->where('pad1.id_sucursal', $idsuc)
            ->where('pad1.personalizado','=',1)
            ->get();
        
           
            return response()->json(['descuento' => $descuento, 'validador' => $validador]);
        }else{
            $descuento = DB::table('par__asignacion_descuento as pad1')
            ->join('par__descuentos as pd', 'pd.id', '=', 'pad1.id_descuento')
            ->join('par_tipo_tabla as ptt', 'ptt.id', '=', 'pd.id_tipo_tabla')
            ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
            ->leftJoin('par__cliente_producto as pcli', 'pcli.id_descuento', '=', 'pd.id')
            ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
            ->leftJoin('par__personalizado as pp2','pp2.id_descuento','=','pd.id') 
            ->select(
                'pad1.id_descuento as id',
                'pad1.cod',
                'pd.nombre_descuento',
                DB::raw("
                    CASE
                        WHEN pd.desc_num = 1 THEN '#'
                        WHEN pd.desc_num = 2 THEN '%'
                        ELSE NULL
                    END AS tipo_num_des
                "),
                'pd.monto_descuento',
                'ptt.id as id_nom_tabla',
                'ptt.nombre as nombre_tabla',
                DB::raw("
                    CASE
                        WHEN pcp.es_cantidad_es_monto = 1 THEN '#'
                        WHEN pcp.es_cantidad_es_monto = 2 THEN 'BS'
                        ELSE NULL
                    END AS tipo_can_valor
                "),
                DB::raw("
                    CASE
                        WHEN pcp.regla = 1 THEN '<'
                        WHEN pcp.regla = 2 THEN '>'
                        WHEN pcp.regla = 3 THEN '='
                        ELSE NULL
                    END AS regla
                "),
                'pcp.cantidad_valor',
                'pcli.id_cliente_p',
                'ppd.id_prod',
                'pcp.id as id_tabla_cant_valor',
                'pcli.id as id_tabla_cliente',
                'ppd.id as id_tabla_prod',
                'pp2.id as id_perso',
                DB::raw("
                CASE
                WHEN ptt.id=1 THEN '0'
                WHEN ptt.id=2 THEN 	pcp.id
                WHEN ptt.id=3 THEN 	pcli.id
                WHEN ptt.id=4 THEN ppd.id
                WHEN ptt.id=5 THEN pp2.id
                WHEN ptt.id=6 THEN 'f' 
            ELSE NULL
              END as id_tablas_x
                "),
                'pad1.personalizado as per'
            )
            ->where('pd.activo', 1)
            ->where(DB::raw('LEFT(pad1.cod, 3)'), 'TDA')
            ->where('pad1.id_sucursal', $idsuc)
            ->get();
        
            return response()->json(['descuento' => $descuento, 'validador' => $validador]);
        }
       

    }


    public function verificador_dosificacion_o_facturacion(Request $request){
        
        $credencialesCorreos = DB::table('adm__credecial_correos as acc')
    ->select('acc.id', 'acc.factura_dosificacion')    
    ->get();
    $data_return_estado="";
    $fechaHoy = Carbon::now()->format('Y-m-d');  
  
    if ($credencialesCorreos[0]->factura_dosificacion==null || $credencialesCorreos[0]->factura_dosificacion=="" || $credencialesCorreos[0]->factura_dosificacion==0) {
        return response()->json(['estado' => 0, 'consulta' => null,'query'=>null]);
    } else{

            switch ($credencialesCorreos[0]->factura_dosificacion) {
                case 1:
                       ///---- falta datos de factura en linea siat
                       $query_1 =  DB::table('adm__credecial_correos as a')
                       ->join('adm__nacionalidads as n', 'a.moneda', '=', 'n.id')
                       ->where('a.id', 1)
                       ->first();
                       
                        $query_2 = DB::table('siat__configuracions')
                        ->where('id', 1)
                        ->first();
                   
$query_emisor= DB::table('siat__emisors as e')
    ->select('e.id','e.id_caja','e.tipo','e.id_punto_venta as punto_venta','e.id_cuis','e.id_cufd','s.codigo_siat as id_sucursal_siat','s.id_sucursal as id_sucursal_sistemas',
        'c.id_users','cuis.dato as cuis','cuis.fecha_vigencia as fecha_vigencia_cuis','cufd.dato as cufd','cufd.fecha_vigencia as fecha_vigencia_cufd','cufd.codigoControl as codigo_control_cufd',
        'cufd.direccion as direccion_cufd'
    )
    ->leftJoin('siat__sucursals as s', 's.id', '=', 'e.id_siat_sucursal')
    ->leftJoin('caja__creacions as c', 'c.id', '=', 'e.id_caja')
    ->leftJoin('siat__cuis as cuis', 'cuis.id', '=', 'e.id_cuis')
    ->leftJoin('siat__cufd as cufd', 'cufd.id', '=', 'e.id_cufd')
    ->where('e.estado', 1)
    ->where('cuis.estado', 1)
    ->where('cufd.estado', 1)
    ->whereNotNull('e.id_caja')
    ->where('e.id_caja', $request->id_caja)//emisor
    ->where('c.id_sucursal', $request->id_sucursal)//sucursal de sistema
    ->first();
  
                        
                     //   $a=$query_2->password;
// Eliminar los dos primeros y los tres últimos caracteres
//$cadena_modificada = substr($a, 2, -3);
//$textoDesencriptado = Crypt::decrypt($cadena_modificada);
//query_2->password,path->query_2, query_2->name 
     $cod_exito=0;
$fechaHoy = Carbon::today(); // Fecha actual (YYYY-MM-DD)
$fechaFutura = Carbon::parse($query_2->vencimiento_token); // Fecha futura límite

$hoy = Carbon::now()->startOfDay(); // Fecha actual sin hora
if ($hoy->greaterThan($fechaFutura)) {
   // dd("Ya pasó la fecha $fechaFutura");
   return response()->json(['estado' => 10, 'consulta' => null,'query'=>null]); 
} else {
   // dd("Está en rango, aún no ha pasado la fecha $fechaFutura");
   // Convertir a Carbon

$fechaA = Carbon::parse($query_emisor->fecha_vigencia_cufd);
$fechaC = Carbon::parse($query_emisor->fecha_vigencia_cuis);
// Obtener la fecha actual en el mismo formato
$hoy = Carbon::now();

// Comparar fechas
if ($hoy->greaterThan($fechaC)) { 
    return response()->json(['estado' => 11, 'consulta' => null,'query'=>null]);
} else { 
    $cod_exito++;
}
// Comparar fechas
if ($hoy->greaterThan($fechaA)) {
    return response()->json(['estado' => 12, 'consulta' => null,'query'=>null]);
} else {
    $cod_exito++;
}

    if ($cod_exito>=2) {
        $datos = [
            'nit' => $query_1->nit,
            'nro_celular' => $query_1->nro_celular,    
            'emisor_razon_soc' =>$query_1->nom_empresa, 
            'cod_sis' => $query_2->cod_sis, 
            'name' => $query_2->name,
            'password' => $query_2->password,
            'path' => $query_2->path,
            'tipo_ambiente' => $query_2->tipo_ambiente,
            'tipo_certificado' => $query_2->tipo_certificado,
            'tipo_modalidad' => $query_2->tipo_modalidad,
            'token_delegado' => $query_2->token_delegado,  
            'moneda' => $query_1->simbolo,       
            ];
    
        return response()->json(['estado' => 1, 'consulta' => $datos,'query'=>$query_emisor]); 
    }else{
        return response()->json(['estado' => 13, 'consulta' => null,'query'=>null]);  
    }
}
//10= error de fecha de token, 11= error de fecha vencimiento cuis o no activo ,12=error de fecha vencimiento cufd o no activo
 
                break;

                case 2:
                    if (auth()->user()->id===1) {                 
                        $idsuc = 1;
                        } else {                
                            $idsuc = session('idsuc');
                        }
                  
                    $dosificaciones = DB::table('dos__dosificacion as dd')
                    ->select(
                        'dd.id',
                        'dd.nro_autorizacion',
                        'dd.dosificacion',
                        'dd.fecha_e',
                        'dd.n_ini_facturacion',
                        'dd.n_fin_facturacion',
                        'dd.n_act_facturacion',
                        'dd.nit',
                        DB::raw("DATEDIFF(dd.fecha_e, '$fechaHoy') as diferencia_dias")
                    )
                    ->where('dd.id_sucursal', $idsuc)
                    ->where('dd.estado', 1)
                    ->first();
        
                        if ( $dosificaciones) {
                            return response()->json(['estado' => 2, 'consulta' => $dosificaciones,'query'=>null]);
                   
                        }
                        else {
                            return response()->json(['estado' => "error", 'consulta' => null,'query'=>null]);
                        }
                 
                break;
                
                case 3:
                    ///---- recibo
                 return response()->json(['estado' => 3, 'consulta' => null,'query'=>null]); 
                break;

                default:
                return response()->json(['estado' => 0, 'consulta' => null,'query'=>null]); 
                break;
            }
    }
    
    }

    public function tieneApertura(){
        $id_user=auth()->user()->id; 
        if ($id_user==1) {
            $idsuc = 1;
        } else {
            $idsuc = session('idsuc');
        }
    //$apertura = DB::table('caja__apertura_cierres')
    //->select('id')
    //->where('id_sucursal', '=',$idsuc)
    //->where('id_apertura_cierre', '=',0)
    //->get();
    //return $apertura;


   // $ultimoRegistro = DB::table('caja__apertura_cierres')
   // ->select('id','turno_caja', 'tipo_caja_c_a', 'total_caja', 'estado_caja', 'id_arqueo','id_apertura_cierre')
   // ->where('id_sucursal', $idsuc)
   // ->where('id_apertura_cierre', '=',0)
   // ->orderBy('id', 'desc')
   // ->first();

    $ultimoRegistro = DB::table('caja__apertura_cierres as cac')
    ->join('caja__arqueo as ca', 'cac.id_arqueo', '=', 'ca.id')
    ->join('users as u', 'u.id', '=', 'ca.id_usuario')
    ->select('cac.id','cac.turno_caja', 'cac.tipo_caja_c_a', 'cac.total_caja', 'cac.estado_caja', 'cac.id_arqueo','cac.id_cierre as id_apertura_cierre','cac.id_caja','cac.id_sucursal')
    ->where('cac.id_sucursal', $idsuc)
    ->where('ca.id_usuario', $id_user)
    ->where('cac.tipo_caja_c_a', 0)
    ->where('cac.id_cierre', 0)
    ->orderBy('cac.created_at', 'desc')
    ->first();

    if($ultimoRegistro==null){
        $ultimoRegistro=0;  
    }     
 return $ultimoRegistro;  

    }
    

    public function tipoPago(){
        $registros = DB::table('excel__emision')
    ->where('id_catalogo', 8)
    ->get();
        return $registros;
    }

    private function ofuscarTarjeta($numeroTarjeta) {
      
    
        // Asegurar que la tarjeta tiene al menos 8 dígitos
        if (strlen($numeroTarjeta) < 8) {
            return null;
        }
    
        // Obtener los primeros 4 y últimos 4 dígitos
        $primeros4 = substr($numeroTarjeta, 0, 4);
        $ultimos4 = substr($numeroTarjeta, -4);
    
        // Rellenar el medio con ceros
        $ceros = str_repeat('0', strlen($numeroTarjeta) - 8);
    
        return $primeros4 . $ceros . $ultimos4;
    }

    private function enviarFactura_siat($codigo_ambiente,$token_delegado,$codigoDocumentoSector,$codigoEmision,$codigoModalidad,$codigoPuntoVenta
    ,$codigoSistema,$codigoSucursal,$cufd,$cuis,$nit,$tipoFacturaDocumento,$archivo,$fechaEnvio,$hashArchivo){
        $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigo_ambiente))
        ->where('se.id',4)
        ->first(); 


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
                                <codigoAmbiente>{$codigo_ambiente}</codigoAmbiente>
                                <codigoDocumentoSector>{$codigoDocumentoSector}</codigoDocumentoSector>
                                <codigoEmision>{$codigoEmision}</codigoEmision>
                                <codigoModalidad>{$codigoModalidad}</codigoModalidad>
                                <codigoPuntoVenta>{$codigoPuntoVenta}</codigoPuntoVenta>
                                <codigoSistema>{$codigoSistema}</codigoSistema>
                                <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                                <cufd>{$cufd}</cufd>
                                <cuis>{$cuis}</cuis>
                                <nit>{$nit}</nit>
                                <tipoFacturaDocumento>{$tipoFacturaDocumento}</tipoFacturaDocumento>
                                <archivo>{$archivo}</archivo>
                                <fechaEnvio>{$fechaEnvio}</fechaEnvio>
                                <hashArchivo>{$hashArchivo}</hashArchivo>
                             </SolicitudServicioRecepcionFactura>
                          </siat:recepcionFactura>
                       </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                               
                   // return $xmlData;
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
                    $xml = simplexml_load_string($response);                    
                    $respuesta=$response;

                    return $respuesta;



    }
 
    private function  insertarVenta($cliente_id,$total_venta,$efectivo_venta,$cambio_venta,$descuento_venta,$total_sin_des,$dato_tipo,$codigo_tienda_almacen_0
    ,$id_lista_v2,$numero_referencia,$num_documento,$nom_a_facturar,$estado_dosificacion_facctura,$id_apertura_cierre,$tipo_venta,
    $monto_vale,$monto_apagar,$moneda,$arrayDescuentoOperacion,$arrayDesatlleVenta,$numeroTarjeta,$cadenaOtros,$tipoBanco,$id_cufd,$id_cuis
    ,$cuf,$id_credenciales,$sucursal_siat,$punto_venta,$direccion,$municipio, $numFactura, $fechaEmision, $xml, $id_leyenda, $codRecepcion){
        DB::beginTransaction();
        $user_1 = auth()->user()->id;
        $nomsucursal="";
        $iduserrolesuc = "";
        $idsuc = "";
        $name_user = ""; 
        if ($user_1==1) {
         $valor = '1';
         return response()->json(['data' => $valor]);
        }else{
         $nomsucursal= session('nomsucursal');
         $iduserrolesuc = session('iduserrolesuc');
         $idsuc = session('idsuc');
         $id_user2 = session('id_user2'); 
        } 

        $ultimoComprobante = DB::table('ven__recibos')
    ->orderBy('contador', 'desc')
    ->value('contador');
    if (is_null($ultimoComprobante)) {
        // La tabla está vacía, iniciar con 1    
        $contador_2 = 1;
    } else {
        // Incrementar el último número de comprobante
        $contador_2 = $ultimoComprobante + 1;
    }
    $currentDateTime = Carbon::now();
    $year = strval($currentDateTime->year); 
$contadorCadena=strval($contador_2);
$controlador_2_1=$year.$contadorCadena;

////tabla recibo
$data_recibo = [
    'id_sucursal' => $idsuc,
    'id_cliente' => $cliente_id,
    'id_usuario'=>$id_user2,
    'nro_comprobante_venta' => $controlador_2_1,
    'total_venta' => $total_venta,
    'efectivo_venta' => $efectivo_venta, 
    'cambio_venta' => $cambio_venta,       
    'descuento_venta' => $descuento_venta,
    'created_at' => $currentDateTime,
    'updated_at' => $currentDateTime,
    'total_sin_des' => $total_sin_des,
    'tipo_venta_reci_fac' => $dato_tipo,
    'contador'=> $contador_2,
    'cod'=> $codigo_tienda_almacen_0,
    'id_lista'=>$id_lista_v2,
    'nro_ref'=>$numero_referencia,
    'nro_doc'=> $num_documento,
    'razon_social'=>$nom_a_facturar,
    'dosificacion_o_electronica'=>$estado_dosificacion_facctura,
    'id_apertura'=>$id_apertura_cierre,
    'tipo_venta'=>$tipo_venta,
    'monto_vale'=>$monto_vale,
    'monto_apagar'=>$monto_apagar,
    'moneda'=>$moneda,
   ];     
   $id_recibo = DB::table('ven__recibos')->insertGetId($data_recibo);

   /////// detalle_descuento
   $descuento_final_2=0;
   $bloque_descuento = $arrayDescuentoOperacion;
   foreach ($bloque_descuento as $item) {
    $id_tabla = $item['id_tabla'];
    $id_descuento = $item['id_descuento'];
    $cantidad_descuento = $item['cantidad_descuento'];
    $tipo_num_des =$item['id_contador'];
    $tipo_2=$item['tipo'];
    $data_descuento = [
        'id_venta' => $id_recibo,
        'id_tabla' => $id_tabla,  
        'id_descuento' => $id_descuento,
        'cantidad_descuento' => $cantidad_descuento, 
        'id_detalle_descuento'=> $tipo_num_des,
        'tipo' => $tipo_2      
       ];    
       DB::table('ven__detalle_descuentos')->insert($data_descuento);
       if($tipo_2==2&&$tipo_num_des==0){
        $descuento_final_2=$descuento_final_2+$cantidad_descuento;
       }      
   }

    /////// detalle_venta
        //$es_lista si es lita esta por default 0 pero si es lista es 1
        $bloque_venta_detalle=$arrayDesatlleVenta;
        foreach ($bloque_venta_detalle as $item) {
            $id_contador=$item['id_contador'];
            $es_lista=$item['es_lista'];
            $id_ges_pre=$item['id_ges_pre'];
            $id_ingreso=$item['id_ingreso'];
            $id_producto=$item['id_producto'];
            $id_linea=$item['id_linea'];
            $precio_venta=$item['precio_venta'];
            $cantidad_venta=$item['cantidad_venta'];
            $codigo_tienda_almacen=$item['codigo_tienda_almacen'];
            $descuento=$item['descuento'];
            $data_det_venta = [
                'id_detalle_descuento'=>$id_contador,
                'id_venta' => $id_recibo,          
                'es_lista' => $es_lista,
                'id_ges_pre' => $id_ges_pre,
                'id_ingreso' => $id_ingreso, 
                'id_producto' => $id_producto,       
                'id_linea' => $id_linea,    
                'precio_venta' => $precio_venta,
                'cantidad_venta' => $cantidad_venta,
                'codigo_tienda_almacen'=>$codigo_tienda_almacen,
                 'descuento'=>$descuento,
               ];  
            DB::table('ven__detalle_ventas')->insert($data_det_venta);
            // Si todo sale bien, confirmar la transacción
           $update = Tda_ingresoProducto2::find($id_ingreso);
           $cantidad_v3=$update->stock_ingreso;
           $update->stock_ingreso = $cantidad_v3-$cantidad_venta;
           $update->save();  
            }
            $validador_1="";
        $valor_2=0;
     
       
        switch ($tipo_venta) {
            case 2:
            $validador_1="QR";
        
            $valor_2=1;
                break;
            case 3:
            $validador_1="TRJ";      
          
            if($numeroTarjeta==null || $numeroTarjeta==''){
                $numeroTarjeta=$numeroTarjeta;  
            } else{ 
                $numeroTarjeta = $this->ofuscarTarjeta($numeroTarjeta); 
            } 
            $valor_2=1;
                break;
            case 0:
                $validador_1="ERROR";
                $numeroTarjeta=0;
                break;    
            default:
                $validador_1="ERROR";
                $numeroTarjeta=0;
                break;
        }   

        if($valor_2==1){
            $data_transaccion = [
                'id_venta'=>$id_recibo,
                'tipo' => $validador_1,          
                'num_tar_o_boleto' => $numeroTarjeta,
                'mas_datos' => $cadenaOtros,  
                'id_banco' => $tipoBanco           
               ];  
            DB::table('ven__trasferencias')->insert($data_transaccion);
        }   


///////////////////////////insercion a latabla factura siat//////////////////////////////
$data_siat = [
    'id_venta'=>$id_recibo,
    'id_cufd' => $id_cufd,          
    'id_cuis' => $id_cuis,
    'cuf' => $cuf,  
    'id_credenciales' => $id_credenciales,
    'sucursal_siat'=>$sucursal_siat,
    'punto_venta' => $punto_venta,          
    'direccion' => $direccion,
    'municipio' => $municipio,   
    'numFactura' => $numFactura,
    'fechaEmision' => $fechaEmision,
    'xml'=>$xml,
    'id_leyenda' => $id_leyenda, 
    'codRecepcion' => $codRecepcion                  
   ];       
   DB::table('ven__factura_siat')->insert($data_siat);
    DB::commit();

  } 

    
}
