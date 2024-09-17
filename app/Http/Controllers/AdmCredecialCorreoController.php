<?php

namespace App\Http\Controllers;

use App\Models\adm_CredecialCorreo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Verhoeff;
use App\Helpers\AllegedRC4Helper;
use App\Helpers\Base64SINHelper;
use App\Helpers\operacionDosificacion;

class AdmCredecialCorreoController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, adm_CredecialCorreo $adm_CredecialCorreo)
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
            $update = adm_CredecialCorreo::find($request->id);
            $update->nit=$request->nit;
            $update->nom_empresa=$request->nombre_empresa;
            $update->nro_celular=$request->celular;
            $update->actividad_economica=$request->actividad_eco;
       
            $update->save();
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()],500);
        }
        
     
    }

    public function tipo_venta_update(Request $request)
    {
        try {
         
            $id=$request->id;
            $validador_variables=$request->validador_variables;          
            $update = adm_CredecialCorreo::find($id);            
            $update->factura_dosificacion=$validador_variables;           
            $update->save();

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
            $update = adm_CredecialCorreo::find($id);            
            $update->moneda=$request->id_moneda;           
            $update->save();
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
        $datos = DB::table('adm__credecial_correos')->get();
        return $datos;
    }

    public function getDataSucursal(Request $request){

        $sucursal = DB::table('adm__sucursals as ass')
        ->select('ass.id', 'ass.cod', 'ass.razon_social', 'ass.nit')
        ->where('ass.activo', 1)
        ->get();     

        $credecion = DB::table('adm__credecial_correos')
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
        $verificacion = DB::table('adm__credecial_correos')
        ->select('id', 'factura_dosificacion')
        ->where('factura_dosificacion', 2)
        ->get();
        $valor="";    
            if (count($verificacion)>0) {
                $valor=1;
                return response()->json(['valor'=>$valor,'id'=>$verificacion[0]->id]); 
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

        $update = adm_CredecialCorreo::find($id_credencial);            
        $update->id_dosificacion_siat=$id;           
        $update->save();

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



}
