<?php

namespace Database\Seeders;

use App\Models\Adm_AccionVentana;
use App\Models\Adm_Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmRoleAccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol = Adm_Role::where('nombre', 'AdmSys')->first();   
        $accionventana=Adm_AccionVentana::where('nombre','create')->first();
        DB::table('adm__role_accions')->insert(['idrole'=>$rol->id,'idaccionventana'=>$accionventana->id]);
        $accionventana=Adm_AccionVentana::where('nombre','update')->first();
        DB::table('adm__role_accions')->insert(['idrole'=>$rol->id,'idaccionventana'=>$accionventana->id]);
        $accionventana=Adm_AccionVentana::where('nombre','read')->first();
        DB::table('adm__role_accions')->insert(['idrole'=>$rol->id,'idaccionventana'=>$accionventana->id]);
        $accionventana=Adm_AccionVentana::where('nombre','delete')->first();
        DB::table('adm__role_accions')->insert(['idrole'=>$rol->id,'idaccionventana'=>$accionventana->id]);
        $accionventana=Adm_AccionVentana::where('nombre','activar')->first();
        DB::table('adm__role_accions')->insert(['idrole'=>$rol->id,'idaccionventana'=>$accionventana->id]);
        
    }
    
}
