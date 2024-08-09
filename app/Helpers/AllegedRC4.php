<?php

namespace App\Helpers;

class AllegedRC4
{
    public static function encrypt($key, $data)
    {
        $s = range(0, 255);
        $j = 0;
        $keyLength = strlen($key);

        // Initialize the state
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            list($s[$i], $s[$j]) = array($s[$j], $s[$i]);
        }

        // Encrypt the data
        $i = $j = 0;
        $result = '';

        for ($k = 0; $k < strlen($data); $k++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            list($s[$i], $s[$j]) = array($s[$j], $s[$i]);
            $t = ($s[$i] + $s[$j]) % 256;
            $result .= chr(ord($data[$k]) ^ $s[$t]);
        }

        return base64_encode($result);
    }

    public static function decrypt($key, $data)
    {
        return self::encrypt($key, base64_decode($data));
    }
}
