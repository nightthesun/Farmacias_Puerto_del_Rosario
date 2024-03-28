<?php

namespace App\Http\Controllers;

use App\Models\Dir_Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DirClienteController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dir_Cliente $dir_Cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dir_Cliente $dir_Cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Cliente $dir_Cliente)
    {
        //
    }

    public function listarTipoDoc(Request $request)
    {
        $tiposDocumento = DB::table('dir__tipo_doc')->get();
        return $tiposDocumento;
    }
   
}
