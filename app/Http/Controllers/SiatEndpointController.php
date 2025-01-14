<?php

namespace App\Http\Controllers;

use App\Models\Siat_Endpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatEndpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $index = DB::table('siat__endpoints as se')
    ->leftJoin('users as u', 'u.id', '=', 'se.id_usuario_modifica')
    ->where('se.tipo', $request->tipo)
    ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version', 'se.updated_at', 'u.name')
    ->paginate(15);
    return 
    [
            'pagination'=>
                [
                    'total'         =>    $index->total(),
                    'current_page'  =>    $index->currentPage(),
                    'per_page'      =>    $index->perPage(),
                    'last_page'     =>    $index->lastPage(),
                    'from'          =>    $index->firstItem(),
                    'to'            =>    $index->lastItem(),
                ] ,
            'index'=>$index,
    ];
    }

    public function crearEndPoint(Request $request){

      try{
        DB::beginTransaction();
        
        $entero = intval($request->prod_piloto); 
        $crear = new Siat_Endpoint();
        $crear->Descripcion=$request->descripcion;
        $crear->Url=$request->endpoint;
        $crear->Version=$request->version;
        $crear->tipo=$entero;
        $crear->id_usuario_registra=auth()->user()->id;
        $crear->id_usuario_modifica=auth()->user()->id;       
        $crear->save();
        DB::commit();
      } catch (\Throwable $th) {
        return $th;
      }
    }
  
}
