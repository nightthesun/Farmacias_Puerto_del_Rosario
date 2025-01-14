<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiatEndpontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siat__endpoints')->insert(['Descripcion'=>'SERVICIO DE SINCRONIZACIÓN DE DATOS','Url'=>'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl','Version'=>'2.0','tipo'=>2]);  
        DB::table('siat__endpoints')->insert(['Descripcion'=>'SERVICIO DE OPERACIONES','Url'=>'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl','Version'=>'2.0','tipo'=>2]);  
        DB::table('siat__endpoints')->insert(['Descripcion'=>'SERVICIO DE OBTENCIÓN DE CÓDIGOS','Url'=>'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl','Version'=>'2.0','tipo'=>2]);  
        DB::table('siat__endpoints')->insert(['Descripcion'=>'FACTURA COMPRA-VENTA','Url'=>'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl','Version'=>'2.0','tipo'=>2]);  
    }
}
