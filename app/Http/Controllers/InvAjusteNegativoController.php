<?php

namespace App\Http\Controllers;

use App\Models\Inv_AjusteNegativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;
use App\Models\Inv_Traspaso;

use PhpParser\Node\Stmt\TryCatch;
use SebastianBergmann\Environment\Console;

class InvAjusteNegativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $buscararray = array();
        $bus = $request->query('buscarAlmTdn');
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
                $query_ajuste_negativos = DB::table('inv__ajuste_negativos as aan')
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
                        'aan.id_tipo as id_tipo',
                        'pte.nombre as nombreTipo',
                        //'aan.fecha as fecha',
                        DB::raw('GREATEST(aan.created_at, aan.updated_at) as fecha'),
                        'aan.id_sucursal as id_sucursal',
                        'ass.razon_social as razon_social',
                        'aan.created_at as fecha_creacion',
                        'aan.activo as activo',
                        'aan.cod as cod',
                        'aan.id_ingreso as id_ingreso',
                        'aan.leyenda as leyenda',
                        'aan.id_traspaso as numero_traspaso'
                    )
                    ->where('aan.cod', '=', $bus)
                   
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

            $query_ajuste_negativos = DB::table('inv__ajuste_negativos as aan')
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
                    'aan.descripcion as descripcion',
                    'aan.cantidad as cantidad',
                    'pte.nombre as nombreTipo',
                    DB::raw('GREATEST(aan.created_at, aan.updated_at) as fecha'),
                   //'aan.fecha as fecha',
                    'aan.id_sucursal as id_sucursal',
                    'ass.razon_social as razon_social',
                    'aan.created_at as fecha_creacion',
                    'aan.activo as activo',
                    'aan.cod as cod',
                    'aan.id_ingreso as id_ingreso',
                    'aan.leyenda as leyenda',
                    'aan.id_traspaso as numero_traspaso'
                )
                ->where('aan.cod', '=', $bus)
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
    /**
     * @method POST
     */
    public function store(Request $request, Inv_AjusteNegativo $inv_AjusteNegativo, Alm_IngresoProducto $alm_IngresoProducto, Tda_IngresoProducto $tda_IngresoProducto)
    {
        try {
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
    
            if ($activador == 1) {
                $ajusteNegativo = new Inv_AjusteNegativo();
                $ajusteNegativo->id_usuario = auth()->user()->id;
                $ajusteNegativo->usuario = auth()->user()->name;
                $ajusteNegativo->id_tipo = $request->id_tipo;            
                $ajusteNegativo->id_producto_linea = $request->id_producto;
              //  $ajusteNegativo->id_producto_linea = $request->id_producto_linea;
                $ajusteNegativo->codigo = $request->codigo;
                $ajusteNegativo->linea = $request->linea;
                $ajusteNegativo->producto = $request->producto;
                $ajusteNegativo->cantidad = $request->cantidad;
                $ajusteNegativo->descripcion = $request->descripcion;
                $ajusteNegativo->fecha = $request->fecha;
                $ajusteNegativo->activo = $request->activo;
                $ajusteNegativo->id_sucursal = $request->id_sucursal;
                $ajusteNegativo->cod = $codigo;
                $ajusteNegativo->id_ingreso = $id;
                $ajusteNegativo->leyenda = $request->leyenda;
                $update = Alm_IngresoProducto::find($id);
    
                $update->stock_ingreso = $request->cantidad_producto;
                $update->save();
                $ajusteNegativo->save();
            } else {
                if ($activador == 2) {
                    $ajusteNegativo = new Inv_AjusteNegativo();
                    $ajusteNegativo->id_usuario = auth()->user()->id;
                    $ajusteNegativo->usuario = auth()->user()->name;
                    $ajusteNegativo->id_tipo = $request->id_tipo;
                    $ajusteNegativo->id_producto_linea = $request->id_producto;
                    //$ajusteNegativo->id_producto_linea = $request->id_producto_linea;
                    $ajusteNegativo->codigo = $request->codigo;
                    $ajusteNegativo->linea = $request->linea;
                    $ajusteNegativo->producto = $request->producto;
                    $ajusteNegativo->cantidad = $request->cantidad;
                    $ajusteNegativo->descripcion = $request->descripcion;
                    $ajusteNegativo->fecha = $request->fecha;
                    $ajusteNegativo->activo = $request->activo;
                    $ajusteNegativo->id_sucursal = $request->id_sucursal;
                    $ajusteNegativo->cod = $codigo;
                    $ajusteNegativo->id_ingreso = $id;
                    $ajusteNegativo->leyenda = $request->leyenda;
                    $update = Tda_IngresoProducto::find($id);
    
                    $update->stock_ingreso = $request->cantidad_producto;
                    $update->save();
                    $ajusteNegativo->save();
                } else {
                    dd("error");
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()],500);
        }
       

    }



    public function listarProductoLineaIngreso(Request $request)
    {
        //->where('aa.codigo', $cod) 
     
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
                      ->where('aa.codigo', '=',$cod)
                      ->where('pp.idrubro','=',1)
                      ->where('pp.activo','=',1);
                })
                ->when($request->tipo == 2, function ($query) use ($cod) {
                $query->where('aa.codigo', '=',$cod)
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
                'pp.id as id_producto',
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
                WHEN ai.envase = 'primario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, ''))
                WHEN ai.envase = 'secundario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, ''))
                WHEN ai.envase = 'terciario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, ''))
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
                      ->where('tt.codigo','=', $cod)
                      ->where('pp.idrubro','=',1)
                      ->where('pp.activo','=',1);
                })
                ->when($request->tipo == 2, function ($query) use ($cod) {
                $query->where('tt.codigo', '=',$cod)
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
                'pp.id as id_producto',
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
                WHEN ti.envase = 'primario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), ' ', COALESCE(ff_1.nombre, ''))
                WHEN ti.envase = 'secundario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), ' ', COALESCE(ff_2.nombre, ''))
                WHEN ti.envase = 'terciario' THEN CONCAT( COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), ' ', COALESCE(ff_3.nombre, ''))
                ELSE NULL
            END AS leyenda
        ")
            );

        $result = $productos->unionAll($tiendas)->get();

        return $result;
    }



    public function listarSucursal()
    {
        // $sucursales = DB::table('adm__sucursals')
        // ->select('id','cod','razon_social')
        // ->orderby('id')
        // ->get();

        $tiendas = DB::table('tda__tiendas')
            ->select('tda__tiendas.id as id_tienda', DB::raw('null as id_almacen'), 'tda__tiendas.codigo', 'adm__sucursals.razon_social', 'adm__sucursals.razon_social as sucursal','adm__sucursals.cod as codigoS', DB::raw('"Tienda" as tipoCodigo'))
            ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id');

        $almacenes = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->select(DB::raw('null as id_tienda'), 'aa.id as id_almacen', 'aa.codigo', 'aa.nombre_almacen as razon_social', 'ass.razon_social as sucursal', 'ass.cod as codigoS,',DB::raw('"Almacen" as tipoCodigo'));

        $result = $tiendas->unionAll($almacenes)->get();


        $jsonSucrusal = [];

        foreach ($result as $key => $sucursal) {
            $elemento = [
                'id' => $key,
                'id_tienda' => $sucursal->id_tienda,
                'id_almacen' => $sucursal->id_almacen,
                'codigo' => $sucursal->codigo,
                'razon_social' => $sucursal->razon_social,
                'sucursal' => $sucursal->sucursal,
                'codigoS' => $sucursal->codigoS,
                'tipoCodigo' => $sucursal->tipoCodigo,
            ];

            $jsonSucrusal[] = $elemento;
        }
        //foreach ($jsonData as $elemento) {
        //    echo "ID: {$elemento['id']}<br>";
        //    echo "ID Tienda: {$elemento['id_tienda']}<br>";
        //    echo "ID Almacen: {$elemento['id_almacen']}<br>";
        //    echo "Código: {$elemento['codigo']}<br>";
        //    echo "Razón Social: {$elemento['razon_social']}<br>";
        //    echo "Sucursal: {$elemento['sucursal']}<br>";
        //    echo "------------------------<br>";
        //}
        //exit;
        return $jsonSucrusal;
    }


    public function listarTipo()
    {
        $productoTipo = DB::table('prod__tipo_entradas')
            ->select(DB::raw('MIN(id) as id'), 'nombre')
           
            ->groupBy('nombre')
            ->get();
        return $productoTipo;
    }
    /**
     * Display the specified resource.
     */
    public function show(Inv_AjusteNegativo $inv_AjusteNegativo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inv_AjusteNegativo $inv_AjusteNegativo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inv_AjusteNegativo $inv_AjusteNegativo)
    {
    try {
        $cod = $request->cod;
        $updateAjusteNegativo = Inv_AjusteNegativo::find($request->id);
        $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
        $updateAjusteNegativo->usuario = auth()->user()->name;
        $updateAjusteNegativo->id_usuario = auth()->user()->id;
        $updateAjusteNegativo->id_tipo = $request->id_tipo;
      
        $updateAjusteNegativo->descripcion = $request->descripcion;
     
        $updateAjusteNegativo->save();
    } catch (\Throwable $th) {
        
        return response()->json(['error' => $th->getMessage()],500);

    }
            

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inv_AjusteNegativo $inv_AjusteNegativo)
    {
        //
    }
    public function desactivar(Request $request)
    {
        try {
            $activador = 0;
            $updateAjusteNegativo = Inv_AjusteNegativo::findOrFail($request->id);
           
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
                $update->stock_ingreso =($update->stock_ingreso)+$cantidad;
    
                if ($updateAjusteNegativo->id_tipo==13) {
                    $id_tras=$updateAjusteNegativo->id_traspaso;
                    $tras = Inv_Traspaso::where('numero_traspaso', $id_tras)->first();
                    if ($tras) {                   
                        $tras->procesado =3; 
                        $tras->activo = 0;     
                        $tras->save();
                    }
                     
                }
               
    
                $update->save();
                $updateAjusteNegativo->save();
            } else {
                if ($activador == 2) {
                $updateAjusteNegativo->activo = 0;
                $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
                $updateAjusteNegativo->id_usuario = auth()->user()->id;
                $updateAjusteNegativo->usuario = auth()->user()->name;
                $update = Tda_IngresoProducto::find($id_i);
                $update->stock_ingreso =($update->stock_ingreso)+$cantidad;
    
                if ($updateAjusteNegativo->id_tipo==13) {
                    $id_tras=$updateAjusteNegativo->id_traspaso;
                    $tras = Inv_Traspaso::where('numero_traspaso', $id_tras)->first();
                    if ($tras) {                   
                        $tras->procesado =3;  
                        $tras->activo = 0;      
                        $tras->save();
                    }
                     
                }
    
                $update->save();
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
    public function activar(Request $request)
    {
        try {
            $activador = 0;
        $updateAjusteNegativo = Inv_AjusteNegativo::findOrFail($request->id);
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
            $update->stock_ingreso =($update->stock_ingreso)-$cantidad;
            $update->save();
            $updateAjusteNegativo->save();
        } else {
            if ($activador == 2) {
            $updateAjusteNegativo->activo = 1;
            $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
            $updateAjusteNegativo->id_usuario = auth()->user()->id;
            $updateAjusteNegativo->usuario = auth()->user()->name;
            $update = Tda_IngresoProducto::find($id_i);
            $update->stock_ingreso =($update->stock_ingreso)-$cantidad;
            if ($updateAjusteNegativo->id_tipo==13) {
                $id_tras=$updateAjusteNegativo->id_traspaso;
                $tras = Inv_Traspaso::where('numero_traspaso', $id_tras)->first();
                if ($tras) {                   
                    $tras->procesado =0;  
                    $tras->activo = 0;       
                    $tras->save();
                }
                 
            }
            $update->save();
            $updateAjusteNegativo->save();
            }
            else {
                dd("error") ;
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
    if (!empty($request->respuesta1)) {

            $buscararray = explode(" ", $request->respuesta1);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                pp.codigointernacional like '%" . $valor . "%' 
                                or pp.nombre like '%" . $valor . "%' 
                               
                                or pp.codigo like '%" . $valor . "%'
                            
                               )";
                    } else {
                        $sqls .= "and ( pp.codigointernacional like '%" . $valor . "%' 
                        or pp.nombre like '%" . $valor . "%' 
                  
                        or pp.codigo like '%" . $valor . "%' 
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
                    ->where('pp.idrubro','=',1) 
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
                      WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                      WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                      WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
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
                    ->whereRaw($sqls)
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
                      WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                      WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                      WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
                      ELSE NULL
                  END AS leyenda
              ")
                    );

                $result = $productos->unionAll($tiendas)->get();
            }

            return $result;
        } else {

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
                  WHEN ai.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                  WHEN ai.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                  WHEN ai.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
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
                  WHEN ti.envase = 'primario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_1.nombre, ''), ' x ', COALESCE(pp.cantidadprimario, ''), '  ', COALESCE(ff_1.nombre, ''))
                  WHEN ti.envase = 'secundario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_2.nombre, ''), ' x ', COALESCE(pp.cantidadsecundario, ''), '  ', COALESCE(ff_2.nombre, ''))
                  WHEN ti.envase = 'terciario' THEN CONCAT(COALESCE(pp.codigo, ''), ' ', COALESCE(pp.nombre, ''), ' ', COALESCE(pd_3.nombre, ''), ' x ', COALESCE(pp.cantidadterciario, ''), '  ', COALESCE(ff_3.nombre, ''))
                  ELSE NULL
              END AS leyenda
          ")

                );

            $result = $productos->unionAll($tiendas)->get();

            return $result;
        }
    }
}
