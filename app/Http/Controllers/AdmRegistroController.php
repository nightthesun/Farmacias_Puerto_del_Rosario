<?php

namespace App\Http\Controllers;

use App\Models\Adm_Registro;
use App\Models\Adm_UserRoleSucursal;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;


class AdmRegistroController extends Controller
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
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
             DB::beginTransaction();
            $email = $request->email;
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
        }

        $existe = DB::table('users')  
            ->where('email', $email)
            ->exists();
        if ($existe==1) {
              return ([
        'estado' => 1,
        'mensaje' => 'Correo duplicado.',
        'correo' => $email,
        'error' => 1
    ]);   
        }

          $contraseña = $request->password;
         $cadena = str_replace(' ', '', $contraseña);
         $autoPass = $request->autoPass;
         if ($autoPass==0) {
            $cumple =
    strlen($cadena) >= 5 &&
    preg_match('/[A-Z]/', $cadena) &&
    preg_match('/[a-z]/', $cadena) &&
    preg_match('/[0-9]/', $cadena) &&
    preg_match('/[^A-Za-z0-9]/', $cadena);

$cumple = (int) $cumple;   

if ($cumple==0) {
     return ([
        'estado' => 1,
        'mensaje' => 'La contraseña no cumple con los requisitos debe tener al menos un tamaño de 5, una letra mayúscula, una letra minúscula, un número y un carácter especial.',
        'correo' => $email,
        'error' => 1
    ]);   

}
         }else{
            $token = random_int(100000, 999999);
            $password="Pass@".$token;
         }



        $cadena_pass = $correo->mail_password;
        $textoEncriptado = substr($cadena_pass, 2, -3);
        $password_1 = Crypt::decrypt($textoEncriptado);

        // Cargar configuración desde la BD
        Config::set('mail.default', $correo->mail_mailer);

        Config::set('mail.mailers.smtp.host', $correo->mail_host);
        Config::set('mail.mailers.smtp.port', $correo->mail_port);
        Config::set('mail.mailers.smtp.username', $correo->mail_username);
        Config::set('mail.mailers.smtp.password', $password_1);        
        Config::set('mail.mailers.smtp.encryption', $correo->mail_encryp);   
        Config::set('mail.from.address', $correo->mail_from_add);         
        Config::set('mail.from.name', $correo->mail_from_na);
         // Enviar correo al mismo correo configurado
        Mail::raw(
            'Creación correctamente.',
            function ($message) use ($email,$password) {
                $message->to($email)
                    ->subject('Contraseña: '.$password);
            }
        );  

            $this->validate(request(),[
                'name'=>'required',
                'idempleado'=>'required',
                'email'=>'required|email',
                'password'=>'required',
            ]);
            
         
            
      
    
        
            /* $user =new User();
            $user->idempleado=$request->idempleado;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->save(); */
          
           // $user =User::create(request(['name','idempleado','email','password']));
            $data = request(['name','idempleado','email']);
            $data['password'] = $password;

            $user = User::create($data);
            
            DB::table('users')->where('id',$user->id)->update(['id_usuario_registra'=>auth()->user()->id]);
    
            $userrolesuc= new Adm_UserRoleSucursal();
    
            $userrolesuc->idrole=$request->idrole;
            $userrolesuc->iduser=$user->id;
            $userrolesuc->idsucursal=$request->idsucursal;
            $userrolesuc->id_usuario_registra=auth()->user()->id;
            $userrolesuc->save();
      DB::commit();
           return ([
        'estado' => 0,
        'mensaje' => 'Creación de usuario correctamente.',
        'correo' => $email,
        'error' => 0
    ]);   
            //auth()->login($user);
            //return redirect()->to('/');
        } catch (\Throwable $th) {
              DB::rollback();
              return ([
        'estado' => 1,
        'mensaje' => 'Correo duplicado.',
        'correo' => $email,
        'error' => $th->getMessage()
    ]);   
        }        
    }



}
