<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenTipoMoneda extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ven_tipo_moneda')->insert(['tipo'=>'BOLIVIANO']);
        DB::table('ven_tipo_moneda')->insert(['tipo'=>'DOLAR']);
        DB::table('ven_tipo_moneda')->insert(['tipo'=>'EURO']);
       
    }
}
