<?php

namespace App\Http\Controllers;

use App\Models\Adm_Qr_simple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AdmQrSimpleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function activar_or_desactivar(Request $request){
        $qr = Adm_Qr_simple::findOrFail($request->id);
        $qr->activo=$request->puntero;       
        $qr->save();
    }

   public function activar_or_desactivar_pri(Request $request){
     
    $id = $request->id;

    $query = DB::table('adm__qr_simples as aqs')
        ->select('aqs.id')
        ->get();

    foreach ($query as $value) {

        if ($id == $value->id) {
            DB::table('adm__qr_simples')
                ->where('id', $value->id)
                ->update(['prioridad' => 1]);
        } else {
            DB::table('adm__qr_simples')
                ->where('id', $value->id)
                ->update(['prioridad' => 0]);
        }

    }
    }

    public function getQR(){

        $query = DB::table('adm__qr_simples as aqs')
    ->select(
        'aqs.id',
        'aqs.nom_servicio_qr',
        'ab.nombre',
        'aqs.token_qr',
        'aqs.activo',
        'u.name',
        'aqs.prioridad',
        'aqs.updated_at',
        'ab.sigla',
        'aqs.url_banco_servicio'
    )
    ->join('adm__bancos as ab', 'aqs.tipo_qr', '=', 'ab.id')
    ->join('users as u', 'u.id', '=', 'aqs.usuario')
    ->orderBy('aqs.id', 'desc')
    ->get();
        return $query;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        DB::beginTransaction();   
       
        //(AES-256-CBC) la incriptacion es 2 caracteres aletorio + la contraseña encryptada + 3 caracteres aletorios
                     $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                     $randomString_2 = substr(str_shuffle($caracteres), 0, 2);
                     $randomString_3 = substr(str_shuffle($caracteres), 0, 3);
                     $textoEncriptado = Crypt::encrypt($request->contraseña);   
                     $cadena_pass=$randomString_2.$textoEncriptado.$randomString_3;            
        $qr =new Adm_Qr_simple;       
        $qr->nom_servicio_qr=$request->nombre;
        $qr->tipo_qr=$request->banco;
        $qr->usuario_qr=$request->usuario;
        $qr->contraseña_qr=$cadena_pass; 
        $qr->url_banco_servicio=$request->url;      
        $qr->usuario=auth()->user()->id;        
        $qr->save(); 
         DB::commit();
        return 0;
        } catch (\Throwable $th) {
            return $th;
        }  
    }

    public function getBancos(){
    $bancos = DB::table('adm__bancos')
    ->select('id', 'nombre', 'sigla', 'data', 'prioridad')
    ->where('activo', 1)
    ->get();
    return $bancos;
    }

}
