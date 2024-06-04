<?php

namespace App\Http\Controllers;

use App\Models\Par_Descuento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Author;

class ParDescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bus=$request->tabla;
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
                            pd.nombre_descuento like '%".$valor."%' 
                                or ppd.leyenda like '%".$valor."%' 
                               
                                                             
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                            pd.nombre_descuento like '%".$valor."%' 
                            or ppd.leyenda like '%".$valor."%' 
                           
                          )" ;
                    }    
                }
                $descuento = DB::table('par__descuentos as pd')
                ->select(
                    'pd.id as id',
                    'pd.id_tipo_tabla',
                    'pd.nombre_descuento',
                    'pd.descripcion',
                    'pd.desc_num',
                    'pd.monto_descuento',
                    'pd.estado',
                    'pd.activo',
                    'u.name as user_name',
                    'pcp.id as id_can_mon',
                    'pcp.es_cantidad_es_monto',
                    'pcp.regla',
                    'pcp.cantidad_valor',
                    
                    'p_cli.id as id_cliente_tabla',
                    'p_cli.num_documento',
                    'p_cli.id_cliente_p',
                    'p_cli.nom_facturar',                    
                    'ppd.id as id_producto_tabla',
                    'ppd.id_prod',
                    'ppd.cod_prod',
                    'ppd.envase',
                    'ppd.tipo_tda_alm as lugar',
                    'ppd.leyenda','pcp.id_descuento as id_descuento_pcp','p_cli.id_descuento as id_descuento_cli','ppd.id_descuento as id_descuento_prod',
                    DB::raw('GREATEST(pd.created_at, pd.updated_at) as fecha')
                )
                ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
                ->leftJoin('par__cliente_producto as p_cli', 'p_cli.id_descuento', '=', 'pd.id')
                ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
                ->join('users as u', 'u.id', '=', 'pd.id_usuario_registra')
                ->where('pd.id_tipo_tabla',  $bus)
                ->whereRaw($sqls)
                ->orderBy('id', 'desc')
                ->paginate(15);
            }
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $descuento->total(),
                            'current_page'  =>    $descuento->currentPage(),
                            'per_page'      =>    $descuento->perPage(),
                            'last_page'     =>    $descuento->lastPage(),
                            'from'          =>    $descuento->firstItem(),
                            'to'            =>    $descuento->lastItem(),
                        ] ,
                    'descuento'=>$descuento,
            ]; 
        }else{
            $descuento = DB::table('par__descuentos as pd')
                ->select(
                    'pd.id as id',
                    'pd.id_tipo_tabla',
                    'pd.nombre_descuento',
                    'pd.descripcion',
                    'pd.desc_num',
                    'pd.monto_descuento',
                    'pd.estado',
                    'pd.activo',
                    'u.name as user_name',
                    'pcp.id as id_can_mon',
                    'pcp.es_cantidad_es_monto',
                    'pcp.regla',
                    'pcp.cantidad_valor',
                    'p_cli.id as id_cliente_tabla',
                    'p_cli.num_documento',
                    'p_cli.id_cliente_p',
                    'p_cli.nom_facturar',  
                    'ppd.id as id_producto_tabla',
                    'ppd.id_prod',
                    'ppd.cod_prod',
                    'ppd.envase',
                    'ppd.tipo_tda_alm as lugar',
                    'ppd.leyenda','pcp.id_descuento as id_descuento_pcp','p_cli.id_descuento as id_descuento_cli','ppd.id_descuento as id_descuento_prod',
                    DB::raw('GREATEST(pd.created_at, pd.updated_at) as fecha')
                )
                ->leftJoin('par__cantidad_precio as pcp', 'pcp.id_descuento', '=', 'pd.id')
                ->leftJoin('par__cliente_producto as p_cli', 'p_cli.id_descuento', '=', 'pd.id')
                ->leftJoin('par__producto_desc as ppd', 'ppd.id_descuento', '=', 'pd.id')
                ->join('users as u', 'u.id', '=', 'pd.id_usuario_registra')
                ->where('pd.id_tipo_tabla',  $bus)                
                ->orderBy('id', 'desc')
                ->paginate(15);
                return 
                [
                        'pagination'=>
                            [
                                'total'         =>    $descuento->total(),
                                'current_page'  =>    $descuento->currentPage(),
                                'per_page'      =>    $descuento->perPage(),
                                'last_page'     =>    $descuento->lastPage(),
                                'from'          =>    $descuento->firstItem(),
                                'to'            =>    $descuento->lastItem(),
                            ] ,
                        'descuento'=>$descuento,
                ]; 
        }

       
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    $primerGuardadoExitoso = false;
        try {
          // Iniciar una transacción
        DB::beginTransaction();
       
        $id=$request->id_tipo_tabla;
        $descuento = new Par_Descuento();

        if ($id==1) {
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=0;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_registra=auth()->user()->id;
            $descuento->save();
            DB::commit();
            }
        if ($id==2) {
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=1;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_registra=auth()->user()->id;
            $descuento->save();
            $primerGuardadoExitoso = true;
            DB::commit();
            $nuevoProductoID = $descuento->id;
            $datos = [
                'es_cantidad_es_monto' => $request->es_cantidad_es_monto,              
                'regla' => $request->regla,
                'cantidad_valor' => $request->cantidad_valor, 
                'id_descuento' => $nuevoProductoID,                            
            ];
        
    DB::table('par__cantidad_precio')->insert($datos);
        }  
        
        if ($id==3) {
            $existe = DB::table('par__cliente_producto')->where('id_cliente_p', $request->id_cliente_p)->exists();

            // Preparar el mensaje
            if ($existe) {
                return response()->json(['message' => 'La persona ya existe']);
            } else {
                $descuento->id_tipo_tabla=$id;
                $descuento->tipo_tabla=2;
                $descuento->nombre_descuento=$request->nombre_descuento;
                $descuento->descripcion=$request->descripcion;
                $descuento->desc_num=$request->desc_num;
                $descuento->monto_descuento=$request->monto_descuento;
                $descuento->id_usuario_registra=auth()->user()->id;
                $descuento->save();
                $primerGuardadoExitoso = true;
                DB::commit();
                $nuevoProductoID = $descuento->id;
                $datos = [
                    'id_cliente_p' => $request->id_cliente_p,
             
                    'num_documento' => $request->num_documento, 
                    'id_descuento' => $nuevoProductoID,  
                    'nom_facturar' => $request->nom_facturar,  
                                              
                ];
            
        DB::table('par__cliente_producto')->insert($datos);
            }
           
        }  

        if ($id==4) {
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=3;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_registra=auth()->user()->id;
            $descuento->save();
            $primerGuardadoExitoso = true;
            DB::commit();
            $nuevoProductoID = $descuento->id;
            $datos = [
                'id_prod' => $request->id_prod,         
                'cod_prod' => $request->cod_prod, 
                'envase' => $request->envase, 
                'tipo_tda_alm' => $request->tipo_tda_alm, 
                'id_linea' => $request->id_linea,                 
                'id_descuento' => $nuevoProductoID,  
                'leyenda' => $request->leyenda,                                            
            ];
        
    DB::table('par__producto_desc')->insert($datos);
        }  

        } catch (\Throwable $th) {
            if ($primerGuardadoExitoso) {       
                // Eliminar el producto guardado
                $descuento->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
        }
     }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Par_Descuento $par_Descuento)
    {
       
        $primerGuardadoExitoso = false;
        try {
          // Iniciar una transacción
        DB::beginTransaction();
        $id=$request->id_tipo_tabla;
        $descuento = Par_Descuento::findOrFail($request->id);
        if ($id==1) {
        
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_modifica=auth()->user()->id;
            $descuento->save();
            DB::commit();
            }
        if ($id==2) {
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=1;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_modifica=auth()->user()->id;
            $descuento->save();
            $primerGuardadoExitoso = true;
            DB::commit();
       
            $datos = [
                'es_cantidad_es_monto' => $request->es_cantidad_es_monto,              
                'regla' => $request->regla,
                'cantidad_valor' => $request->cantidad_valor,                                 
            ];       
   
    DB::table('par__cantidad_precio')->where('id_descuento', $request->id_descuento_x)->update($datos);
        }  
        
        if ($id==3) {
       
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=2;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_registra=auth()->user()->id;
            $descuento->save();
            $primerGuardadoExitoso = true;
            DB::commit();
 
            $datos = [
          
                'id_cliente_p' => $request->id_cliente_p,
             
                    'num_documento' => $request->num_documento, 
     
                    'nom_facturar' => $request->nom_facturar,  
                                              
                                          
            ];
    DB::table('par__cliente_producto')->where('id_descuento', $request->id_descuento_x)->update($datos);    
    
        }  

        if ($id==4) {
            $descuento->id_tipo_tabla=$id;
            $descuento->tipo_tabla=3;
            $descuento->nombre_descuento=$request->nombre_descuento;
            $descuento->descripcion=$request->descripcion;
            $descuento->desc_num=$request->desc_num;
            $descuento->monto_descuento=$request->monto_descuento;
            $descuento->id_usuario_registra=auth()->user()->id;
            $descuento->save();
            $primerGuardadoExitoso = true;
            DB::commit();
          
            $datos = [
                'id_prod' => $request->id_prod,         
                'cod_prod' => $request->cod_prod, 
                'envase' => $request->envase, 
                'tipo_tda_alm' => $request->tipo_tda_alm, 
                'id_linea' => $request->id_linea,                 
             
                'leyenda' => $request->leyenda,                                            
            ];
    DB::table('par__producto_desc')->where('id_descuento', $request->id_descuento_x)->update($datos);    
         
    
        }  

        } catch (\Throwable $th) {
            if ($primerGuardadoExitoso) {       
                // Eliminar el producto guardado
                $descuento->delete();
            }
            return response()->json(['error' => $th->getMessage()],500);
        }

    }
    public function listarTipoDescuentos(){
        // Obtener los descuentos desde la base de datos
        $descuentos = DB::table('prod__tipo_descuentos')
                        ->select('id', 'aplica_a', 'subcategorias', 'activo')
                        ->where('activo', '=', 1)
                        ->get();
    
        // Crear un nuevo array para almacenar los resultados
        $result = [];
    
        // Iterar sobre cada descuento
        foreach ($descuentos as $descuento) {
            // Explode para dividir las subcategorías
            $metodos = explode('|', $descuento->subcategorias);
            
            // Crear entradas individuales para cada método
            foreach ($metodos as $metodo) {
                $result[] = [
                    'id' => $descuento->id,
                    'aplica_a' => $descuento->aplica_a,
                    'metodo' => $metodo
                ];
            }
        }
    
    
 // Retornar los resultados (opcional)
 return ['subcategoria' => $result, 'descuentos' => $descuentos];
    }
    
    public function listarTipoTabla(Request $request){

         // Utilizar el constructor de consultas para obtener todos los registros
         $records = DB::table('par_tipo_tabla')->get();
        return $records;
    }

    public function listarClienteX(Request $request){
        $nom_a_facturar = trim($request->num);
      
        $clientes = DB::table('dir__clientes')
            ->select('id', 'nom_a_facturar', 'num_documento')
            ->where('activo', 1)
            ->where('num_documento','=',$nom_a_facturar) 
            ->first();
          return $clientes;
    } 

    public function listarProductoX(Request $request){
        $consulta1 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'primario' as envase"),
                DB::raw("'tienda' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadprimario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->where('pp.almacenprimario', 0)
            ->where('pp.tiendaprimario', 1)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        $consulta2 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'secundario' as envase"),
                DB::raw("'tienda' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadsecundario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispensersecundario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticasecundario')
            ->where('pp.almacensecundario', 0)
            ->where('pp.tiendasecundario', 1)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        $consulta3 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'terciario' as envase"),
                DB::raw("'tienda' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadterciario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserterciario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaterciario')
            ->where('pp.almacenterciario', 0)
            ->where('pp.tiendaterciario', 1)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        $consulta4 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'primario' as envase"),
                DB::raw("'almacen' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadprimario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserprimario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaprimario')
            ->where('pp.almacenprimario', 1)
            ->where('pp.tiendaprimario', 0)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        $consulta5 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'secundario' as envase"),
                DB::raw("'almacen' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadsecundario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispensersecundario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticasecundario')
            ->where('pp.almacensecundario', 1)
            ->where('pp.tiendasecundario', 0)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        $consulta6 = DB::table('prod__productos as pp')
            ->select(
                'pp.id',
                'pp.codigo as codigo_prod',
                'pp.idlinea as id_liena',
                'pl.nombre as nombre_linea',
                DB::raw("'terciario' as envase"),
                DB::raw("'almacen' as tipo_lugar"),
                DB::raw("CONCAT(IFNULL(pp.nombre, ''), ' ', IFNULL(pd_1.nombre, ''), ' x ', IFNULL(pp.cantidadterciario, ''), ' ', IFNULL(ff_1.nombre, '')) as leyenda")
            )
            ->join('prod__lineas as pl', 'pl.id', '=', 'pp.idlinea')
            ->join('adm__rubros as ar', 'pp.idrubro', '=', 'ar.id')
            ->join('prod__dispensers as pd_1', 'pd_1.id', '=', 'pp.iddispenserterciario')
            ->join('prod__forma_farmaceuticas as ff_1', 'ff_1.id', '=', 'pp.idformafarmaceuticaterciario')
            ->where('pp.almacenterciario', 1)
            ->where('pp.tiendaterciario', 0)
            ->where('pp.idrubro', 1)
            ->where('pp.activo', 1);

        // Combinar las consultas con UNION ALL
        $productos = $consulta1->unionAll($consulta2)
            ->unionAll($consulta3)
            ->unionAll($consulta4)
            ->unionAll($consulta5)
            ->unionAll($consulta6)
            ->get();

        // Retornar la respuesta como JSON
        return $productos;
    }

    public function desactivar(Request $request)
    {
        $descuento = Par_Descuento::findOrFail($request->id);
        $descuento->activo = 0;
        $descuento->id_usuario_modifica=auth()->user()->id;
        
        $descuento->save();
    }

    public function activar(Request $request)
    {
        $descuento = Par_Descuento::findOrFail($request->id);
        $descuento->activo = 1;
        $descuento->id_usuario_modifica=auth()->user()->id;
        $descuento->save();
    }
   


    public function asignar(Request $request){
        // Buscar asignaciones existentes para el vehículo
        $asignarExistente = DB::table('par__asignacion_descuento')
        ->where('id_descuento', $request->id)
        ->get();
        // Si existen asignaciones, eliminarlas
        if ($asignarExistente->count() > 0) {
            DB::table('par__asignacion_descuento')
            ->where('id_descuento', $request->id)
            ->delete();
          }
      
        $bloque = $request->bloque;
            foreach ($bloque as $item) {
             $codigo = $item['codigo'];
             $idSucursal = $item['id_sucursal'];
             $idTiendaAlmacen = $item['id_tienda_almacen'];
             $datos = [
                 'id_descuento' => $request->id,
                 'id_sucursal' => $idSucursal,
                 'id_alm_tda' => $idTiendaAlmacen,
                 'cod' => $codigo,
             ];
         
             DB::table('par__asignacion_descuento')->insert($datos);
          
          }
     }
     public function listarAsignar(Request $request){
        if ($request->id != "undefined" || !empty($request->id)) {
         $alm = DB::table('par__asignacion_descuento as tip')
         ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
         ->join('alm__almacens as aa', 'aa.codigo', '=', 'tip.cod')
         ->where('tip.id_descuento', '=', $request->id)
         ->select('tip.id_descuento as id','tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','aa.nombre_almacen as nombre', 'tip.cod as codigo');
     
     $tda = DB::table('par__asignacion_descuento as tip')
         ->join('adm__sucursals as ass', 'ass.id', '=', 'tip.id_sucursal')
         ->join('tda__tiendas as tt', 'tt.codigo', '=', 'tip.cod')
         ->where('tip.id_descuento', '=', $request->id)
         ->select('tip.id_descuento as id', 'tip.id_sucursal as id_sucursal','tip.id_alm_tda as id_alm_tda','ass.razon_social as nombre', 'tip.cod as codigo');
     
     $resultado = $alm->unionAll($tda)->get();
     
     return $resultado;
        }       
         
     }

    public function listarSucursalesX_descuentos(){

        // Consulta adicional para sucursales
$sucursales = DB::table('adm__sucursals')
->select('id', 'razon_social', 'cod')
->orderBy('id', 'asc')
->get();

        // Consulta principal
$resultados = DB::table('par__asignacion_descuento as pad1')
->join('adm__sucursals as ass', 'ass.id', '=', 'pad1.id_sucursal')
->leftJoin('tda__tiendas as tt', 'tt.codigo', '=', 'pad1.cod')
->leftJoin('alm__almacens as aa', 'aa.codigo', '=', 'pad1.cod')
->join('par__descuentos as ad', 'ad.id', '=', 'pad1.id_descuento')
->join('par_tipo_tabla as att', 'ad.id_tipo_tabla', '=', 'att.id')
->select(
    'ass.id',
    'ass.razon_social as nom_suc',
    'ass.cod as cod_suc',
    DB::raw("CASE 
        WHEN tt.codigo IS NOT NULL THEN 'Tienda'
        WHEN aa.codigo IS NOT NULL THEN 'Almacen'
        ELSE NULL
    END as tipo_tienda_almacen"),
    DB::raw("CASE 
        WHEN tt.codigo IS NOT NULL THEN tt.codigo
        WHEN aa.codigo IS NOT NULL THEN aa.codigo
        ELSE NULL
    END as codigo_alm_tda"),
    DB::raw("CASE 
        WHEN tt.codigo IS NOT NULL THEN ass.razon_social
        WHEN aa.codigo IS NOT NULL THEN aa.nombre_almacen
        ELSE NULL
    END as nom_alm_td"),
    'ad.nombre_descuento',
    DB::raw("CASE 
        WHEN ad.desc_num = 1 THEN 'Descuento en bolivianos'
        WHEN ad.desc_num = 2 THEN 'Descuento en porcentaje'
        ELSE NULL
    END as tipo_descuento"),
    'att.nombre as nombre_de_tabla',
    'ad.monto_descuento'
)
->orderBy('ass.id', 'asc')
->get();

$arrayTotal=[];
$controlador=0;
foreach ($sucursales as $key => $s) {
    
    foreach ($resultados as $key => $r) {
     
        if ($s->cod==$r->cod_suc) {
            $arrayTotal[]=[
                 'id' => $r->id,
                 'nom_suc' => $r->nom_suc,
                 'cod_suc' => $r->cod_suc,
                 'tipo_tienda_almacen' => $r->tipo_tienda_almacen,
                 'codigo_alm_tda' => $r->codigo_alm_tda,
                 'nom_alm_td' => $r->nom_alm_td,
                 'nombre_descuento' => $r->nombre_descuento,
                 'tipo_descuento' => $r->tipo_descuento,
                 'nombre_de_tabla' => $r->nombre_de_tabla,
                 'monto_descuento' => $r->monto_descuento,
            ]; 
            $controlador=1;
        }
    }
    if ($controlador==0) {
        $arrayTotal[]=[
            'id' => $s->id,
            'nom_suc' => $s->razon_social,
            'cod_suc' => $s->cod,
            'tipo_tienda_almacen' =>'',
            'codigo_alm_tda' =>'',
            'nom_alm_td' =>'',
            'nombre_descuento' =>'',
            'tipo_descuento' =>'',
            'nombre_de_tabla' =>'',
            'monto_descuento' =>'',
       ];
     
    }
    $controlador=0;
}

return  $arrayTotal;
    }
}
