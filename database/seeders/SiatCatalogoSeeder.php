<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiatCatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo emision']);  
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo documento fisical']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo sector']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Motivo anulacion']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Mensaje servicio']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Codigo evento']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo documento']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo pago']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo moneda']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo origen']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo leyenda']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo sector']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo unidad medida']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Tipo punto venta']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Codigo tipo habilitacion']); 
        DB::table('siat__catalogo')->insert(['catalogo'=>'Codigo tipo actividades']);
        DB::table('siat__catalogo')->insert(['catalogo'=>'Codigo tipo actividesdes documento sector']); 
 
    }
}
