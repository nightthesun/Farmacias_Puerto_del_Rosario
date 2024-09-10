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
        DB::table('adm__nacionalidads')->insert(['nombre'=>'boliviano','pais'=>'Bolivia','simbolo'=>'Bs']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'peruano','pais'=>'Peru','simbolo'=>'S/']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'argentino','pais'=>'Argentina','simbolo'=>'$']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'chileno','pais'=>'Chile','simbolo'=>'$']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'brasileño','pais'=>'Brasil','simbolo'=>'R$']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'estadounidense','pais'=>'Estados unidos','simbolo'=>'$']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'brasileño','pais'=>'Europa','simbolo'=>'€']);
        DB::table('adm__nacionalidads')->insert(['nombre'=>'brasileño','pais'=>'China','simbolo'=>'¥']);
    }
}
