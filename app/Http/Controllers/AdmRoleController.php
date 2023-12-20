<?php

namespace App\Http\Controllers;

use App\Models\Adm_Role;
use Illuminate\Http\Request;

class AdmRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray=array();
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (nombre like '%".$valor."%' or descripcion like '%".$valor."%')" ;
                    }
    
                }
                $roles= Adm_Role::orderby('nombre','asc')->whereraw($sqls)->paginate(20);
            }
        }
        
        else
        {
            $roles= Adm_Role::orderby('nombre','asc')->paginate(20);
        }
        
        //$roles = Adm_Role::all();
        
        
        return ['pagination'=>[
            'total'         =>    $roles->total(),
            'current_page'  =>    $roles->currentPage(),
            'per_page'      =>    $roles->perPage(),
            'last_page'     =>    $roles->lastPage(),
            'from'          =>    $roles->firstItem(),
            'to'            =>    $roles->lastItem(),

            ] ,
                'roles'=>$roles
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
        $role = new Adm_Role();
       
        $role->nombre=$request->nombre;
        $role->descripcion=$request->descripcion;
        $role->idmodulos=$request->idmodulos;
        $role->idventanas=$request->idventanas;
        $role->idacciones=$request->idacciones;
        $role->id_usuario_registra=auth()->user()->id;
        $role->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Role  $adm_Role
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Role $adm_Role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Role  $adm_Role
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Role $adm_Role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Role  $adm_Role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Role $adm_Role)
    {
        // $role = new Adm_Role();
        // $role->nombre=$request->nombre;
        // $role->descripcion=$request->descripcion;
        // $role->idmodulos=$request->idmodulos;
        // $role->idventanas=$request->idventanas;
        // $role->idacciones=$request->idacciones;
        // $role->id_usuario_registra=auth()->user()->id;
        // $role->save();

        $role = Adm_Role::findOrFail($request->id);
        $role->nombre=$request->nombre;
        $role->descripcion=$request->descripcion;
        $role->idmodulos=$request->idmodulos;
        $role->idventanas=$request->idventanas;
        //$role->idacciones=$request->idacciones;
        $role->id_usuario_registra=auth()->user()->id;
        $role->save();
                
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Role  $adm_Role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Role $adm_Role)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $role = Adm_Role::findOrFail($request->id);
        $role->activo=0;
        $role->id_usuario_modifica=auth()->user()->id;
        $role->save();
    }

    public function activar(Request $request)
    {
        $role = Adm_Role::findOrFail($request->id);
        $role->activo=1;
        $role->id_usuario_modifica=auth()->user()->id;
        $role->save();
    }
    public function selectRole(){
        $role=Adm_Role::select('id','nombre')
                                ->where('activo',1)
                                ->orderby('nombre','asc')
                                ->get();
        return $role;
    }
    
}
