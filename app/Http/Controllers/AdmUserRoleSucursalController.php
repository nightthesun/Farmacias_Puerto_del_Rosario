<?php

namespace App\Http\Controllers;

use App\Models\Adm_AsigPermisoEA;
use App\Models\Adm_UserRoleSucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmUserRoleSucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->iduser);
        $rawroles=DB::raw('concat(adm__roles.nombre," - ",adm__sucursals.razon_social) as rolsucursal');
        $userrolesuc=Adm_UserRoleSucursal::join('adm__roles','adm__roles.id','adm__user_role_sucursals.idrole')
                                            ->join('adm__sucursals','adm__sucursals.id','adm__user_role_sucursals.idsucursal')
                                            ->select('adm__user_role_sucursals.id as id',
                                                        $rawroles,
                                                        'idsucursal',
                                                        'idrole',
                                                        'adm__user_role_sucursals.activo'
                                                        )
                                            ->where('iduser',$request->iduser)
                                            ->get();
        return $userrolesuc;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //dd($request->iduser);
        $userrolesuc = new Adm_UserRoleSucursal();

        $userrolesuc->iduser=$request->iduser;
        $userrolesuc->idsucursal=$request->idsucursal;
        $userrolesuc->idrole=$request->idrole;
        $userrolesuc->id_usuario_registra=auth()->user()->id;
        $userrolesuc->save();
    }
    public function registrarEditar_Activar(Request $request){
        // Buscar asignaciones existentes 
       $asignarExistente = Adm_AsigPermisoEA::where('id_user_role_sucu', $request->id)->get();

       // Si existen asignaciones, eliminarlas
       if ($asignarExistente->count() > 0) {
        Adm_AsigPermisoEA::where('id_user_role_sucu', $request->id)->delete();
       }
       $datos = [
        'id_user_role_sucu' => $request->id,
        'edit' => $request->editar,
        'activar' => $request->activar      
        ];
    DB::table('adm__asig_permiso_e_a_s')->insert($datos); 
    }

    public function listarPermiso_Activacion(Request $request){
        $results = DB::table('adm__asig_permiso_e_a_s')->get();
        return $results;
    }

    public function listarMas_sucursales(Request $request){

    }
    
    public function registrar_mas_sucursales(Request $request){

    }
    public function listarSucursales_almacen(Request $request){
        if ($request->id != "undefined" || !empty($request->id)) {
         $alm = DB::table('log__asignacion_sucursal_vehiculos as tip')
         ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
         ->join('alm__almacens as aa', 'aa.codigo', '=', 'tip.cod')
         ->where('tip.id_vehiculo', '=', $request->id)
         ->select('tip.id_vehiculo as id','tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','aa.nombre_almacen as nombre', 'tip.cod as codigo');
     
     $tda = DB::table('log__asignacion_sucursal_vehiculos as tip')
         ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
         ->join('tda__tiendas as tt', 'tt.codigo', '=', 'tip.cod')
         ->where('tip.id_vehiculo', '=', $request->id)
         ->select('tip.id_vehiculo as id', 'tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','ass.razon_social as nombre', 'tip.cod as codigo');
     
     $resultado = $alm->unionAll($tda)->get();
     
     return $resultado;
        }       
         
     }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_UserRoleSucursal  $adm_UserRoleSucursal
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_UserRoleSucursal $adm_UserRoleSucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_UserRoleSucursal  $adm_UserRoleSucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_UserRoleSucursal $adm_UserRoleSucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_UserRoleSucursal  $adm_UserRoleSucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_UserRoleSucursal $adm_UserRoleSucursal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_UserRoleSucursal  $adm_UserRoleSucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_UserRoleSucursal $adm_UserRoleSucursal)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $userrolesuc = Adm_UserRoleSucursal::findOrFail($request->id);
        $userrolesuc->activo=0;
        $userrolesuc->id_usuario_modifica=auth()->user()->id;
        $userrolesuc->save();
    }

    public function activar(Request $request)
    {
        $userrolesuc = Adm_UserRoleSucursal::findOrFail($request->id);
        $userrolesuc->activo=1;
        $userrolesuc->id_usuario_modifica=auth()->user()->id;
        $userrolesuc->save();
    }
}
