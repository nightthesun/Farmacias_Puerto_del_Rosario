<?php

namespace App\Http\Controllers;

use App\Models\Adm_UserRoleSucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;

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
                        $sqls="(rrh__personals.nombre like '%".$valor."%' or rrh__personals.papellido like '%".$valor."%' or rrh__personals.sapellido like '%".$valor."%' or email like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (rrh__personals.nombre  like '%".$valor."%' or rrh__personals.papellido like '%".$valor."%' or rrh__personals.sapellido like '%".$valor."%' or email like '%".$valor."%')" ;
                    }
    
                }
                $users= User::join('rrh__personals','rrh__personals.id','users.idempleado')
             ->where('users.user_unique',0)
                                ->select($raw,
                                        'users.id as id',
                                        'email',
                                        'users.activo',
                                        'name')
                                ->orderby('rrh__personals.papellido','asc')
                                ->orderby('rrh__personals.sapellido','asc')
                                ->orderby('nombre','asc')
                                ->whereraw($sqls)->paginate(50);
            }
        }
        
        else
        {
            $users= User::join('rrh__personals','rrh__personals.id','users.idempleado')
             ->where('users.user_unique',0)
                            ->select($raw,
                                    'users.id as id',
                                    'email',
                                    'users.activo',
                                    'name')
                            ->orderby('rrh__personals.papellido','asc')
                            ->orderby('rrh__personals.sapellido','asc')
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
        try {
                DB::beginTransaction();
                     $contraseña = $request->password;

            
            if($request->cambiarpass==false){
                $user =User::findOrFail($request->id);
                $user->email=$request->email;
                $user->id_usuario_modifica=auth()->user()->id;
                $user->save(); 
                
            }

            if ($request->cambiarpass==true) {
                   $cadena = str_replace(' ', '', $contraseña);
                   $cadena_2= str_replace(' ', '', $contraseña);

$cumple =
    strlen($cadena) >= 5 &&
    preg_match('/[A-Z]/', $cadena) &&
    preg_match('/[a-z]/', $cadena) &&
    preg_match('/[0-9]/', $cadena) &&
    preg_match('/[^A-Za-z0-9]/', $cadena);

$cumple = (int) $cumple;   

if ($cumple==0) {
    return "La contraseña no cumple con los requisitos debe tener al menos un tamaño de 5, una letra mayúscula, una letra minúscula, un número y un carácter especial";
}
                $user =User::findOrFail($request->id);
                $user->email=$request->email;
                $user->password=$cadena_2;
                $user->id_usuario_modifica=auth()->user()->id;
                $user->save(); 
            }                
             
                  DB::commit();
        return 0;
            
        } catch (\Throwable $th) {
                DB::rollback();
            return $th;
        }
       
    }

    public function verificar_correo_x2(Request $request){
        try {
    $email=$request->email;
      $correo = DB::table('adm__credencial_erp')
            ->where('id', 1)
            ->first();

        if (!$correo) {
              return ([
        'estado' => 1,
        'mensaje' => 'No existe información o datos en tabla credencial.',
        'correo' => $email,
        'error' => 1
    ]);
            return "Incorrecto";
        }

        $cadena_pass = $correo->mail_password;
        $textoEncriptado = substr($cadena_pass, 2, -3);
        $password = Crypt::decrypt($textoEncriptado);

        // Cargar configuración desde la BD
        Config::set('mail.default', $correo->mail_mailer);

        Config::set('mail.mailers.smtp.host', $correo->mail_host);
        Config::set('mail.mailers.smtp.port', $correo->mail_port);
        Config::set('mail.mailers.smtp.username', $correo->mail_username);
        Config::set('mail.mailers.smtp.password', $password);        
        Config::set('mail.mailers.smtp.encryption', $correo->mail_encryp);   
        Config::set('mail.from.address', $correo->mail_from_add);         
        Config::set('mail.from.name', $correo->mail_from_na);  
  
        // Enviar correo al mismo correo configurado
        Mail::raw(
            'Creación correctamente desde el sistema.',
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Correo');
            }
        );

  return response()->json([
        'estado' => 1,
        'mensaje' => 'El servidor SMTP aceptó el correo.',
        'correo' => $email,
        'error' => 0
    ]);    

} catch (\Throwable $e) {
    return ([
        'estado' => 0,
        'mensaje' => 'No se pudo enviar el correo.',
        'correo' => $email,
        'error' => $e->getMessage()
    ]);
}       
   
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
