<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParTipoTabla extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Normal','descripcion'=>'solo para tabla normal']);
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Cantidad_compra','descripcion'=>'valores por cantidad o compra']);
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Cliente_producto','descripcion'=>'valore por cliente o producto']);
       
    }
}
