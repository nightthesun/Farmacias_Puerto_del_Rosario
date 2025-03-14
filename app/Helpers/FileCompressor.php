<?php

namespace App\Helpers;

class FileCompressor
{
    /**
     * Comprime un archivo usando GZIP
     *
     * @param string $filePath Ruta del archivo a comprimir
     * @return bool TRUE si se comprimió con éxito, FALSE si falló
     */
    public static function comprimir($filePath)
    {
        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return false;
        }

        // Definir el archivo de salida con extensión .gz
        $compressedFile = $filePath . '.gz';

        try {
            // Abrir los archivos en modo binario
            $inFile = fopen($filePath, 'rb');  // Archivo original
            $outFile = gzopen($compressedFile, 'wb9'); // Archivo comprimido con nivel máximo (9)

            if (!$inFile || !$outFile) {
                return false;
            }

            // Buffer de lectura
            $bufferSize = 1024;
            while (!feof($inFile)) {
                $data = fread($inFile, $bufferSize);
                gzwrite($outFile, $data);
            }

            // Cerrar archivos
            fclose($inFile);
            gzclose($outFile);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
