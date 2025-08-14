<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

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

    ////////////////////listar alamcenes y tiendas con permisos//////////////////////////
    public function listar_tienda_alamce_generico_lista_x_rol_usuario(Request $request) {
        $iduserrolesuc = session('iduserrolesuc');
        $idsuc = session('idsuc');
        $id_user2 = session('id_user2'); 
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
        
        if ($user_1 == 1 && $user_2 == 'admin') {
            // Uniendo ambas consultas para el administrador
            $tiendas = DB::table('tda__tiendas')
                ->join('adm__sucursals as ass', 'tda__tiendas.idsucursal', '=', 'ass.id')
                ->select(
                    'tda__tiendas.id as id_tienda',
                    DB::raw('NULL as id_almacen'),
                    'tda__tiendas.codigo',
                    'ass.razon_social',
                    'ass.razon_social as sucursal',
                    'ass.cod as codigoS',
                    DB::raw('"Tienda" as tipoCodigo'),
                    'tda__tiendas.id AS id_tienda_almacen',
                    'ass.id AS id_sucursal'
                )
                ->where('ass.activo', 1);
    
            $almacenes = DB::table('alm__almacens as aa')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->select(
                    DB::raw('NULL as id_tienda'),
                    'aa.id as id_almacen',
                    'aa.codigo',
                    'aa.nombre_almacen as razon_social',
                    'ass.razon_social as sucursal',
                    'ass.cod as codigoS',
                    DB::raw('"Almacen" as tipoCodigo'),
                    'aa.id AS id_tienda_almacen',
                    'ass.id AS id_sucursal'
                )
                ->where('ass.activo', 1);
    
            $resultadoConsulta = $tiendas->union($almacenes)->get();
            return $resultadoConsulta;
        } else {
            // Consultas para otros usuarios
            $asignaciones = DB::table('adm__asig_mas_sucursales')
                ->where('id_user_role_sucu', $id_user2)
                ->get();               
            
            $codigos = [];
            $where1 = '';
            $where2 = '';
            
            if ($asignaciones->isNotEmpty()) {
                foreach ($asignaciones as $value) {
                    $codigos[] = "'" . $value->id_sucursal . "'";
                }
                $where1 = 'aa.codigo IN (' . implode(',', $codigos) . ')';
                $where2 = 'tda__tiendas.codigo IN (' . implode(',', $codigos) . ')';
            } else {
                $where1 = 'ass.id = ' . $idsuc;
                $where2 = 'ass.id = ' . $idsuc;
            }
            
            $tiendas = DB::table('tda__tiendas')
                ->join('adm__sucursals as ass', 'tda__tiendas.idsucursal', '=', 'ass.id')
                ->select(
                    'tda__tiendas.id as id_tienda',
                    DB::raw('NULL as id_almacen'),
                    'tda__tiendas.codigo',
                    'ass.razon_social',
                    'ass.razon_social as sucursal',
                    'ass.cod as codigoS',
                    DB::raw('"Tienda" as tipoCodigo'),
                    'tda__tiendas.id AS id_tienda_almacen',
                    'ass.id AS id_sucursal'
                )
                ->where('ass.activo', 1)
                ->whereRaw($where2);
            
            $almacenes = DB::table('alm__almacens as aa')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->select(
                    DB::raw('NULL as id_tienda'),
                    'aa.id as id_almacen',
                    'aa.codigo',
                    'aa.nombre_almacen as razon_social',
                    'ass.razon_social as sucursal',
                    'ass.cod as codigoS',
                    DB::raw('"Almacen" as tipoCodigo'),
                    'aa.id AS id_tienda_almacen',
                    'ass.id AS id_sucursal'
                )
                ->where('ass.activo', 1)
                ->whereRaw($where1);
            
            $resultadoConsulta = $tiendas->union($almacenes)->get();
            return $resultadoConsulta;
        }
    }
    ///////////////////////////sucursal x usuario//////////////////////////////////////////
    public function listar_sucursal_x_usuario(){
     
        $idsuc = session('idsuc');
        $id_user2 = session('id_user2'); 
        $user_1 = auth()->user()->id;
        $user_2 = auth()->user()->name;
         if ($user_1 == 1 && $user_2 == 'admin') {
           $sucursal = DB::table('adm__sucursals as ass')
            ->join('adm__departamentos as ad', 'ass.departamento', '=', 'ad.id')
            ->select('ass.id','ass.razon_social','ass.telefonos','ass.nit','ass.direccion','ass.nombre_comercial','ad.nombre as departamento')
            ->where('ass.activo', 1)->get();
            return $sucursal;
         }else{
            $asignaciones = DB::table('adm__asig_mas_sucursales')
            ->where('id_user_role_sucu', $id_user2)
            ->distinct()
            ->get();

            $codigos = [];
            $where1 = '';
          
            
            if ($asignaciones->isNotEmpty()) {
                foreach ($asignaciones as $value) {
                    $codigos[] = "'" . $value->id_sucursal . "'";
                }
                $where1 = 'ass.id IN (' . implode(',', $codigos) . ')';
               
            } else {
                $where1 = 'ass.id = ' . $idsuc;                            
            }
             $sucursal = DB::table('adm__sucursals as ass')
            ->join('adm__departamentos as ad', 'ass.departamento', '=', 'ad.id')
            ->select('ass.id','ass.razon_social','ass.telefonos','ass.nit','ass.direccion','ass.nombre_comercial','ad.nombre as departamento')
            ->where('ass.activo', 1)
             ->whereRaw($where1)
             ->get();  
               return $sucursal;
         }    


    }

}
