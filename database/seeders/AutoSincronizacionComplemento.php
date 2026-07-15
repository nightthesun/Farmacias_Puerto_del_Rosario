<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutoSincronizacionComplemento extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('siat__sincronizacions')->insert(['descripcion'=>'sincro_sucursales','estado'=>0,'prioridad'=>0]);
        DB::table('siat__sincronizacions')->insert(['descripcion'=>'sincro_cufds','estado'=>0,'prioridad'=>0]);
        DB::table('siat__sincronizacions')->insert(['descripcion'=>'sincro_ventas','estado'=>0,'prioridad'=>0]);
    }
}
