<?php

namespace App\Http\Controllers;

use App\Models\Adm_Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmDepartamentoController extends Controller
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
     * @param  \App\Models\Adm_Departamento  $adm_Departamento
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Departamento $adm_Departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Departamento  $adm_Departamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Departamento $adm_Departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Departamento  $adm_Departamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adm_Departamento $adm_Departamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Departamento  $adm_Departamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Departamento $adm_Departamento)
    {
        //
    }
    public function selectDepartamento()
    {
        $departamentos=Adm_Departamento::select('nombre',
                            'abrev',
                            'id')
                    ->orderby('id','asc')
                    ->get();
        return $departamentos;
        

    }
}
