<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmDepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adm__departamentos')->insert(['nombre'=>'La Paz','abrev'=>'LP']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Cochabamba','abrev'=>'CB']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Santa Cruz','abrev'=>'SC']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Oruro','abrev'=>'OR']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Potosí','abrev'=>'PT']);        
        DB::table('adm__departamentos')->insert(['nombre'=>'Chuquisaca','abrev'=>'CH']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Tarija','abrev'=>'TJ']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Beni','abrev'=>'BN']);
        DB::table('adm__departamentos')->insert(['nombre'=>'Pando','abrev'=>'PD']);
    }
}
