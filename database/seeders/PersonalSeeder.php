<?php

namespace Database\Seeders;

use App\Models\Rrh_Cargo;
use App\Models\Rrh_Formacion;
use App\Models\Rrh_Profesion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalSeeder extends Seeder
{
   public function run()
    {
        $cargo = Rrh_Cargo::where('nombre', 'Admin')->first();        
        $formacion = Rrh_Formacion::where('nombre', 'Titulado Universitario')->first();        
        $profecion = Rrh_Profesion::where('nombre', 'Ingeniero de Sistemas')->first();        

        DB::table('rrh__personals')->insert(['codempleado'=>'ADMIN','nombre'=>'ADMIN','sexo'=>'M','idcargo'=>$cargo->id,'idformacion'=>$formacion->id,'idprofesion'=>$profecion->id]);
        DB::table('rrh__personals')->insert(['codempleado'=>'ADMIN2','nombre'=>'RENZO','sexo'=>'M','idcargo'=>$cargo->id,'idformacion'=>$formacion->id,'idprofesion'=>$profecion->id]);
    }
}
