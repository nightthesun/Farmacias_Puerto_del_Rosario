<?php

namespace App\Http\Controllers;

use App\Models\Adm_UserRoleSucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdmUserController extends Controller
{
    public static function getUser() {    
        //dd(Auth::user());
        $user=Auth::user();
        return $user;    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $raw=DB::raw('concat(nombre," ",ifnull(papellido," ")," ",ifnull(sapellido," ")) as nombre');
        $buscararray=array();
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(rrh__nombre like '%".$valor."%' or papellido like '%".$valor."%' or amaaterno like '%".$valor."%' or email like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (rrh__nombre like '%".$valor."%' or papellido like '%".$valor."%' or amaaterno like '%".$valor."%' or email like '%".$valor."%')" ;
                    }
    
                }
                $users= User::join('rrh__empleados','rrh__empleados.id','users.idempleado')
                                ->select($raw,
                                        'users.id as id',
                                        'email',
                                        'users.activo',
                                        'name')
                                ->orderby('rrh__empleados.papellido','asc')
                                ->orderby('rrh__empleados.sapellido','asc')
                                ->orderby('nombre','asc')
                                ->whereraw($sqls)->paginate(50);
            }
        }
        
        else
        {
            $users= User::join('rrh__empleados','rrh__empleados.id','users.idempleado')
                            ->select($raw,
                                    'users.id as id',
                                    'email',
                                    'users.activo',
                                    'name')
                            ->orderby('rrh__empleados.papellido','asc')
                            ->orderby('rrh__empleados.sapellido','asc')
                            ->orderby('nombre','asc')
                            ->paginate(20);
        }
        $rawroles=DB::raw('concat(adm__roles.nombre," - ",adm__sucursals.razon_social) as rolsucursal');
        foreach ($users as  $value) {
            $rolsuc=Adm_UserRoleSucursal::join('adm__sucursals','adm__sucursals.id','adm__user_role_sucursals.idsucursal')
                                        ->join('adm__roles','adm__roles.id','adm__user_role_sucursals.idrole')
                                        ->select('adm__user_role_sucursals.id as id',
                                                $rawroles,
                                                'idsucursal',
                                                'idrole',
                                                'adm__user_role_sucursals.activo')
                                        ->where('iduser',$value->id)
                                        //->where('adm__user_role_sucursals.activo',1)
                                        ->get();
        
            $value->rolsucursal=$rolsuc;            
        }
        
        //$users = User::all();
        
        
        return ['pagination'=>[
            'total'         =>    $users->total(),
            'current_page'  =>    $users->currentPage(),
            'per_page'      =>    $users->perPage(),
            'last_page'     =>    $users->lastPage(),
            'from'          =>    $users->firstItem(),
            'to'            =>    $users->lastItem(),

            ] ,
                'users'=>$users
        ];
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
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function show(User $adm_Rubro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function edit(User $adm_Rubro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $adm_Rubro)
    {
        $user = User::findOrFail($request->id);

        $user->email=$request->email;
        if($request->cambiarpass)
            $user->password=$request->password;
        $user->id_usuario_modifica=auth()->user()->id;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $adm_Rubro
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $adm_Rubro)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->activo=0;
        $user->id_usuario_modifica=auth()->user()->id;
        $user->save();
    }

    public function activar(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->activo=1;
        $user->id_usuario_modifica=auth()->user()->id;
        $user->save();
    }
    public function selectRubro(Request $request)
    {
        $users=User::select('id','nombre','areamedica')
                                ->where('activo',1)
                                ->orderBy('nombre', 'asc')
                                ->get();
        return $users;
        
        /* $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw(DB::raw('concat(codigo," ",nombre) as cod'));
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (nombre like '%".$valor."%' )";
            }   
            $users = User::select($raw,'id','nombre','codigo')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('codigo','asc')
                                ->get();
        }
        else {
            if ($request->id){
                    $users = User::select($raw,'id','nombre','codigo')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('codigo','asc')
                                                ->get();
            }

            else
            {
                $users = User::select($raw,'id','nombre','codigo')
                                    ->where('activo',1)
                                    ->orderby('codigo','asc')
                                    ->get();
            }
              
        }
        return ['users' => $users]; */
        

    }

    public function listaUsuarios(Request $request)
    {
        $usuarios = User::selectRaw('id, name, activo')
                         ->get();
        return $usuarios;
    }
    

}
