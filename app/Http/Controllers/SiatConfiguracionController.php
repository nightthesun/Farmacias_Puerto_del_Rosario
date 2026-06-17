<?php

namespace App\Http\Controllers;

use App\Helpers\CufHelper;
use SoapClient;
use App\Models\Siat_Configuracion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SiatConfiguracionController extends Controller
{
    public function index_general()
    {
        $resultado = DB::table('siat__configuracions')
        ->where('id', 1)
        ->first();
        return $resultado;
    }

    public function verificationKey(Request $request){
        try {
            if($request->hasFile('firma'))
        {
            $archivo = $request->file('firma');          
            $contenido = file_get_contents($archivo->getRealPath());
            $certificados = [];

            if (!openssl_pkcs12_read($contenido, $certificados, $request->password)) {
                return 0;
            }
            else{
                return 1;
            }
        }else{
            return 2;
        }  
        } catch (\Throwable $th) {
            return $th;
        }
              
    }

    public function updateTablaSiatConfiguracion(Request $request){
        try {
            DB::beginTransaction();
            $actualizar = Siat_Configuracion::findOrFail(1);
            $actualizar->tipo_factura=$request->tipoFactura; 
            $actualizar->save();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function update_general(Request $request)
    {

    
        try {
            DB::beginTransaction();
            
            //$textoEncriptado="eyJpdiI6IndQNGQyZCsyald6TmlRV1VtRXZGS1E9PSIsInZhbHVlIjoiVUNrNklEWnJ0OWpEZzFpdnk1Zms1dzQ0NFhiRnQyWEhtaXJhL21IUEp2dz0iLCJtYWMiOiJkMGIzMjk4NzUxMTI1MjlhYTY3MzNjMmE2NDU0ODNlMDU2M2QwM2M0YTk4NDZlMTliNDIzMDg2MzA0NjUzYWZiIiwidGFnIjoiIn0=";
           // $textoDesencriptado = Crypt::decrypt($textoEncriptado); //desencriptar clave
          //  return $textoDesencriptado;
      
    
      

            $actualizar = Siat_Configuracion::findOrFail(1);
            $actualizar->cod_sis=$request->cod_sis; 
            $actualizar->tipo_ambiente=$request->selectTipoAmbiente; 
            $actualizar->formato_fecha=$request->forFecha; 
            $actualizar->max_paquete=$request->paquetes; 
            $actualizar->token_delegado=$request->token_delegado; 
            $actualizar->url_QR=$request->qr_; 
            $actualizar->vencimiento_token=$request->selectVenToken; 
            $actualizar->tiempo_espera=$request->maxTiempoRespuesta; 
            $actualizar->tipo_modalidad=$request->codigoModalidad; 
            
            if ($request->activarCambioFirma==1) {
                
                $actualizar->tipo_certificado=$request->selectCertificado;           
                $data= (int)$request->selectCertificado;   

                if ($data==1) {
                   if ($request->password==""||$request->password==null) {
                            return "la contraseña no puede estar vacia";           
                           } else {
                            if($request->hasFile('firma'))
                            {
                              
                                //  VALIDAR P12 ANTES DE GUARDAR
          
                                  // Ruta de la carpeta donde se guardarán los archivos
                                 $carpeta = 'firma';
                                // Eliminar el archivo anterior si existe
                                $archivos = Storage::files($carpeta);
                                if (count($archivos) > 0) {
                                 Storage::delete($archivos[0]); // Elimina el primer archivo encontrado
                                    }
                            $filename=$request->firma->getClientOriginalName();
                            info($filename);
                            $actualizar->path=$request->file('firma')->store('firma');                                        
                            $actualizar->name=$filename; 
                            //(AES-256-CBC) la incriptacion es 2 caracteres aletorio + la contraseña encryptada + 3 caracteres aletorios
                            $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                            $randomString_2 = substr(str_shuffle($caracteres), 0, 2);
                            $randomString_3 = substr(str_shuffle($caracteres), 0, 3);
                            $textoEncriptado = Crypt::encrypt($request->password);   
                            $cadena_pass=$randomString_2.$textoEncriptado.$randomString_3;           
                            $actualizar->password=$cadena_pass;                      
                            } else{
                            return "No tiene archivo";  
                        }
    
                            $actualizar->llave_privada=$request->key_privade; 
                            $actualizar->certificado_x509=$request->certificado_x509; 
                           }
                }

                if ($data==2) {
                      if ($request->key_privade==""||$request->certificado_x509==""||$request->key_privade==null||$request->certificado_x509==null) {
                            return "Contenido vacio o nulo"; 
                      }else{
                 $actualizar->llave_privada=$request->key_privade;
                        $actualizar->certificado_x509=$request->certificado_x509; 
                      }  
                }

                if ($data==3) {
                  if ($request->password==""||$request->password==null) {
                            return "la contraseña no puede estar vacia";           
                           } else {
                            if($request->hasFile('firma'))
                        {
                       
                    
                              // Ruta de la carpeta donde se guardarán los archivos
                             $carpeta = 'firma';
                               
                            // Eliminar el archivo anterior si existe
                            $archivos = Storage::files($carpeta);
                        
                            if (count($archivos) > 0) {
                             Storage::delete($archivos[0]); // Elimina el primer archivo encontrado
                                }
                                   
                        $filename=$request->firma->getClientOriginalName();
                      
                        info($filename);
                     
                        $a=$request->file('firma')->store('firma');
                     
                   
                        $actualizar->path=$a;                                        
                        $actualizar->name=$filename; 
                     //(AES-256-CBC) la incriptacion es 2 caracteres aletorio + la contraseña encryptada + 3 caracteres aletorios
                     $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                     $randomString_2 = substr(str_shuffle($caracteres), 0, 2);
                     $randomString_3 = substr(str_shuffle($caracteres), 0, 3);
                     $textoEncriptado = Crypt::encrypt($request->password);   
                     $cadena_pass=$randomString_2.$textoEncriptado.$randomString_3;           
                     $actualizar->password=$cadena_pass;                    

                        }else{
                            return "No tiene archivo";  
                        }
                        } 
                }
               
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
                        's1' => $item['s1'],
                        's2' => $item['s2'],
                    ];                
                    DB::table('excel__emision')->insert($datos);                 
                 }

                 // Si llegamos aquí sin errores, confirmamos la transacción
                DB::commit();
            } catch (\Throwable $th) {
            //throw $th;
            }        
    
    }
    public function subirArchivo(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'archivo' => 'required|file|mimes:pem,p12', // Solo permite .pem y .p12
        ]);

        // Ruta de la carpeta donde se guardarán los archivos
        $carpeta = 'archivos';

        // Eliminar el archivo anterior si existe
        $archivos = Storage::files($carpeta);
        if (count($archivos) > 0) {
            Storage::delete($archivos[0]); // Elimina el primer archivo encontrado
        }

        // Guardar el nuevo archivo
        $archivo = $request->file('archivo');
        $nombreArchivo = $archivo->getClientOriginalName(); // Nombre original del archivo
        $rutaArchivo = $archivo->storeAs($carpeta, $nombreArchivo); // Guardar en storage/app/archivos

        return response()->json([
            'message' => 'Archivo subido correctamente.',
            'ruta' => $rutaArchivo,
        ]);
    }

    public function get_list_siat(Request $request){
    $id_catalogo=$request->id_catalogo;    
    if ($id_catalogo==11) {
          $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', $id_catalogo)
        ->where('id_erp', $request->id_rubro)
        ->get();
        }else{
$datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', $id_catalogo)
        ->get();
        }
         
        return $datos; 
    }

    public function upload_list_siat(Request $request){
        try {
               DB::beginTransaction();
        
               $id_catalogo=(int)$request->id_catalogo;
               $codigo=(int)$request->codigo;
               $descripcion=$request->descripcion;
               
               $existe = DB::table('siat__catalogo_lista_siat')
    ->where('id_catalogo', $id_catalogo)
    ->exists();

$data = [
    'id_catalogo' => $id_catalogo,
    'codigo' => $codigo,
    'descripcion'=>$descripcion
];

if ($existe) {
    DB::table('siat__catalogo_lista_siat')
        ->where('id_catalogo', $id_catalogo)
        ->update($data);
} else {
    DB::table('siat__catalogo_lista_siat')
        ->insert($data);
}
                 DB::commit();
                 return 0;
        } catch (\Throwable $th) {
            return $th;
        }
   
    
    }


    public function upload_all(){
        try {
            DB::table('siat__catalogo_lista_siat')->truncate();
             DB::beginTransaction();
    $n=1;
    while($n<=5){
            switch ($n) {
                case 1:
                    $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', 1)
        ->where('codigo', 1)
        ->first();
                break;
                case 2:
                    $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', 3)
        ->where('codigo', 1)
        ->first();
                break;
                case 3:
                    $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', 9)
        ->where('codigo', 1)
        ->first();
                break;
                case 4:
                    $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', 11)
        ->where('codigo',2)
        ->first();
                break;
                  case 5:
                    $datos = DB::table('excel__emision')
         ->select('descripcion', 'codigo','id_catalogo','id_erp')
        ->where('id_catalogo', 2)
        ->where('codigo',1)
        ->first();
                break;
                
                default:
                  $datos=null;
                    break;
            }
            $data = [
    'id_catalogo' => $datos->id_catalogo,
    'codigo' => $datos->codigo,
    'descripcion'=>$datos->descripcion
];
     DB::table('siat__catalogo_lista_siat')
        ->insert($data);

    $n++;
    }

                

             DB::commit();
                 return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getTablaList_siat(){
        $datos = DB::table('siat__catalogo_lista_siat')
         ->select('id', 'id_catalogo','codigo','descripcion')->get();
        return $datos;             
    }

}
