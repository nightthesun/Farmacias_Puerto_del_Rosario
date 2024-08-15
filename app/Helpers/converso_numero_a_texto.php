<?php

namespace App\Helpers;

use NumberToWords\NumberToWords;

class converso_numero_a_texto
{
    public static function convertirNumeroATexto($numero)
    {
        $numberToWords = new NumberToWords();

        // Obtén el convertidor para español
        $numberTransformer = $numberToWords->getNumberTransformer('es');

        // Separa la parte entera y la parte decimal
        $partes = explode('.', number_format($numero, 2, '.', ''));
        $entero = intval($partes[0]);
        $decimal = intval($partes[1]);

        // Convierte la parte entera a texto
        $textoEntero = $numberTransformer->toWords($entero);
        $textoDecimal = $decimal . '/100 Bs';

        // Retorna la combinación de ambas partes
        return ucfirst($textoEntero) . ' ' . $textoDecimal;
    }
}
