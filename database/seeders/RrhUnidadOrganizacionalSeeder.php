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
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Direccion General','alias'=>'DIR','codigo'=>'DIR-001']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Administracion','alias'=>'ADM','codigo'=>'ADM-002']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Compras','alias'=>'COM','codigo'=>'COM-003']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Comercial','alias'=>'COM','codigo'=>'COM-004']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Almacen','alias'=>'ALM','codigo'=>'ALM-005']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Servicios','alias'=>'SER','codigo'=>'SER-006']);
        DB::table('rrh__unidad_organizacionals')->insert(['nombre'=>'Logistica','alias'=>'LOG','codigo'=>'LOG-007']);
        
    }
}
