<?php

namespace App\Http\Controllers;

use App\Models\adm_CredecialCorreo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmCredecialCorreoController extends Controller
{
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
   
    
}
