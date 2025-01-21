<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExcelEmisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('excel__emision')->insert(['descripcion'=>'EN LINEA','codigo'=>1,'id_catalogo'=>1]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FUERA DE LINEA','codigo'=>2,'id_catalogo'=>1]); 
        DB::table('excel__emision')->insert(['descripcion'=>'MASIVO','codigo'=>3,'id_catalogo'=>1]); 
        DB::table('excel__emision')->insert(['descripcion'=>'CONTINGENCIA','codigo'=>4,'id_catalogo'=>1]);   

        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA CON DERECHO A CREDITO FISCAL','codigo'=>1,'id_catalogo'=>2]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA SIN DERECHO A CREDITO FISCAL','codigo'=>2,'id_catalogo'=>2]); 
        DB::table('excel__emision')->insert(['descripcion'=>'DOCUMENTO DE AJUSTE','codigo'=>3,'id_catalogo'=>2]); 
        DB::table('excel__emision')->insert(['descripcion'=>'DOCUMENTO EQUIVALENTE','codigo'=>4,'id_catalogo'=>2]);  

        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMPRA-VENTA','codigo'=>1,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE ALQUILER DE BIENES INMUEBLES','codigo'=>2,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACIÓN','codigo'=>3,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACIÓN EN LIBRE CONSIGNACIÓN','codigo'=>4,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE ZONA FRANCA','codigo'=>5,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE SERVICIO TURÍSTICO Y HOSPEDAJE','codigo'=>6,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE COMERCIALIZACIÓN DE ALIMENTOS – SEGURIDAD','codigo'=>7,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE TASA CERO POR VENTA DE LIBROS Y TRANSPORTE INTERNACIONAL DE CARGA','codigo'=>8,'id_catalogo'=>3]);  
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE COMPRA Y VENTA DE MONEDA EXTRANJERA','codigo'=>9,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DUTTY FREE','codigo'=>10,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA SECTORES EDUCATIVOS','codigo'=>11,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE COMERCIALIZACIÓN DE HIDROCARBUROS','codigo'=>12,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE SERVICIOS BÁSICOS','codigo'=>13,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA PRODUCTOS ALCANZADOS POR EL ICE','codigo'=>14,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE ENTIDADES FINANCIERAS','codigo'=>15,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE TASA CERO POR VENTA DE LIBROS Y TRANSPORTE INTERNACIONAL DE CARGA','codigo'=>8,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE HOTELES','codigo'=>16,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE HOSPITALES/CLÍNICAS','codigo'=>17,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE JUEGOS DE AZAR','codigo'=>18,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA HIDROCARBUROS ALCANZADA IEHD','codigo'=>19,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACIÓN DE MINERALES','codigo'=>20,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA VENTA INTERNA MINERALES','codigo'=>21,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA TELECOMUNICACIONES','codigo'=>22,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA PREVALORADA','codigo'=>23,'id_catalogo'=>3]);
        DB::table('excel__emision')->insert(['descripcion'=>'NOTA DE CRÉDITO-DÉBITO','codigo'=>24,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACIÓN DE SERVICIOS','codigo'=>28,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'NOTA DE CONCILIACION','codigo'=>29,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'BOLETO AEREO','codigo'=>30,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE SUMINISTRO','codigo'=>31,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA ICE ZONA FRANCA','codigo'=>32,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA TASA CERO BIENES CAPITAL','codigo'=>33,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE SEGUROS','codigo'=>34,'id_catalogo'=>3]);  
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMPRA VENTA BONIFICACIONES','codigo'=>35,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA PREVALORADA SDCF','codigo'=>36,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE COMERCIALIZACIÓN DE GNV','codigo'=>37,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA HIDROCARBUROS NO ALCANZADA IEHD','codigo'=>38,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIALIZACION GN y GLP','codigo'=>39,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE SERVICIOS BÁSICOS ZF','codigo'=>40,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMPRA VENTA TASAS','codigo'=>41,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA ALQUILER ZF','codigo'=>42,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACIÓN HIDROCARBUROS','codigo'=>43,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA IMPORTACION COMERCIALIZACION LUBRICANTES','codigo'=>44,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA COMERCIAL DE EXPORTACION PRECIO VENTA','codigo'=>45,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA SECTORES EDUCATIVO ZONA FRANCA','codigo'=>46,'id_catalogo'=>3]);   
        DB::table('excel__emision')->insert(['descripcion'=>'NOTA CREDITO DEBITO DESCUENTO','codigo'=>47,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'NOTA CREDITO DEBITO ICE','codigo'=>48,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA TELECOMUNICACIONES ZONA FRANCA','codigo'=>49,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA DE HOSPITALES/CLÍNICAS ZONA FRANCA','codigo'=>50,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA ENGARRAFADORAS','codigo'=>51,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA VENTA MINERAL BANCO CENTRAL','codigo'=>52,'id_catalogo'=>3]); 
        DB::table('excel__emision')->insert(['descripcion'=>'FACTURA IMPORTACION COMERCIALIZACION LUBRICANTES IEHD','codigo'=>53,'id_catalogo'=>3]);   

    }
}
