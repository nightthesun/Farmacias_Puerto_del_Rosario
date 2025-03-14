<?php

namespace App\Helpers;

use DOMDocument;
use Exception;
use OpenSSLAsymmetricKey;

class XmlSigner
{
    /**
     * Firma un documento XML con la llave privada y pública
     *
     * @param string $xmlString Contenido del XML a firmar
     * @param string $privateKey Ruta o contenido de la llave privada
     * @param string $publicKey Ruta o contenido de la llave pública
     * @return string XML firmado
     * @throws Exception
     */
    public static function signXml(string $xmlString, string $privateKey, string $publicKey): string
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlString, LIBXML_NOBLANKS);

        // Canonicalizar el XML
        $canonicalXml = self::canonicalizeXml($doc);

        // Generar el HASH con SHA256
        $hash = hash('sha256', $canonicalXml, true);

        // Convertir a Base64
        $digestValue = base64_encode($hash);

        // Generar firma RSA SHA256
        $signatureValue = self::generateSignature($hash, $privateKey);

        // Agregar etiquetas de firma
        self::appendSignatureToXml($doc, $digestValue, $signatureValue, $publicKey);

        return $doc->saveXML();
    }

    private static function canonicalizeXml(DOMDocument $doc): string
    {
        return $doc->C14N();
    }

    private static function generateSignature(string $hash, string $privateKey): string
    {
        $key = openssl_get_privatekey($privateKey);
        if (!$key) {
            throw new Exception('No se pudo cargar la llave privada.');
        }
        openssl_sign($hash, $signature, $key, OPENSSL_ALGO_SHA256);
       //openssl_free_key($key);
        return base64_encode($signature);
    }

    private static function appendSignatureToXml(DOMDocument $doc, string $digestValue, string $signatureValue, string $publicKey): void
    {
        $signature = $doc->createElement('Signature');
        
        $signedInfo = $doc->createElement('SignedInfo');
        $digest = $doc->createElement('DigestValue', $digestValue);
        $signedInfo->appendChild($digest);

        $signatureValueElement = $doc->createElement('SignatureValue', $signatureValue);
        $publicKeyElement = $doc->createElement('X509Certificate', base64_encode($publicKey));

        $signature->appendChild($signedInfo);
        $signature->appendChild($signatureValueElement);
        $signature->appendChild($publicKeyElement);

        $doc->documentElement->appendChild($signature);
    }
}
