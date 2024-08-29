<?php

namespace App\Helpers;

use NumberToWords\NumberToWords;

class conversorNumaTexoParaTodos
{
    public static function conversorNumaTexoParaTodos($numero)
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
        $textoDecimal = $decimal;

        // Retorna la combinación de ambas partes
        return ucfirst($textoEntero) ;
    }
}
