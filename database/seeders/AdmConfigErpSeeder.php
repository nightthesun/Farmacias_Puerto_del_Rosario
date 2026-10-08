<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmConfigErpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('adm__config_erp')->insert(['nit'=>'123456789123','nro_celular'=>'000000000','nom_empresa'=>'Empresa_prubea','factura_dosificacion'=>3,'actividad_economica'=>'Sin actividad economica','moneda'=>0]);
   
    }
}
