<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmRubroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adm__rubros')->insert(['nombre'=>'Venta de Productos Farmaceuticos']);
        DB::table('adm__rubros')->insert(['nombre'=>'Venta de Servicios Medicos']);
    }
}
