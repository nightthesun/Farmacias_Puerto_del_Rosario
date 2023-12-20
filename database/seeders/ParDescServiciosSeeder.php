<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParDescServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('par__desc_servicios')->insert(
            [
                'id'=>'1',
                'nombre'=>'Ninguno',
                'descripcion'=>'Sin descuento',
                'siporcentaje'=>1,
                'monto'=>0,
                'activo'=>1,
                'id_usuario_registra'=>1,
            ]
        );
    }
}
