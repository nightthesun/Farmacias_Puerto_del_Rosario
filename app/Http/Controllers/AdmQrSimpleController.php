<?php

namespace App\Http\Controllers;

use App\Models\Adm_Qr_simple;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdmQrSimpleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    private function activar_p($id): bool
{
    return DB::table('adm__qr_endpoints')
        ->where('id', $id)
        ->update(['activar' => 1]);
}

    public function desactivate_p($id){
       try {
        return 0;
          DB::beginTransaction();
            $updated = DB::table('adm__qr_endpoints')
            ->where('id', $id)
            ->update(['activar' => 0]);
            DB::commit();
       } catch (\Throwable $th) {
       return $th;
       }
      
    }

    

    public function jsonOperacion_1(Request $request){
         $id=$request->id;
         $opcion_1=$request->opc;

        $id_qr_simple=$request->id_qr_simple;
        $tipo=$request->tipo;
        $id_servicio=$request->id_servicio;
        $query_1= $this->queryACtivador($id,$id_qr_simple,$tipo,$id_servicio);
        //dd($query_1['success']);
        if ($query_1['success']==true) {
           switch ($opcion_1) {
            case 1:
                $data_1=$this->opcion_1_1($id_qr_simple,$id); 
                $data = $data_1->getData(); // stdClass

$success = $data->success;
          
                
                  if ($success==1 ||$success=='1') {
                    
                    $A=$this->activar_p($id);                 
                  
                    return $data_1;
                  }else{
                    return $data_1;
                  }  
                
            break;
            
            
                default:
                # code...
            break;
        }   
        }else{
            return $query_1;
        }  
    }

    private function opcion_1_1($id,$id_lista){
          try {            
       // $id_lista=1;///-------------------revisar
    $query = $this->request_user($id);
   
    $query_2=$this->request_endpoint_list_1($id,$id_lista);
 
    if ($query==null || $query_2==null) {
         return response()->json([
                'success' => false,
    'message' => 'sin datos'
            ]);
    }else{

$link = $query_2->url;
$user_1 = $query->usuario_qr;
$pass_1 = $query->contraseña_qr;
$sigla = $query->sigla;

// Quitar caracteres aleatorios
$textoEncriptado = substr($pass_1, 2, -3);

// Desencriptar
$passwordOriginal = Crypt::decrypt($textoEncriptado);

// Enviar request
$response = Http::withHeaders([
    'Content-Type' => 'application/json',
    //----------añadio--------
    'Accept' => 'application/json',
    'User-Agent' => 'Laravel-Client',
    //------------------------

])->post($link, [
    'accountId' => $user_1,
    'authorizationId' => $passwordOriginal,
]);



if ($response->ok()) {
    $data = $response->json();
$message = $data['message'];
    return response()->json([
        'success' => $response->successful(),
        'message' => $message,   
        'body'    => $response->body(),
        'json'    => $response->json()
    ]);
}else{
     return response()->json([
        'success' => $response->successful(),
        'message' => 'Error desconocido al validar credenciales '.$sigla, 
        'body'    => 'error',
        'json'    => 'error'
    ]);
}


         
    }
        } catch (\Throwable $th) {
    return response()->json([
        'success' => false,
        'message' => 'Error de comunicación con el servicio del '.$sigla,
        'error'   => $th->getMessage(),
    ], 500);
}    
    }

    private function queryACtivador($id,$id_qr_simple,$tipo,$id_servicio){
        $query = DB::table('adm__qr_endpoints')
    ->where('id_qr_simple', $id_qr_simple)
    ->where('tipo', $tipo)
    ->where('id_servicio', $id_servicio)
    ->where('id_servicio', '<>', 8)
    ->select('id')
    ->get();
    if(count($query)>1){
       return [
                'success' => false,
                'message' => 'Solo puede haber uno activo'
            ];
    }else{
        $query = DB::table('adm__qr_endpoints')
    ->where('id_qr_simple', $id_qr_simple)
    ->where('tipo', $tipo)
    ->where('id_servicio', $id_servicio)
    ->where('id_servicio', '<>', 8)
    ->where('id', $id)
    ->select('id')
    ->get();
    }

      return [
                'success' => true,
                'message' => 'sin problemas'
            ];
    }

    private function request_user($id){
      $resultado = DB::table('adm__qr_simples as aqs')
    ->select(
        'aqs.nom_servicio_qr',
        'aqs.usuario_qr',
        'aqs.contraseña_qr',
        'aqs.token_qr',
        'aqs.url_banco_servicio',
        'aqs.activo',
        'aqs.prioridad',
        'ab.sigla',
        'ab.nombre'
    )
    ->join('adm__bancos as ab', 'aqs.tipo_qr', '=', 'ab.id')
    ->where('aqs.activo', 1)
    ->where('aqs.id', $id)
    ->first(); // usar get() si esperas varios registros
    return $resultado;
    }

    private function request_endpoint_list_1($id_qr_simple,$id_usar){
        $resultado = DB::table('adm__qr_endpoints as aqe')
    ->select(
        'aqe.id',
        'aqe.id_qr_simple',
        'aqe.url',
        'aqe.tipo'        
        )    
    ->where('aqe.id_qr_simple', $id_qr_simple)
    ->where('aqe.id', $id_usar)
    ->first(); // usar get() si esperas varios registros
    return $resultado;
    }

    public function get_endpoint_lister(Request $request){
        $query = DB::table('adm__qr_endpoints as aqe')
    ->select(
        'aqe.id',
        'aqe.id_qr_simple',
        'aqe.descripcion',
        'aqe.url',
        'aqe.version',
        DB::raw("
            CASE
                WHEN aqe.tipo = 1 THEN 'PRODUCCION'
                WHEN aqe.tipo = 2 THEN 'PILOTO'
                ELSE 'SIN ACCION'
            END AS tipo
        "),
        'aqe.id_servicio',
        'aqe.tipo',
        'aqe.id_servicio',
        'aqe.activar'

    )
    ->join('adm__qr_simples as aqs', 'aqs.id', '=', 'aqe.id_qr_simple')
    ->where( 'aqe.id_qr_simple',$request->id_qr_simple)
    ->where( 'aqe.tipo',$request->tipo)
    ->orderBy('aqe.id', 'desc')
    ->get();
    return $query;
    } 


public function edit_enpoint(Request $request){
try {
            //code...adm__qr_endpoints
            DB::beginTransaction();
             $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual

            if ($request->id_servicio==8) {
                $datos = [
                'id_qr_simple' => $request->id_qr_simple,
                'descripcion' => $request->descripcion,
                'url' => $request->url,
                'version' => $request->version,
                'tipo' => $request->tipo,   
                'id_servicio'=>$request->id_servicio,
            ];        
           DB::table('adm__qr_endpoints')
    ->where('id', $request->id)
    ->update($datos);

             $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 4,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$request->id,   
            ];        
            DB::table('log__sistema')->insert($datos);
            DB::commit();
        return 0;
            } else {
                 $existe = DB::table('adm__qr_endpoints')
    ->where('tipo', $request->tipo)
    ->where('id_servicio', $request->id_servicio)
    ->where('id_qr_simple',$request->id_qr_simple)
    ->exists();

if ($existe) {
    return 1;
} else {
   $datos = [
                'id_qr_simple' => $request->id_qr_simple,
                'descripcion' => $request->descripcion,
                'url' => $request->url,
                'version' => $request->version,
                'tipo' => $request->tipo,   
                'id_servicio'=>$request->id_servicio,
            ];        
             DB::table('adm__qr_endpoints')
    ->where('id', $request->id)
    ->update($datos);

             $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 4,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=> $request->id,   
            ];        
            DB::table('log__sistema')->insert($datos);
            DB::commit();
        return 0;
}
            }           
            
        } catch (\Throwable $th) {
    
            return $th;
        }
}

    public function create_enpoint(Request $request){
        try {
            //code...adm__qr_endpoints
            DB::beginTransaction();
             $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual

            if ($request->id_servicio==8) {
                $datos = [
                'id_qr_simple' => $request->id_qr_simple,
                'descripcion' => $request->descripcion,
                'url' => $request->url,
                'version' => $request->version,
                'tipo' => $request->tipo,   
                'id_servicio'=>$request->id_servicio,
            ];        
            $id_index=DB::table('adm__qr_endpoints')->insertGetId($datos);

             $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 3,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id_index,   
            ];        
            DB::table('log__sistema')->insert($datos);
            DB::commit();
        return 0;
            } else {
                 $existe = DB::table('adm__qr_endpoints')
    ->where('tipo', $request->tipo)
    ->where('id_servicio', $request->id_servicio)
    ->where('id_qr_simple',$request->id_qr_simple)
    ->exists();

if ($existe) {
    return 1;
} else {
   $datos = [
                'id_qr_simple' => $request->id_qr_simple,
                'descripcion' => $request->descripcion,
                'url' => $request->url,
                'version' => $request->version,
                'tipo' => $request->tipo,   
                'id_servicio'=>$request->id_servicio,
            ];        
            $id_index=DB::table('adm__qr_endpoints')->insertGetId($datos);

             $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 3,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id_index,   
            ];        
            DB::table('log__sistema')->insert($datos);
            DB::commit();
        return 0;
}
            }           
            
        } catch (\Throwable $th) {
    
            return $th;
        }
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
        'aqs.url_banco_servicio',
        'aqs.tipo_qr'
    )
    ->join('adm__bancos as ab', 'aqs.tipo_qr', '=', 'ab.id')
    ->join('users as u', 'u.id', '=', 'aqs.usuario')
    ->orderBy('aqs.id', 'desc')
    ->get();
        return $query;
    }

    public function edit(Request $request){
         
        try {
        DB::beginTransaction();
         $usuario_=$request->usuario_;
        $contraseña_=$request->contraseña_;
        $qr = Adm_Qr_simple::findOrFail($request->id);
        if ($usuario_==""||$usuario_==null||$contraseña_==""||$contraseña_==null) {
        $qr->nom_servicio_qr=$request->nombre;
        $qr->tipo_qr=$request->banco;        
        $qr->url_banco_servicio=$request->url;      
        $qr->usuario=auth()->user()->id;        
        $qr->save(); 
        }else{
            //(AES-256-CBC) la incriptacion es 2 caracteres aletorio + la contraseña encryptada + 3 caracteres aletorios
                     $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                     $randomString_2 = substr(str_shuffle($caracteres), 0, 2);
                     $randomString_3 = substr(str_shuffle($caracteres), 0, 3);
                     $textoEncriptado = Crypt::encrypt($request->contraseña);   
                     $cadena_pass=$randomString_2.$textoEncriptado.$randomString_3;     
                     
        $qr->nom_servicio_qr=$request->nombre;
        $qr->tipo_qr=$request->banco;        
        $qr->url_banco_servicio=$request->url;      
        $qr->usuario=auth()->user()->id;   
        $qr->usuario_qr=$request->usuario;
        $qr->contraseña_qr=$cadena_pass;      
        $qr->save(); 
        }  
         DB::commit();
        return 0;
        } catch (\Throwable $th) {
           return $th;
        }
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

    //$response = Http::withHeaders([
//    'Content-Type' => 'application/json',
//    'Accept'       => 'application/json',
//    'User-Agent'   => 'Laravel-Client',
//])->post(
//    'http://test.bnb.com.bo/ClientAuthentication.API/api/v1/auth/token',
//    [
//        'username' => 's9CG8FE7Id75ef2jeX9bUA==',
//        'password' => '713K7PvTlACs1gdmv9jGgA==',
//    ]
//);

//dd($response->successful());
//$response->successful();      // true si código HTTP 200-299
//dd($response->ok());              // igual que successful()
//dd($response->failed());          // true si código HTTP >= 400
//dd($response->serverError());     // true si 500-599
//dd($response->clientError());     // true si 400-499
//dd($response->status());          // devuelve el código HTTP (ej: 200, 401, 500)

//dd($response->body());            // Devuelve todo el contenido como string
//dd($response->json());            // Convierte JSON a array
//dd($response->object());          // Convierte JSON a objeto stdClass
//dd($response->collect());         // Convierte JSON a colección de Laravel (Collection)

// return $response;

}
