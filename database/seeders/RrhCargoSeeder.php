<?php

namespace Database\Seeders;

use App\Models\Rrh_UnidadOrganizacional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RrhCargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Direccion General')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Admin','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Gerente General','idunidadorganizacional'=>$unidadorg->id]);
        
        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Administracion')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Activos Fijos','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Almacenes','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Archivo','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Recursos Humanos','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Sistemas','idunidadorganizacional'=>$unidadorg->id]);
        
        
        
        DB::table('rrh__cargos')->insert(['nombre'=>'Asesor Jurídico','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auditor','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Analista Desarrollador','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Secretaria','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Contador General','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auxiliar Contable','idunidadorganizacional'=>$unidadorg->id]);

        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Comercial')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Regente Farmaceutica','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auxiliar de Farmacia','idunidadorganizacional'=>$unidadorg->id]);

        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Servicios')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Encargado Ecografia','idunidadorganizacional'=>$unidadorg->id]);
        DB::table('rrh__cargos')->insert(['nombre'=>'Encargado Radiografia','idunidadorganizacional'=>$unidadorg->id]);
    }
}
