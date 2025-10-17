<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSincroGesstockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('log__sincro_ges_stock')->insert(['hora'=>'00:00:00','frecuencia'=>1,'activo'=>0,'error'=>0,'informe'=>'Sin errores']);
    }
}
