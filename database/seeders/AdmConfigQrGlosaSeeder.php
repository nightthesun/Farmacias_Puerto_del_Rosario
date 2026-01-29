<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmConfigQrGlosaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             
        DB::table('adm__qr_glosa')->insert(['currency'=>'BOB','tipoFecha'=>1,'gloss'=>'Prueba QR','singleUse'=>true,'additionalData'=>'Datos Adicionales para identificar el QR','destinationAccountId'=>1]);
   
    }
}
