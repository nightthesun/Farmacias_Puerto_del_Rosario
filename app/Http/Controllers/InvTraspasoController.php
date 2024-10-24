<?php

namespace App\Http\Controllers;

use App\Models\Inv_Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Alm_IngresoProducto;
use App\Models\Tda_IngresoProducto;
use App\Models\Inv_AjusteNegativo;
use App\Models\Inv_AjustePositivo;

class InvTraspasoController extends Controller
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
            $where = "(it.cod_1 = '$bus' and it.user_id = $user)";            
        } else {
            $where = "(it.cod_1 = '$bus')"; 
        }

        if (!empty($request->buscar)) {
            $buscararray = explode(" ", $request->buscar);
            $valor = sizeof($buscararray);
            if ($valor > 0) {
                $sqls = '';

                foreach ($buscararray as $valor) {
                    if (empty($sqls)) {
                        $sqls = "(
                                it.numero_traspaso like '%" . $valor . "%' 
                                or it.envase like '%" . $valor . "%' 
                                or it.name_ori like '%" . $valor . "%'
                                or it.name_des like '%" . $valor . "%' 
                                or it.leyenda like '%" . $valor . "%'
                                or it.cod_1 like '%" . $valor . "%'
                                or it.cod_2 like '%" . $valor . "%' 
                               )";
                    } else {
                        $sqls .= "and (
                            it.numero_traspaso like '%" . $valor . "%' 
                            or it.envase like '%" . $valor . "%' 
                            or it.name_ori like '%" . $valor . "%'
                            or it.name_des like '%" . $valor . "%' 
                            or it.leyenda like '%" . $valor . "%'
                            or it.cod_1 like '%" . $valor . "%'
                            or it.cod_2 like '%" . $valor . "%'
                       )";
                    }
                }
                $traspasos_alm = DB::table('inv__traspasos as it')
                ->select([
                    'it.id as id',
                    'it.id_almacen_tienda as id_almacen_tienda',
                    'pp.nombre as linea',
                    'it.id_prod_producto as id_prod_producto',
                    'it.envase as envase',
                    'it.id_tipoentrada as id_tipoentrada',
                    'pie.nombre as tipo_nombre',
                    'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento',
                    'it.lote as lote',
                    'it.registro_sanitario as registro_sanitario',
                    'it.activo as activo',
                    'it.id_origen as id_origen',
                    'it.id_ingreso as id_ingreso',
                    'it.cod_1 as cod_1',
                    'it.cod_2 as cod_2',
                    'it.name_ori as origen',
                    'it.name_des as destino',
                    'it.leyenda as leyenda',
                    'it.glosa as glosa',
                    'it.numero_traspaso as numero_traspaso',
                    'it.procesado as estado_procesado',
                    'u.name as user_name',
                    'it.cantidad_old as cantidad_old',
                    DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
                    DB::raw('CASE
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
                        ELSE "Error"
                    END AS tipo_traspaso')
                ])
                ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
                ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
                ->join('alm__almacens as aa_1', 'aa_1.codigo', '=', 'it.cod_1')
                ->join('alm__ingreso_producto as ai_1', 'ai_1.id', '=', 'it.id_ingreso')
                ->leftJoin('alm__almacens as aa_2', 'aa_2.codigo', '=', 'it.cod_2')
                ->join('users as u', 'u.id', '=', 'it.user_id');
            
                $traspasos_tienda = DB::table('inv__traspasos as it')
                ->select([
                    'it.id as id',
                    'it.id_almacen_tienda as id_almacen_tienda',
                    'pp.nombre as linea',
                    'it.id_prod_producto as id_prod_producto',
                    'it.envase as envase',
                    'it.id_tipoentrada as id_tipoentrada',
                    'pie.nombre as tipo_nombre',
                    'it.cantidad__stock_ingreso as cantidad',
                    'it.fecha_vencimiento as fecha_vencimiento',
                    'it.lote as lote',
                    'it.registro_sanitario as registro_sanitario',
                    'it.activo as activo',
                    'it.id_origen as id_origen',
                    'it.id_ingreso as id_ingreso',
                    'it.cod_1 as cod_1',
                    'it.cod_2 as cod_2',
                    'it.name_ori as origen',
                    'it.name_des as destino',
                    'it.leyenda as leyenda',
                    'it.glosa as glosa',
                    'it.numero_traspaso as numero_traspaso',
                    'it.procesado as estado_procesado',
                    'u.name as user_name',
                    'it.cantidad_old as cantidad_old',
                    DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
                    DB::raw('CASE
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
                        WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
                        ELSE "Error"
                    END AS tipo_traspaso')
                ])
                ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
                ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
                ->join('tda__tiendas as tt_1', 'tt_1.codigo', '=', 'it.cod_1')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'tt_1.idsucursal')
                ->join('tda__ingreso_productos as ti_1', 'ti_1.id', '=', 'it.id_ingreso')
                ->leftJoin('tda__tiendas as tt_2', 'tt_2.codigo', '=', 'it.cod_2')
                ->join('users as u', 'u.id', '=', 'it.user_id');

    $reusltadocombinado = $traspasos_alm->whereRaw($where)->whereRaw($sqls)->orderBy('id', 'desc')->unionAll($traspasos_tienda->whereRaw($where)->whereRaw($sqls)->orderBy('id', 'desc'));
    $reusltadocombinado= $reusltadocombinado ->orderByRaw('id DESC')->paginate(15);
 
   
            }
            
        
            return
                [
                    'pagination' =>
                    [
                        'total'         =>    $reusltadocombinado->total(),
                        'current_page'  =>    $reusltadocombinado->currentPage(),
                        'per_page'      =>    $reusltadocombinado->perPage(),
                        'last_page'     =>    $reusltadocombinado->lastPage(),
                        'from'          =>    $reusltadocombinado->firstItem(),
                        'to'            =>    $reusltadocombinado->lastItem(),
                    ],
                    'reusltadocombinado' => $reusltadocombinado,
                ];
        } else {

            $traspasos_alm = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as linea',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.cantidad_old as cantidad_old',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('alm__almacens as aa_1', 'aa_1.codigo', '=', 'it.cod_1')
    ->join('alm__ingreso_producto as ai_1', 'ai_1.id', '=', 'it.id_ingreso')
    ->leftJoin('alm__almacens as aa_2', 'aa_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');

    $traspasos_tienda = DB::table('inv__traspasos as it')
    ->select([
        'it.id as id',
        'it.id_almacen_tienda as id_almacen_tienda',
        'pp.nombre as linea',
        'it.id_prod_producto as id_prod_producto',
        'it.envase as envase',
        'it.id_tipoentrada as id_tipoentrada',
        'pie.nombre as tipo_nombre',
        'it.cantidad__stock_ingreso as cantidad',
        'it.fecha_vencimiento as fecha_vencimiento',
        'it.lote as lote',
        'it.registro_sanitario as registro_sanitario',
        'it.activo as activo',
        'it.id_origen as id_origen',
        'it.id_ingreso as id_ingreso',
        'it.cod_1 as cod_1',
        'it.cod_2 as cod_2',
        'it.name_ori as origen',
        'it.name_des as destino',
        'it.leyenda as leyenda',
        'it.glosa as glosa',
        'it.numero_traspaso as numero_traspaso',
        'it.procesado as estado_procesado',
        'u.name as user_name',
        'it.cantidad_old as cantidad_old',
        DB::raw('GREATEST(it.created_at, it.updated_at) AS fecha'),
        DB::raw('CASE
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Almacen a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "ALM" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Almacen a Tienda"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "ALM" THEN "Tienda a Almacen"
            WHEN SUBSTRING(it.cod_1, 1, 3) = "TDA" AND SUBSTRING(it.cod_2, 1, 3) = "TDA" THEN "Tienda a Tienda"
            ELSE "Error"
        END AS tipo_traspaso')
    ])
    ->join('prod__productos as pp', 'it.id_prod_producto', '=', 'pp.id')
    ->join('prod__tipo_entradas as pie', 'pie.id', '=', 'it.id_tipoentrada')
    ->join('tda__tiendas as tt_1', 'tt_1.codigo', '=', 'it.cod_1')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt_1.idsucursal')
    ->join('tda__ingreso_productos as ti_1', 'ti_1.id', '=', 'it.id_ingreso')
    ->leftJoin('tda__tiendas as tt_2', 'tt_2.codigo', '=', 'it.cod_2')
    ->join('users as u', 'u.id', '=', 'it.user_id');
    
    $reusltadocombinado = $traspasos_alm->whereRaw($where)->whereBetween(DB::raw('DATE(it.created_at)'), [$ini, $fini])->orderBy('id', 'desc')->unionAll($traspasos_tienda->whereRaw($where)->whereBetween(DB::raw('DATE(it.created_at)'), [$ini, $fini])->orderBy('id', 'desc'));
    $reusltadocombinado= $reusltadocombinado ->orderByRaw('id DESC')
                    ->paginate(15);
            return [
                'pagination' => [
                    'total'         =>    $reusltadocombinado->total(),
                    'current_page'  =>    $reusltadocombinado->currentPage(),
                    'per_page'      =>    $reusltadocombinado->perPage(),
                    'last_page'     =>    $reusltadocombinado->lastPage(),
                    'from'          =>    $reusltadocombinado->firstItem(),
                    'to'            =>    $reusltadocombinado->lastItem(),
                ],
                'reusltadocombinado' => $reusltadocombinado
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
    public function store(Request $request)
    {
        $traspaso=new Inv_Traspaso();
        $traspaso->id_almacen_tienda=$request->id_almacen_tienda;
        $traspaso->id_prod_producto=$request->id_prod_producto;
        $traspaso->envase=$request->envase;
        $traspaso->id_tipoentrada=13;// es deacuerdo al ide de la tabla prod__tipo_entradas donde se el id ques e busca es el 13 de tarspasos
        $traspaso->cantidad__stock_ingreso=$request->cantidad;
        $traspaso->fecha_vencimiento=$request->fecha_vencimiento;
        $traspaso->lote=$request->lote;
        $traspaso->registro_sanitario=$request->registro_sanitario;
        $traspaso->activo=$request->activo;
        $traspaso->id_origen=$request->id_origen;
        $traspaso->id_destino=$request->id_destino;
        $traspaso->id_ingreso=$request->id_ingreso;
        $traspaso->cod_1=$request->cod_1;
        $traspaso->cod_2=$request->cod_2;
        $traspaso->leyenda=$request->leyenda;
        $traspaso->glosa=$request->glosa;
       
        //codigo de para traspaso
        $letracodigo='TRS';      
        
        $maxcorrelativo = Inv_Traspaso::select(DB::raw('max(correlativo) as maximo'))
                                      ->get()->toArray();
        $correlativo=$maxcorrelativo[0]['maximo'];
     
        if(is_null($correlativo))
            $correlativo=1;
        else
            $correlativo=$correlativo+1;

      
        if($correlativo>=0 && $correlativo<10)
            $codigo='0000000'.$correlativo;
        if($correlativo>=10 && $correlativo<100)
            $codigo='000000'.$correlativo;
        if($correlativo>=100 && $correlativo<1000)
            $codigo='00000'.$correlativo;
        if($correlativo>=1000 && $correlativo<10000)
            $codigo='0000'.$correlativo;
        if($correlativo>=10000 && $correlativo<100000)
            $codigo='000'.$correlativo;
        if($correlativo>=100000 && $correlativo<1000000)
            $codigo='00'.$correlativo;
        if($correlativo>=1000000 && $correlativo<10000000)
            $codigo='0'.$correlativo;
   
        $traspaso->correlativo=$correlativo;
        $codigo=$letracodigo.$codigo;
        $codigoClon=$codigo;
        $traspaso->numero_traspaso=$codigo;
        $traspaso->id_usuario_registro=auth()->user()->id;
        $traspaso->user_id=auth()->user()->id;
        $traspaso->name_ori=$request->name_ori;
        $traspaso->name_des=$request->name_des;
       
       
        //////////parte de ajuste positivo

        $cod = $request->cod_1;
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
        $msn=$codigoClon." - ".$request->glosa;
        if ($activador == 1) {
            $ajusteNegativo = new Inv_AjusteNegativo();
            $ajusteNegativo->id_usuario = auth()->user()->id;
            $ajusteNegativo->usuario = auth()->user()->name;
            $ajusteNegativo->id_tipo = 13;
            $ajusteNegativo->id_producto_linea = $request->id_prod_producto;
            $ajusteNegativo->codigo = $request->codigo;
            $ajusteNegativo->linea = $request->linea;
            $ajusteNegativo->producto = $request->prod_name;                       ;
            $ajusteNegativo->cantidad = $request->cantidad;
            $ajusteNegativo->descripcion = $msn;
            $ajusteNegativo->fecha = $request->fecha_ingreso;
            $ajusteNegativo->activo = $request->activo;
            $ajusteNegativo->id_sucursal = $request->id_sucursal;
            $ajusteNegativo->cod = $request->cod_1;
            $ajusteNegativo->id_ingreso = $id_ingreso;
            $ajusteNegativo->leyenda = $request->leyenda;
            $ajusteNegativo->id_traspaso=$codigoClon;
            $update = Alm_IngresoProducto::find($id_ingreso);

            $update->stock_ingreso = $request->cantidad__stock_ingreso;
            $traspaso->save();
            $update->save();
            $ajusteNegativo->save();
        } else {
            if ($activador == 2) {
                $ajusteNegativo = new Inv_AjusteNegativo();
                $ajusteNegativo->id_usuario = auth()->user()->id;
                $ajusteNegativo->usuario = auth()->user()->name;
                $ajusteNegativo->id_tipo = 13;
                $ajusteNegativo->id_producto_linea = $request->id_prod_producto;
                $ajusteNegativo->codigo = $request->codigo;
                $ajusteNegativo->linea = $request->linea;
                $ajusteNegativo->producto = $request->prod_name; 
                $ajusteNegativo->cantidad = $request->cantidad;
                $ajusteNegativo->descripcion = $msn;
                $ajusteNegativo->fecha = $request->fecha_ingreso;
                $ajusteNegativo->activo = $request->activo;
                $ajusteNegativo->id_sucursal = $request->id_sucursal;
                $ajusteNegativo->cod = $request->cod_1;
                $ajusteNegativo->id_ingreso = $id_ingreso;
                $ajusteNegativo->leyenda = $request->leyenda;
                $ajusteNegativo->id_traspaso=$codigoClon;
                $update = Tda_IngresoProducto::find($id_ingreso);

                $update->stock_ingreso = $request->cantidad__stock_ingreso;
                $traspaso->save();
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
        try {
            $update=Inv_Traspaso::find($request->id);
            $cod = $request->cod_1;
            $id_ingreso = $request->id_ingreso;
            
            if($request->cantidad__stock_ingreso==0){
               //casos nulo------------------------
        $update->glosa=$request->glosa;
        $update->id_usuario_modifico=auth()->user()->id;// modificar solo datos 
     
        $update->name_ori=$request->name_ori;
        $update->name_des=$request->name_des;  
        $update->cod_2=$request->cod_2;
       
    
        $update->save();
            }
            else{
                //casos comlejo--------------------------------
                $ajustesNegativos = Inv_AjusteNegativo::select('inv__ajuste_negativos.id as id_ajuste_N')
            ->join('inv__traspasos', 'inv__traspasos.numero_traspaso', '=', 'inv__ajuste_negativos.id_traspaso')
            ->where('inv__traspasos.numero_traspaso', $request->numero_traspaso)
            ->first();
           
            if ($ajustesNegativos) {
    
                $consulta = Alm_IngresoProducto::join('alm__almacens as aa', 'aa.id', '=', 'alm__ingreso_producto.idalmacen')
                ->where('alm__ingreso_producto.id', '=', $id_ingreso)
                ->where('aa.codigo', '=', $cod)
                ->first();
                if ($consulta) {    

                    $res=$consulta->stock_ingreso;    
                    $stock1=$res + $update->cantidad__stock_ingreso;
                    $stock2=$stock1 - $request->cantidad__stock_ingreso; 
                    $consulta->stock_ingreso = $stock2;
                    $consulta->save();

                    $newAjustePositivo=new Inv_AjustePositivo(); 
                    $newAjustePositivo->id_usuario = auth()->user()->id;
                    $newAjustePositivo->usuario = auth()->user()->name;
                    $newAjustePositivo->id_usuario_registra = auth()->user()->id;
                    $newAjustePositivo->id_tipo=13;
                    $newAjustePositivo->id_producto_linea=$request->id_ingreso;
                    $newAjustePositivo->codigo=$request->codigo;
                    $newAjustePositivo->linea=$request->linea;
                    $newAjustePositivo->producto=$request->prod_name;
                    $newAjustePositivo->cantidad=$request->cantidad__stock_ingreso;
                    $newAjustePositivo->stock=$request->stock;
                    $newAjustePositivo->descripcion=$request->numero_traspaso;
                    $newAjustePositivo->fecha_ingreso=$request->fecha_ingreso;
                    $newAjustePositivo->fecha_vencimiento=$request->fecha_vencimiento;
                    $newAjustePositivo->lote=$request->lote;                     
                    $newAjustePositivo->id_sucursal=$request->id_sucursal;
                    $newAjustePositivo->cod=$request->cod_1;
                    $newAjustePositivo->id_ingreso=$id_ingreso;
                    $newAjustePositivo->leyenda = $request->leyenda;  
                    $newAjustePositivo->save();
                
                    $updateAjusteNegativo = Inv_AjusteNegativo::find($ajustesNegativos->id_ajuste_N);
                    $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
                    $updateAjusteNegativo->usuario = auth()->user()->name;
                    $updateAjusteNegativo->cantidad = $request->cantidad__stock_ingreso;
                    $updateAjusteNegativo->save();  
                    
                    $update->cantidad_old=$update->cantidad__stock_ingreso;
                    $update->cantidad__stock_ingreso=$request->cantidad__stock_ingreso;
                    $update->glosa=$request->glosa;
                    $update->id_almacen_tienda=$request->id_almacen_tienda;
                    $update->id_prod_producto=$request->id_prod_producto;
                    $update->envase=$request->envase;       
                    $update->fecha_vencimiento=$request->fecha_vencimiento;
                    $update->lote=$request->lote;
                    $update->registro_sanitario=$request->registro_sanitario;       
                    $update->id_origen=$request->id_origen;
                    $update->id_destino=$request->id_destino;
 
                    $update->id_ingreso=$request->id_ingreso;
                    $update->cod_1=$request->cod_1;
                    $update->cod_2=$request->cod_2;
                    $update->leyenda=$request->leyenda;  
                    $update->id_usuario_modifico=auth()->user()->id;
                    $update->user_id=auth()->user()->id;
                    $update->name_ori=$request->name_ori;
                    $update->name_des=$request->name_des;
                   
                  
                    $update->save();
                }else{
                      // Obtener el stock_ingreso de TdaIngresoProducto
                $consulta2 = Tda_IngresoProducto::join('tda__tiendas as tt', 'tt.id', '=', 'tda__ingreso_productos.idtienda')
                ->where('tda__ingreso_productos.id', '=', $id_ingreso)
                ->where('tt.codigo', '=', $cod)
                ->first();
                if ($consulta2) {
                    $res=$consulta2->stock_ingreso;    
                    $stock1=$res + $update->cantidad__stock_ingreso;
                    $stock2=$stock1 - $request->cantidad__stock_ingreso; 
                    $consulta2->stock_ingreso = $stock2;
                    $consulta2->save();
                    $newAjustePositivo=new Inv_AjustePositivo(); 
                    $newAjustePositivo->id_usuario = auth()->user()->id;
                    $newAjustePositivo->usuario = auth()->user()->name;
                    $newAjustePositivo->id_usuario_registra = auth()->user()->id;
                    $newAjustePositivo->id_tipo=13;
                    $newAjustePositivo->id_producto_linea=$request->id_ingreso;
                    $newAjustePositivo->codigo=$request->codigo;
                    $newAjustePositivo->linea=$request->linea;
                    $newAjustePositivo->producto=$request->prod_name;
                    $newAjustePositivo->cantidad=$request->cantidad__stock_ingreso;
                    $newAjustePositivo->stock=$request->stock;
                    $newAjustePositivo->descripcion=$request->numero_traspaso;
                    $newAjustePositivo->fecha_ingreso=$request->fecha_ingreso;
                    $newAjustePositivo->fecha_vencimiento=$request->fecha_vencimiento;
                    $newAjustePositivo->lote=$request->lote; 
                    $newAjustePositivo->id_sucursal=$request->id_sucursal;
                    $newAjustePositivo->cod=$request->cod_1;
                    $newAjustePositivo->id_ingreso=$id_ingreso;
                    $newAjustePositivo->leyenda = $request->leyenda;
                      
                    $newAjustePositivo->save();
                  
                    //ajuste negativo
                    $res=$consulta2->stock_ingreso;
                    $res=$res-$request->cantidad__stock_ingreso;
                    $consulta2->stock_ingreso = $res;
                    $updateAjusteNegativo = Inv_AjusteNegativo::find($ajustesNegativos->id_ajuste_N);
                    $updateAjusteNegativo->id_usuario_modifica = auth()->user()->id;
                    $updateAjusteNegativo->usuario = auth()->user()->name;
                    $updateAjusteNegativo->cantidad = $request->cantidad__stock_ingreso;
                    $updateAjusteNegativo->save();
                    $update->cantidad_old=$update->cantidad__stock_ingreso;
                    $update->cantidad__stock_ingreso=$request->cantidad__stock_ingreso;
                    $update->glosa=$request->glosa;
                    $update->id_almacen_tienda=$request->id_almacen_tienda;
                    $update->id_prod_producto=$request->id_prod_producto;
                    $update->envase=$request->envase;       
                    $update->fecha_vencimiento=$request->fecha_vencimiento;
                    $update->lote=$request->lote;
                    $update->registro_sanitario=$request->registro_sanitario;       
                    $update->id_origen=$request->id_origen;
                    $update->id_destino=$request->id_destino;         
                    $update->id_ingreso=$request->id_ingreso;
                    $update->cod_1=$request->cod_1;
                    $update->cod_2=$request->cod_2;
                    $update->leyenda=$request->leyenda;  
                    $update->id_usuario_modifico=auth()->user()->id;
                    $update->user_id=auth()->user()->id;
                    $update->name_ori=$request->name_ori;
                    $update->name_des=$request->name_des;
                   
                  
                    $update->save();
                }
                }
                
                   
            }else{
                // no existe ahuste negativo
            
                return response()->json(['error' => "no existe ajuste negativo"],500);
            }
    
            } 
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()],500);
        }
     
       
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
        ->join('adm__sucursals', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
        ->where('tda__tiendas.activo','=',1)
        ->where('adm__sucursals.activo','=',1)
        ;

    $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->where('aa.activo','=',1)
        ->where('ass.activo','=',1)
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
         'lista_id_almacen_id_tienda'=>$sucursal->lista_id_almacen_id_tienda,
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
       ->when($request->tipo == 1, function ($query) use ($cod) {
        $query->where('ai.stock_ingreso', '>', 0)
              ->where('aa.codigo', '=',$cod)
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
   ->when($request->tipo == 1, function ($query) use ($cod) {
    $query->where('ti.stock_ingreso', '>', 0)
          ->where('tt.codigo','=', $cod)
          ->where('pp.idrubro','=',1)
          ->where('pp.activo','=',1);
    })
    ->when($request->tipo == 2, function ($query) use ($cod) {
    $query->where('tt.codigo','=', $cod)
          ->where('pp.idrubro','=',1)
          ->where('pp.activo','=',1);
    })       
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
     public function desactivar(Request $request)
     {
         $traspaso = Inv_Traspaso::findOrFail($request->id);
   
         $traspaso->activo = 0;
         $traspaso->user_id = auth()->user()->id;         
         $traspaso->id_usuario_modifico=auth()->user()->id;


        $traspaso->save();
     }
 
     public function activar(Request $request)
     {
       
 
        $traspaso = Inv_Traspaso::findOrFail($request->id);
        $traspaso->activo = 1;
        $traspaso->user_id = auth()->user()->id;         
        $traspaso->id_usuario_modifico=auth()->user()->id;
       $traspaso->save();
     }
     public function desactivarListo(Request $request)
     {
         $traspaso = Inv_Traspaso::findOrFail($request->id);
   
         $traspaso->procesado = 0;
         $traspaso->user_id = auth()->user()->id;         
         $traspaso->id_usuario_modifico=auth()->user()->id;


        $traspaso->save();
     }
 
     public function activarListo(Request $request)
     {
       
 
        $traspaso = Inv_Traspaso::findOrFail($request->id);
        $traspaso->procesado = 4;
        $traspaso->user_id = auth()->user()->id;         
        $traspaso->id_usuario_modifico=auth()->user()->id;
       $traspaso->save();
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
                $query->where('tt.codigo', $cod)
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

}
