<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmNacionalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adm__nacionalidads')->insert(['nombre'=>'boliviano']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'peruano']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'argentino']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'chileno']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'brasileño']);
    }
}
