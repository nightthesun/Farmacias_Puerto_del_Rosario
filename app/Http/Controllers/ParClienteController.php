<?php

namespace App\Http\Controllers;

use App\Models\Par_Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParClienteController extends Controller
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
    public function selectClientes(Request $request)  //AjaxSelect
    {
        $buscararray = array(); 
        if(!empty($request->buscar)) $buscararray = explode(" ",$request->buscar); 
        $raw=DB::raw(DB::raw('concat(if(ISNULL(papellido)=1," ",papellido)," ",if(ISNULL(sapellido)=1," ",sapellido)," ",nombre) as nom'));
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(papellido like '%".$valor."%' or sapellido like '%".$valor."%' or nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (papellido like '%".$valor."%' or sapellido like '%".$valor."%' or nombre like '%".$valor."%' )";
            }   
            $clientes = Par_Cliente::select($raw,'id','nombre')
                                ->where('activo',1)
                                ->whereraw($sqls)
                                ->orderby('papellido','asc')
                                ->orderby('sapellido','asc')
                                ->orderby('nombre','asc')
                                ->get();
        }
        else {
            if ($request->id){
                    $clientes = Par_Cliente::select($raw,'id','nombre')
                                                 ->where('activo',1)
                                                ->where('id',$request->id)
                                                ->orderby('papellido','asc')
                                                ->orderby('sapellido','asc')
                                                ->orderby('nombre','asc')
                                                ->get();
            }

            else
            {
                $clientes = Par_Cliente::select($raw,'id','nombre')
                                    ->where('activo',1)
                                    ->orderby('papellido','asc')
                                    ->orderby('sapellido','asc')
                                    ->orderby('nombre','asc')
                                    ->get();
            }
              
        }
        return ['clientes' => $clientes];
    }
    public function selectCli(Request $request)  //AjaxSelect
    {
        $raw=DB::raw(DB::raw('concat(if(ISNULL(papellido)=1," ",papellido)," ",if(ISNULL(sapellido)=1," ",sapellido)," ",nombre) as nom,ci,nit'));
        $clientes = Par_Cliente::select($raw,'id','nombre')
                            ->where('activo',1)
                            ->orderby('papellido','asc')
                            ->orderby('sapellido','asc')
                            ->orderby('nombre','asc')
                            ->get();

        return $clientes;
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
        $cliente = new Par_Cliente();

        $cliente->nombre=$request->nombre;
        $cliente->papellido=$request->papellido;
        $cliente->sapellido=$request->sapellido;
        $cliente->nit=$request->nit;
        $cliente->ci=$request->ci;
        $cliente->telefono=$request->telefono;
        //$cliente->tipo_cliente=1;
        $cliente->id_usuario_registra=auth()->user()->id;
        $cliente->save();
        
        return $cliente->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Par_Cliente  $par_Cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Cliente $par_Cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Par_Cliente  $par_Cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Cliente $par_Cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Par_Cliente  $par_Cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_Cliente $par_Cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Par_Cliente  $par_Cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_Cliente $par_Cliente)
    {
        //
    }
}
