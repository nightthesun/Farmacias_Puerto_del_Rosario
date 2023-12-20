<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GesPre_Venta;
use App\Models\Prod_Producto;

class GesPreVentaController extends Controller
{
    //
    
    
    public function update_store(Request $request)
    {
        if ($request->existe_registro_gespreventa == 1 ){
            $precioVentaProducto = GesPre_Venta :: firstWhere('id',$request->id);
        }
        else {
            $precioVentaProducto = new GesPre_Venta();
        }
                
        // $actualizarProductoPrecioVenta = Prod_Producto::firstWhere('id',$request->idProdProducto);
        // $envase='precioventa'.$request->envaseregistrado;
        // $actualizarProductoPrecioVenta->$envase = $request->precio_venta_prodproductos;
        // $actualizarProductoPrecioVenta->save();

        $precioVentaProducto->id_table_ingreso_tienda_almacen = $request->id_table_ingreso_tienda_almacen;
        $precioVentaProducto->tienda = $request->tienda;
        $precioVentaProducto->almacen = $request->almacen;
        $precioVentaProducto->precio_lista_gespreventa = $request->precio_lista_gespreventa;
        $precioVentaProducto->precio_venta_gespreventa = $request->precio_venta_gespreventa;
        $precioVentaProducto->cantidad_envase_gespreventa = preg_match('/[a-z]/',strtolower($request->cantidad_envase_gespreventa)) == 1 ? 1 :$request->cantidad_envase_gespreventa;
        $precioVentaProducto->costo_compra_gespreventa = $request->costo_compra_gespreventa;
        $precioVentaProducto->margen_20p_gespreventa = $request->margen_20p_gespreventa;
        $precioVentaProducto->margen_30p_gespreventa = $request->margen_30p_gespreventa;
        $precioVentaProducto->utilidad_bruta_gespreventa = $request->utilidad_bruta_gespreventa; 
        $precioVentaProducto->utilidad_neto_gespreventa = $request->utilidad_neto_gespreventa;
        $precioVentaProducto->listo_venta = 1;
        $precioVentaProducto->idusuario = auth()->user()->id;
        $precioVentaProducto->save();
        
        return $request;
    }

    public function verificarProductoPrecio(Request $request)
    {
        $tipoTiendaOAlmacen = '';
        if(strtolower($request->tienda_almacen) == 'tienda'){
            $tipoTiendaOAlmacen = 'tienda';
        }else{
            $tipoTiendaOAlmacen = 'almacen';
        }
        
        $registroGestionPrecioVenta = GesPre_Venta::where('id_table_ingreso_tienda_almacen',$request->id_table_ingreso_tienda_almacen)
                                                    ->where($tipoTiendaOAlmacen,1)
                                                    ->get();
        return $registroGestionPrecioVenta;
    }
}
