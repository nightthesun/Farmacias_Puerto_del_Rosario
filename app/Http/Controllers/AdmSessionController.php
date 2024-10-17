<?php

namespace App\Http\Controllers;

use App\Mail\PruebaMail;
use App\Models\Adm_Modulo;
use App\Models\Adm_Role;
use App\Models\Adm_Session;
use App\Models\Adm_UserRoleSucursal;
use App\Models\Adm_VentanaModulo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\returnSelf;

class AdmSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.login');
    }

    public function recpass()
    {
        return view('auth.recpass');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
         $res=User::join('rrh__empleados','rrh__empleados.id','users.idempleado')
                    ->select('rrh__empleados.activo')                
                    ->where('email',request()->email)
                    ->get()->toarray();
        //dd($res);
        if(count($res)==0){
            return back()->withErrors([
                'message'=>'No existe Usuario'
            ]);   
        }
        else
        {
            if($res[0]['activo']==false)
            {
                return back()->withErrors([
                    'message'=>'Usuario Bloqueado'
                ]);
            }
            else
            {
                if(auth()->attempt(request(['email','password']))==false ) 
                {
                    return back()->withErrors([
                        'message'=>'Email o contraseña incorrectos, intentelo de nuevo'
                    ]);

                }
                return redirect()->to('/selectsuc');
            }
        }
            
    }

    public function verEmail(Request $request)
    {
        //dd($request);
        $usuario= User::where('email',$request->email)->get();
        $token=mt_rand(10000,99999);
        
        
        
        if(count($usuario)>0)
        {
            $email=$usuario[0]->email;
            DB::table('password_resets')->where('email',$request->email)->delete();
            DB::table('password_resets')->insert(['email'=>$email,'token'=>$token]);
            $respuesta=$this->sendEmail($email,$token);
            if($respuesta=='correcto')
                /* return view('auth.codigo')->with('email',$email); */
                //return redirect('/resetpass');
                return redirect('/resetpass');
            //return "correcto";
        }
        else
            return view('auth.recpass')->with('error','error');
            
    }

    public function resetpass(Request $request)
    {
        //dd($request);
        return view('auth.codigo');
    }

    public function actpass(Request $request)
    {
        //dd($request);
        $respuesta=DB::table('password_resets')->where('token',$request->codigo)->get();
        $password=$request->newpass;

        if(count($respuesta)>0)
        {
            $usuario=User::where('email',$respuesta[0]->email)->get();
            $user = User::findOrFail($usuario[0]->id);
            $user->password=$password;
            $user->save();
            DB::table('password_resets')->where('email',$respuesta[0]->email)->delete();
            return redirect()->to('/');
            
        }
        else
        {
            $incorrecto='Incorrecto';
            return view('auth.codigo')->with('incorrecto',$incorrecto);            
        }
        
    }

    public function sendEmail($email,$token)
    {
        $detalles=[
            'title'=>'Correo de prueba',
            'body'=>'Este es el codigo de Recuperacion de Contraseña '. $token .'Copielo y peguelo en la aplicacion'
        ];
        Mail::to($email)->send(new PruebaMail($detalles));
        return "correcto";
    }

    public function sucursal(Request $request)
    {
        try {
            $user = User::select('id', 'name', 'activo')
            ->where('id', auth()->user()->id)
            ->first();
           
            if ($user->activo==1) {
                //$rawroles=DB::raw('concat(adm__roles.nombre," - ",adm__sucursals.razon_social) as rolsucursal');
        $sucursales=Adm_UserRoleSucursal::join('adm__roles','adm__roles.id','adm__user_role_sucursals.idrole')
        ->join('adm__sucursals','adm__sucursals.id','adm__user_role_sucursals.idsucursal')
        ->select('adm__user_role_sucursals.id as id',
        'adm__roles.nombre as nomrole',
        'adm__sucursals.razon_social as nomsuc',
        'idsucursal',
        'idrole'
    )
    ->where('iduser',auth()->user()->id)
    ->where('adm__user_role_sucursals.activo',1)
    ->get();
$lleno=count($sucursales);
//  dd($sucursales);
if($lleno==0)                                
return redirect()->to('/');
else
return view('auth.sucursal')->with('sucursales',$sucursales);
            }else{
                return view('banned.ban')->with('baneo',auth()->user()->name); 
            }
        
        } catch (\Throwable $th) {
            dd("error de autorizacion");
        }
        
      
    }
    
    public function entrar(Request $request)
    {
      
       if (auth()->user()->activo==1) {
        $sucurs=Adm_UserRoleSucursal::join('adm__roles','adm__roles.id','adm__user_role_sucursals.idrole')
                                        ->join('adm__sucursals','adm__sucursals.id','adm__user_role_sucursals.idsucursal')
                                        ->select('adm__user_role_sucursals.id as id',
                                        'adm__roles.nombre as nomrole',
                                        'adm__sucursals.razon_social as nomsuc',
                                        'idsucursal',
                                        'idrole','idventanas'
                                        )
                                        ->where('adm__user_role_sucursals.id',$request->sucur)
                                        ->get();

        
  

        session(['idsuc'=>$sucurs[0]->idsucursal,
                'nomsucursal'=>$sucurs[0]->nomsuc,
                'nomrol'=>$sucurs[0]->nomrole,
                'idrole'=>$sucurs[0]->idrole,
                'iduserrolesuc'=>$request->sucur,
                'id_user2'=>auth()->id(),
                'idventanas'=>$sucurs[0]->idventanas,
            ]);
        
        return redirect()->to('/');
       }else {
        return view('banned.ban')->with('baneo',auth()->user()->name); 
       }
        
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Session  $adm_Session
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Session $adm_Session)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Session  $adm_Session
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Session $adm_Session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Session  $adm_Session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Session $adm_Session)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Session  $adm_Session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Session $adm_Session)
    {
        session()->forget('idsucursal');
        session()->forget('nomsucursal');
        session()->forget('nomrol');
        auth()->logout();
        return redirect()->to('/');
    }

    public static function listarPermisos()
    {
        if(auth()->user()->name=='admin'){
            $modulos=Adm_Modulo::all();
            //dd($modulos);
            foreach ($modulos as $value) {
                $ventanas=Adm_VentanaModulo::where('idmodulo',$value->id)    
                                            ->where('activo',1)
                                            ->get();

                $value->ventanas=$ventanas;
            }
            return ['modulos'=>$modulos];
        }
        else
        {
          
            if (auth()->user()->activo==1) {
                $idrole=session('idrole');
          
                 if ($idrole==null) {
                   
                   
                    session()->forget('idsucursal');
                    session()->forget('nomsucursal');
                    session()->forget('nomrol');
                    auth()->logout();               
                    return ['modulos'=>'baneado'];
                 }else{
                    $roles=Adm_Role::where('id',$idrole)->get();                
                    $idmodulos=explode(",",$roles[0]->idmodulos);             
                    $idventanas=explode(",",$roles[0]->idventanas);     
                    $modulos=Adm_Modulo::wherein('id',$idmodulos)->get();
         
                     foreach ($modulos as $value) {
                         $ventanas=Adm_VentanaModulo::wherein('id',$idventanas)->get();    
                         $value->ventanas=$ventanas;
                     }            
                     return ['modulos'=>$modulos];
                 }
                
                 
                 
            } else {
                return ['modulos'=>'baneado'];
               
            }
            
            
        }
       
        
    }

    public static function listarVentanas()
    {
        $ventanas =Adm_VentanaModulo::where('activo',1)->get();
    
        return $ventanas;
    }

}
