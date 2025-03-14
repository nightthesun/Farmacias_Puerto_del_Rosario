<?php

namespace App\Helpers;

class HashHelper
{
    public static function algoritmoHash($fileContent, $algorithm = 'sha256')
    {
        try {
            return hash($algorithm, $fileContent);
        } catch (\Exception $e) {
            //\Log::error("Error generando Hash: " . $e->getMessage());
            return $e."Error generando Hash:";
        }
    }
}
