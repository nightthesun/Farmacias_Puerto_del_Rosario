<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmConfigQrGlosaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mensaje de debug para confirmar ejecución
        $this->command->info('Ejecutando AdmConfigQrGlosaSeeder');

        // Inserción de datos
        DB::table('adm__qr_glosa')->insert([
            'currency' => 'BOB',
            'tipoFecha' => 1,
            'gloss' => 'Prueba QR',
            'singleUse' => 1,
            'additionalData' => 'Datos adicionales para identificar el QR',
            'destinationAccountId' => 1,
        ]);
    }
}
