<?php

namespace App\Http\Controllers;

use App\Models\adm_CredecialCorreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmCredecialCorreoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(adm_CredecialCorreo $adm_CredecialCorreo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(adm_CredecialCorreo $adm_CredecialCorreo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, adm_CredecialCorreo $adm_CredecialCorreo)
    {
        try {
            $update = adm_CredecialCorreo::find($request->id);
            $update->host=$request->host;
            $update->correo=$request->correo;
            $update->puerto=$request->puerto;
            $update->usuario=$request->usuario;
            $update->contraseña=$request->contraseña;
            $update->ssl=$request->ssl;
            $update->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
     
    }

    public function credencia_correo(Request $request){
        $datos = DB::table('adm__credecial_correos')->get();
        return $datos;
    }
}
