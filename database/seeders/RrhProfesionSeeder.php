<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RrhProfesionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__profesions')->insert(['nombre'=>'Administrador de Empresas']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Abogado']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Auditor']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Contador']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Auxiliar Contable']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Regente Farmaceutico']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Auxiliar de Farmacia']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Ingeniero de Sistemas']);
        DB::table('rrh__profesions')->insert(['nombre'=>'Secretaria Ejecutiva']);
    }
}
