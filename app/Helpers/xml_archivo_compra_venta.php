<?php

namespace App\Helpers;

class xml_archivo_compra_venta
{
    public static function generarXML_fac_cv(
        $nit,$razonSocialEmisor,$municipio,$telefono,$numeroFactura,$cuf,$cufd,$codigoSucursal,$direccion,$fechaEmision,$nombreRazonSocial, 
        $codigoTipoDocumentoIdentidad,$numeroDocumento,$codigoCliente,$codigoMetodoPago,$montoTotal,$codigoMoneda,$tipoCambio,$usuario,$codigoDocumentoSector, 
        $actividadEconomica,$codigoProductoSin,$codigoProducto,$descripcion,$cantidad,$unidadMedida,$precioUnitario,$montoTotalSujetoIva,$montoTotalMoneda,
        $subTotal,$DigestValue,$X509Certificate,$X509SubjectName
        ) {        
        $xmlData = <<<EOD
        <facturaElectronicaCompraVenta xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="facturaElectronicaCompraVenta.xsd">
            <cabecera>
                <nitEmisor>{$nit}</nitEmisor>
                <razonSocialEmisor>{$razonSocialEmisor}</razonSocialEmisor>
                <municipio>{$municipio}</municipio>
                <telefono>{$telefono}</telefono>
                <numeroFactura>{$numeroFactura}</numeroFactura>
                <cuf>{$cuf}</cuf>
                <cufd>{$cufd}</cufd>
                <codigoSucursal>{$codigoSucursal}</codigoSucursal>
                <direccion>{$direccion}</direccion>
                <codigoPuntoVenta xsi:nil="true"/>
                <fechaEmision>{$fechaEmision}</fechaEmision>
                <nombreRazonSocial>{$nombreRazonSocial}</nombreRazonSocial>
                <codigoTipoDocumentoIdentidad>{$codigoTipoDocumentoIdentidad}</codigoTipoDocumentoIdentidad>
                <numeroDocumento>{$numeroDocumento}</numeroDocumento>
                <complemento xsi:nil="true"/>
                <codigoCliente>{$codigoCliente}</codigoCliente>
                <codigoMetodoPago>{$codigoMetodoPago}</codigoMetodoPago>
                <numeroTarjeta xsi:nil="true"/>
                <montoTotal>{$montoTotal}</montoTotal>
                <montoTotalSujetoIva>{$montoTotalSujetoIva}</montoTotalSujetoIva>
                <codigoMoneda>{$codigoMoneda}</codigoMoneda>
                <tipoCambio>{$tipoCambio}</tipoCambio>
                <montoTotalMoneda>{$montoTotalMoneda}</montoTotalMoneda>
                <leyenda>Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.</leyenda>
                <usuario>{$usuario}</usuario>
                <codigoDocumentoSector>{$codigoDocumentoSector}</codigoDocumentoSector>
            </cabecera>
            <detalle>
                <actividadEconomica>{$actividadEconomica}</actividadEconomica>
                <codigoProductoSin>{$codigoProductoSin}</codigoProductoSin>
                <codigoProducto>{$codigoProducto}</codigoProducto>
                <descripcion>{$descripcion}</descripcion>
                <cantidad>{$cantidad}</cantidad>
                <unidadMedida>{$unidadMedida}</unidadMedida>
                <precioUnitario>{$precioUnitario}</precioUnitario>
                <montoDescuento xsi:nil="true"/>
                <subTotal>{$subTotal}</subTotal>
                <numeroSerie xsi:nil="true"/>
                <numeroImei xsi:nil="true"/>
            </detalle>
            <Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
                <SignedInfo>
                    <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                    <SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/>
                    <Reference URI="">
                        <Transforms>
                            <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                            <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments"/>
                        </Transforms>
                        <DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/>
                        <DigestValue>{$DigestValue}</DigestValue>
                    </Reference>
                </SignedInfo>
                <SignatureValue/>
                <KeyInfo>
                    <X509Data>
                        <X509Certificate>{$X509Certificate}</X509Certificate>
                        <X509Certificate/>{$X509SubjectName}<X509SubjectName/>
                    </X509Data>                      
                </KeyInfo>
            </Signature>
        </facturaElectronicaCompraVenta>
        EOD;

        return $xmlData;
    }
    
}