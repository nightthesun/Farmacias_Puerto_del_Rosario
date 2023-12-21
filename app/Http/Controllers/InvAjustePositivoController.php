<?php

namespace App\Http\Controllers;

use App\Models\Inv_AjustePositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;

class InvAjustePositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();
       
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
                                aan.codigo like '%".$valor."%' 
                                or aan.linea like '%".$valor."%' 
                                or aan.producto like '%".$valor."%'
                                or pte.nombre like '%".$valor."%' 
                                or aan.descripcion like '%".$valor."%'
                                or ass.razon_social like '%".$valor."%'
                                or aan.cod like '%".$valor."%' 
                               )";
                    }
                    else
                    {
                        $sqls.="and (aan.codigo like '%".$valor."%' 
                        or aan.linea like '%".$valor."%' 
                        or aan.producto like '%".$valor."%' 
                        or pte.nombre like '%".$valor."%' 
                        or aan.descripcion like '%".$valor."%'
                        or ass.razon_social like '%".$valor."%'
                        or aan.cod like '%".$valor."%' 
                       )";
                    }
    
                }
                    $query_ajuste_positivo = DB::table('inv__ajuste_positivos as aan')
                        ->join('prod__tipo_entradas as pte', 'aan.id_tipo', '=', 'pte.id')
                        ->join('adm__sucursals as ass', 'aan.id_sucursal', '=', 'ass.id')
                        ->select('aan.id as id',
                        'aan.id_producto_linea as id_producto_linea',
                        'aan.usuario as nombre_usuario',
                        'aan.producto as nombreProd',
                        'aan.codigo as codigo',
                        'aan.linea as linea',
                        'aan.cantidad as cantidad',
                        'aan.stock as stock',
                        'aan.lote as lote',
                        'pte.nombre as nombreTipo',
                        'aan.fecha_vencimiento as fecha_vencimiento',
                        'aan.fecha_ingreso as fecha_ingreso',
            
                       
                        'aan.id_sucursal as id_sucursal',
                        'ass.razon_social as razon_social',
                        'aan.created_at as fecha_creacion',
                        'aan.activo as activo',
                        'aan.cod as cod',
                        'aan.id_ingreso as id_ingreso')
                        ->whereRaw($sqls)
                        ->paginate(15);
                        
            }
      
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $query_ajuste_positivo->total(),
                            'current_page'  =>    $query_ajuste_positivo->currentPage(),
                            'per_page'      =>    $query_ajuste_positivo->perPage(),
                            'last_page'     =>    $query_ajuste_positivo->lastPage(),
                            'from'          =>    $query_ajuste_positivo->firstItem(),
                            'to'            =>    $query_ajuste_positivo->lastItem(),
                        ] ,
                    'query_ajuste_positivo'=>$query_ajuste_positivo,
            ];
        }else {

            $query_ajuste_positivo = DB::table('inv__ajuste_positivos as aan')
            ->join('prod__tipo_entradas as pte', 'aan.id_tipo', '=', 'pte.id')
            ->join('adm__sucursals as ass', 'aan.id_sucursal', '=', 'ass.id')
            ->select('aan.id as id',
            'aan.id_producto_linea as id_producto_linea',
            'aan.id_tipo as id_tipo',
            'aan.usuario as nombre_usuario',
            'aan.producto as nombreProd',
            'aan.codigo as codigo',
            'aan.linea as linea',
            'aan.cantidad as cantidad',
            'aan.stock as stock',
            'aan.lote as lote',
            'pte.nombre as nombreTipo',
            'aan.fecha_vencimiento as fecha_vencimiento',
            'aan.fecha_ingreso as fecha_ingreso',
            'aan.id_sucursal as id_sucursal',
            'ass.razon_social as razon_social',
            'aan.created_at as fecha_creacion',
            'aan.activo as activo',
            'aan.cod as cod',
            'aan.id_ingreso as id_ingreso')
            //->whereraw($sqls)
            ->paginate(15);
            return [
                    'pagination'=>[
                        'total'         =>    $query_ajuste_positivo->total(),
                        'current_page'  =>    $query_ajuste_positivo->currentPage(),
                        'per_page'      =>    $query_ajuste_positivo->perPage(),
                        'last_page'     =>    $query_ajuste_positivo->lastPage(),
                        'from'          =>    $query_ajuste_positivo->firstItem(),
                        'to'            =>    $query_ajuste_positivo->lastItem(),
                    ] ,
                    'query_ajuste_positivo'=>$query_ajuste_positivo,
               ];
        }
        

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
    public function store(Request $request, Inv_AjustePositivo $inv_AjustePositivo,Alm_IngresoProducto $alm_IngresoProducto,Tda_IngresoProducto $tda_IngresoProducto)
    {
        $cod=$request->cod;
        $id_ingreso=$request->id_ingreso;
        $activador=0;
        $almacenIngreso = DB::table('alm__ingreso_producto as ai')
        ->join('alm__almacens as aa', 'ai.idalmacen', '=', 'aa.id')
        ->where('ai.id', '=', $id_ingreso)
        ->where('aa.codigo', '=', $cod)
        ->select('ai.id as id', 'aa.codigo as codigo')
        ->first();
        $tinedaIngreso = DB::table('tda__ingreso_productos as ti')
        ->join('tda__tiendas as tt', 'ti.idtienda', '=', 'tt.idsucursal')
        ->where('ti.id', '=', $id_ingreso)
        ->where('tt.codigo', '=', $cod)
        ->select('ti.id as id', 'tt.codigo as codigo')
        ->first();
        if ($almacenIngreso) {
            $activador=1;  
            $id = $almacenIngreso->id;
            $codigo = $almacenIngreso->codigo;          
        } else {
            if ($tinedaIngreso) {
                $activador=2; 
                $id = $tinedaIngreso->id;
                $codigo = $tinedaIngreso->codigo;
            } else {
                $activador=0; 
            }
            
        }
        
        if ($activador==1) {
            $ajusteNegativo=new Inv_AjustePositivo();
            $ajusteNegativo->id_usuario = auth()->user()->id;
            $ajusteNegativo->usuario = auth()->user()->name;
            $ajusteNegativo->id_usuario_registra = auth()->user()->id;
            $ajusteNegativo->id_tipo=$request->id_tipo;
            $ajusteNegativo->id_producto_linea=$request->id_producto_linea;
            $ajusteNegativo->codigo=$request->codigo;
            $ajusteNegativo->linea=$request->linea;
            $ajusteNegativo->producto=$request->producto;
            $ajusteNegativo->cantidad=$request->cantidad;
            $ajusteNegativo->stock=$request->stock;
           // $ajusteNegativo->descripcion=$request->descripcion;
            $ajusteNegativo->fecha_ingreso=$request->fecha_ingreso;
            $ajusteNegativo->fecha_vencimiento=$request->fecha_vencimiento;
            $ajusteNegativo->lote=$request->lote;
            $ajusteNegativo->activo=$request->activo;
            $ajusteNegativo->id_sucursal=$request->id_sucursal;
            $ajusteNegativo->cod=$codigo;
            $ajusteNegativo->id_ingreso=$id;
            $update=Alm_IngresoProducto::find($id);
            $update->stock_ingreso=$request->stock;
            $update->save();
           $ajusteNegativo->save();

        }else {
            if ($activador==2) {
                $ajusteNegativo=new Inv_AjustePositivo();
                $ajusteNegativo->id_usuario = auth()->user()->id;
                $ajusteNegativo->usuario = auth()->user()->name;
                $ajusteNegativo->id_usuario_registra = auth()->user()->id;
                $ajusteNegativo->id_tipo=$request->id_tipo;
                $ajusteNegativo->id_producto_linea=$request->id_producto_linea;
                $ajusteNegativo->codigo=$request->codigo;
                $ajusteNegativo->linea=$request->linea;
                $ajusteNegativo->producto=$request->producto;
                $ajusteNegativo->cantidad=$request->cantidad;
                $ajusteNegativo->stock=$request->stock;
               // $ajusteNegativo->descripcion=$request->descripcion;
               $ajusteNegativo->fecha_ingreso=$request->fecha_ingreso;
               $ajusteNegativo->fecha_vencimiento=$request->fecha_vencimiento;
               $ajusteNegativo->lote=$request->lote;
                $ajusteNegativo->activo=$request->activo;
                $ajusteNegativo->id_sucursal=$request->id_sucursal;
                $ajusteNegativo->cod=$codigo;
                $ajusteNegativo->id_ingreso=$id;
                $update=Tda_IngresoProducto::find($id);
                $update->stock_ingreso=$request->stock;
                $update->save();
               $ajusteNegativo->save();
            } else {
                dd("error");
            }
            
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inv_AjustePositivo $inv_AjustePositivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inv_AjustePositivo $inv_AjustePositivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inv_AjustePositivo $inv_AjustePositivo)
    {
        $cod=$request->cod;
        $id_ingreso=$request->id_ingreso;
        $activador=0;

        $almacenIngreso = DB::table('alm__ingreso_producto as ai')
        ->join('alm__almacens as aa', 'ai.idalmacen', '=', 'aa.id')
        ->where('ai.id', '=', $id_ingreso)
        ->where('aa.codigo', '=', $cod)
        ->select('ai.id as id', 'aa.codigo as codigo')
        ->first();
        $tinedaIngreso = DB::table('tda__ingreso_productos as ti')
        ->join('tda__tiendas as tt', 'ti.idtienda', '=', 'tt.idsucursal')
        ->where('ti.id', '=', $id_ingreso)
        ->where('tt.codigo', '=', $cod)
        ->select('ti.id as id', 'tt.codigo as codigo')
        ->first();
        if ($almacenIngreso) {
            $activador=1;  
            $id_i = $almacenIngreso->id;
            $codigo = $almacenIngreso->codigo;          
        } else {
            if ($tinedaIngreso) {
                $activador=2; 
                $id_i = $tinedaIngreso->id;
                $codigo = $tinedaIngreso->codigo;
            } else {
                $activador=0; 
            }
            
        }
        if ($activador==1) {
            $updateAjusteNegativo=Inv_AjustePositivo::find($request->id);
            $updateAjusteNegativo->id_usuario_modifica= auth()->user()->id;
            $updateAjusteNegativo->usuario = auth()->user()->name;
           $updateAjusteNegativo->id_tipo=$request->id_tipo;
           $updateAjusteNegativo->id_producto_linea=$request->id_producto_linea;
           $updateAjusteNegativo->codigo=$request->codigo;
           $updateAjusteNegativo->linea=$request->linea;
          $updateAjusteNegativo->producto=$request->producto;
            $updateAjusteNegativo->cantidad=$request->cantidad;           
          // $updateAjusteNegativo->descripcion=$request->descripcion;
            $updateAjusteNegativo->fecha=$request->fecha;
           $updateAjusteNegativo->id_sucursal=$request->id_sucursal;
            $updateAjusteNegativo->cod=$codigo;
            $updateAjusteNegativo->id_ingreso=$id_i;
            $update=Alm_IngresoProducto::find($id_i);
           
            $update->stock_ingreso=$request->cantidad;
           $update->save();
           $updateAjusteNegativo->save();
           
        
        }else{
            if ($activador==2) {
                $updateAjusteNegativo=Inv_AjustePositivo::find($request->id);
                $updateAjusteNegativo->id_usuario_modifica= auth()->user()->id;
            $updateAjusteNegativo->usuario = auth()->user()->name;
           $updateAjusteNegativo->id_tipo=$request->id_tipo;
           $updateAjusteNegativo->id_producto_linea=$request->id_producto_linea;
           $updateAjusteNegativo->codigo=$request->codigo;
           $updateAjusteNegativo->linea=$request->linea;
          $updateAjusteNegativo->producto=$request->producto;
            $updateAjusteNegativo->cantidad=$request->cantidad;           
          // $updateAjusteNegativo->descripcion=$request->descripcion;
            $updateAjusteNegativo->fecha=$request->fecha;
           $updateAjusteNegativo->id_sucursal=$request->id_sucursal;
            $updateAjusteNegativo->cod=$codigo;
            $updateAjusteNegativo->id_ingreso=$id_i;
                $update=Tda_IngresoProducto::find($id_i);
             
                $update->stock_ingreso=$request->cantidad;
                $update->save();
               $updateAjusteNegativo->save();
            } else {
                echo "error..";
                
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inv_AjustePositivo $inv_AjustePositivo)
    {
        //
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
        DB::raw('null as id_tienda'),
        'ai.idalmacen as id_almacen'
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
        'ti.idtienda as id_tienda',
        DB::raw('null as id_almacen')
      );

  $result = $productos->unionAll($tiendas)->get();

  return $result;
       
    }

    public function listarSucursal(){
       
  
        $tiendas = DB::table('tda__tiendas')
     ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal', DB::raw('"Tienda" as tipoCodigo'))
     ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');
 
 $almacenes = DB::table('alm__almacens as aa')
     ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
     ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', DB::raw('"Almacen" as tipoCodigo'));
 
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
         'tipoCodigo' =>$sucursal->tipoCodigo,
     ];
 
     $jsonSucrusal[] = $elemento;
 }
 
     return $jsonSucrusal;
     
     }
     public function listarTipo(){
        $productoTipo = DB::table('prod__tipo_entradas')
        ->select(DB::raw('MIN(id) as id'), 'nombre')
        ->whereNotIn('id', [13])
        ->groupBy('nombre')
        ->get();
        return $productoTipo;
    }
    public function desactivar(Request $request){
        $updateAjusteNegativo = Inv_AjustePositivo::findOrFail($request->id);
        $updateAjusteNegativo->activo=0;
        $updateAjusteNegativo->id_usuario_modifica= auth()->user()->id;
        $updateAjusteNegativo->save();
    }
    public function activar(Request $request){
        $updateAjusteNegativo = Inv_AjustePositivo::findOrFail($request->id);
        $updateAjusteNegativo->activo=1;
        $updateAjusteNegativo->id_usuario_modifica= auth()->user()->id;
        $updateAjusteNegativo->save();
    }
    public function retornarProductosIngreso(Request $request)
    {
        $cod = $request->query('respuesta0');
        $buscar = $request->query('respuesta1');
        $buscararray=array();
       
        if(!empty($buscar))
        {
            $buscararray = explode(" ",$buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(
                                pp.codigointernacional like '%".$valor."%' 
                                or pp.nombre like '%".$valor."%' 
                               
                                or pp.codigo like '%".$valor."%'
                            
                               )";
                    }
                    else
                    {
                        $sqls.="and ( pp.codigointernacional like '%".$valor."%' 
                        or pp.nombre like '%".$valor."%' 
                  
                        or pp.codigo like '%".$valor."%' 
                       )";
                    }
    
                }
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
                ->whereRaw($sqls)
                ->select(
                  'pp.codigointernacional as codigointernacional',
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
                  DB::raw('null as id_tienda'),
                  'ai.idalmacen as id_almacen'
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
                ->whereRaw($sqls)
                ->select(
                  'pp.codigointernacional as codigointernacional',
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
                  'ti.idtienda as id_tienda',
                  DB::raw('null as id_almacen')
                );
          
            $result = $productos->unionAll($tiendas)->get();
                        
            }
      
            return $result;
        }else {

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
              DB::raw('null as id_tienda'),
              'ai.idalmacen as id_almacen'
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
              'ti.idtienda as id_tienda',
              DB::raw('null as id_almacen')
            );
      
        $result = $productos->unionAll($tiendas)->get();
     
        return $result;
        }
        
      
     
        
}
}
