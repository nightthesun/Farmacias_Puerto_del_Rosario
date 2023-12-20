<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adm__roles')->insert(['id'=>1,'nombre'=>'AdmSys','descripcion'=>'Acceso a todos los mudulos como administrador del sistema','idmodulos'=>1,'idventanas'=>1]);
    }
}
