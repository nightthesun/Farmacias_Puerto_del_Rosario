<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prod_TipoEntrada;

class ProdTipoEntradaController extends Controller
{
    public function index(Request $request)
    {
        $tipoentrada_data = Prod_TipoEntrada::orderby('id','desc')->get();
        $tipoentrada_paginate = Prod_TipoEntrada::orderby('id','desc')->paginate(15);
        
        return
        [
                'pagination'=>
                [
                    'total'         =>    $tipoentrada_paginate->total(),
                    'current_page'  =>    $tipoentrada_paginate->currentPage(),
                    'per_page'      =>    $tipoentrada_paginate->perPage(),
                    'last_page'     =>    $tipoentrada_paginate->lastPage(),
                    'from'          =>    $tipoentrada_paginate->firstItem(),
                    'to'            =>    $tipoentrada_paginate->lastItem(),
                 ] ,
                'tipoentrada'=>$tipoentrada_paginate,
                'tipoentrada_data'=> $tipoentrada_data,
        ];
    }

    public function store(Request $request){
        $newTipoEntrada = new Prod_TipoEntrada();
        $newTipoEntrada->nombre = $request->nombre;
        $newTipoEntrada->id_usuario_registra = auth()->user()->id;
        $newTipoEntrada->save();
    }

    public function update(Request $request)
    {
        $tipoEntrada = Prod_TipoEntrada::findOrFail($request->id);
        $tipoEntrada->nombre = $request->nombre;
        $tipoEntrada->id_usuario_modifica = auth()->user()->id;
        $tipoEntrada->save();
    }

    public function desactivar(Request $request){
        $tipoEntrada = Prod_TipoEntrada::findOrFail($request->id);
        $tipoEntrada->activo = 0;
        $tipoEntrada->id_usuario_modifica = auth()->user()->id;
        $tipoEntrada->save();
    }

    public function activar(Request $request){
        $tipoEntrada = Prod_TipoEntrada::findOrFail($request->id);
        $tipoEntrada->activo = 1;
        $tipoEntrada->id_usuario_modifica = auth()->user()->id;
        $tipoEntrada->save();
    }
}
