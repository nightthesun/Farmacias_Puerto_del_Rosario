<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajaMonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'0.10','unidad'=>'ctvo','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Diez','unidad_entera'=>10]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'0.20','unidad'=>'ctvo','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Veinte','unidad_entera'=>20]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'0.50','unidad'=>'ctvo','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Cincuenta','unidad_entera'=>50]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'1.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Uno','unidad_entera'=>1]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'2.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Dos','unidad_entera'=>2]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Moneda','valor'=>'5.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Cinco','unidad_entera'=>5]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Billete','valor'=>'10.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Diez','unidad_entera'=>10]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Billete','valor'=>'20.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Veinte','unidad_entera'=>20]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Billete','valor'=>'50.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Cincuenta','unidad_entera'=>50]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Billete','valor'=>'100.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Cien','unidad_entera'=>100]);
        DB::table('caja__monedas')->insert(['tipo_corte'=>'Billete','valor'=>'200.00','unidad'=>'Bs','id_nacionalidad_pais'=>1, 'texto_unidad_entera'=>'Doscientos','unidad_entera'=>200]);
        }
}
