<?php

namespace App\Http\Controllers;

use App\Models\Alm_IngresoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmIngresoProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $columnasIngresoProductos = '
    alm__ingreso_producto.id, 
    alm__ingreso_producto.id_prod_producto,
    alm__ingreso_producto.envase as envaseregistrado, 
    alm__ingreso_producto.idalmacen, 
    alm__ingreso_producto.cantidad,
    alm__ingreso_producto.stock_ingreso,
    alm__ingreso_producto.id_tipoentrada, 
    alm__ingreso_producto.fecha_vencimiento, 
    alm__ingreso_producto.lote, 
    alm__ingreso_producto.registro_sanitario, 
    alm__ingreso_producto.activo,
    alm__ingreso_producto.id_usuario_registra,
    alm__ingreso_producto.updated_at as fecingreso,
    alm__almacens.id as idalmacen, 
    alm__almacens.idsucursal, 
    alm__almacens.codigo as codalmacen, 
    alm__almacens.nombre_almacen, 
    alm__almacens.direccion,
    prod__productos.id as idprodproducto, 
    prod__productos.idlinea, 
    prod__productos.codigo as codproducto, 
    prod__productos.nombre as nomproducto,
    prod__productos.iddispenserprimario,
    prod__productos.iddispensersecundario,
    prod__productos.iddispenserterciario,
    prod__productos.idformafarmaceuticaprimario,
    prod__productos.idformafarmaceuticasecundario,
    prod__productos.idformafarmaceuticaterciario,
    prod__productos.cantidadprimario,
    prod__productos.cantidadsecundario,
    prod__productos.cantidadterciario,
    prod__productos.preciolistaprimario,
    prod__productos.precioventaprimario,
    prod__productos.preciolistasecundario,
    prod__productos.precioventasecundario,
    prod__productos.preciolistaterciario,
    prod__productos.precioventaterciario,
    prod__productos.idrubro as idrubroproducto,
    ges_pre__ventas.id as idgespreventas,
    ges_pre__ventas.id_table_ingreso_tienda_almacen,
    ges_pre__ventas.tienda,
    ges_pre__ventas.almacen,
    ges_pre__ventas.precio_lista_gespreventa,
    ges_pre__ventas.cantidad_envase_gespreventa,
    ges_pre__ventas.costo_compra_gespreventa,
    ges_pre__ventas.precio_venta_gespreventa,
    ges_pre__ventas.margen_20p_gespreventa,
    ges_pre__ventas.margen_30p_gespreventa,
    ges_pre__ventas.utilidad_bruta_gespreventa,
    ges_pre__ventas.utilidad_neto_gespreventa,
    ges_pre__ventas.idusuario as idgespreventasusuario,
    ges_pre__ventas.listo_venta,
    ges_pre__ventas.updated_at as fecha_utilidad,
    ges_pre__ventas.created_at as fecha_creacion_utilidad';
    
    public function index(Request $request)
    {
            $productosAlmacen = DB::table('alm__ingreso_producto')
                              ->leftJoin('alm__almacens','alm__ingreso_producto.idalmacen','=','alm__almacens.id')
                              ->leftJoin('prod__productos','alm__ingreso_producto.id_prod_producto','=','prod__productos.id')
                              //->leftJoin('ges_pre__ventas','ges_pre__ventas.id_table_ingreso_tienda_almacen','=','alm__ingreso_producto.id') //aqui hay que agregar el la condidicional ges_pre__ventas.id_table_ingreso_tienda_almacen = alm__ingreso_producto.id and ges_pre__ventas.almacen = 1
                              ->leftJoin("ges_pre__ventas", function($join){
                                $join->on("ges_pre__ventas.id_table_ingreso_tienda_almacen", "=", "alm__ingreso_producto.id")
                                ->where("ges_pre__ventas.almacen", "=", 1);
                                })
                              ->select(DB::raw($this->columnasIngresoProductos))    
                              ->where('alm__ingreso_producto.idalmacen','=',$request->idalmacen)
                              ->orderBy('alm__ingreso_producto.updated_at','desc')
                              ->orderBy('ges_pre__ventas.updated_at','desc')
                              ->paginate(10);
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $productosAlmacen->total(),
                            'current_page'  =>    $productosAlmacen->currentPage(),
                            'per_page'      =>    $productosAlmacen->perPage(),
                            'last_page'     =>    $productosAlmacen->lastPage(),
                            'from'          =>    $productosAlmacen->firstItem(),
                            'to'            =>    $productosAlmacen->lastItem(),
                        ] ,
                    'productosAlmacen'=> $productosAlmacen,
            ];
        // }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return $request;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nuevoProducto = new Alm_IngresoProducto();
        $nuevoProducto->id_prod_producto = $request->id_prod_producto;
        $nuevoProducto->envase = $request->envase;
        $nuevoProducto->idalmacen = $request->idalmacen;
        $nuevoProducto->cantidad = $request->cantidad;
        $nuevoProducto->stock_ingreso = $request->cantidad;
        $nuevoProducto->id_tipoentrada = $request->id_tipo_entrada;
        $nuevoProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $nuevoProducto->lote = $request->lote;
        $nuevoProducto->registro_sanitario = $request->registro_sanitario;
        $nuevoProducto->id_usuario_registra=auth()->user()->id;
        $nuevoProducto->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Alm_IngresoProducto $alm_IngresoProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alm_IngresoProducto $alm_IngresoProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alm_IngresoProducto $alm_IngresoProducto)
    {
        $actualizarProducto = Alm_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->id_prod_producto = $request->id_prod_producto;
        $actualizarProducto->envase = $request->envase;
        $actualizarProducto->idalmacen = $request->idalmacen;
        $actualizarProducto->cantidad = $request->cantidad;
        $actualizarProducto->stock_ingreso = $request->cantidad;
        $actualizarProducto->id_tipoentrada = $request->id_tipo_entrada;
        $actualizarProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $actualizarProducto->lote = $request->lote;
        $actualizarProducto->registro_sanitario = $request->registro_sanitario;
        $actualizarProducto->id_usuario_registra=auth()->user()->id;
        $actualizarProducto->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alm_IngresoProducto $alm_IngresoProducto)
    {
        //
    }

    public function desactivar(Request $request)
    {
        $actualizarProducto = Alm_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->activo = 0;
        $actualizarProducto->id_usuario_modifica=auth()->user()->id;
        $actualizarProducto->save();
    }

    public function activar(Request $request)
    {
        $actualizarProducto = Alm_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->activo = 1;
        $actualizarProducto->id_usuario_modifica=auth()->user()->id;
        $actualizarProducto->save();
    }

    public function retornarProductosIngreoAlmacen(Request $request)
    {
        $buscararray = array();

        if(!empty($request->buscar)) {
           $buscararray = explode(" ",$request->buscar); 
        }
           $raw=DB::raw(DB::raw($this->columnasIngresoProductos));
            
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls))
                    $sqls="(codigo like '%".$valor."%' or nombre like '%".$valor."%' )";
                else
                    $sqls.=" and (codigo like '%".$valor."%' or nombre like '%".$valor."%' )";
            }   
            // $lineas = Prod_Linea::select($raw,'id','nombre','codigo')
            //                     ->where('activo',1)
            //                     ->whereraw($sqls)
            //                     ->orderby('codigo','asc')
            //                     ->get();
        }
        else {
            if ($request->id){
                    $productosAlmacen = Alm_IngresoProducto::select($raw)
                                            ->leftJoin('alm__almacens','alm__ingreso_producto.idalmacen','=','alm__almacens.id')
                                            ->leftJoin('prod__productos','alm__ingreso_producto.id_prod_producto','=','prod__productos.id')
                                            ->leftJoin('ges_pre__ventas','ges_pre__ventas.id_table_ingreso_tienda_almacen','=','alm__ingreso_producto.id')
                                            ->where('alm__ingreso_producto.activo',1)
                                            ->where('alm__ingreso_producto.id',$request->id)
                                            ->where('alm__ingreso_producto.idalmacen','=',$request->idalmacen)
                                            ->paginate(10);
            }

            else
            {
                $productosAlmacen = Alm_IngresoProducto::select($raw)
                                            // DB::table('alm__ingreso_producto')
                                            ->leftJoin('alm__almacens','alm__ingreso_producto.idalmacen','=','alm__almacens.id')
                                            ->leftJoin('prod__productos','alm__ingreso_producto.id_prod_producto','=','prod__productos.id')
                                            ->leftJoin('ges_pre__ventas','ges_pre__ventas.id_table_ingreso_tienda_almacen','=','alm__ingreso_producto.id')
                                            ->where('alm__ingreso_producto.activo',1)
                                            ->where('alm__ingreso_producto.idalmacen','=',$request->idalmacen)
                                            ->paginate(10);
            }
              
        }
        return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $productosAlmacen->total(),
                            'current_page'  =>    $productosAlmacen->currentPage(),
                            'per_page'      =>    $productosAlmacen->perPage(),
                            'last_page'     =>    $productosAlmacen->lastPage(),
                            'from'          =>    $productosAlmacen->firstItem(),
                            'to'            =>    $productosAlmacen->lastItem(),
                        ] ,
                    'productosAlmacen'=>$productosAlmacen,
            ];
    }


}
