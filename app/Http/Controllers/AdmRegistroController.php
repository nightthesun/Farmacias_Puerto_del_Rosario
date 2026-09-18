<?php

namespace App\Http\Controllers;

use App\Models\Adm_Registro;
use App\Models\Adm_UserRoleSucursal;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            $this->validate(request(),[
                'name'=>'required',
                'idempleado'=>'required',
                'email'=>'required|email',
                'password'=>'required',
            ]);
            
            $email = $request->email;
            $existe = DB::table('users')  
            ->where('email', $email)
            ->exists();
        if ($existe==1) {
            return "Correo duplicado";
        }
        $contraseña = $request->password;
         $cadena = str_replace(' ', '', $contraseña);

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
    
        
            /* $user =new User();
            $user->idempleado=$request->idempleado;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->save(); */
            
            $user =User::create(request(['name','idempleado','email','password']));
            
            DB::table('users')->where('id',$user->id)->update(['id_usuario_registra'=>auth()->user()->id]);
    
            $userrolesuc= new Adm_UserRoleSucursal();
    
            $userrolesuc->idrole=$request->idrole;
            $userrolesuc->iduser=$user->id;
            $userrolesuc->idsucursal=$request->idsucursal;
            $userrolesuc->id_usuario_registra=auth()->user()->id;
            $userrolesuc->save();
      DB::commit();
        return 0;
            //auth()->login($user);
            //return redirect()->to('/');
        } catch (\Throwable $th) {
              DB::rollback();
            return $th;
        }        
    }



}
