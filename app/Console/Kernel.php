<?php

namespace App\Console;

use App\Models\Siat_Emisor;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Siat_Paramatros_sincronizacion;


class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        

        $tablaSincro_S=DB::table('log__sincro_ges_stock')->where('id', 1)->first();
      
        if ($tablaSincro_S->activo==1) {
            $frecuencia=$tablaSincro_S->frecuencia;
            $tabla_sucu=$tablaSincro_S->id_sucursales;          
            $horaDB = Carbon::parse($tablaSincro_S->hora)->format('H:i');             
              $evento= $schedule->call(function () use ($horaDB,$tabla_sucu) {        
                    Log::info("Ejecutando tarea programada el  las {$horaDB}");
                    // Aquí ejecutas la lógica de la tarea
                     // Convertir a array
             $tablasincro_23=DB::table('adm__credecial_correos')->where('id', 1)->first();       
             $tipoTabla=$tablasincro_23->stock_medio;              
             $cadena_error="";
            $fechaHoy = Carbon::now()->format('Y-m-d');
            $hora=Carbon::now()->format('H:i:s');
            $vector = explode(',', $tabla_sucu);
         $cadena_error="";
          $error_z=0;
            for ($i=0; $i < count($vector); $i++) { 
                 $idsucursal = trim($vector[$i]); // elimina espacios extra
                 
                
               switch ($tipoTabla) {
                case 1:               
                $a=$this->addTabla_1($idsucursal,$tipoTabla);
                break;             
                case 2:                
                $a=$this->addTabla_2($idsucursal,$tipoTabla);
                break;            
                default:
                $a=$this->addTabla_1($idsucursal,$tipoTabla);
             break; 
              }
                $primeraLetra = substr($a, 0, 1); // obtiene el primer carácter
            if ($primeraLetra=='S') {
                $error_z=1;
            }            
              $cadena_error=$cadena_error." ".$a." Hora:".$hora." Fecha:".$fechaHoy;
                   
            } 
             $datos = [
            'informe' => $cadena_error,
            'error' =>$error_z         
        ];
        DB::table('log__sincro_ges_stock')
            ->where('id', 1)            
            ->update($datos);
                });   
                    // Asignar la frecuencia según el valor en la base de datos
        switch ($frecuencia) {
            case 1:  // Diaria
                $evento->dailyAt($horaDB);
                break;
            case 2:  // Semanal (Ejemplo: Viernes)
                $evento->weeklyOn(5, $horaDB); // 5 = Viernes
                break;
            case 3:  // Último día del mes
                $evento->lastDayOfMonth($horaDB);
                break;
            case 4:  // Trimestral (día 1 de enero, abril, julio, octubre)
                $evento->quarterlyOn(1, $horaDB);
                break;
            default:
                Log::info("Frecuencia no válida.");
        }       
 
        }


        $tareaActiva = DB::table('auto__sincronizacion')->where('id', 1)->first();
        $intentos=$tareaActiva->intentos;
        $intervalo_min=$tareaActiva->intervalo_min;
        $operacion=$intervalo_min*60;
        $frecuencia=$tareaActiva->frecuencia;
        $cufd_x=$tareaActiva->activacionCufd;
        $hora_cufd=$tareaActiva->hora_cufd;

        if ($tareaActiva && $tareaActiva->activo == 1) {

            $horaDB = Carbon::parse($tareaActiva->hora)->format('H:i');
            $horaDB_2 = Carbon::parse($hora_cufd)->format('H:i');
           $evento= $schedule->call(function () use ($horaDB,$intentos,$operacion) {        
                    Log::info("Ejecutando tarea programada el  las {$horaDB}");
                    // Aquí ejecutas la lógica de la tarea
                    $datos_1 = DB::table('siat__configuracions')->where('id', 1)->first();
                $datos_2 = DB::table('adm__credecial_correos')->where('id', 1)->first();  
                    $configuracion = DB::table('siat__emisors as s')
                    ->join('siat__sucursals as ss', 'ss.id', '=', 's.id_siat_sucursal')
                    ->join('siat__cuis as c', 'c.id', '=', 's.id_cuis')
                    ->where('s.estado', 1)->where('c.estado', 1)
                    ->select('s.id','s.nombre as nombre_emisor','s.id_punto_venta as punto_venta','ss.nombre_suc_siat','ss.codigo_siat','c.dato')->get();
                   
                    $tama=count($configuracion);
                        foreach ($configuracion as $key => $value) { 
                            $n=1;   
                            while ($n <= $intentos) {
                                $a= $this->sincronizar_m_a($datos_1->tipo_ambiente,$datos_1->token_delegado,$datos_1->cod_sis,$datos_2->nit,$value->punto_venta,$value->codigo_siat,$value->dato);                         
                                if ($a==0) {
                            $n=$intentos+1;
                            $tama--;
                                }else{
                                    $n++;
                                }                         
                            }
                           // if ($a!=0) {
                           //     Log::info("error "); 
                           // }
                           // sleep($operacion);    
                        }   
                if ($tama==0) {
                    $crear=new Siat_Paramatros_sincronizacion();
                    $crear->tipo="automatico";
                    $crear->estado=1;
                    $crear->descripcion="Todas las sucursales";
                    $crear->save();
                }else{
                    $crear=new Siat_Paramatros_sincronizacion();
                    $crear->tipo="automatico";
                    $crear->estado=2;
                    $crear->descripcion="Todas las sucursales";
                    $crear->save();
                }
            });
             // Asignar la frecuencia según el valor en la base de datos
        switch ($frecuencia) {
            case 1:  // Diaria
                $evento->dailyAt($horaDB);
                break;
            case 2:  // Semanal (Ejemplo: Viernes)
                $evento->weeklyOn(5, $horaDB); // 5 = Viernes
                break;
            case 3:  // Último día del mes
                $evento->lastDayOfMonth($horaDB);
                break;
            case 4:  // Trimestral (día 1 de enero, abril, julio, octubre)
                $evento->quarterlyOn(1, $horaDB);
                break;
            default:
                Log::info("Frecuencia no válida.");
        }           
             if ($cufd_x==1) {
                $evento_2= $schedule->call(function () use ($horaDB_2) {

                    $datos_1 = DB::table('siat__configuracions')->where('id', 1)->first();
                    $datos_2 = DB::table('adm__credecial_correos')->where('id', 1)->first();  
                    $nit=$datos_2->nit;
                        $configuracion = DB::table('siat__emisors as s')
                        ->join('siat__sucursals as ss', 'ss.id', '=', 's.id_siat_sucursal')
                        ->join('siat__cuis as c', 'c.id', '=', 's.id_cuis')
                        ->leftJoin('siat__cufd as cu', 'cu.id', '=', 's.id_cufd')
                        ->where('s.estado', 1)->where('c.estado', 1)
                        ->select('s.id','s.nombre as nombre_emisor','s.id_punto_venta as punto_venta','ss.nombre_suc_siat','ss.codigo_siat','c.dato','cu.dato as cufd','cu.id as cufd_id')->get();
                        
                           
                            
                            $intento_fin=3;
                            foreach ($configuracion as $key => $value) { 
                                $intento_ini=1;
                                while ($intento_ini <= $intento_fin) {
                                    $endPoints = DB::table('siat__endpoints as se')    
                                ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
                                ->where('se.tipo', intval($datos_1->tipo_ambiente))
                                ->where('se.id',3)
                                ->get(); 
                             
                                
                                $cadena_url=$endPoints[0]->Url; 
                                $wsdl = $cadena_url;
                                    // Asignación de la URL y API key
                                    $wsdl = $cadena_url; 
                                    $apikeyValue = 'TokenApi ' .$datos_1->token_delegado; // Concatenar correctamente el valor del API key
                                
                                // Crear el cuerpo del mensaje SOAP, sustituyendo los valores con los parámetros correspondientes        
                                $xmlData = <<<EOD
                                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                        <siat:cufd>
                                            <SolicitudCufd>
                                                <codigoAmbiente>{$datos_1->tipo_ambiente}</codigoAmbiente>
                                                <codigoModalidad>{$datos_1->tipo_modalidad}</codigoModalidad>              
                                                <codigoPuntoVenta>{$value->punto_venta}</codigoPuntoVenta>
                                                <codigoSistema>{$datos_1->cod_sis}</codigoSistema>
                                                <codigoSucursal>{$value->codigo_siat}</codigoSucursal>
                                                <cuis>{$value->dato}</cuis>
                                                <nit>{$nit}</nit>
                                            </SolicitudCufd>
                                        </siat:cufd>
                                    </soapenv:Body>
                                </soapenv:Envelope>
                                EOD;
                             
                                
                                    // Inicializar cURL
                                    $ch = curl_init();
                        
                                    // Configuración de la solicitud cURL
                                    curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Content-Type: text/xml; charset=utf-8',
                                        'SOAPAction: ""', // Si el SOAPAction es requerido, inclúyelo aquí
                                        'apikey: ' . $apikeyValue // Incluye la API key con el valor correspondiente
                                    ]);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        
                                    // Ejecutar la solicitud y obtener la respuesta
                                    $response = curl_exec($ch);
                                   
                                    // Verificar si hubo un error en cURL
                                    if (curl_errno($ch)) {
                                        throw new \Exception(curl_error($ch));
                                    }
                        
                                    // Cerrar la sesión de cURL
                                    curl_close($ch);
                                // Convertir la respuesta en un objeto SimpleXMLElement
                                $xml = simplexml_load_string($response);
                                
                                $respuesta=$response;
                              
                                // Usar XPath para encontrar el nodo <transaccion>    
                                $transaccion = $xml->xpath('//transaccion');
                                if ($transaccion && isset($transaccion[0])) {
                                    if ($transaccion[0]== 'true') {                     
                                            $intento_ini=$intento_fin+1;
                                            $codigo_2 = $xml->xpath('//codigo');
                                            $fechaVigencia= $xml->xpath('//fechaVigencia');
                                            $direccion= $xml->xpath('//direccion');
                                            $codigoControl= $xml->xpath('//codigoControl');     
                                            $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
                                       
                                            $actualizar = Siat_Emisor::findOrFail($value->id);
                                           
                                            $existe_cufd = DB::table('siat__cufd')->where('dato', $value->cufd)->first();
                                            
                                            if ($existe_cufd) {     
                                                                
                                                DB::table('siat__cufd')->where('dato', $value->cufd)->where('id',$value->cufd_id)->update(['estado' =>0,'id_emisor' =>$value->id]);
                                                $datos_3=[                        
                                                    'dato' => $codigo_2[0],
                                                    'fecha_vigencia' => $fechaVigencia[0],
                                                    'created_at' => $fechaActual,
                                                    'id_emisor' => $value->id,
                                                    'codigoControl' => $codigoControl[0],
                                                    'direccion' => $direccion[0] 
                                                ];
                                                $id_cufd = DB::table('siat__cufd')->insertGetId($datos_3);
                        
                                            } else {             
                                               
                                                $datos_3=[                        
                                                    'dato' => $codigo_2[0],
                                                    'fecha_vigencia' => $fechaVigencia[0],
                                                    'created_at' => $fechaActual,
                                                    'id_emisor' => $value->id,
                                                    'codigoControl' => $codigoControl[0],
                                                    'direccion' => $direccion[0] 
                                                ];
                                                $id_cufd = DB::table('siat__cufd')->insertGetId($datos_3);
                                            }
                                            $actualizar->id_cufd = $id_cufd;    
                                            $actualizar->save();              
                                           
                                           
                                    } 
                                    $intento_ini++;

                            } 
                            $intento_ini++;        
                                }
                                                               
                            }
                        
                       

                }); 
                $evento_2->dailyAt($horaDB_2);
             }   
        }else{
            Log::info("modo normal-...."); 
        }
       }   

    
       

    /**
     * Register the commands for the application.
     *
     * @return void
     */

    protected function commands()
    {
        $this->load(__DIR__.'/Commands'); // Carga los comandos personalizados
        require base_path('routes/console.php'); // Carga las rutas de consola
    }

     private function get_bitacora_v2(){
        $datos = DB::table('sis__bitacora_stock_v2')->get();
        return $datos;    
    }

    private function addTabla_1($data_sucursal,$tipoTabla){
            try {
                $fechaHoy = Carbon::now()->format('Y-m-d');              
                $idsucursal=$data_sucursal;                
                 $generarstocks=$this->generarstocks($idsucursal);
                    foreach ($generarstocks as $key => $value) {   
            $datos_3=[
                'id_producto' => $value->id_producto,
                'stock' => $value->stock_total,
                'fecha_ingreso' => $fechaHoy, 
                'id_sucursal' => $idsucursal,
                'envase' => $value->envase,         
            ];
            
           DB::table('sis_bitacora_stock')->insert($datos_3);  
                    } 
                    return "sucursal:".$data_sucursal." OK";
            } catch (\Throwable $th) {
                return "Sucursal:".$data_sucursal." Error:".$th;
            }                    
    }

    private function addTabla_2($data_sucursal,$tipoTabla){
       
       try {
        $fechaHoy = Carbon::now()->format('Y-m-d');                 
                //---- caso dos por sucursales----
                $idsucursal=$data_sucursal;
                $bd_2=$this->get_bitacora_v2();
                $generarstocks=$this->generarstocks($idsucursal);
// Paso 1: Reindexar $bd_2 por id_producto (para búsquedas rápidas O(1))
$mapaBitacora = [];
foreach ($bd_2 as $registro) {
    // Crear clave única combinando id_producto + envase + id_sucursal
    $key = "{$registro->id_producto}_{$registro->envase}_{$registro->id_sucursal}";
    // Guardar todo el registro en el mapa
    $mapaBitacora[$key] = $registro;
}
// Paso 2: Recorrer los productos nuevos
foreach ($generarstocks as $value) {
    $idProducto = $value->id_producto;
    $stockActual = $value->stock_total;
    $envase=$value->envase;
    
    $key = "{$idProducto}_{$envase}_{$idsucursal}";
   if (isset($mapaBitacora[$key])) {
        // Ya existe en bitácora
        $registro_1 = $mapaBitacora[$key];          
        $anterior = $registro_1->stock;
        $contador = $registro_1->contador + 1;
        $suma = $stockActual + $anterior;

        $datos = [
            'stock' => $stockActual,
            'anterior' => $anterior,
            'suma' => $suma,
            'contador' => $contador,
            'fecha_ingreso' => $fechaHoy,
            'id_sucursal' => $idsucursal,
            'envase' => $value->envase,
        ];

         DB::table('sis__bitacora_stock_v2')
            ->where('id_producto', $idProducto)
            ->where('id_sucursal', $idsucursal)
            ->where('envase', $envase)
            ->update($datos);  
    } else {
        // No existe → crear nuevo
        $datos = [
            'id_producto' => $idProducto,
            'stock' => $stockActual,
            'anterior' => 0,
            'suma' => $stockActual,
            'contador' => 1,
            'fecha_ingreso' => $fechaHoy,
            'id_sucursal' => $idsucursal,
            'envase' => $value->envase,
        ];
        DB::table('sis__bitacora_stock_v2')->insert($datos);
    }
}
        return "sucursal:".$data_sucursal." OK";
       } catch (\Throwable $th) {

        return "Sucursal:".$data_sucursal." Error:".$th;
       }        
    }

    private function  generarstocks($id_sucursal){  
// Subconsulta gettion_tienda stock_total
$gettionTienda = DB::table('prod__productos as pp')
    ->join('tda__ingreso_productos as tip', 'tip.id_prod_producto', '=', 'pp.id')
    ->join('tda__tiendas as tt','tt.id','=','tip.idtienda')
    ->join('adm__sucursals as ass','tt.idsucursal','=','ass.id') 
    ->join('pivot__modulo_tienda_almacens as pivot', function ($join) {
        $join->on('pivot.id_ingreso', '=', 'tip.id')
             ->where('pivot.tipo', '=', 'TDA');
    })
    ->join('ges_pre__venta2s as gpv2', 'gpv2.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
    ->join('prod__dispensers as pd', DB::raw("pd.id"), '=', DB::raw("
        CASE 
            WHEN tip.envase = 'primario' THEN pp.iddispenserprimario
            WHEN tip.envase = 'secundario' THEN pp.iddispensersecundario
            WHEN tip.envase = 'terciario' THEN pp.iddispenserterciario
        END
    "))
    ->join('prod__forma_farmaceuticas as pff', DB::raw("pff.id"), '=', DB::raw("
        CASE 
            WHEN tip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
            WHEN tip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
            WHEN tip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
        END
    "))
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->select(
        'pp.id as id_producto',
        'pl.nombre as nombre_linea',
        'pl.tiempo_demora',
        'pp.nombre as nombre_producto',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.tiempopedidoprimario
                WHEN tip.envase = 'secundario' THEN pp.tiempopedidosecundario
                WHEN tip.envase = 'terciario' THEN pp.tiempopedidoterciario
                ELSE NULL
            END as tiempo_producto
        "),
        'pd.nombre as nombre_dis',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.cantidadprimario
                WHEN tip.envase = 'secundario' THEN pp.cantidadsecundario
                WHEN tip.envase = 'terciario' THEN pp.cantidadterciario
                ELSE NULL
            END as cantidad_dispenser_producto
        "),
        'pff.nombre as nombre_forma_farmaceutica',
        DB::raw("
            CASE
                WHEN tip.envase = 'primario' THEN pp.preciolistaprimario
                WHEN tip.envase = 'secundario' THEN pp.preciolistasecundario
                WHEN tip.envase = 'terciario' THEN pp.preciolistaterciario
                ELSE NULL
            END as precio_lista_producto
        "),
        'tip.stock_ingreso',
        'gpv2.utilidad_neto_gespreventa',
        'gpv2.costo_compra_gespreventa',
        'tip.envase',
        DB::raw("'Tienda' as tipo")
    )
        ->where('ass.id',$id_sucursal);
      
        

// Subconsulta gettion_almacen
$gettionAlmacen = DB::table('prod__productos as pp')
    ->join('alm__ingreso_producto as aip', 'aip.id_prod_producto', '=', 'pp.id')
    ->join('alm__almacens as aa', 'aip.idalmacen', '=', 'aa.id')
    ->join('adm__sucursals as ass', 'aa.idsucursal', '=', 'ass.id')

    ->join('pivot__modulo_tienda_almacens as pivot', function ($join) {
        $join->on('pivot.id_ingreso', '=', 'aip.id')
             ->where('pivot.tipo', '=', 'ALM');
    })
    ->join('ges_pre__venta2s as gpv2', 'gpv2.id_table_ingreso_tienda_almacen', '=', 'pivot.id')
    ->join('prod__dispensers as pd', DB::raw("pd.id"), '=', DB::raw("
        CASE 
            WHEN aip.envase = 'primario' THEN pp.iddispenserprimario
            WHEN aip.envase = 'secundario' THEN pp.iddispensersecundario
            WHEN aip.envase = 'terciario' THEN pp.iddispenserterciario
        END
    "))
    ->join('prod__forma_farmaceuticas as pff', DB::raw("pff.id"), '=', DB::raw("
        CASE 
            WHEN aip.envase = 'primario' THEN pp.idformafarmaceuticaprimario
            WHEN aip.envase = 'secundario' THEN pp.idformafarmaceuticasecundario
            WHEN aip.envase = 'terciario' THEN pp.idformafarmaceuticaterciario
        END
    "))
    ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
    ->select(
        'pp.id as id_producto',
        'pl.nombre as nombre_linea',
        'pl.tiempo_demora',
        'pp.nombre as nombre_producto',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.tiempopedidoprimario
                WHEN aip.envase = 'secundario' THEN pp.tiempopedidosecundario
                WHEN aip.envase = 'terciario' THEN pp.tiempopedidoterciario
                ELSE NULL
            END as tiempo_producto
        "),
        'pd.nombre as nombre_dis',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.cantidadprimario
                WHEN aip.envase = 'secundario' THEN pp.cantidadsecundario
                WHEN aip.envase = 'terciario' THEN pp.cantidadterciario
                ELSE NULL
            END as cantidad_dispenser_producto
        "),
        'pff.nombre as nombre_forma_farmaceutica',
        DB::raw("
            CASE
                WHEN aip.envase = 'primario' THEN pp.preciolistaprimario
                WHEN aip.envase = 'secundario' THEN pp.preciolistasecundario
                WHEN aip.envase = 'terciario' THEN pp.preciolistaterciario
                ELSE NULL
            END as precio_lista_producto
        "),
        'aip.stock_ingreso',
        'gpv2.utilidad_neto_gespreventa',
        'gpv2.costo_compra_gespreventa',
        'aip.envase',
        DB::raw("'Almacen' as tipo")
    )
    ->where('ass.id',$id_sucursal);

// Unión de tienda y almacén
$combinado = $gettionTienda->unionAll($gettionAlmacen);

// Consulta principal con agrupación
$resultado = DB::table(DB::raw("({$combinado->toSql()}) as sub"))
    ->mergeBindings($combinado)
    ->select(
        'sub.id_producto',
        'sub.nombre_linea',
        'sub.tiempo_demora',
        'sub.nombre_producto',
        'sub.tiempo_producto',
        'sub.nombre_dis',
        'sub.cantidad_dispenser_producto',
        'sub.nombre_forma_farmaceutica',
        'sub.precio_lista_producto',
        DB::raw('SUM(sub.stock_ingreso) AS stock_total'),
        DB::raw('AVG(sub.utilidad_neto_gespreventa) AS utilidad_neta'),
        DB::raw('AVG(sub.costo_compra_gespreventa) AS precio_unitario'),
        'sub.envase',
        'sub.tipo'
    )
    ->groupBy(
        'sub.id_producto',
        'sub.nombre_linea',
        'sub.tiempo_demora',
        'sub.nombre_producto',
        'sub.tiempo_producto',
        'sub.nombre_dis',
        'sub.cantidad_dispenser_producto',
        'sub.nombre_forma_farmaceutica',
        'sub.precio_lista_producto',
        'sub.envase',
        'sub.tipo'
    )
    ->get();

    return $resultado;     
    }


     private function sincronizar_m_a($codigoAmbiente,$token_delegado,$codigoSistema,$nit,$codigoPuntoVenta,$codigoSucursal,$cuis){
        try {
          
            $endPoints = DB::table('siat__endpoints as se')    
        ->select('se.id', 'se.Descripcion', 'se.Url', 'se.Version')
        ->where('se.tipo', intval($codigoAmbiente))
        ->where('se.id',1)
        ->get(); 
    
        $cadena_url=$endPoints[0]->Url; 
        $wsdl = $cadena_url;
            // Asignación de la URL y API key
            $wsdl = $cadena_url; 
            $apikeyValue = 'TokenApi ' .$token_delegado; // Concatenar correctamente el valor del API key
            
            $numero=0;
            while ($numero <= 18) {
                   // Llamar al método privado dentro de la misma clase
    $xmlData = $this->endpoint($codigoAmbiente, $codigoPuntoVenta, $codigoSistema, $codigoSucursal, $cuis, $nit, $numero);
       
    $ch = curl_init();
    // Configuración de la solicitud cURL
    curl_setopt($ch, CURLOPT_URL, $wsdl); // Reemplaza con el endpoint correcto
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: ""', // Si el SOAPAction es requerido, inclúyelo aquí
        'apikey: ' . $apikeyValue // Incluye la API key con el valor correspondiente
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);

    // Verificar si hubo un error en cURL
    if (curl_errno($ch)) {
        throw new \Exception(curl_error($ch));
    }

    // Cerrar la sesión de cURL
    curl_close($ch);
   
    $respuesta_final= $this->operacionSincro($numero,$response);      
    

    if ($respuesta_final!=0) {
        Log::info("error de ingreso ".$respuesta_final); 
        return "error de entrada ".$respuesta_final;
    } 
                $numero++;
            }
           
            Log::info("sin errores......"); 
           return 0;           
             
        } catch (\Exception $e) {
            Log::info("error de inicio".$e); 
        return "error de inicio".$e;
        }   

    }

    public function endpoint($codigoAmbiente, $codigoPuntoVenta, $codigoSistema, $codigoSucursal, $cuis, $nit, $n) {
        $xmlData = null;
        switch ($n) {
            case 0:
                $xmlData = <<<EOD
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                    <soapenv:Header/>
                    <soapenv:Body>
                        <siat:verificarComunicacion/>
                    </soapenv:Body>
                </soapenv:Envelope>
                EOD;
                break;
            case 1:
                $xmlData = <<<EOD
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                   <soapenv:Header/>
                   <soapenv:Body>
                      <siat:sincronizarActividades>
                         <SolicitudSincronizacion> 
                            <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                            <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                            <codigoSistema>$codigoSistema</codigoSistema>
                            <codigoSucursal>$codigoSucursal</codigoSucursal>
                            <cuis>$cuis</cuis>
                            <nit>$nit</nit>
                         </SolicitudSincronizacion>
                      </siat:sincronizarActividades>
                   </soapenv:Body>
                </soapenv:Envelope>
                EOD;
            
                break;
            case 2:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <siat:sincronizarFechaHora>
                                <SolicitudSincronizacion>
                                    <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                    <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                    <codigoSistema>$codigoSistema</codigoSistema>
                                    <codigoSucursal>$codigoSucursal</codigoSucursal>
                                    <cuis>$cuis</cuis>
                                    <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarFechaHora>
                        </soapenv:Body>
                        </soapenv:Envelope>
                    EOD;
                    break;
                case 3:                              
                        $xmlData = <<<EOD
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                            <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarListaActividadesDocumentoSector>
                                    <SolicitudSincronizacion>
                                        <codigoAmbiente>$codigoAmbiente</codigoAmbiente>          
                                        <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                        <codigoSistema>$codigoSistema</codigoSistema>
                                        <codigoSucursal>$codigoSucursal</codigoSucursal>
                                        <cuis>$cuis</cuis>
                                        <nit>$nit</nit>
                                        </SolicitudSincronizacion>
                                </siat:sincronizarListaActividadesDocumentoSector>
                            </soapenv:Body>
                        </soapenv:Envelope>
                        EOD;
                        break;    
                    case 4:                              
                            $xmlData = <<<EOD
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                                <soapenv:Header/>
                                    <soapenv:Body>
                                        <siat:sincronizarListaLeyendasFactura>
                                        <SolicitudSincronizacion>
                                        <codigoAmbiente>$codigoAmbiente</codigoAmbiente>           
                                        <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                        <codigoSistema>$codigoSistema</codigoSistema>
                                        <codigoSucursal>$codigoSucursal</codigoSucursal>
                                        <cuis>$cuis</cuis>
                                        <nit>$nit</nit>
                                        </SolicitudSincronizacion>
                                    </siat:sincronizarListaLeyendasFactura>
                                </soapenv:Body>
                            </soapenv:Envelope>
                            EOD;
                    break;  
                    case 5:                              
                        $xmlData = <<<EOD
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                            <soapenv:Header/>
                                <soapenv:Body>
                                    <siat:sincronizarListaMensajesServicios>
                                    <SolicitudSincronizacion>
                                    <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                    <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                    <codigoSistema>$codigoSistema</codigoSistema>
                                    <codigoSucursal>$codigoSucursal</codigoSucursal>
                                    <cuis>$cuis</cuis>
                                    <nit>$nit</nit>
                                    </SolicitudSincronizacion>
                                </siat:sincronizarListaMensajesServicios>
                            </soapenv:Body>
                        </soapenv:Envelope>
                        EOD;
                    break;
                case 6:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarListaProductosServicios>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarListaProductosServicios>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;      
                case 7:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaEventosSignificativos>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaEventosSignificativos>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;  
                case 8:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaMotivoAnulacion>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaMotivoAnulacion>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 9:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaPaisOrigen>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaPaisOrigen>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 10:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoDocumentoIdentidad>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoDocumentoIdentidad>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 11:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoDocumentoSector>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>            
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoDocumentoSector>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 12:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoEmision>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>           
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoEmision>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 13:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoHabitacion>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoHabitacion>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 14:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoMetodoPago>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoMetodoPago>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 15:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoMoneda>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoMoneda>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 16:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTiposFactura>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTiposFactura>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 17:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaTipoPuntoVenta>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaTipoPuntoVenta>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;
                case 18:                              
                    $xmlData = <<<EOD
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siat="https://siat.impuestos.gob.bo/">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <siat:sincronizarParametricaUnidadMedida>
                                <SolicitudSincronizacion>
                                <codigoAmbiente>$codigoAmbiente</codigoAmbiente>
                                <codigoPuntoVenta>$codigoPuntoVenta</codigoPuntoVenta>
                                <codigoSistema>$codigoSistema</codigoSistema>
                                <codigoSucursal>$codigoSucursal</codigoSucursal>
                                <cuis>$cuis</cuis>
                                <nit>$nit</nit>
                                </SolicitudSincronizacion>
                            </siat:sincronizarParametricaUnidadMedida>
                        </soapenv:Body>
                    </soapenv:Envelope>
                    EOD;
                break;

            default:
                return "Opción no válida";
        }

        return $xmlData;
    }

    public function query_($n){
        $query="";
        switch ($n) {
            case 1:
                $query = DB::table('excel__emision')->where('id_catalogo', 15)->get();
                break;
            case 2:
                $query = Carbon::now()->format('Y-m-d H:i:s');
                break;
            case 3:   
                $query = DB::table('excel__emision')->where('id_catalogo', 3)->get();
                break;
            case 4:
                $query = DB::table('excel__emision')->where('id_catalogo', 11)->whereNotNull('id_erp')->get();
                break;
            case 5:
                $query = DB::table('excel__emision')->where('id_catalogo', 5)->get();
                break; 
            case 6:
                $query = DB::table('excel__emision')->where('id_catalogo', 16)->get();
                break;   
            case 7:
                $query = DB::table('excel__emision')->where('id_catalogo', 6)->get();
                break;
            case 8:
                $query = DB::table('excel__emision')->where('id_catalogo', 4)->get();
                break; 
            case 9:
                $query = DB::table('excel__emision')->where('id_catalogo', 10)->get();
                break; 
            case 10:
                $query = DB::table('excel__emision')->where('id_catalogo', 7)->get();
                break;
            case 11:
                $query = DB::table('excel__emision')->where('id_catalogo', 3)->whereNull('id_erp')->get();
                break;   
            case 12:
                $query = DB::table('excel__emision')->where('id_catalogo', 1)->get();
                break;  
            case 13:
                $query = DB::table('excel__emision')->where('id_catalogo', 14)->get();
                break;
            case 14:
                $query = DB::table('excel__emision')->where('id_catalogo', 8)->get();
                break;  
            case 15:
                $query = DB::table('excel__emision')->where('id_catalogo', 9)->get();
                break;
            case 16:
                $query = DB::table('excel__emision')->where('id_catalogo', 2)->get();
                break;
            case 17:
                $query = DB::table('excel__emision')->where('id_catalogo', 13)->get();
                break;  
            case 18:
                $query = DB::table('excel__emision')->where('id_catalogo', 12)->get();
                break;                                                 
            default:
                      
                break;
        }
        return $query;
    }

   public function operacionSincro($n,$response){
        $respuesta="";
        switch ($n) {
            case 0:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
             
                // Usar XPath para encontrar el nodo <transaccion>    
                $transaccion = $xml->xpath('//transaccion');
                if ($transaccion && isset($transaccion[0])) {
                    if ($transaccion[0]== 'true') {                     
                            $respuesta=0;
                    } else {
                        $respuesta=$response;
                    }                
            } else {   
                $respuesta="error en sicronización con siat";
            }   
             break;   
            case 1:
               // Crear un objeto DOMDocument para formatear el XML
//$dom = new DOMDocument();
//$dom->preserveWhiteSpace = false;
//$dom->formatOutput = true;
//$dom->loadXML($response);

// Mostrar el XML formateado
//header('Content-Type: text/xml');
//echo $dom->saveXML();
  // Convertir la respuesta en un objeto SimpleXMLElement
  
  // Convertir la respuesta en un objeto SimpleXMLElement
  $xml = simplexml_load_string($response);
               
  // Usar XPath para encontrar el nodo <transaccion>
  $transaccion = $xml->xpath('//transaccion');

if ($transaccion && isset($transaccion[0])) {
    
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividades/codigoCaeb"); 
           $descripciones = $xml->xpath("//listaActividades/descripcion");
            $tamaño=count($codigos);
                      
                $data_query = $this->query_($n);  
               
             
                $controlador_12=0;
               $existe_1=0;   
             $existe_2=0;  

                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {
                             $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }    

                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO ACTIVIDADES:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                } 
        } else {
            $respuesta=$response;
        }    
   
} else {   
   
    $respuesta="error en sicronización de actividad";
}   
                break;
            
            case 2:
                   // Convertir la respuesta en un objeto SimpleXMLElement
                   $xml = simplexml_load_string($response);     
                       
                   // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');     
                if ($transaccion && isset($transaccion[0])) {
                    if ($transaccion[0]== 'true') {
                        // Extraer y decodificar las descripciones de actividades
                        $codigos = $xml->xpath("//fechaHora"); 
                    // Obtener el primer resultado y convertirlo a string
$fechaTexto = (string) $codigos[0];

// Convertir la fecha extraída a Carbon
$fecha1 = Carbon::parse($fechaTexto); // Convierte el string a una fecha Carbon
$fechaHoy = Carbon::now(); // Obtiene la fecha de hoy como Carbon
// Truncar a segundos
$fecha1Truncada = $fecha1->format('Y-m-d\TH:i:s');
$fecha2Truncada = $fechaHoy->format('Y-m-d\TH:i:s');
$minutos_1 = intval($fecha1->format('i')); // Extrae solo los minutos
$minutos_2 = intval($fechaHoy->format('i')); // Extrae solo los minutos
$respuesta_minutos = abs($minutos_1 - $minutos_2); // Convierte a positivo             

                        $time="";   
                        
                        
                            $data_query = $this->query_($n);     
                            if ($fecha1Truncada==$fecha2Truncada) {
                                $time=0;
                            } else {
                                if ($respuesta_minutos == 0 || $respuesta_minutos == 1)  {
                                    $time=0;
                                }else{
                                    $time= "Las fechas son diferentes.";
                                }                              
                            }
                            $respuesta=$time;
                    } else {
                        $respuesta="error del siat de sincronización";
                    }    
               
            } else {   
                $respuesta="error en sicronización de de fecha y hora";
            }   
            break;   
            
            case 3:
                // Convertir la respuesta en un objeto SimpleXMLElement
                $xml = simplexml_load_string($response);
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');
            
if ($transaccion && isset($transaccion[0])) {
        if ($transaccion[0]== 'true') {
            // Extraer y decodificar las descripciones de actividades
            $codigos = $xml->xpath("//listaActividadesDocumentoSector/codigoDocumentoSector"); 
            $tipoDocumentoSector = $xml->xpath("//listaActividadesDocumentoSector/tipoDocumentoSector"); 
          
            $tamaño=count($codigos);
            $data_query = $this->query_($n); 

             $controlador_12=0;              
             $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }  
                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO SECTOR:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($tipoDocumentoSector[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                }          
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sicronización de sector";
}  
            break;

            case 4:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                   
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaLeyendas/codigoActividad");                         
                            $descrip = $xml->xpath("//listaLeyendas/descripcionLeyenda");                           
                            $tamaño_1=count($codigos); 
                            $tamaño_2=count($descrip);                     
                            $data_query = $this->query_($n);  


                            $controlador_12=0;              
  $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->id_erp==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                }   
                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO LEYENDA:";
                    for ($i=0; $i <$tamaño_1 ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descrip[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." id_erp: ".$dato_12." descripcion: ".$dato_13;
                    }
                    
               $respuesta=$cadenaA;
                }                    
                                   
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sicronización de leyenda de factura";}  
            break;

            case 5:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                   
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripciones = $xml->xpath("//listaCodigos/descripcion");   
                                                                         
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n); 
                            
                             $controlador_12=0;
                $existe_1=0;   
             $existe_2=0; 

                foreach ($codigos as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                         if ($data->codigo==$dato) {                            
                            $existe_1=1;                           
                            break;
                        }
                    }
                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                } 
                 if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar MENSAJE SERVICIO:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      
                      $dato_13= html_entity_decode($descripciones[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                }       
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sicronización de mensaje servicio";
}  
            break;
            case 6:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                    
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoActividad");        
                            $codigos_2 = $xml->xpath("//listaCodigos/codigoProducto");    
                            $codigos_3 = $xml->xpath("//listaCodigos/descripcionProducto");                                            
                            $tamaño=count($codigos_2);                                                                   
                            $data_query = $this->query_($n); 

                        $controlador_12=0;               
  $existe_1=0;   
             $existe_2=0;  
                foreach ($codigos_2 as $key_0 => $codigo) {
                     $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                    foreach ($data_query as $key_1 => $data) {
                        if ($data->codigo==$dato) {
                            $existe_1=1;                           
                            break;
                        }
                    }
                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                } 

                if ($existe_2==1) {
                    $respuesta=0;
                }else{
                    $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO TIPO ACTIVIDADES DOCUMENTO SECTOR:";
                    for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos_2[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');
                      $dato_14= html_entity_decode($codigos_3[$i], ENT_QUOTES, 'UTF-8');
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_14, 'UTF-8')." id_erp: ".$dato_13;
                    }
                    
               $respuesta=$cadenaA;
                } 
     
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sicronización de sincronizarListaProductosServicios";
}  
            break;
            case 7:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                 
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);      
                            $existe_1=0;   
             $existe_2=0;                 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                         $existe_1=1;                           
                            break;
                                     }
                                   
                                }
                                  if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                       
                             
                            }else{
                                  $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO EVENTO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error del siat de sincronizarParametricaEventosSignificativos";
}  
            break;
            case 8:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
              
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");   
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");                                              
                            $tamaño=count($codigos);    
                            $existe_1=0;   
             $existe_2=0;                                         
                            $data_query = $this->query_($n);                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0; 
                             
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar CODIGO EVENTO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaMotivoAnulacion";
}  
            break;
            case 9:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                    
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");  
                                                                          
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n); 
                            $existe_1=0;   
             $existe_2=0;                     
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $existe_1=1;                           
                            break;
                                     }
                                }
                                if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0; 
                             
                            }else{
                             $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO ORIGEN:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaPaisOrigen";
}  
            break;
            case 10:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
      
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");   
                                                                          
                            $tamaño=count($codigos);                                          
                            $data_query = $this->query_($n);   
                               $existe_1=0;   
             $existe_2=0;                          
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                              
                            }else{
                               $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO IDENTIDAD:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoDocumentoIdentidad";
}  
            break;
            case 11:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
      
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");   
                              $descripcion = $xml->xpath("//listaCodigos/descripcion");   
                                                              
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                  $existe_1=0;   
             $existe_2=0;    
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                        $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                     $respuesta=0;  
                             
                            }else{
                             $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO SECTOR:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoDocumentoSector";
}  
            break;
            case 12:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
         
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripcion = $xml->xpath("//listaCodigos/descripcion");  
                                                                           
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                 $respuesta=0;                            
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO EMISION:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoEmision";
}  
            break;
            case 13:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                    
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                       $existe_1=1;                           
                            break;
                                     }
                                }
                                    if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;                           
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO HABILITACION:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoHabitacion";
}  
            break;
            case 14:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
             
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                      $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                     if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                   $respuesta=0;
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO PAGO:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }    
   
} else {   
    $respuesta="error en sincronizarParametricaTipoMetodoPago";
}  
            break;
            case 15:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                 
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                 $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                   $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                               $respuesta=0;
                            }else{
                                 $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO MONEDA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }       
} else {   
    $respuesta="error en sincronizarParametricaTipoMoneda";
}  
            break;
            case 16:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                   
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador");  
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                           
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                     $existe_1=1;                           
                            break;
                                     }
                                }
                                 if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                  $respuesta=0;                             
                            }else{
                                   $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO DOCUMENTO FISCAL:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }       
} else {   
    $respuesta="error en sincronizarParametricaTiposFactura";
}  
            break;
            case 17:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
                
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                             $descripcion = $xml->xpath("//listaCodigos/descripcion");
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                      $existe_1=1;                           
                            break;
                                     }
                                }
                                   if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO PUNTO VENTA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }       
} else {   
    $respuesta="error en sincronizarParametricaTipoPuntoVenta";
}  
            break;
            case 18:
                // Convertir la respuesta en un objeto SimpleXMLElement                 
                $xml = simplexml_load_string($response);              
                // Usar XPath para encontrar el nodo <transaccion>
                $transaccion = $xml->xpath('//transaccion');  
             
                    if ($transaccion && isset($transaccion[0])) {
                        if ($transaccion[0]== 'true') {
                            // Extraer y decodificar las descripciones de actividades
                            $codigos = $xml->xpath("//listaCodigos/codigoClasificador"); 
                            $descripcion = $xml->xpath("//listaCodigos/descripcion"); 
                                                                            
                            $tamaño=count($codigos);                                                               
                            $data_query = $this->query_($n); 
                         $existe_1=0;   
             $existe_2=0; 
                            foreach ($codigos as  $codigo) {
                                foreach ($data_query as  $query) {
                                 $dato= html_entity_decode($codigo, ENT_QUOTES, 'UTF-8');
                                     if ($dato==$query->codigo) {
                                     $existe_1=1;                           
                            break;
                                     }
                                }
                                  if ($existe_1==0) {
                        $existe_2=0;
                        break;
                    }else{                   
                      $existe_2=1;
                      $existe_1=0;
                    }
                             }
                            if($existe_2==1){
                                $respuesta=0;                           
                            }else{
                                $cadenaA="Debe editar el documento excel debe entrar SIAT administración en la pestaña sincronizacion SIAT y descargar editar TIPO UNIDAD DE MEDIDA:";
                               for ($i=0; $i <$tamaño ; $i++) { 
                      $dato_12= html_entity_decode($codigos[$i], ENT_QUOTES, 'UTF-8');                      
                      $dato_13= html_entity_decode($descripcion[$i], ENT_QUOTES, 'UTF-8');
                    
                      $cadenaA=$cadenaA." codigo: ".$dato_12." descripcion: ".mb_strtoupper($dato_13, 'UTF-8');
                    }
                    
               $respuesta=$cadenaA;
                            }        
        } else {
            $respuesta=$response;
        }       
} else {   
    $respuesta="error en sincronizarParametricaUnidadMedida";
}  
            break;
            default:
                # code...
                break;

                


        }
        return $respuesta;
      
    }


}