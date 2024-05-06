<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionPerimsoController extends Controller
{

    
    //********para listar si tiene permiso editar y activar
    public function permisos_editar_activar(Request $request){
       // Obtener el usuario autenticado
     
    // Determinar la ruta actual
    $currentRoute = $request->route()->getName();
    //dd($currentRoute);
        dd(session()->all());
        $iduserrolesuc=session('iduserrolesuc');
        $user_1 = Auth()->user()->id;
        $user_2 = Auth()->user()->name;
        if($user_1==1&&$user_2=='admin'){
            $resultadoConsulta = [
                ['id' => 0, 'edit' => 0, 'activar' => 0,'especial' =>0,'crear'=>0]
            ];
            return $resultadoConsulta;           
        }else{
        // $idsucursal=session('idsuc');
       // $nomsucursal=session('nomsucursal');
     
       $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
       ->leftJoin('adm__asig_permiso_e_a_s as aap', 'aap.id_user_role_sucu', '=', 'aur.iduser')
       ->where('aur.activo', '=', 1)
       ->where('aur.id', '=', $iduserrolesuc)
       ->select('aur.id as id', 'aap.edit as edit', 'aap.activar as activar')
       ->get();
       return $resultadoConsulta;
        }      
    }
}
