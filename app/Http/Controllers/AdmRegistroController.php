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
        
        $user =User::create(request(['name','idempleado','email','password']));
        
        DB::table('users')->where('id',$user->id)->update(['id_usuario_registra'=>auth()->user()->id]);

        $userrolesuc= new Adm_UserRoleSucursal();

        $userrolesuc->idrole=$request->idrole;
        $userrolesuc->iduser=$user->id;
        $userrolesuc->idsucursal=$request->idsucursal;
        $userrolesuc->id_usuario_registra=auth()->user()->id;
        $userrolesuc->save();


        //auth()->login($user);
        //return redirect()->to('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Registro  $adm_Registro
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Registro $adm_Registro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Registro  $adm_Registro
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Registro $adm_Registro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Registro  $adm_Registro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Registro $adm_Registro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Registro  $adm_Registro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Registro $adm_Registro)
    {
        //
    }
}
