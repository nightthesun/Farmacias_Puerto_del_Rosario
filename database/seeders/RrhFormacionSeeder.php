<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RrhFormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__formacions')->insert(['nombre'=>'Primaria']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Secundaria']);        
        DB::table('rrh__formacions')->insert(['nombre'=>'Técnico Medio']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Técnico Superior']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Egresado Técnico']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Estudiante Universitario']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Egresado Universitario']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Titulado Universitario']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Post Grado']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Maestría']);
        DB::table('rrh__formacions')->insert(['nombre'=>'Doctorado']);
    }
}
