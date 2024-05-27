<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdTipoDescuentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prod__tipo_descuentos')->insert(['aplica_a'=>'Producto','cod'=>1,'subcategorias'=>'Metodo ABC|Producto Individual|Lote|Cantidad de Compras','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__tipo_descuentos')->insert(['aplica_a'=>'Cliente','cod'=>2,'subcategorias'=>'Monto mayor A|Cliente en especifico|Evento','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__tipo_descuentos')->insert(['aplica_a'=>'Fechas','cod'=>3,'subcategorias'=>'Semana|Rango de Fechas|Dia en especifico','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__tipo_descuentos')->insert(['aplica_a'=>'Forma de pago','cod'=>4,'subcategorias'=>'Efectivo|Tarjeta|Transferencia|QR','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__tipo_descuentos')->insert(['aplica_a'=>'Asociacion con entidades externas','cod'=>5,'subcategorias'=>'Bancos|Tiendas|Universidades|Colegios|Otros','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
    }
}
