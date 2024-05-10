<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenMetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ven_metodo_pago')->insert(['tipo'=>'EFECTIVO']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'TARJETA']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'CHEQUE']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'VALES']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'OTROS']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'GIFT-CARD OTROS']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'GIFT-CARD']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'BILLETERA MOVIL']);
        DB::table('ven_metodo_pago')->insert(['tipo'=>'PAGO ONLINE']);
    

    }
}
