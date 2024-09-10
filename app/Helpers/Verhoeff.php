<?php

namespace App\Helpers;

class Verhoeff
{
    protected static $tablaMultiplicacion = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0],
    ];

    protected static $tablaPermutacion = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
        [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
        [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
        [9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
        [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
        [2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
        [7, 0, 4, 6, 9, 1, 3, 2, 5, 8],
    ];

    protected static $tablaInversa = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9];

    public static function generar($numero)
    {
        $c = 0;
        $numero = array_reverse(str_split($numero));

        foreach ($numero as $i => $digito) {
            $c = self::$tablaMultiplicacion[$c][self::$tablaPermutacion[($i + 1) % 8][$digito]];      
        }

        return self::$tablaInversa[$c];
    }

    public static function validar($numero)
    {
        $c = 0;
        $numero = array_reverse(str_split($numero));

        foreach ($numero as $i => $digito) {
            $c = self::$tablaMultiplicacion[$c][self::$tablaPermutacion[$i % 8][$digito]];
        }

        return $c === 0;
    }
}
