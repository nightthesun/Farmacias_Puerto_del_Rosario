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
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Cantidad o Compra','descripcion'=>'valores por cantidad o compra']);
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Cliente','descripcion'=>'valore por cliente']);
        DB::table('par_tipo_tabla')->insert(['nombre'=>'Producto','descripcion'=>'valor por producto']);
    }
}
