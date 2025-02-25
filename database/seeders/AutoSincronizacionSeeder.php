<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutoSincronizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('auto__sincronizacion')->insert(['hora'=>'00:00:00','frecuencia'=>1,'intentos'=>5,'intervalo_min'=>30]);    
    }
}
