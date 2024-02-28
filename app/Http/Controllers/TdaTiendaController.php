<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tda_Tienda;
use App\Models\tda__ingreso_productos;
use App\Models\Adm_Sucursal;
use Illuminate\Support\Facades\DB;
use App\Models\Prod_Producto;
use App\Models\Tda_IngresoProducto;

class TdaTiendaController extends Controller
{
    
    public function index(Request $request)
    {

        $tiendas = Adm_Sucursal::selectRaw(
                                            'adm__sucursals.id as id_sucursal,
                                            adm__sucursals.tipo as tipo_sucursal,
                                            adm__sucursals.cod as codigo_sucursal,
                                            adm__sucursals.razon_social,
                                            adm__sucursals.nombre_comercial,
                                            adm__sucursals.telefonos,
                                            adm__sucursals.nit,
                                            adm__sucursals.direccion,
                                            adm__sucursals.departamento,
                                            adm__sucursals.ciudad,
                                            adm__sucursals.activo as activo_sucursal,
                                            tda__tiendas.id as id_tienda,
                                            tda__tiendas.codigo as codigo_tienda,
                                            tda__tiendas.activo as activo_tienda,
                                            adm__rubros.id as id_rubro,
                                            adm__rubros.nombre as nombre_rubro,
                                            adm__rubros.areamedica,
                                            adm__rubros.activo as activo_rubro')
                             ->leftJoin('tda__tiendas', 'tda__tiendas.idsucursal', '=', 'adm__sucursals.id')
                             ->leftJoin('adm__rubros', 'adm__rubros.id', '=', 'adm__sucursals.idrubro')
                             ->paginate(15);
        return 
        [
            'pagination'=>
            [
                'total'         =>    $tiendas->total(),
                'current_page'  =>    $tiendas->currentPage(),
                'per_page'      =>    $tiendas->perPage(),
                'last_page'     =>    $tiendas->lastPage(),
                'from'          =>    $tiendas->firstItem(),
                'to'            =>    $tiendas->lastItem(),
            ] ,
           'tiendas'=>$tiendas,
        ];
    }

    public function store(Request $request)
    { 
        $primerGuardadoExitoso = false;
        try {
              // Iniciar una transacción
             DB::beginTransaction();
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
            $nuevoProducto->activo = 1;
            $nuevoProducto->id_usuario_registra=auth()->user()->id;
            $nuevoProducto->save();
            // Obtener el ID asignado al nuevo producto
            $nuevoProductoID = $nuevoProducto->id;
            // Indicar que el primer guardado fue exitoso
        $primerGuardadoExitoso = true;         
            $datos = [
                'id_tienda_almacen' => $request->idtienda,              
                'id_ingreso' => $nuevoProductoID,
                'tipo' => "TDA",               
            ];
            DB::table('pivot__modulo_tienda_almacens')->insert($datos);
            // Si llegamos aquí sin errores, confirmamos la transacción
            DB::commit();
        } catch (\Throwable $th) {
            // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
            if ($primerGuardadoExitoso) {
                DB::rollback();
                // Eliminar el producto guardado
                $nuevoProducto->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
        }
       
    }


    public function desactivar(Request $request)
    {
        $producto = Tda_Tienda::findOrFail($request->id);
        $producto->activo=0;
        $producto->id_usuario_modifica=auth()->user()->id;
        $producto->save();
    }

    public function activar(Request $request)
    {
        $producto = Tda_Tienda::findOrFail($request->id);
        $producto->activo=1;
        $producto->id_usuario_modifica=auth()->user()->id;
        $producto->save();
    }


    public function getProductosTiendaEnvase(Request $request)
    {
        $idrubroDeAlmacenSeleccionado = DB::table('alm__almacens')
                                        ->select('adm__sucursals.idrubro') 
                                        ->leftJoin('adm__sucursals','adm__sucursals.id','alm__almacens.idsucursal')
                                        ->where('alm__almacens.id',$request->idalmacen)
                                        ->get();

        foreach ($idrubroDeAlmacenSeleccionado as $value) {
            $idrubroTable = $value->idrubro;
        } 

        $raw = DB::raw(DB::raw('concat(ifnull(prod__productos.codigo,"")," ",ifnull(prod__productos.nombre,"")," ",ifnull(prod__dispensers.nombre,"")," X ",ifnull(prod__productos.cantidad'.$request->envase.',"")," - ",ifnull(prod__forma_farmaceuticas.nombre,"")) as cod'));
        $productos = Prod_Producto::leftJoin('prod__forma_farmaceuticas','prod__forma_farmaceuticas.id','prod__productos.idformafarmaceutica'.$request->envase)
                                    ->leftJoin('prod__dispensers','prod__dispensers.id','prod__productos.iddispenser'.$request->envase)
                                    ->leftJoin('adm__rubros','adm__rubros.id','prod__productos.idrubro')
                                    ->select(DB::raw('prod__productos.id as idproduc,
                                                prod__productos.codigo,
                                                prod__productos.nombre, 
                                                prod__productos.idformafarmaceutica'.$request->envase.',
                                                prod__productos.cantidad'.$request->envase.', 
                                                prod__forma_farmaceuticas.nombre as nomformafarmaceutica,
                                                prod__dispensers.id as idenvase,
                                                prod__dispensers.nombre as nomenvase,
                                                adm__rubros.id as idrubro,
                                                adm__rubros.nombre as nomrubro,
                                                adm__rubros.areamedica,
                                                prod__productos.codigointernacional,
                                                prod__productos.tienda'.$request->envase.',
                                                prod__productos.almacen'.$request->envase),
                                                $raw
                                            )
                                    ->where('prod__productos.activo',1)
                                    ->where('adm__rubros.id',$idrubroTable)
                                    ->orderby('prod__productos.nombre','asc')
                                    ->get();
        
        return $productos;
    }


}
