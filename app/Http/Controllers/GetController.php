<?php

namespace App\Http\Controllers;

use App\Models\Adm_AsigMasSucursales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetController extends Controller
{
    //********para listar si tiene permiso editar y activar
    public function permisos_editar_activar(Request $request){
        $iduserrolesuc=session('iduserrolesuc');
        $user_1 = Auth()->user()->id;
        $user_2 = Auth()->user()->name;
        if($user_1==1&&$user_2=='admin'){
            $resultadoConsulta = [
                ['id' => 1, 'edit' => 1, 'activar' => 1]
            ];
            return $resultadoConsulta;           
        }else{
        // $idsucursal=session('idsuc');
       // $nomsucursal=session('nomsucursal');
       //dd(session()->all());
       $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
       ->leftJoin('adm__asig_permiso_e_a_s as aap', 'aap.id_user_role_sucu', '=', 'aur.iduser')
       ->where('aur.activo', '=', 1)
       ->where('aur.id', '=', $iduserrolesuc)
       ->select('aur.id as id', 'aap.edit as edit', 'aap.activar as activar')
       ->get();
       return $resultadoConsulta;
        }      
    }

    //******************************permisos para ver lista por defecto */
    public function listarSucursal()
    {
        $idsuc=session('idsuc');
        $user_1 = Auth()->user()->id;
        $asignaciones = Adm_AsigMasSucursales::where('id_user_role_sucu', $user_1)->get();
        if($asignaciones->count() > 0){
           
        }else{
            $where = "(adm__sucursals.id = $idsuc)";
        }
         
        $tiendas = DB::table('tda__tiendas')
            ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'),
            'tda__tiendas.id AS id_tienda_almacen','adm__sucursals.id AS id_sucursal')
            ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
            ->whereRaw($where);
        $almacenes = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'),
             'aa.id AS id_tienda_almacen', 'ass.id AS id_sucursal')
             ->whereRaw($where);
        $result = $tiendas->unionAll($almacenes)->get();
        return $result;

    }

    
}
