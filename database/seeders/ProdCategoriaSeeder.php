<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prod__categorias')->insert(['nombre'=>'analgesicos','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__categorias')->insert(['nombre'=>'antibioticos','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__categorias')->insert(['nombre'=>'antivirales','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__categorias')->insert(['nombre'=>'antitermicos','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
        DB::table('prod__categorias')->insert(['nombre'=>'vitaminas','created_at'=>date("Y/m/d H:i:s"),'updated_at'=>date("Y/m/d H:i:s")]);
    }
}
