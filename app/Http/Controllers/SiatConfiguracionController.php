<?php

namespace App\Http\Controllers;
use SoapClient;
use App\Models\Siat_Configuracion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;


class SiatConfiguracionController extends Controller
{
    public function index_general()
    {
        $resultado = DB::table('siat__configuracions')
        ->where('id', 1)
        ->first();
        return $resultado;
    }
    public function update_general(Request $request)
    {
        try {
            DB::beginTransaction();
            $actualizar = Siat_Configuracion::findOrFail($request->id);
            $actualizar->cod_sis=$request->cod_sis; 
            $actualizar->tipo_ambiente=$request->selectTipoAmbiente; 
            $actualizar->formato_fecha=$request->forFecha; 
            $actualizar->max_paquete=$request->paquetes; 
            $actualizar->token_delegado=$request->token_delegado; 
            $actualizar->url_QR=$request->qr_; 
            $actualizar->vencimiento_token=$request->selectVenToken; 
            $actualizar->tiempo_espera=$request->maxTiempoRespuesta; 
            $actualizar->tipo_modalidad=$request->codigoModalidad;             
            $actualizar->tipo_certificado=$request->selectCertificado; 
       
           
            $data= (int)$request->selectCertificado; 
          
            switch ($data) {
                case 1:
                    $actualizar->password=$request->password; 
                    $actualizar->llave_privada=$request->key_privade; 
                    $actualizar->certificado_x509=$request->certificado_x509; 
                    break;
            
                case 2:
                    $actualizar->password=$request->password; 
                    $actualizar->llave_privada=""; 
                    $actualizar->certificado_x509=""; 
                    break;
            
                case 3:
                    $actualizar->password=""; 
                    $actualizar->llave_privada=$request->key_privade; 
                    $actualizar->certificado_x509=$request->certificado_x509; 
                    break;
            
                default:
                $actualizar->password=""; 
                $actualizar->llave_privada=""; 
                $actualizar->certificado_x509=""; 
                    break;
            }   
            $actualizar->save();
            $fechaActual = SupportCarbon::now(); // Obtiene la fecha y hora actual
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
        } catch (\Throwable $th) {
           return $th;
        }
     
    }
 
    public function obtenerCuis()
    {
        $wsdl = 'https://pilotosiat.impuestos.gob.bo/v2/FacturacionCodigos?wsdl'; // Cambiar a producción si es necesario

        // Parámetros para la solicitud
        $params = [
            'SolicitudCuis' => [
                'codigoAmbiente' => 2, // Cambiar a 1 en producción
                'codigoPuntoVenta' => 1,
                'codigoSistema' => env('SIN_CODIGO_SISTEMA'),
                'nit' => env('SIN_NIT'),
                'codigoSucursal' => 0,
                'codigoModalidad' => env('SIN_CODIGO_MODALIDAD'),
            ],
        ];

        try {
            // Crear cliente SOAP
            $client = new SoapClient($wsdl, [
                'trace' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            // Hacer la solicitud
            $response = $client->cuis($params);

            return response()->json([
                'cuis' => $response->RespuestaCuis->codigo,
                'fechaVigencia' => $response->RespuestaCuis->fechaVigencia,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al solicitar CUIS',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }

   
    public function catalagos(){
        $catalogos = DB::table('siat__catalogo')->select('id', 'catalogo')->get();
        return $catalogos;  
    }

    public function excel_emision(Request $request){
        $emisiones = DB::table('excel__emision')
        ->where('id_catalogo', $request->id_catalogo)
        ->get();
        return $emisiones;    
    }

    public function upload_exe(Request $request){
            try {
                // Iniciar una transacción
                DB::beginTransaction();
                $bloque=$request->data;
                $id=$request->id;
              // return $array;    
              DB::table('excel__emision')->where('id_catalogo', $id)->delete();        
                foreach ($bloque as $item) {
                 
                    $datos = [
                        'descripcion' => $item['descripcion'],
                        'codigo' => $item['codigo'],
                        'id_catalogo' => $item['id_catalogo'],
                        'id_erp' => $item['id_erp'],
                    ];                
                    DB::table('excel__emision')->insert($datos);                 
                 }

                 // Si llegamos aquí sin errores, confirmamos la transacción
                DB::commit();
            } catch (\Throwable $th) {
            //throw $th;
            }        
    
    }
    

}
