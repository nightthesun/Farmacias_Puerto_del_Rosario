<?php

namespace App\Http\Controllers;

use App\Models\Adm_configuracionV2;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class AdmConfiguracionV2Controller extends Controller
{
     public function get_credencial_erp(Request $request){
        $datos = DB::table('adm__credencial_erp')->first();
        return $datos;
    }

    public function probarCorreo()
{
    try {

        $correo = DB::table('adm__credencial_erp')
            ->where('id', 1)
            ->first();

        if (!$correo) {
            return response()->json([
                'success' => false,
                'message' => 'No existe la configuración de correo.'
            ], 400);
        }
$cadena_pass = $correo->mail_password;

$textoEncriptado = substr($cadena_pass, 2, -3);

$password = Crypt::decrypt($textoEncriptado);


        // Cargar configuración desde la BD
        Config::set('mail.default', $correo->mail_mailer);

        Config::set('mail.mailers.smtp.host', $correo->mail_host);
        Config::set('mail.mailers.smtp.port', $correo->mail_port);
        Config::set('mail.mailers.smtp.username', $correo->mail_username);
        Config::set('mail.mailers.smtp.password', $password);
        
        Config::set('mail.mailers.smtp.encryption', $correo->mail_encryp);
   
       Config::set('mail.from.address', $correo->mail_from_add);
         
    Config::set('mail.from.name', $correo->mail_from_na);
  
        // Enviar correo al mismo correo configurado
        Mail::raw(
            'Este es un correo de prueba enviado correctamente desde el sistema.',
            function ($message) use ($correo) {

                $message->to($correo->mail_from_add)
                    ->subject('Correo de prueba');
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'Correo de prueba enviado correctamente.'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function update(Request $request)
    {
        try {
             DB::beginTransaction();
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
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
          $mailet=$request->mailet;
          $host=$request->host;
          $port=$request->port;
          $userName=$request->userName;

          $password=$request->password;
          $encryp=$request->encryp;
          $fromAddress=$request->fromAddress;
          $fromName=$request->fromName;

          $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                            $randomString_2 = substr(str_shuffle($caracteres), 0, 2);
                            $randomString_3 = substr(str_shuffle($caracteres), 0, 3);
                            $textoEncriptado = Crypt::encrypt($password);   
                            $cadena_pass=$randomString_2.$textoEncriptado.$randomString_3;           
                           
                            

               if (mb_strlen($mailet)>20) {
                return "la cadena es muy larga de remitente";
               }

                if (mb_strlen($host)>200) {
                return "la cadena es muy larga del host";
               }

                if (!is_numeric($port)) {
                return "debe ser un numero no nulo";
               }

                if (mb_strlen($userName)>200) {
                return "la cadena es muy larga del email del remitente";
               }

                if (mb_strlen($encryp)>20) {
                return "la cadena es muy larga de encryptación";
               }

               if (mb_strlen($fromAddress)>200) {
                return "la cadena es muy larga email de destino muy largo";
               }

               if (mb_strlen($fromName)>250) {
                return "la cadena es muy larga del cadena de destino remitente.";
               }
               
          if($request->tipo==1){
            $datos2=[
                'mail_mailer' => $mailet,
                'mail_host' => $host,
                'mail_port' => $port,
                'mail_username' => $userName,
                'mail_password' => $cadena_pass,
                'mail_encryp' => $encryp,
                'mail_from_add' => $fromAddress,
                'mail_from_na' => $fromName                
            ];
          }
            if($request->tipo==2){
                $datos2=[
                'mail_mailer' => $mailet,
                'mail_host' => $host,
                'mail_port' => $port,
                'mail_username' => $userName,
             
                'mail_encryp' => $encryp,
                'mail_from_add' => $fromAddress,
                'mail_from_na' => $fromName                
            ];
            }
        
            
              DB::table('adm__credencial_erp')->where('id', 1)->update($datos2); 
   
  DB::commit();
          return 0;
        } catch (\Throwable $th) {
            return $th;
        }
        
     
    }

    public function update_datos_empresa(Request $request)
    {
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
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
            $datos2 = [
                'nit' => $request->nit,
                'nom_empresa' => $request->nombre_empresa,
                'nro_celular' => $request->celular,
                'actividad_economica' => $request->actividad_eco,               
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2); 
           
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
     
    }

    public function tipo_venta_update(Request $request)
    {
        try {
         
            $id=$request->id;
            $validador_variables=$request->validador_variables;  
            
             $datos2 = [
                'factura_dosificacion' =>$validador_variables,                              
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2); 
            
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
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
            } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }   
    }

    public function tipomonedaUpdate(Request $request)
    {
        try {        
            $id=$request->id; 
              $datos2 = [
                'moneda' =>$request->id_moneda,                              
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2);              
       
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
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
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
     
    }
    

    public function credencia_correo(Request $request){
        $datos = DB::table('adm__config_erp')->get();
        return $datos;
    }

    public function getDataSucursal(Request $request){

        $sucursal = DB::table('adm__sucursals as ass')
        ->select('ass.id', 'ass.cod', 'ass.razon_social', 'ass.nit')
        ->where('ass.activo', 1)
        ->get();     

        $credecion = DB::table('adm__config_erp')
        ->select('nom_empresa','nit')       
        ->first();

        return response()->json(['sucursal' => $sucursal, 'credecion' => $credecion]);
    }

    public function tipo_moneda(Request $request){
        $monedas = DB::table('caja__monedas as cm')
    ->join('adm__nacionalidads as an', 'an.id', '=', 'cm.id_nacionalidad_pais')
    ->select('cm.id_nacionalidad_pais', 'an.pais', 'an.simbolo')
    ->distinct()
    ->get();
    return $monedas;
    }


   public function editar_cambiar_stock(Request $request){
        try {
              //1=add, 2=delete, 3=create, 4=edit, 5=show
    // Truncar la tabla para eliminar todo su contenido
            /////// detalle_descuento
            DB::beginTransaction();
            
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
             $id = $request->id;        
               
              $datos2 = [
                'stock_medio' =>(int)$request->stock_medio,                              
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2);   

           
                $datos = [
                    'id_modulo' => $request->id_modulo,
                    'id_sub_modulo' => $request->id_sub_modulo,
                    'accion' => 4,
                    'descripcion' => $request->des,          
                    'user_id' =>auth()->user()->id, 
                    'created_at'=>$fechaActual,
                    'id_movimiento'=>$id,   
                ];
            
                DB::table('log__sistema')->insert($datos);   
            DB::commit();
           
            
        } catch (\Throwable $th) {
            return $th;
        }
       
    } 

    public function store_dosificacion(Request $request){
        try {
              //1=add, 2=delete, 3=create, 4=edit, 5=show
    // Truncar la tabla para eliminar todo su contenido
            /////// detalle_descuento
            $arrayCargar_dosificacion = $request->arrayCargar_dosificacion;
            $nit=$request->nit_empresa;
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            foreach ($arrayCargar_dosificacion as $item) {
             $id_sucursal = $item['id_sucursal'];
             $nro_autorizacion = $item['autorizacion'];
             $identificacion = $item['identificacion'];
             $dosificacion = $item['dosificacion'];
             $fecha_a = $item['fecha_a'];
             $fecha_e = $item['fecha_e'];
             $n_ini_facturacion = $item['n_ini_facturacion'];
             $n_fin_facturacion = $item['n_fin_facturacion'];
             $n_act_facturacion = $item['n_act_facturacion'];
             
            //cod:me.cod_sucursal,nom_sucursal:me.nom_sucursal,nit_x_sucursal:me.nit_x_sucursal
             $data_cargar = [
                 'id_sucursal' => $id_sucursal,
                 'nro_autorizacion' => $nro_autorizacion,  
                 'identificacion' => $identificacion,
                 'dosificacion' => $dosificacion, 
                 'fecha_a'=> $fecha_a,
                 'fecha_e' => $fecha_e, 
                 'n_ini_facturacion' => $n_ini_facturacion,   
                 'n_fin_facturacion' => $n_fin_facturacion,   
                 'n_act_facturacion' => $n_act_facturacion, 
                 'nit' => $nit, 
                 'id_usuario_registra' =>auth()->user()->id, 
                 'created_at' => $fechaActual,      
                ];    
                $id_descuento = DB::table('dos__dosificacion')->insertGetId($data_cargar);

                $datos = [
                    'id_modulo' => $request->id_modulo,
                    'id_sub_modulo' => $request->id_sub_modulo,
                    'accion' => 3,
                    'descripcion' => $request->des,          
                    'user_id' =>auth()->user()->id, 
                    'created_at'=>$fechaActual,
                    'id_movimiento'=>$id_descuento,   
                ];
            
                DB::table('log__sistema')->insert($datos);   
           
            } 
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
       
    }
   
    public function index_dosificacion(Request $request){
       // $Llave_Dosificacion="A3Fs4s$)2cvD(eY667A5C4A2rsdf53kw9654E2B23s24df35F5";
       // $num_auto="79040011859";
      //  $num_factura="152";
      //  $num_documento="1026469026";
      //  $fecha_transaccion="20070728";
      //  $monto_transaccion="135";
   
     //  $dos= operacionDosificacion::operacion($Llave_Dosificacion, $num_auto,$num_factura,$num_documento,$fecha_transaccion,$monto_transaccion); 
     //  dd($dos);
       $sucursalId = $request->id_sucursal;
        $buscararray=array();  
        $fechaHoy = Carbon::now()->format('Y-m-d');      
        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls=''; 
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                            u.name like '%".$valor."%' 
                                or dd.nro_autorizacion like '%".$valor."%' 
                                or dd.dosificacion like '%".$valor."%'                              
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            u.name like '%".$valor."%' 
                                or dd.nro_autorizacion like '%".$valor."%' 
                                or dd.dosificacion like '%".$valor."%'  
                          )" ;
                    }  
                } 
                $dosificacion = 
                DB::table('dos__dosificacion as dd')
    ->select(
        'dd.id', 
        'dd.nro_autorizacion', 
        'dd.identificacion', 
        'dd.dosificacion', 
        'dd.fecha_a',
        'dd.fecha_e', 
        'dd.n_ini_facturacion', 
        'dd.n_fin_facturacion', 
        'dd.n_act_facturacion', 
        'u.name',
        'dd.estado',
        'dd.id_sucursal',
        DB::raw("DATEDIFF(dd.fecha_e, '$fechaHoy') as diferencia_dias")
    )
    ->join('users as u', 'u.id', '=', 'dd.id_usuario_registra')
    ->where('dd.id_sucursal', $sucursalId)
    ->whereRaw($sqls)->paginate(15);                   
            }   
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $dosificacion->total(),
                            'current_page'  =>    $dosificacion->currentPage(),
                            'per_page'      =>    $dosificacion->perPage(),
                            'last_page'     =>    $dosificacion->lastPage(),
                            'from'          =>    $dosificacion->firstItem(),
                            'to'            =>    $dosificacion->lastItem(),
                        ] ,
                    'dosificacion' => $dosificacion,
            ];    
        } else {
            $dosificacion = DB::table('dos__dosificacion as dd')
            ->select('dd.id', 'dd.nro_autorizacion', 'dd.identificacion', 'dd.dosificacion', 'dd.fecha_a',
            'dd.fecha_e', 'dd.n_ini_facturacion', 'dd.n_fin_facturacion', 'dd.n_act_facturacion', 'u.name','dd.estado','dd.id_sucursal'
            ,DB::raw("DATEDIFF(dd.fecha_e, '$fechaHoy') as diferencia_dias"))
            ->join('users as u', 'u.id', '=', 'dd.id_usuario_registra')
            ->where('dd.id_sucursal',  $sucursalId)          
            ->orderByDesc('dd.id')
            ->paginate(15);  
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $dosificacion->total(),
                            'current_page'  =>    $dosificacion->currentPage(),
                            'per_page'      =>    $dosificacion->perPage(),
                            'last_page'     =>    $dosificacion->lastPage(),
                            'from'          =>    $dosificacion->firstItem(),
                            'to'            =>    $dosificacion->lastItem(),
                        ] ,
                    'dosificacion' => $dosificacion,
            ];    
        }
       
    }        

    public function update_dosificacion(Request $request){
                  //1=add, 2=delete, 3=create, 4=edit, 5=show
                // Truncar la tabla para eliminar todo su contenido
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;
            $nro_autorizacion = $request->autorizacion;
            $identificacion = $request->identificacion;
            $dosificacion = $request->dosificacion;
            $fecha_a = $request->fecha_a;
            $fecha_e = $request->fecha_e;
            $n_ini_facturacion = $request->n_ini_facturacion;
            $n_fin_facturacion = $request->n_fin_facturacion;
            $n_act_facturacion = $request->n_act_facturacion;
            $id_sucursal = $request->id_sucursal;
            $data_cargar = [
                'id_sucursal' => $id_sucursal,
                'nro_autorizacion' => $nro_autorizacion,  
                'identificacion' => $identificacion,
                'dosificacion' => $dosificacion, 
                'fecha_a'=> $fecha_a,
                'fecha_e' => $fecha_e, 
                'n_ini_facturacion' => $n_ini_facturacion,   
                'n_fin_facturacion' => $n_fin_facturacion,   
                'n_act_facturacion' => $n_act_facturacion,              
                'id_usuario_registra' =>auth()->user()->id, 
                'created_at' => $fechaActual,      
               ];    
               DB::table('dos__dosificacion')->where('id', $id)->update($data_cargar);
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 4,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
       
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }

    }

    public function activar_verificar_dosificacion(Request $request){

        try {
            // Using DB facade for raw SQL query
        $verificacion = DB::table('adm__config_erp')
        ->select('id', 'factura_dosificacion')
        ->where('factura_dosificacion', 2)
        ->first();
        $valor="";    
            if ($verificacion) {
                $valor=1;
                return response()->json(['valor'=>$valor,'id'=>$verificacion->id]); 
            }
            else{
                $valor=0;
                return response()->json(['valor'=>$valor,'id'=>0]);              
            }
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
       
    }

    public function verifica_esta_activo_dosificacacion_x_sucursal(Request $request){
      
        $existe = DB::table('dos__dosificacion')
                      ->where('id_sucursal', $request->id_sucursal)
                      ->where('estado', 1)
                      ->exists();

                      if($existe){
                        return 1;
                      }
                      else{
                        return 0;
                      }       
      
    }
    public function desactivar_dosificacion(Request $request)
    {        //1=add, 2=delete, 3=create, 4=edit, 5=show
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;
            $user = auth()->user()->id;
            $data_load = [
                'estado' => 0,
                'id_usuario_registra' => $user            
            ];
            DB::table('dos__dosificacion')->where('id', $id)->update($data_load);
    
          
    
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
      
    }

    public function activar_dosificacion(Request $request)
    { 
        $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
        $id = $request->id;
        $id_credencial=$request->id_credencial;
        $user = auth()->user()->id;
   $datos2 = [
                'id_dosificacion_siat' =>$id,                              
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2);  

     
        $data_load = [
            'estado' => 1,
            'id_usuario_registra' => $user            
        ];
        DB::table('dos__dosificacion')->where('id', $id)->update($data_load);
      
        $datos = [
            'id_modulo' => $request->id_modulo,
            'id_sub_modulo' => $request->id_sub_modulo,
            'accion' => 4,
            'descripcion' => $request->des,          
            'user_id' =>auth()->user()->id, 
            'created_at'=>$fechaActual,
            'id_movimiento'=>$id,   
        ];
    
        DB::table('log__sistema')->insert($datos);   
    }

    public function crear_banco(Request $request){
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'nombre' => $request->nombre,
                'id_usuario_registra' => auth()->user()->id,
                'created_at'=>$fechaActual,
            ]; 
            DB::table('adm__bancos')->insert($datos);    
                
        }
         catch (\Throwable $th) {
            return $th;
        }      
    }

    public function editar_banco(Request $request){
        try {
            $fechaActual = Carbon::now();
            $datos =[
                'nombre' => $request->nombre,
                'id_usuario_modifica' => auth()->user()->id,
                'updated_at'=>$fechaActual,
            ];
            DB::table('adm__bancos')->where('id','=',$request->id)->update($datos);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
    public function desactivar(Request $request)
    {  
        $fechaActual = Carbon::now();
        $datos =[
            'activo' => 0,
            'id_usuario_modifica' => auth()->user()->id,
            'updated_at'=>$fechaActual,
        ];
        DB::table('adm__bancos')->where('id','=',$request->id)->update($datos);
    }

    public function activar(Request $request)
    {  
        $fechaActual = Carbon::now();
        $datos =[
            'activo' => 1,
            'id_usuario_modifica' => auth()->user()->id,
            'updated_at'=>$fechaActual,
        ];
        DB::table('adm__bancos')->where('id','=',$request->id)->update($datos);
    }


    public function get_cuenta(){
        $cuentas = DB::table('adm__cuenta_banco as acb')
        ->join('adm__bancos as ab', 'ab.id', '=', 'acb.id_banco')
        ->select('acb.id','ab.nombre','acb.id_banco','acb.nombre as nombre_cuenta','acb.nro_cuenta','acb.titular','acb.estado')
        ->get();
        return $cuentas;
    }

    public function crear_cuenta(Request $request){
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'id_banco' => $request->id_banco,                
                'nombre' => $request->nombre,                
                'nro_cuenta' => $request->nro_cuenta,                
                'titular' => $request->titular,
                'id_usuario_registra' => auth()->user()->id,
                'created_at'=>$fechaActual,
            ]; 
            DB::table('adm__cuenta_banco')->insert($datos);    
                
        }
         catch (\Throwable $th) {
            return $th;
        }      
    }

    public function editar_cuenta(Request $request){
        try {
            $fechaActual = Carbon::now();
            $datos =[
                'id_banco' => $request->id_banco,                
                'nombre' => $request->nombre,                
                'nro_cuenta' => $request->nro_cuenta,                
                'titular' => $request->titular,
                'id_usuario_registra' => auth()->user()->id,
                'created_at'=>$fechaActual,
            ];
            DB::table('adm__cuenta_banco')->where('id','=',$request->id)->update($datos);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
    public function desactivar_cuenta(Request $request)
    {  
        try {
            $fechaActual = Carbon::now();
        $datos =[
            'estado' => 0,
            'id_usuario_modifica' => auth()->user()->id,
            'updated_at'=>$fechaActual,
        ];
        DB::table('adm__cuenta_banco')->where('id','=',$request->id)->update($datos);
        } catch (\Throwable $th) {
          return $th;
        }
      
    }

    public function activar_cuenta(Request $request)
    { try {
        $fechaActual = Carbon::now();
        $datos =[
            'estado' => 1,
            'id_usuario_modifica' => auth()->user()->id,
            'updated_at'=>$fechaActual,
        ];
        DB::table('adm__cuenta_banco')->where('id','=',$request->id)->update($datos);
    } catch (\Throwable $th) {
        return $th;
    }      
    }

    public function añadir_quitar_Encargado(Request $request)
    {   
        //data 1= adicion de cargo 0= sin cargo
        try {
            $data=$request->data;
            if ($data==0) {
                $datos=['responsable' => 1];
            }else{
                if ($data==1) {
                $datos=['responsable' => 0];
                } else {
                    dd("error...");
                }               
            }           
            DB::table('users')->where('id','=',$request->id)->update($datos);
        
        } catch (\Throwable $th) {            
            return $th;
        }
    }

    public function añadirLimite(Request $request){
        try {        
            $id=$request->id;    
                $datos2 = [
                'tiempo_limite' =>$request->limite_hora, 
                 'monto_limite' =>$request->limite_monto,                             
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2);             
          
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
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
        } catch (\Throwable $th) {
            return $th;
        }
    }

 

    public function añadir_quitar_superUsuario(Request $request)
    {   
        //data 1= tiene permiso   0= sin permisos
        try {
            $data=$request->data;
            if ($data==0) {
                $datos=['super_usuario' => 1];
            }else{
                if ($data==1) {
                $datos=['super_usuario' => 0];
                } else {
                    dd("error...");
                }               
            }           
            DB::table('users')->where('id','=',$request->id)->update($datos);
        
        } catch (\Throwable $th) {            
            return $th;
        }
    }

    public function añadir_quitar_rubro(Request $request)
    {   
        //data 1= tiene permiso   0= sin permisos
        try {
            $data=$request->data;
            if ($data==0) {
                $datos=['rubro_x_usuario' => $request->id_cadena];
            }else{
                if ($data==1) {
                $datos=['rubro_x_usuario' => null];
                } else {
                    dd("error...");
                }               
            }           
            DB::table('users')->where('id', $request->id)->update($datos);
        
        } catch (\Throwable $th) {            
            return $th;
        }
    }

    public function editar_modal_apertura(Request $request){        
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;        
                 
                  $datos2 = [
                'modal_apertura' =>$request->modal_apertura,                                                            
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2); 

            
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function updateEfecto_sobrante(Request $request){        
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;        
               
             $datos2 = [
                'efecto_sobrante' =>$request->efecto_sobrante,                                                            
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2); 

            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function editar_transaccion_v2(Request $request){        
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $id = $request->id;        
              
            $datos2 = [
                'imprimir_trans' =>$request->tras,                                                            
            ];
             DB::table('adm__config_erp')->where('id', 1)->update($datos2); 
  
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function editar_gestionStock_panel(Request $request){        
        try {
            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
                   
            $data2=[
                'tipo_sucursal' => $request->tipoSucursal,
                'id_sucursales' => $request->entabla,
                'hora' => $request->hora,
                'frecuencia' => $request->frecuencia,                
            ];

             DB::table('log__config_gestion_stock')->where('id', 1)->update($data2);         
    
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>1,   
            ];
        
            DB::table('log__sistema')->insert($datos);   
        } catch (\Throwable $th) {
            return $th;
        }
    }   

    public function activador_gestionStock_panel(Request $request){
        $bu=$request->data;
        $tipo=$request->tipo;             
              
        switch ($bu) {
            case 1:
                $datos = ['activo_c_r_1' => $tipo];
            break;
            case 2:
                $datos = ['activo_d_l_2' => $tipo];
            break;
            case 3:
                $datos = ['activo_d_m_m_3' => $tipo];
            break;
            case 4:
                $datos = ['activo_m_abc_4' => $tipo];
            break;
            case 5:
                $datos = ['activo_canal_5' => $tipo];
            break;
            default:
               $datos = ['activo_c_r_1' => 0,'activo_d_l_2' => 0, 'activo_d_m_m_3' => 0, 'activo_m_abc_4' => 0,'activo_canal_5' => 0];
                break;
        }
         DB::table('log__config_gestion_stock')->where('id', 1)->update($datos); 
    }

    public function crearDistribuidorXautomatico(Request $request){
        try {
            //1= existe
            $id_dis=$request->id_distribuidor;
            $id_linea=$request->id_linea;
             DB::beginTransaction();
               $existe = DB::table('log__distribuidor_auto')
                ->where('id_linea', $id_linea)
                ->where('id_distribuidor', $id_dis)
                 ->first();
            if ($existe) {            
             return response()->json([
                'valor' => 1,
                'id' => $existe->id,
                'forma_pago'=> $existe->forma_pago,
                'intervalo_pago'=> $existe->intervalo_pago,
                'Plazo_pago'=> $existe->Plazo_pago,
                'entrega_pedido'=> $existe->entrega_pedido,
                'observacion'=> $existe->observacion,
                'limiteCompra_1'=>$existe->limiteCompra_1,
                'limiteCompra_2'=>$existe->limiteCompra_2,
            ]); 
            } 
             $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'id_linea' => $request->id_linea,
                'id_distribuidor' => $request->id_distribuidor,
                'forma_pago' => $request->formaPago,
                'intervalo_pago' => $request->fechaPago,          
                'Plazo_pago' => $request->plazoPago,    
                'entrega_pedido'=> $request->pedidoEntre,
                'observacion'=> $request->observacion, 
                'created_at' => $fechaActual, 
                'updated_at' => $fechaActual,
                'limiteCompra_1'=>$request->limiteCompra_1,
                'limiteCompra_2'=>$request->limiteCompra_2,
            ];
             DB::table('log__distribuidor_auto')->insert($datos); 
             DB::commit();
             return response()->json(['valor' => 0]);
         
        } catch (\Throwable $th) {
            return $th;
        }
    }

   public function editarDistribuidorXautomatico(Request $request){
    try {
      DB::beginTransaction();
      $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [                
                'forma_pago' => $request->formaPago,
                'intervalo_pago' => $request->fechaPago,          
                'Plazo_pago' => $request->plazoPago,    
                'entrega_pedido'=> $request->pedidoEntre,
                'observacion'=> $request->observacion,       
                'updated_at' => $fechaActual,
                'limiteCompra_1'=>$request->limiteCompra_1,
                'limiteCompra_2'=>$request->limiteCompra_2,
            ];
             DB::table('log__distribuidor_auto')->where('id', $request->id)->update($datos); 
       DB::commit();
       return 0;
    } catch (\Throwable $th) {
        return $th;
    }    
   }

   public function getDistribuidorAutomatico(){
   
    $resultado = DB::table('log__distribuidor_auto as lda')
    ->join('prod__lineas as pl', 'lda.id_linea', '=', 'pl.id')
    ->join('dir__distribuidors as dd', 'dd.id', '=', 'lda.id_distribuidor')
    ->select(
        'lda.id',
        'lda.forma_pago',
        DB::raw("CASE
            WHEN lda.forma_pago = 1 THEN 'CHEQUE'
            WHEN lda.forma_pago = 2 THEN 'CONTADO'
            WHEN lda.forma_pago = 3 THEN 'CREDITO'
            WHEN lda.forma_pago = 4 THEN 'TRASFERENCIA BANCARIA'
            ELSE NULL
        END AS forma_pago_nombre"),
        'lda.intervalo_pago',
        'lda.Plazo_pago',
        'lda.entrega_pedido',
        DB::raw("CASE
            WHEN lda.entrega_pedido = 1 THEN 'MAÑANA'
            WHEN lda.entrega_pedido = 2 THEN 'TARDE'
            ELSE NULL
        END AS entrega_pedido_nombre"),
        'lda.observacion',
        'lda.ciclo_2',
        DB::raw("CASE
            WHEN lda.ciclo_2 = 7 THEN 'UNA SEMANA'
            WHEN lda.ciclo_2 = 30 THEN 'UN MES'
            WHEN lda.ciclo_2 = 90 THEN 'TRES MESES'
            WHEN lda.ciclo_2 = 180 THEN 'SEIS MESES'
            WHEN lda.ciclo_2 = 360 THEN 'DOCE MESES'
            ELSE 'SIN ACCION'
        END AS ciclo_2_nombre"),
        'lda.lim_inferior',
        'lda.lim_superior',
        'pl.nombre as nombre_linea',
        'dd.nom_linea_array',
        'dd.alias'
    )
     ->orderBy('lda.id', 'desc')
    ->get();

    return $resultado;
   } 

  public function updateDiferenciaVentas_3(Request $request){
    try {
    DB::beginTransaction();
    if ($request->data==1) {
       $datos = [                  
                'ciclo_2' => $request->ciclo              
            ];
    }else{
        if ($request->data==2) {
       $datos = [                
                'lim_inferior' => $request->limiteInferior, 
                'lim_superior' => $request->limiteSuperior,                
            ];
    }else {
        $datos = [  
            'ciclo_2' => 0,              
                'lim_inferior' =>0, 
                'lim_superior' =>0,               
            ];
        }
    }   
    
    DB::table('log__distribuidor_auto')->where('id', $request->id)->update($datos); 
    DB::commit();
       return 0;
    } catch (\Throwable $th) {
        return $th;
    }
  }

  public function deleteDisGesAut_3(Request $request){
   DB::table('log__distribuidor_auto')
    ->where('id', $request->id)
    ->delete();
  }
  //-------------backend traspasos pesteña 13---------
  public function update_acciones_traspaso_SS(Request $request){
        $bu=$request->data;
        $tipo=$request->tipo;             
              
        switch ($bu) {
            case 1:
                if($tipo==0){
                    $datos = ['activo_traspaso' => 0, 'activo_traslado' => 0,'activo_recepcion' => 0];
                }else{
                    $datos = ['activo_traspaso' => 1];
                }                
            break;
            case 2:
                if($tipo==0){
                    $datos = ['activo_traslado' => 0,'activo_recepcion' => 0];
                }else{
                    $datos = ['activo_traslado' => 1];
                }
               
            break;
            case 3:
                $datos = ['activo_recepcion' => $tipo];
            break;            
            default:
               $datos = ['activo_traspaso' => 0,'activo_traslado' => 0, 'activo_recepcion' => 0];
            break;
        }
         DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
    }

   public function update_DiasAcumulados_traspaso_SS(Request $request){
    try {
        DB::beginTransaction();
        $valor=30+$request->valor;
        $datos = ['dias_acumulados' => $valor];
         DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
        DB::commit();
        return 0;
    } catch (\Throwable $th) {
        return $th;
    }        
   }

    public function updateTraspaso_traspaso_SS(Request $request){
    try {
        DB::beginTransaction();       
        $datos = ['glosa_traspaso' => $request->palabra,'estado_traspaso' => $request->estado,'limiteTraspaso_1'=>$request->limiteTraspaso_1,'limiteTraspaso_2'=>$request->limiteTraspaso_2];
         DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
        DB::commit();
        return 0;
    } catch (\Throwable $th) {
        return $th;
    }        
   }

   public function updateConfiguracionTraspaso_traspado_SS(Request $request){
    try {
           DB::beginTransaction();  
           $indice = $request->indice;
            switch ($indice) {
                case 1:
                    $datos = ['id_users_traslado' => $request->tabla_];
                break;
                case 2:
                    $datos = ['id_vehiculo_traslado' => $request->tabla_];
                break;
                case 3:
                    $datos = [
                        'ini_traslado' => $request->hora_I,
                        'fin_traslado' => $request->hora_F,
                        'max_items_traslado' => $request->input_cantidad_max,
                        'limiteTraslado_1' => $request->limiteTraslado_1,
                        'limiteTraslado_2' => $request->limiteTraslado_2,
                        'observacion_traslado' => $request->inputObservacio,
                    ];
                break;
                default:                   
                break;
            }        
        DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
        DB::commit();
        return 0;         
       
    } catch (\Throwable $th) {
        return $th;
    }        
   }

   public function updateTraspaso_recepcio_SS(Request $request){
    try {
        DB::beginTransaction();       
        $datos = ['observacion_recepcion' => $request->palabra,
                    'limiteRecepcion_1' => $request->limiteRecepcion_1,
                        'limiteRecepcion_2' => $request->limiteRecepcion_2,
        ];
         DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
        DB::commit();
        return 0;
    } catch (\Throwable $th) {
        return $th;
    }        
   }
   
   public function updateEcuacionZ(Request $request){
    try {
           DB::beginTransaction(); 
      $datos=['valor_Z'=>$request->ecuacion_radio_1];
     DB::table('log__config_traspaso')->where('id', 1)->update($datos); 
      DB::commit();
        return 0;
    } catch (\Throwable $th) {
            return $th;
    } 
    }

    public function update_tipo_caja_v_1(Request $request){
    try {
           DB::beginTransaction(); 
             $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
       $datos=['tipo_Caja'=>$request->tipo_caja];               
               
     DB::table('adm__config_erp')->where('id', 1)->update($datos); 
     
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 2,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$request->id,   
            ];
        
            DB::table('log__sistema')->insert($datos);  
      DB::commit();
        return 0;
    } catch (\Throwable $th) {
            return $th;
    } 
    }

    

}
