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

    //********permisos para sucursal por defecto ALMACEN****************** */
    public function listar_alamcen_tienda_permisos(Request $request){
        //**verifica si tiene permisos */  1=igual si es default dos no lo es

         $iduserrolesuc=session('iduserrolesuc');
        $idsuc=session('idsuc');
        $id_user2=session('id_user2'); 
        $user_1 = Auth()->user()->id;
        $user_2 = Auth()->user()->name;
        if($user_1==1&&$user_2=='admin'){
            $resultadoConsulta = "root";
            return $resultadoConsulta;           
        }else{   
            $resultadoConsulta = DB::table('adm__asig_mas_sucursales as aam')
            ->select('aam.id_alm_tda as id', 'aam.cod as codigo', 'aam.id_sucursal as idsucursal')
            ->join('adm__user_role_sucursals as aur', 'aur.iduser', '=', 'aam.id_user_role_sucu')
          
            ->where('aur.id', '=', $iduserrolesuc)   
          
            ->addSelect(DB::raw('2 as defaul'))
            ->get();
         
            if (count($resultadoConsulta) > 0) {
                // Se encontraron resultados
               
                return $resultadoConsulta;
            } else {
                // No se encontraron resultados
                if ($request->a==1) {
                    $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
                    ->join('adm__sucursals as ass', 'ass.id', '=', 'aur.idsucursal')
                    ->leftJoin('tda__tiendas as tt', 'tt.idsucursal', '=', 'aur.idsucursal')
                    ->leftJoin('alm__almacens as aa', 'aa.idsucursal', '=', 'aur.idsucursal')
                    ->select('ass.id as id_sucursal', 'ass.cod as cod_sucursal', 'aa.id as id_alm', 'aa.codigo as cod_alm', 'tt.id as id_tda', 'tt.codigo as cod_tda')
                    ->where('aur.id', '=', $iduserrolesuc)
                    ->addSelect(DB::raw('1 as defaul'))
                    ->get();
             
                    return $resultadoConsulta; 

                } else {
                    if ($request->a==2) {
                        $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
                        ->join('adm__sucursals as ass', 'ass.id', '=', 'aur.idsucursal')
                        ->leftJoin('tda__tiendas as tt', 'tt.idsucursal', '=', 'aur.idsucursal')
                        
                        ->select('ass.id as id_sucursal', 'ass.cod as cod_sucursal','tt.id as id_tda','tt.codigo as cod_tda')
                        ->where('aur.id', '=', $iduserrolesuc)
                        ->addSelect(DB::raw('1 as defaul'))
                        ->get();
                        return $resultadoConsulta;
                    
                    } else {
                        $resultadoConsulta ="error";
                        return $resultadoConsulta;
                    }
                    
                }
                
            
                  
               

              //  $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
              //  ->join('alm__almacens as aa', 'aur.idsucursal', '=', 'aa.idsucursal')
              //  ->select('aa.id', 'aa.codigo', 'aa.idsucursal')
              //  ->where('aur.id', '=', $iduserrolesuc )
              //  ->where('aur.iduser', '=', $id_user2)
              //  ->where('aur.idsucursal', '=', $idsuc)
              //  ->addSelect(DB::raw('1 as defaul'))                
              //  ->get();

           
            }
        }   
        
    }
}
