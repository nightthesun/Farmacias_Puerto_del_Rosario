<?php

namespace App\Helpers;

class CufHelper
{
    public static function generarCUF($nitEmisor, $fechaHora, $sucursal, $modalidad, $tipoEmision, $tipoFactura, $tipoDocumentoSector, $numeroFactura, $puntoVenta, $codigoControl)
    {
        // Formatear los valores con ceros a la izquierda
        $nitEmisor = str_pad($nitEmisor, 13, '0', STR_PAD_LEFT);
        $fechaHora = str_pad($fechaHora, 17, '0', STR_PAD_LEFT);
        $sucursal = str_pad($sucursal, 4, '0', STR_PAD_LEFT);
        $modalidad = str_pad($modalidad, 1, '0', STR_PAD_LEFT);
        $tipoEmision = str_pad($tipoEmision, 1, '0', STR_PAD_LEFT);
        $tipoFactura = str_pad($tipoFactura, 1, '0', STR_PAD_LEFT);
        $tipoDocumentoSector = str_pad($tipoDocumentoSector, 2, '0', STR_PAD_LEFT);
        $numeroFactura = str_pad($numeroFactura, 10, '0', STR_PAD_LEFT);
        $puntoVenta = str_pad($puntoVenta, 4, '0', STR_PAD_LEFT);

        // Concatenar los valores
        $cadena = $nitEmisor . $fechaHora . $sucursal . $modalidad . $tipoEmision . $tipoFactura . $tipoDocumentoSector . $numeroFactura . $puntoVenta;
    
       // 3 Calcular el dígito autoverificador con Módulo 11
       $codigoAutoverificador = self::calculaDigitoMod11($cadena, 1, 9, false);
    
        // Agregar el dígito verificador a la cadena
        $cadena=$cadena.$codigoAutoverificador;
       
       // Eliminar los ceros a la izquierda si es necesario
$cadena = ltrim($cadena, '0');

// Convertir la cadena a un número entero (PHP maneja números grandes como cadenas)
$numero_2 = gmp_init($cadena);

// Convertir el número a una cadena hexadecimal
$hexadecimal = gmp_strval($numero_2, 16);
        return $hexadecimal;
        $cufBase16 = strtoupper(base_convert($cadena, 10, 16));
        return $cufBase16;
        // Concatenar con el código de control
        $cufFinal = $cufBase16 . $codigoControl;
        
        return $cufFinal;
    }

    private static function modulo11($cadena)
    {
        $sum = 0;
        $factor = 2;

        for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
            $sum += $cadena[$i] * $factor;
            $factor = ($factor == 9) ? 2 : $factor + 1;
        }

        $digito = 11 - ($sum % 11);
        if ($digito >= 10) {
            $digito = 1;
        }

        return $digito;
    }
     /**
     * Calcula el dígito verificador usando Módulo 11.
     */
    //String cadena, int numDig, int limMult, boolean x10
    private static function calculaDigitoMod11($cadena, $numDig, $limMult, $x10)
    {
     
        if (!$x10) {
            $numDig = 1;
        }

        for ($n = 1; $n <= $numDig; $n++) {
            $suma = 0;
            $mult = 2;

            for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
                $suma += ($mult * (int) $cadena[$i]);
                if (++$mult > $limMult) {
                    $mult = 2;
                }
            }

            if ($x10) {
                $dig = (($suma * 10) % 11) % 10;
            } else {
                $dig = $suma % 11;
            }

            if ($dig == 10) {
                $cadena .= "1";
            } elseif ($dig == 11) {
                $cadena .= "0";
            } else {
                $cadena .= (string) $dig;
            }
        }
        //
        $resultado =substr($cadena, -$numDig);;
        return $resultado;
   
    }

    /**
     * Completa con ceros a la izquierda hasta alcanzar la longitud deseada.
     */
    private static function completeCero($pString, $pMaxChar, $pRigth = false) {
        return str_pad($pString, $pMaxChar, "0", STR_PAD_LEFT);
    }

    /**
     * Convierte un número decimal en base 16 (hexadecimal).
     */
    private static function base16($pString) {
        return strtoupper(dechex($pString)); // Convertir a hexadecimal en mayúsculas
    }

    /**
     * Convierte un número hexadecimal a base 10 (decimal).
     */
    private static function base10($pString) {
        return base_convert($pString, 16, 10); // Convertir de base 16 a base 10
    }
}
