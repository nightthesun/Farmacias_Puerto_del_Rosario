<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProdTipoEntradasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Bonificación','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Compensación','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Compra','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Devolución','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Donación','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Error de registro','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Permuta','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Préstamo ','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Recuperación','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Reintegro','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Reposición','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Sobrante','id_usuario_registra'=>1,]);  
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Traspaso','id_usuario_registra'=>1,]); 
        DB::table('prod__tipo_entradas')->insert(['nombre'=>'Caducado','id_usuario_registra'=>1,]); 
         
    }
}
