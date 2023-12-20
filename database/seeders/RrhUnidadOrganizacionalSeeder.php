<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RrhUnidadOrganizacionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Direccion General']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Administracion']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Compras']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Comercial']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Almacen']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Servicios']);
        
    }
}
