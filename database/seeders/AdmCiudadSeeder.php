<?php

namespace Database\Seeders;

use App\Models\Adm_Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmCiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $depto = Adm_Departamento::where('nombre', 'La Paz')->first();        
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'La Paz']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'El Alto']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Viacha']);
        $depto = Adm_Departamento::where('nombre', 'Cochabamba')->first();        
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Quillacollo']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Cochabamba']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Vinto']);
        $depto = Adm_Departamento::where('nombre', 'Santa Cruz')->first();        
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Santa Cruz']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Montero']);
        DB::table('adm__ciudads')->insert(['iddepartamento'=>$depto->id,'nombre'=>'Warnes']);

    }
}
