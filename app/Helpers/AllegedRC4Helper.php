<?php

namespace App\Helpers;

class AllegedRC4Helper
{
    /**
     * Retorna mensaje encriptado usando el algoritmo RC4.
     * 
     * @param string $message El mensaje a encriptar.
     * @param string $key La clave para encriptar.
     * @param bool $unscripted Si se retorna sin guiones (TRUE|FALSE).
     * @return string El mensaje encriptado.
     */
    public static function encryptMessageRC4($message, $key)
    {
        $state = range(0, 255);
        $x = 0;
        $y = 0;
        $index1 = 0;
        $index2 = 0;        
        $messageEncryption = "";

        // Inicializar el estado
        for ($i = 0; $i <= 255; $i++) {
            $index2 = (ord($key[$index1]) + $state[$i] + $index2) % 256;
            $aux = $state[$i];
            $state[$i] = $state[$index2];
            $state[$index2] = $aux;
            $index1 = ($index1 + 1) % strlen($key);
        }

        // Encriptar el mensaje
        for ($i = 0; $i < strlen($message); $i++) {
            $x = ($x + 1) % 256;
            $y = ($state[$x] + $y) % 256;
            $aux = $state[$x];
            $state[$x] = $state[$y];
            $state[$y] = $aux; 
            $nmen = ord($message[$i]) ^ $state[($state[$x] + $state[$y]) % 256];
            $nmenHex = strtoupper(dechex($nmen));
            $messageEncryption .= (strlen($nmenHex) == 1 ? '0'.$nmenHex : $nmenHex);
        }

        return $messageEncryption;
    }
}
