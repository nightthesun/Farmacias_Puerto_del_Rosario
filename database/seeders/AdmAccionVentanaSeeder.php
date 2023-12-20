<?php

namespace Database\Seeders;

use App\Models\Adm_VentanaModulo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmAccionVentanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ventana=Adm_VentanaModulo::where('codventana','101')->first();
        DB::table('adm__accion_ventanas')->insert(['idventana'=>$ventana->id,'nombre'=>'create','metodo_vue'=>'registrarModulo']);
        DB::table('adm__accion_ventanas')->insert(['idventana'=>$ventana->id,'nombre'=>'update','metodo_vue'=>'actualizarModulo']);
        DB::table('adm__accion_ventanas')->insert(['idventana'=>$ventana->id,'nombre'=>'read','metodo_vue'=>'listarModulo']);
        DB::table('adm__accion_ventanas')->insert(['idventana'=>$ventana->id,'nombre'=>'delete','metodo_vue'=>'desactivarModulo']);
        DB::table('adm__accion_ventanas')->insert(['idventana'=>$ventana->id,'nombre'=>'activar','metodo_vue'=>'activarModulo']);
    }
}
