<?php

namespace App\Http\Controllers;

use App\Models\Inv_AjustePositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;
use Illuminate\Contracts\Session\Session;
use Psy\Command\WhereamiCommand;

class InvAjustePositivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        $buscararray = array();
        $bus = $request->query('buscarAlmTdn');

        $ini=$request->ini;
        $fini=$request->fini;
        if (auth()->user()->super_usuario == 0) {
            $user = auth()->user()->id; 
            $where = "(aan.cod = '$bus' and aan.id_usuario_registra = $user)";            
        } else {
            $where = "(aan.cod = '$bus')";  
        }

        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);

            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                aan.codigo like '%" . $valor . "%' 
                                or aan.linea like '%" . $valor . "%' 
                                or aan.producto like '%" . $valor . "%'
                                or pte.nombre like '%" . $valor . "%' 
                                or aan.descripcion like '%" . $valor . "%'
                                or ass.razon_social like '%" . $valor . "%'
                                or aan.cod like '%" . $valor . "%' 
                               )";
                    } else {
                        $sqls .= "and (aan.codigo like '%" . $valor . "%' 
                        or aan.linea like '%" . $valor . "%' 
                        or aan.producto like '%" . $valor . "%' 
                        or pte.nombre like '%" . $valor . "%' 
                        or aan.descripcion like '%" . $valor . "%'
                        or ass.razon_social like '%" . $valor . "%'
                        or aan.cod like '%" . $valor . "%' 
                       )";
                    }
                }
                $query_ajuste_negativos = DB::table('inv__ajuste_positivos as aan')
                    ->join('prod__tipo_entradas as pte', 'aan.id_tipo', '=', 'pte.id')
                    ->join('adm__sucursals as ass', 'aan.id_sucursal', '=', 'ass.id')
                    ->select(
                        'aan.id as id',
                        'aan.id_producto_linea as id_producto_linea',
                        'aan.usuario as nombre_usuario',
                        'aan.producto as nombreProd',
                        'aan.codigo as codigo',
                        'aan.linea as linea',
                        'aan.descripcion as descripcion',
                        'aan.cantidad as cantidad',
                        'aan.stock as stock',
                        'aan.lote as lote',
                        'aan.created_at as fecha_ingreso',
                        'aan.fecha_vencimiento as fecha_vencimiento',
                        'aan.id_tipo as id_tipo',
                        'pte.nombre as nombreTipo',
                        //'aan.fecha as fecha',
                         'aan.descripcion as tras',   
                        DB::raw('GREATEST(aan.created_at, aan.updated_at) as fecha'),
                        'aan.id_sucursal as id_sucursal',
                        'ass.razon_social as razon_social',
                        'aan.created_at as fecha_creacion',
                        'aan.activo as activo',
                        'aan.cod as cod',
                        'aan.id_ingreso as id_ingreso',
                        'aan.leyenda as leyenda'
                    )
                    ->whereRaw($where)
                    //->where('aan.cod', '=', $bus)
                    //->whereDate('aan.created_at', '>=', now()->subDays(30))
                    ->whereRaw($sqls)
                    ->orderByDesc('aan.id')
                    ->paginate(15);
            }

            return
                [
                    'pagination' =>
                    [
                        'total'         =>    $query_ajuste_negativos->total(),
                        'current_page'  =>    $query_ajuste_negativos->currentPage(),
                        'per_page'      =>    $query_ajuste_negativos->perPage(),
                        'last_page'     =>    $query_ajuste_negativos->lastPage(),
                        'from'          =>    $query_ajuste_negativos->firstItem(),
                        'to'            =>    $query_ajuste_negativos->lastItem(),
                    ],
                    'query_ajuste_negativos' => $query_ajuste_negativos,
                ];
        } else {

            $query_ajuste_negativos = DB::table('inv__ajuste_positivos as aan')
                ->join('prod__tipo_entradas as pte', 'aan.id_tipo', '=', 'pte.id')
                ->join('adm__sucursals as ass', 'aan.id_sucursal', '=', 'ass.id')
                ->select(
                    'aan.id as id',
                    'aan.id_producto_linea as id_producto_linea',
                    'aan.id_tipo as id_tipo',
                    'aan.usuario as nombre_usuario',
                    'aan.producto as nombreProd',
                    'aan.codigo as codigo',
                    'aan.linea as linea',
                  //  'aan.descripcion as descripcion',
                  
                  'aan.stock as stock',
                        'aan.lote as lote',
                        'aan.fecha_ingreso as fecha_ingreso',
                        'aan.fecha_vencimiento as fecha_vencimiento',
                  'aan.cantidad as cantidad',
                    'pte.nombre as nombreTipo',
                    DB::raw('GREATEST(aan.created_at, aan.updated_at) as fecha'),
                   //'aan.fecha as fecha',
                   'aan.descripcion as tras', 
                    'aan.id_sucursal as id_sucursal',
                    'ass.razon_social as razon_social',
                    'aan.created_at as fecha_creacion',
                    'aan.activo as activo',
                    'aan.cod as cod',
                    'aan.id_ingreso as id_ingreso',
                    'aan.leyenda as leyenda'
                )
                //->where('aan.cod', '=', $bus)
                //->whereDate('aan.created_at', '>=', now()->subDays(30))
                ->whereRaw($where)
                ->whereBetween(DB::raw('DATE(aan.created_at)'), [$ini, $fini]) 
                ->orderByDesc('aan.id')
                ->paginate(15);
            return [
                'pagination' => [
                    'total'         =>    $query_ajuste_negativos->total(),
                    'current_page'  =>    $query_ajuste_negativos->currentPage(),
                    'per_page'      =>    $query_ajuste_negativos->perPage(),
                    'last_page'     =>    $query_ajuste_negativos->lastPage(),
                    'from'          =>    $query_ajuste_negativos->firstItem(),
                    'to'            =>    $query_ajuste_negativos->lastItem(),
                ],
                'query_ajuste_negativos' => $query_ajuste_negativos,
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
        $primerGuardadoExitoso = false;
        try {
            // Iniciar una transacción
            DB::beginTransaction();
            $cod = $request->cod;
            $id_ingreso = $request->id_ingreso;
            $activador = 0;
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
                $activador = 1;
                $id = $almacenIngreso->id;
                $codigo = $almacenIngreso->codigo;
            } else {
                if ($tinedaIngreso) {
                    $activador = 2;
                    $id = $tinedaIngreso->id;
                    $codigo = $tinedaIngreso->codigo;
                } else {
                    $activador = 0;
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
                $ajusteNegativo->leyenda = $request->leyenda;
                $ajusteNegativo->save();

                $primerGuardadoExitoso = true;   
                  // Si llegamos aquí sin errores, confirmamos la transacción
                DB::commit();
                $update=Alm_IngresoProducto::find($id);
                $update->stock_ingreso=$request->stock;
                $update->save();
            
    //s
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
                    $ajusteNegativo->leyenda = $request->leyenda;
                    $ajusteNegativo->save();
                    $primerGuardadoExitoso = true;   
                      // Si llegamos aquí sin errores, confirmamos la transacción
                     DB::commit();
                    $update=Tda_IngresoProducto::find($id);
                    $update->stock_ingreso=$request->stock;
                    $update->save();
                 
                } else {
                    dd("error");
                }
                
            }
        } catch (\Throwable $th) {
            // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
            if ($primerGuardadoExitoso) {
                DB::rollback();
                // Eliminar el producto guardado
                $ajusteNegativo->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
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
      try {
        $update=Inv_AjustePositivo::find($request->id);
        $update->id_usuario_modifica= auth()->user()->id;
        $update->usuario = auth()->user()->name;
       $update->id_tipo=$request->id_tipo;
   
       $update->save();
   
      } catch (\Throwable $th) {

        return response()->json(['error' => $th->getMessage()],500);
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
      ->when($request->tipo == 1, function ($query) use ($cod) {
        $query->where('ai.stock_ingreso', '>', 0)
              ->where('aa.codigo','=', $cod)
              ->where('pp.idrubro','=',1)
              ->where('pp.activo','=',1);
        })
        ->when($request->tipo == 2, function ($query) use ($cod) {
        $query->where('aa.codigo','=', $cod)
              ->where('pp.idrubro','=',1)
              ->where('pp.activo','=',1);
        })
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
        'ai.idalmacen as id_almacen',
        DB::raw("
            CASE
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
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
  ->when($request->tipo == 1, function ($query) use ($cod) {
    $query->where('ti.stock_ingreso', '>', 0)
          ->where('tt.codigo', '=' ,$cod)
          ->where('pp.idrubro','=',1)
          ->where('pp.activo','=',1);
    })
    ->when($request->tipo == 2, function ($query) use ($cod) {
    $query->where('tt.codigo', '=' ,$cod)
          ->where('pp.idrubro','=',1)
          ->where('pp.activo','=',1);
    })   
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
        DB::raw('null as id_almacen'),
        DB::raw("
        CASE
            WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
            WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
            WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
            ELSE NULL
        END AS leyenda
    ")
      );

  $result = $productos->unionAll($tiendas)->get();

  return $result;
       
    }

    public function listarSucursal(){
        $idsucursal=session('idsuc');
        $nomsucursal=session('nomsucursal');
        
        $tiendas = DB::table('tda__tiendas')
        ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'))
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
        ->where('tda__tiendas.activo','=',1)
        ->where('adm__sucursals.activo','=',1);

        $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'))
        ->where('aa.activo','=',1)
        ->where('ass.activo','=',1);
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

     public function listarTipo(){
             //->whereNotIn('id', [13])
        $productoTipo = DB::table('prod__tipo_entradas')
        ->select(DB::raw('MIN(id) as id'), 'nombre')
       
        ->groupBy('nombre')
        ->get();
        return $productoTipo;
    }
    public function desactivar(Request $request){
       
        try {
         
            $activador = 0;
            $updateAjusteNegativo = Inv_AjustePositivo::findOrFail($request->id);
            $updateAjusteNegativo->activo=0;
            $cantidad = $updateAjusteNegativo->cantidad;
            $cod=$updateAjusteNegativo->cod;
            $id_ingreso=$updateAjusteNegativo->id_ingreso;
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
            $activador = 1;
            $id_i = $almacenIngreso->id;
            $codigo = $almacenIngreso->codigo;
        } else {
            if ($tinedaIngreso) {
                $activador = 2;
                $id_i = $tinedaIngreso->id;
                $codigo = $tinedaIngreso->codigo;
            } else {
                $activador = 0;
            }
        }
        if ($activador == 1) {
            $updateAjusteNegativo->activo = 0;
            $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
            $updateAjusteNegativo->id_usuario = auth()->user()->id;
            $updateAjusteNegativo->usuario = auth()->user()->name;
         
            $update = Alm_IngresoProducto::find($id_i);
            $update->stock_ingreso =($update->stock_ingreso)-$cantidad;
            $update->save();

            $updateAjusteNegativo->stock= $update->stock_ingreso; 
            $updateAjusteNegativo->save();
        } else {
            if ($activador == 2) {
            $updateAjusteNegativo->activo = 0;
            $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
            $updateAjusteNegativo->id_usuario = auth()->user()->id;
            $updateAjusteNegativo->usuario = auth()->user()->name;
            $update = Tda_IngresoProducto::find($id_i);
            $update->stock_ingreso =($update->stock_ingreso)-$cantidad;
            $update->save();
            $updateAjusteNegativo->stock= $update->stock_ingreso; 
            $updateAjusteNegativo->save();
            }
            else {
                echo "error..";
            }
        }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()],500);
        }
       
      
    }
    public function activar(Request $request){
        try {
            $activador = 0;
        $updateAjusteNegativo = Inv_AjustePositivo::findOrFail($request->id);
        $updateAjusteNegativo->activo=0;
        $cantidad = $updateAjusteNegativo->cantidad;
        $cod=$updateAjusteNegativo->cod;
        $id_ingreso=$updateAjusteNegativo->id_ingreso;
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
        $activador = 1;
        $id_i = $almacenIngreso->id;
        $codigo = $almacenIngreso->codigo;
    } else {
        if ($tinedaIngreso) {
            $activador = 2;
            $id_i = $tinedaIngreso->id;
            $codigo = $tinedaIngreso->codigo;
        } else {
            $activador = 0;
        }
    }
    if ($activador == 1) {
        $updateAjusteNegativo->activo = 1;
        $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
        $updateAjusteNegativo->id_usuario = auth()->user()->id;
        $updateAjusteNegativo->usuario = auth()->user()->name;
     
        $update = Alm_IngresoProducto::find($id_i);
        $update->stock_ingreso =($update->stock_ingreso)+$cantidad;
        $update->save();
        $updateAjusteNegativo->stock= $update->stock_ingreso; 
        $updateAjusteNegativo->save();
    } else {
        if ($activador == 2) {
        $updateAjusteNegativo->activo = 1;
        $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
        $updateAjusteNegativo->id_usuario = auth()->user()->id;
        $updateAjusteNegativo->usuario = auth()->user()->name;
        $update = Tda_IngresoProducto::find($id_i);
        $update->stock_ingreso =($update->stock_ingreso)+$cantidad;
        $update->save();
        $updateAjusteNegativo->stock= $update->stock_ingreso; 
        $updateAjusteNegativo->save();
        }
        else {
            echo "error..";
        }
    }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()],500);
        }
        
    }
    public function retornarProductosIngreso(Request $request)
    {
        $cod = $request->query('respuesta0');
       // $buscar = $request->query('respuesta1');
        $buscararray=array();
       
        if(!empty($request->respuesta1))
        {
            $buscararray = explode(" ",$request->respuesta1);
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
                ->when($request->tipo == 1, function ($query) use ($cod) {
                    $query->where('ai.stock_ingreso', '>', 0)
                          ->where('aa.codigo', $cod)
                          ->where('pp.idrubro','=',1)
                          ->where('pp.activo','=',1);
                    })
                    ->when($request->tipo == 2, function ($query) use ($cod) {
                    $query->where('aa.codigo', $cod)
                          ->where('pp.idrubro','=',1)
                          ->where('pp.activo','=',1);
                    })
               
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
                  'ai.idalmacen as id_almacen',
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
            ->when($request->tipo == 1, function ($query) use ($cod) {
                $query->where('ti.stock_ingreso', '>', 0)
                      ->where('tt.codigo', $cod)
                      ->where('pp.idrubro','=',1)
                      ->where('pp.activo','=',1);
                })
                ->when($request->tipo == 2, function ($query) use ($cod) {
                $query->where('aa.codigo', $cod)
                      ->where('pp.idrubro','=',1)
                      ->where('pp.activo','=',1);
                })
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
                  DB::raw('null as id_almacen'),
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
            ->where('pp.idrubro','=',1) 
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
              'ai.idalmacen as id_almacen',
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
            ->where('pp.idrubro','=',1) 
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
              DB::raw('null as id_almacen'),
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
       
    }

    ///retorno de stock en cero
    public function retornarProductosIngresoCero(Request $request)
    {
        $cod = $request->query('respuesta0');
       // $buscar = $request->query('respuesta1');
        $buscararray=array();
       
        if(!empty($request->respuesta1))
        {
            $buscararray = explode(" ",$request->respuesta1);
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
                ->where('ai.stock_ingreso', '=', 0)
                          ->where('aa.codigo', $cod)
                          ->where('pp.idrubro','=',1)
                          ->where('pp.activo','=',1)
           
                    
               
                ->whereRaw($sqls)
              
                ->select(
                    'ai.idalmacen as id_alm_tda',
                  'pp.codigointernacional as codigointernacional',
                  'ai.id_tipoentrada as id_tipoentrada',
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
                       DB::raw('null as id_tienda'),
                  'ai.idalmacen as id_almacen',
                  DB::raw("
                  CASE
                      WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                      WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                      WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
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
            ->where('ti.stock_ingreso', '=', 0)
                      ->where('tt.codigo', $cod)
                      ->where('pp.idrubro','=',1)
                      ->where('pp.activo','=',1)
             
               
                ->whereRaw($sqls)
               
                ->select(
                    'ti.idtienda as id_alm_tda',
                  'pp.codigointernacional as codigointernacional',
                  'ti.id_tipoentrada as id_tipoentrada',
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
                  'ti.idtienda as id_tienda',
                  DB::raw('null as id_almacen'),
                  DB::raw("
            CASE
                WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
                ELSE NULL
            END AS leyenda
        ")
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
            ->where('ai.stock_ingreso', '=', 0)
            ->where('aa.codigo', $cod) 
            ->where('pp.idrubro','=',1) 
            ->select(
                'ai.idalmacen as id_alm_tda',
              'pp.codigointernacional as codigointernacional',
              'ai.id_tipoentrada as id_tipoentrada',
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
              DB::raw('null as id_tienda'),
              'ai.idalmacen as id_almacen',
              DB::raw("
            CASE
                WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
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
            ->where('ti.stock_ingreso', '=', 0)
            ->where('tt.codigo', $cod) 
            ->where('pp.idrubro','=',1) 
            ->select(
                'ti.idtienda as id_alm_tda',
              'pp.codigointernacional as codigointernacional',
              'ti.registro_sanitario as registro_sanitario',
              'ti.id_tipoentrada as id_tipoentrada',
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
              DB::raw('null as id_almacen'),
             
              DB::raw("
              CASE
                  WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                  WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                  WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
                  ELSE NULL
              END AS leyenda
          ")
            );
      
        $result = $productos->unionAll($tiendas)->get();
     
        return $result;
        }   
       
    }
    public function updateTdaAlm(Request $request)
    {
        $primerGuardadoExitoso = false;
        try {
          // Iniciar una transacción
          DB::beginTransaction();
          $codigo  = substr($request->cod, 0, 3);
          if ($codigo == "TDA") {
              $actualizarProducto = Tda_IngresoProducto::findOrFail($request->id);
  
          } else {
              if ($codigo == "ALM") {
              $actualizarProducto = Alm_IngresoProducto::findOrFail($request->id);              
              }
              else{
                  dd("error");
              }
          }
  
          $actualizarProducto->cantidad = $request->cantidad;
          $actualizarProducto->stock_ingreso = $request->cantidad;
          $actualizarProducto->id_tipoentrada = $request->id_tipo_entrada;
          $actualizarProducto->fecha_vencimiento = $request->fecha_vencimiento;
          $actualizarProducto->lote = $request->lote;
          $actualizarProducto->registro_sanitario = $request->registro_sanitario;
          $actualizarProducto->id_usuario_modifica = auth()->user()->id;
          $actualizarProducto->save();
                // Indicar que el primer guardado fue exitoso
                $primerGuardadoExitoso = true;   
    
          
  
  
                  $ajusteNegativo=new Inv_AjustePositivo();
                  $ajusteNegativo->id_usuario = auth()->user()->id;
                  $ajusteNegativo->usuario = auth()->user()->name;
                  $ajusteNegativo->id_usuario_registra = auth()->user()->id;
                  $ajusteNegativo->id_tipo=$request->id_tipo_entrada;
                  $ajusteNegativo->id_producto_linea=$request->id;
                  $ajusteNegativo->codigo=$request->codigo;                
                  $ajusteNegativo->linea=$request->linea;                
                  $ajusteNegativo->producto=$request->producto;                
                  $ajusteNegativo->cantidad=$request->cantidad;
                  $ajusteNegativo->stock=$request->cantidad;
                  $ajusteNegativo->descripcion="stock en cero";
  
                  $ajusteNegativo->fecha_ingreso=$actualizarProducto->updated_at;
                  $ajusteNegativo->fecha_vencimiento=$request->fecha_vencimiento;
                  $ajusteNegativo->lote=$request->lote;
             
                  $ajusteNegativo->id_sucursal=$request->id_sucursal;
                  $ajusteNegativo->cod=$request->cod;
                  $ajusteNegativo->id_ingreso=$request->id;
                  $cadena = $request->leyenda . " FI:" . $actualizarProducto->updated_at . " Lote:" . $request->lote . " FV:" . $request->fecha_vencimiento;
                  $ajusteNegativo->leyenda = $cadena;               
                  $ajusteNegativo->save();
                  DB::commit();
        } catch (\Throwable $th) {
            // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
            if ($primerGuardadoExitoso) {
                DB::rollback();
                // Eliminar el producto guardado
                $actualizarProducto->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
        }
       
    }


}
