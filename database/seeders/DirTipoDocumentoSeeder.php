<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DirTipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dir__tipo_doc')->insert(['nombre_doc'=>'CELULA DE IDENTIDAD','datos'=>'CI']);
        DB::table('dir__tipo_doc')->insert(['nombre_doc'=>'CEDULA DE IDENTIDAD DE EXTRANJERO','datos'=>'CEX']);
        DB::table('dir__tipo_doc')->insert(['nombre_doc'=>'PASAPORTE','datos'=>'PAS']);
        DB::table('dir__tipo_doc')->insert(['nombre_doc'=>'OTRO DOCUMENTO DE IDENTIDAD','datos'=>'OD']);
        DB::table('dir__tipo_doc')->insert(['nombre_doc'=>'NÚMERO DE IDENTIFICACIÓN TRIBUTARIA','datos'=>'NIT']); 
        
    }
}
