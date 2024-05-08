<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GestionPerimsoController extends Controller
{
  
    public function bloqueado(){
        if (Auth()->user()->activo==0) {
            return view('banned.ban')->with('baneo',auth()->user()->name); 
        }
    }
    //********para listar si tiene permiso editar y activar
    public function permisos_editar_activar(Request $request){
    
  
        $iduserrolesuc=session('iduserrolesuc');
        $idsuc=session('idsuc');
    
        $user_1 = Auth()->user()->id;
        $user_2 = Auth()->user()->name;
        if($user_1==1&&$user_2=='admin'){
            $resultadoConsulta = "root";
            return $resultadoConsulta;           
        }else{   
     
        $resultadoConsulta = DB::table('adm__asig_permiso_e_a_s')
        ->select('id_user_role_sucu', 'edit', 'activar', 'id_ventana', 'especial', 'crear')
        ->join('adm__user_role_sucursals', 'adm__user_role_sucursals.iduser', '=', 'adm__asig_permiso_e_a_s.id_user_role_sucu')
        ->where('adm__asig_permiso_e_a_s.id_ventana', '=', $request->win)
        ->where('adm__user_role_sucursals.id', '=', $iduserrolesuc)
        ->where('adm__user_role_sucursals.idsucursal', '=', $idsuc)
        ->where('adm__asig_permiso_e_a_s.id_user_role_sucu', '=', $user_1)
        ->first();                    
        return $resultadoConsulta;
        }  
        
    }
}
