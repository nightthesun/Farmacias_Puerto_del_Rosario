<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmBancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adm__bancos')->insert(['nombre'=>'Banco de Credito']);
        DB::table('adm__bancos')->insert(['nombre'=>'Banco Union']);
        DB::table('adm__bancos')->insert(['nombre'=>'Banco Mercantil Santa Cruz']);
    }
}
