<?php

namespace App\Http\Controllers;
use App\Models\Adm_AsigMasSucursales;
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
        
       // dd($request->iduser);
        $userrolesuc = new Adm_UserRoleSucursal();

        $userrolesuc->iduser=$request->iduser;
        $userrolesuc->idsucursal=$request->idsucursal;
        $userrolesuc->idrole=$request->idrole;
        $userrolesuc->id_usuario_registra=auth()->user()->id;
        $userrolesuc->save();
    }
    public function registrarEditar_Activar(Request $request){
        try {
           // Buscar asignaciones existentes 
       $asignarExistente = Adm_AsigPermisoEA::where('id_user_role_sucu', $request->id)->get();

       // Si existen asignaciones, eliminarlas
       if ($asignarExistente->count() > 0) {
        Adm_AsigPermisoEA::where('id_user_role_sucu', $request->id)->delete();
       }
     
       $datos = $request->input('datos');
      

    // Iterar sobre cada elemento del objeto JSON
    foreach ($datos as $clave => $valor) {
        // Aquí puedes realizar las acciones que necesites con cada elemento
        // Por ejemplo, guardarlos en la base de datos
        DB::table('adm__asig_permiso_e_a_s')->insert([
            'id_user_role_sucu' => $request->id,
            'edit' => $valor['a'],
            'activar' => $valor['b'],
            'id_ventana' => $clave
        ]);
    }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],500);
        }
      
    }

    public function listarPermiso_Activacion(Request $request){
        $results = DB::table('adm__asig_permiso_e_a_s')->get();
        return $results;
    }

    public function listarMas_sucursales(Request $request){
        if ($request->id != "undefined" || !empty($request->id)) {
            $alm = DB::table('adm__asig_mas_sucursales as tip')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
            ->join('alm__almacens as aa', 'aa.codigo', '=', 'tip.cod')
            ->where('tip.id_user_role_sucu', '=', $request->id)
            ->select('tip.id_user_role_sucu as id','tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','aa.nombre_almacen as nombre', 'tip.cod as codigo');

        $tda = DB::table('adm__asig_mas_sucursales as tip')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
            ->join('tda__tiendas as tt', 'tt.codigo', '=', 'tip.cod')
            ->where('tip.id_user_role_sucu', '=', $request->id)
            ->select('tip.id_user_role_sucu as id', 'tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','ass.razon_social as nombre', 'tip.cod as codigo');

        $resultado = $alm->unionAll($tda)->get();

        return $resultado;

           }       


    }

    public function asignar(Request $request){
        // Buscar asignaciones existentes para el vehículo
        $asignarExistente = Adm_AsigMasSucursales::where('id_user_role_sucu', $request->id)->get();

        // Si existen asignaciones, eliminarlas
        if ($asignarExistente->count() > 0) {
            Adm_AsigMasSucursales::where('id_user_role_sucu', $request->id)->delete();
        }

        $bloque = $request->bloque;
            foreach ($bloque as $item) {
             $codigo = $item['codigo'];
             $idSucursal = $item['id_sucursal'];
             $idTiendaAlmacen = $item['id_tienda_almacen'];
             $datos = [
                 'id_user_role_sucu' => $request->id,
                 'id_sucursal' => $idSucursal,
                 'id_alm_tda' => $idTiendaAlmacen,
                 'cod' => $codigo,
             ];

             DB::table('adm__asig_mas_sucursales')->insert($datos);          
          }
     }

    public function listarSucursal(){

        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS',
         DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as id_tienda_almacen', 'tda__tiendas.idsucursal as id_sucursal')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

        $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',
        DB::raw('"Almacen" as tipoCodigo'),'aa.id as id_tienda_almacen','aa.idsucursal as id_sucursal');
        $result = $tiendas->unionAll($almacenes)->get();
        return $result;
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

    public function listarVentanas(Request $request){
        //$ventanas = DB::table('adm__ventana_modulos as avm')
        //    ->join('adm__modulos as am', 'avm.idmodulo', '=', 'am.id')
        //    ->select('am.id as id_modulo', 'am.nombre as nombre_modulo', 'avm.id as id_ventana', 'avm.nombre as ventana_nombre')
        //    ->where('am.activo', 1)
        //    ->where('avm.activo', 1)
        //    ->orderBy('am.id', 'asc')
        //    ->get();
    
        //1 verdad 2 falso
        $usuario_x_permisos = DB::table('adm__asig_permiso_e_a_s')
            ->where('id_user_role_sucu', $request->id)
            ->get();
// Consulta para adm__modulos
$modulos = DB::table('adm__modulos')
    ->select('id', 'nombre')
    ->where('activo', 1)
    ->get();

// Consulta para adm__ventana_modulos
$ventanasModulos = DB::table('adm__ventana_modulos')
    ->select('id', 'idmodulo', 'nombre')
    ->where('activo', 1)
    ->get();
    $arrayModelos = [];

    foreach ($modulos as $modulo) {
        $arrayVentanas = [];
        foreach ($ventanasModulos as $ventana) {
            $editar = 0;
            $eliminar = 0;
    
            foreach ($usuario_x_permisos as $permiso) {
                if ($ventana->id == $permiso->id_ventana) {
                    $editar = $permiso->edit;
                    $eliminar = $permiso->activar;
                    break;
                }              
            }
            if ($modulo->id == $ventana->idmodulo) {

                $arrayVentanas[] = [
                    'id_ventana' => $ventana->id,
                    'nombre_ventana' => $ventana->nombre,
                    'editar' => $editar,
                    'eliminar' => $eliminar
                ];
            }
        }
        
        $arrayModelo = [
            'id_modelo' => $modulo->id,
            'nom_modelo' =>ucfirst(strtolower($modulo->nombre)),
            'ventanas' => $arrayVentanas
        ];
        $arrayModelos[] = $arrayModelo;
    }
return($arrayModelos);
       
    }

    public function listar_asig_permiso_e_a_s(Request $request){
        $usuario_x_permisos = DB::table('adm__asig_permiso_e_a_s')
       // ->where('id_user_role_sucu', $request->id)
        ->get();
    
        return $usuario_x_permisos;
    }
}
