<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiatConfiguracion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siat__configuracions')->insert(['cod_sis'=>'NOMBRE PRUEBA DE SISTEMA','tipo_ambiente'=>0,'formato_fecha'=>'debe ingresar el formato de fecha que desea','max_paquete'=>0,'token_delegado'=>'El toque delegado esta en su cuenta de siat','url_QR'=>'el link piloto esta en la cuenta siat','tiempo_espera'=>0,'tipo_modalidad'=>0,'tipo_certificado'=>0]);  
    }
}
