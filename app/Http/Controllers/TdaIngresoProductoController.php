<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tda_IngresoProducto;
use App\Models\Pivot_Modulo_tienda_almacen;

use Illuminate\Support\Facades\DB;

class TdaIngresoProductoController extends Controller
{
    private $columnasIngresoProductos = 'tda__ingreso_productos.id,
	tda__ingreso_productos.idtienda, 
	tda__ingreso_productos.id_prod_producto,
	tda__ingreso_productos.envase as envaseregistrado,
	tda__ingreso_productos.id_tipoentrada,
	tda__ingreso_productos.cantidad,
	tda__ingreso_productos.stock_ingreso,
	tda__ingreso_productos.fecha_vencimiento,
	tda__ingreso_productos.lote,
	tda__ingreso_productos.registro_sanitario,
	tda__ingreso_productos.activo as activo_tda_ingreso_producto,
	tda__ingreso_productos.id_usuario_registra,
	tda__ingreso_productos.updated_at as fecha_ingreso,
	prod__productos.id as id_producto,
	prod__productos.codigo as codigo_producto,
	prod__productos.idlinea,
	prod__productos.nombre as nombre_producto,
	prod__productos.idrubro as id_rubro_producto,
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
    prod__productos.activo as activo_producto,
	tda__tiendas.id as id_tienda,
	tda__tiendas.codigo as codigo_tienda,
	tda__tiendas.activo as activo_tienda,
	ges_pre__ventas.id as id_gespreventa,
	ges_pre__ventas.id_table_ingreso_tienda_almacen,
	ges_pre__ventas.tienda, 
	ges_pre__ventas.almacen,
	ges_pre__ventas.precio_lista_gespreventa,
	ges_pre__ventas.precio_venta_gespreventa,
	ges_pre__ventas.cantidad_envase_gespreventa,
	ges_pre__ventas.costo_compra_gespreventa,
	ges_pre__ventas.margen_20p_gespreventa,
	ges_pre__ventas.margen_30p_gespreventa,
	ges_pre__ventas.utilidad_bruta_gespreventa,
	ges_pre__ventas.utilidad_neto_gespreventa,
    ges_pre__ventas.listo_venta,
    ges_pre__ventas.updated_at as fecha_utilidad';


    public function index(Request $request)
    {
        $productosTienda = DB::table('tda__ingreso_productos')
                              ->leftJoin('prod__productos','prod__productos.id','=', 'tda__ingreso_productos.id_prod_producto')
                              ->leftJoin('tda__tiendas','tda__tiendas.id','=','tda__ingreso_productos.idtienda')
                              //->leftJoin('ges_pre__ventas', 'ges_pre__ventas.id_table_ingreso_tienda_almacen','=','tda__ingreso_productos.id')
                              ->leftJoin("ges_pre__ventas", function($join){
                                $join->on("ges_pre__ventas.id_table_ingreso_tienda_almacen", "=", "tda__ingreso_productos.id")
                                ->where("ges_pre__ventas.tienda", "=", 1);
                                })
                              ->select(DB::raw($this->columnasIngresoProductos))    
                              ->where('prod__productos.activo',1)
                              ->where('tda__tiendas.activo',1)
                              ->where('tda__ingreso_productos.idtienda',$request->idtienda)
                              ->orderBy('tda__ingreso_productos.updated_at','desc')
                              ->orderBy('ges_pre__ventas.updated_at','desc')
                              ->paginate(10);
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $productosTienda->total(),
                            'current_page'  =>    $productosTienda->currentPage(),
                            'per_page'      =>    $productosTienda->perPage(),
                            'last_page'     =>    $productosTienda->lastPage(),
                            'from'          =>    $productosTienda->firstItem(),
                            'to'            =>    $productosTienda->lastItem(),
                        ] ,
                    'productosTienda'=>$productosTienda,
            ];
    }


    public function store(Request $request)
    {
        
        $nuevoProducto = new Tda_IngresoProducto();
        $nuevoProducto->id_prod_producto = $request->id_prod_producto;
        $nuevoProducto->envase = $request->envase;
        $nuevoProducto->idtienda = $request->idtienda;
        $nuevoProducto->cantidad = $request->cantidad;
        $nuevoProducto->stock_ingreso = $request->cantidad;
        $nuevoProducto->id_tipoentrada = $request->id_tipo_entrada;
        $nuevoProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $nuevoProducto->lote = $request->lote;
        $nuevoProducto->registro_sanitario = $request->registro_sanitario;
        $nuevoProducto->id_usuario_registra=auth()->user()->id;
        $nuevoProducto->save();      
        
    }

    public function update(Request $request)
    {
        $actualizarProducto = Tda_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->id_prod_producto = $request->id_prod_producto;
        $actualizarProducto->envase = $request->envase;
        $actualizarProducto->idtienda = $request->idtienda;
        $actualizarProducto->cantidad = $request->cantidad;
        $actualizarProducto->stock_ingreso = $request->cantidad;
        $actualizarProducto->id_tipoentrada = $request->id_tipo_entrada;
        $actualizarProducto->fecha_vencimiento = $request->fecha_vencimiento;
        $actualizarProducto->lote = $request->lote;
        $actualizarProducto->registro_sanitario = $request->registro_sanitario;
        $actualizarProducto->id_usuario_modifica = auth()->user()->id;
        $actualizarProducto->save();
    }


    public function desactivar(Request $request)
    {
        $actualizarProducto = Tda_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->activo = 0;
        $actualizarProducto->id_usuario_modifica = auth()->user()->id;
        $actualizarProducto->save();
    }

    public function activar(Request $request)
    {
        $actualizarProducto = Tda_IngresoProducto::findOrFail($request->id);
        $actualizarProducto->activo = 1;
        $actualizarProducto->id_usuario_modifica = auth()->user()->id;
        $actualizarProducto->save();
    }

}
