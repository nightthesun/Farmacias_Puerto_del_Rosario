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
        DB::table('rrh__cargos')->insert(['nombre'=>'Admin','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'D'.$unidadorg->id.'G001']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Gerente General','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'G'.$unidadorg->id.'I002']);
        
        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Administracion')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Activos Fijos','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'A001']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Almacenes','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'A002']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Archivo','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'A003']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Recursos Humanos','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'R004']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Responsable de Sistemas','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'S005']);        
        
        
        DB::table('rrh__cargos')->insert(['nombre'=>'Asesor Jurídico','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'J006']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auditor','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'A007']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Analista Desarrollador','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'D008']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Secretaria','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'S009']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Contador General','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'C010']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auxiliar Contable','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'A011']);

        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Comercial')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Regente Farmaceutica','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'F001']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Auxiliar de Farmacia','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'F002']);

        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Servicios')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Encargado Ecografia','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'E001']);
        DB::table('rrh__cargos')->insert(['nombre'=>'Encargado Radiografia','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'R002']);

        $unidadorg=Rrh_UnidadOrganizacional::where('nombre','Logistica')->first();
        DB::table('rrh__cargos')->insert(['nombre'=>'Chofer','idunidadorganizacional'=>$unidadorg->id,'codigo'=>'R'.$unidadorg->id.'C001']);
    
    }
}
