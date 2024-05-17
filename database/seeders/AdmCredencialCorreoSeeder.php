<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmCredencialCorreoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('adm__credecial_correos')->insert(['host'=>'mail.ejemplo.com','correo'=>'correo@correo.es','puerto'=>'555','usuario'=>'usuario@admin.uk','contraseña'=>bcrypt('secret'),'ssl'=>'0']);
    }
}
