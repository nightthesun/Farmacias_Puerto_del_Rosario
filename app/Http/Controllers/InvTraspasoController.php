<?php

namespace App\Http\Controllers;

use App\Models\Inv_Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;

class InvTraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inv_Traspaso $inv_Traspaso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inv_Traspaso $inv_Traspaso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inv_Traspaso $inv_Traspaso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inv_Traspaso $inv_Traspaso)
    {
        //
    }
    public function listarSucursal(){
       
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'),'tda__tiendas.id as lista_id_almacen_id_tienda')
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'),'aa.id  as lista_id_almacen_id_tienda');

    $result = $tiendas->unionAll($almacenes)->get();
 
 
         $jsonSucrusal = [];
 
 foreach ($result as $key=>$sucursal) {
     $elemento = [
         'id' => $key,
         'id_tienda' => $sucursal->id_tienda,
         'id_almacen' => $sucursal->id_almacen,
         'codigo' => $sucursal->codigo,
         'razon_social' => $sucursal->razon_social,
         'sucursal' => $sucursal->sucursal,
         'codigoS' => $sucursal->codigoS,
         'tipoCodigo' =>$sucursal->tipoCodigo,
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $jsonSucrusal;
     
     }

     public function listarProductoLineaIngreso(Request $request)
     {
       $cod = $request->query('respuesta0');
       
       $productos = DB::table('prod__productos as pp')
       ->join('alm__ingreso_producto as ai', 'pp.id', '=', 'ai.id_prod_producto')
       ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
       ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
       ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
       ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
       ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
       ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
       ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
       ->join('alm__almacens as aa', 'aa.id', '=', 'ai.idalmacen')
       ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
       ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
       ->where('ai.stock_ingreso', '>', 0)
       ->where('aa.codigo', $cod) 
       ->select(
         'pp.codigointernacional as codigointernacional',
         'ai.registro_sanitario as registro_sanitario',
         'ai.envase as envase',        
         'aa.codigo as cod',
         'ai.id as id_ingreso',
         'ai.id_prod_producto as id_producto',
         'ai.lote as lote',
         'ai.stock_ingreso as stock_ingreso',
         'ai.cantidad as cantidad_ingreso',
         'ai.created_at as fecha_ingreso',
         'ai.fecha_vencimiento as fecha_vencimiento',
         'pp.nombre as nombre',
         'pp.codigo as codigo_producto',
         'pp.cantidadprimario as cantidad_dispenser_p',
         'pp.cantidadsecundario as cantidad_dispenser_s',
         'pp.cantidadterciario as cantidad_dispenser_t',
         'l.nombre as nombre_linea',
         'pd_1.nombre as nombre_dispenser_1',
         'pd_2.nombre as nombre_dispenser_2',
         'pd_3.nombre as nombre_dispenser_3',
         'ff_1.nombre as nombre_farmaceutica_1',
         'ff_2.nombre as nombre_farmaceutica_2',
         'ff_3.nombre as nombre_farmaceutica_3',
         'ass.id AS id_sucursal',
         'ass.razon_social as razon_social',
         'ai.idalmacen as id_almacen_tienda',
         DB::raw('"Almacen" as tipoCodigo'),
         DB::raw('null as id_tienda'),
         'ai.idalmacen as id_almacen',
         'aa.nombre_almacen as razon_social',       
         DB::raw("
             CASE
                 WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' - ', COALESCE(ff_1.nombre, ''))
                 WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' - ', COALESCE(ff_2.nombre, ''))
                 WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' - ', COALESCE(ff_3.nombre, ''))
                 ELSE NULL
             END AS leyenda
         ")
     );
 
   $tiendas = DB::table('prod__productos as pp')
   ->join('tda__ingreso_productos as ti', 'pp.id', '=', 'ti.id_prod_producto')
   ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
   ->leftJoin('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
   ->leftJoin('prod__dispensers as pd_2', 'pd_2.id', '=', 'pp.iddispensersecundario')
   ->leftJoin('prod__dispensers as pd_3', 'pd_3.id', '=', 'pp.iddispenserterciario')
   ->leftJoin('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
   ->leftJoin('prod__forma_farmaceuticas as ff_2', 'ff_2.id', '=', 'pp.idformafarmaceuticasecundario')
   ->leftJoin('prod__forma_farmaceuticas as ff_3', 'ff_3.id', '=', 'pp.idformafarmaceuticaterciario')
   ->join('adm__sucursals as ass', 'ass.id', '=', 'ti.idtienda')
   ->join('prod__lineas as l', 'l.id', '=', 'pp.idlinea')
   ->join('tda__tiendas as tt', 'tt.id', '=', 'ti.idtienda')
       ->where('ti.stock_ingreso', '>', 0)
       ->where('tt.codigo', $cod) 
       ->select(
         'pp.codigointernacional as codigointernacional',
         'ti.registro_sanitario as registro_sanitario',
         'ti.envase as envase',   
         'tt.codigo as cod',
         'ti.id as id_ingreso',
         'ti.id_prod_producto as id_producto',
         'ti.lote as lote',
         'ti.stock_ingreso as stock_ingreso',
         'ti.cantidad as cantidad_ingreso',
         'ti.created_at as fecha_ingreso',
         'ti.fecha_vencimiento as fecha_vencimiento',
         'pp.nombre as nombre',
         'pp.codigo as codigo_producto',
         'pp.cantidadprimario as cantidad_dispenser_p',
         'pp.cantidadsecundario as cantidad_dispenser_s',
         'pp.cantidadterciario as cantidad_dispenser_t',
         'l.nombre as nombre_linea',
         'pd_1.nombre as nombre_dispenser_1',
         'pd_2.nombre as nombre_dispenser_2',
         'pd_3.nombre as nombre_dispenser_3',
         'ff_1.nombre as nombre_farmaceutica_1',
         'ff_2.nombre as nombre_farmaceutica_2',
         'ff_3.nombre as nombre_farmaceutica_3',
         'ass.id AS id_sucursal',
         'ass.razon_social as razon_social',
         'ti.idtienda as id_almacen_tienda',
         DB::raw('"Tienda" as tipoCodigo'),
         'ti.idtienda as id_tienda',
         DB::raw('null as id_almacen'),
         'ass.razon_social as razon_social',
         DB::raw("
         CASE
             WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' X ', COALESCE(pp.cantidadprimario, ''), ' - ', COALESCE(ff_1.nombre, ''))
             WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' X ', COALESCE(pp.cantidadsecundario, ''), ' - ', COALESCE(ff_2.nombre, ''))
             WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' X ', COALESCE(pp.cantidadterciario, ''), ' - ', COALESCE(ff_3.nombre, ''))
             ELSE NULL
         END AS leyenda
     ")
       );
 
   $result = $productos->unionAll($tiendas)->get();
 
   return $result;
        
     }
     public function verificador(Request $request)
     {
        // las posibles combinaciones
        //11 almacen a almacen, 22 tienda a tienda, 12 almacen a tienda, 21 tienda a almcen
        $comb_1=0;
        $comb_2=0;
        $producto=$request->query('producto');
        $var="";
        $origen=$request->query('origen');
        $destino=$request->query('destino');
        $subOrigen = substr($origen, 0, 3);
        $subDestino = substr($destino, 0, 3);
        $number=0;
        while ($number < 2) {
                 if ($number==0) {
                    $var=$subOrigen; 
                    if ($var=="ALM") {
                        $comb_1=1;
                     } 
                     if ($var=="TDA") {
                        $comb_1=2; 
                     }     
                 }
                 if ($number==1) {
                    $var=$subDestino;
                    if ($var=="ALM") {
                        $comb_2=1;
                     } 
                     if ($var=="TDA") {
                        $comb_2=2; 
                     }  
                 }          
             $number++;
        }
       
        if ($comb_1==1){
            $almacen1 = DB::table('alm__ingreso_producto AS ai')
            ->join('alm__almacens AS aa', 'aa.id', '=', 'ai.idalmacen')
            ->join('prod__productos AS pp', 'ai.id_prod_producto', '=', 'pp.id')       
            ->where('aa.codigo', '=', $origen)
            ->where('pp.codigo', '=', $producto)
            ->select('aa.codigo as codigo', 'aa.nombre_almacen as nombre', 'pp.codigo as codigo_producto', 'ai.envase')
            ->get();
        }
        if ($comb_2==1){
            $almacen2 = DB::table('alm__ingreso_producto AS ai')
            ->join('alm__almacens AS aa', 'aa.id', '=', 'ai.idalmacen')
            ->join('prod__productos AS pp', 'ai.id_prod_producto', '=', 'pp.id')       
            ->where('aa.codigo', '=', $destino)
            ->where('pp.codigo', '=', $producto)
            ->select('aa.codigo as codigo', 'aa.nombre_almacen as nombre', 'pp.codigo as codigo_producto', 'ai.envase')
            ->get();
        }
        if ($comb_1==2) {
            $tienda1 = DB::table('tda__ingreso_productos as ti')
            ->join('tda__tiendas as tt', 'ti.idtienda', '=', 'tt.id')
            ->join('adm__sucursals as ass', 'tt.idsucursal', '=', 'ass.id')
            ->join('prod__productos as pp', 'ti.id_prod_producto', '=', 'pp.id')
            ->where('tt.codigo', '=', $origen)
            ->where('pp.codigo', '=', $producto)           
            ->select('tt.codigo as codigo', 'ass.razon_social as nombre', 'pp.codigo as codigo_producto', 'ti.envase')
            ->get();
        }
        if ($comb_2==2) {
            $tienda2 = DB::table('tda__ingreso_productos as ti')
            ->join('tda__tiendas as tt', 'ti.idtienda', '=', 'tt.id')
            ->join('adm__sucursals as ass', 'tt.idsucursal', '=', 'ass.id')
            ->join('prod__productos as pp', 'ti.id_prod_producto', '=', 'pp.id')
            ->where('tt.codigo', '=', $destino)
            ->where('pp.codigo', '=', $producto)           
            ->select('tt.codigo as codigo', 'ass.razon_social as nombre', 'pp.codigo as codigo_producto', 'ti.envase')
            ->get();
        }
        $jsonSucrusal =array();
        //////combinacion 11
        if ($comb_1==1 && $comb_2==1) {
           if ($almacen1->count() > 0) {
          
            foreach ($tienda1 as $key=>$alm) {
             $elemento = [      
            'codigo' => $alm->codigo,
            'nombre_almacen' => $alm->nombre,
            'codigo_producto' => $alm->codigo_producto,
            'almacen' => $origen,       
            ];
 
     $jsonSucrusal[] = $elemento;
 }
          
           }else{
            $elemento = [
      
                'codigo' => "",
                 'nombre_almacen' => "",
              'codigo_producto' => "",
              'almacen' => $origen,
            
          ];
          $jsonSucrusal[] = $elemento;
           }
           if ($almacen2->count() > 0) {
          
            foreach ($almacen2 as $key=>$alm) {
             $elemento = [      
            'codigo' => $alm->codigo,
            'nombre_almacen' => $alm->nombre,
            'codigo_producto' => $alm->codigo_producto,
            'almacen' => $destino,       
            ];
 
     $jsonSucrusal[] = $elemento;
 }
          
           }else{
            $elemento = [
      
                'codigo' => "",
                 'nombre_almacen' => "",
              'codigo_producto' => "",
              'almacen' => $destino,
            
          ];
          $jsonSucrusal[] = $elemento;
           }
            
        }
        //////combinacion 22
        if ($comb_1==2 && $comb_2==2) {
            if ($tienda1->count() > 0) {
           
             foreach ($tienda1 as $key=>$alm) {
              $elemento = [      
             'codigo' => $alm->codigo,
             'nombre_almacen' => $alm->nombre,
             'codigo_producto' => $alm->codigo_producto,
             'almacen' => $origen,       
             ];
  
      $jsonSucrusal[] = $elemento;
  }
           
            }else{
             $elemento = [
       
                 'codigo' => "",
                  'nombre_almacen' => "",
               'codigo_producto' => "",
               'almacen' => $origen,
             
           ];
           $jsonSucrusal[] = $elemento;
            }
            if ($tienda2->count() > 0) {
           
             foreach ($tienda2 as $key=>$alm) {
              $elemento = [      
             'codigo' => $alm->codigo,
             'nombre_almacen' => $alm->nombre,
             'codigo_producto' => $alm->codigo_producto,
             'almacen' => $destino,       
             ];
  
      $jsonSucrusal[] = $elemento;
  }
           
            }else{
             $elemento = [
       
                 'codigo' => "",
                  'nombre_almacen' => "",
               'codigo_producto' => "",
               'almacen' => $destino,
             
           ];
           $jsonSucrusal[] = $elemento;
            }
             
         }

         if ($comb_1==1 && $comb_2==2) {
            if ($almacen1->count() > 0) {
          
                foreach ($tienda1 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $origen,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $origen,
                
              ];
              $jsonSucrusal[] = $elemento;
               }
               if ($tienda1->count() > 0) {
           
                foreach ($tienda1 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $destino,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $destino,
                
              ];
              $jsonSucrusal[] = $elemento;
               }

         }
          //////combinacion 12
        if ($comb_1==1 && $comb_2==2) {
            if ($almacen1->count() > 0) {
          
                foreach ($tienda1 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $origen,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $origen,
                
              ];
              $jsonSucrusal[] = $elemento;
               }
               if ($tienda2->count() > 0) {
           
                foreach ($tienda2 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $destino,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $destino,
                
              ];
              $jsonSucrusal[] = $elemento;
               }
        }
           //////combinacion 21
           if ($comb_1==2 && $comb_2==1) {
            if ($tienda1->count() > 0) {
           
                foreach ($tienda1 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $origen,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $origen,
                
              ];
              $jsonSucrusal[] = $elemento;
               }
               if ($almacen2->count() > 0) {
          
                foreach ($almacen2 as $key=>$alm) {
                 $elemento = [      
                'codigo' => $alm->codigo,
                'nombre_almacen' => $alm->nombre,
                'codigo_producto' => $alm->codigo_producto,
                'almacen' => $destino,       
                ];
     
         $jsonSucrusal[] = $elemento;
     }
              
               }else{
                $elemento = [
          
                    'codigo' => "",
                     'nombre_almacen' => "",
                  'codigo_producto' => "",
                  'almacen' => $destino,
                
              ];
              $jsonSucrusal[] = $elemento;
               }
           }
           return $jsonSucrusal;

     }


}
