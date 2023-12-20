<?php

namespace App\Http\Controllers;

use App\Models\Alm_Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray=array();
        // $raw=DB::raw('concat(prod__productos.nombre," ",prod__dispensers.nombre," ",prod__productos.cantidad," ",prod__forma_farmaceuticas.nombre) as codprod');
        // if(!empty($request->buscar)){
        //     $buscararray = explode(" ",$request->buscar);
        //     //dd($buscararray);
        //     $valor=sizeof($buscararray);
        //     if($valor > 0){
        //         $sqls='';
        //         foreach($buscararray as $valor){
        //             if(empty($sqls)){
        //                 $sqls="(prod__productos.codigo like '%".$valor."%' 
        //                         or prod__productos.nombre like '%".$valor."%' 
        //                         or prod__dispensers.nombre like '%".$valor."%' 
        //                         or prod__forma_farmaceuticas.nombre like '%".$valor."%' 
        //                         or prod__categorias.nombre like '%".$valor."%' 
        //                         or alm__almacens.codigo like '%".$valor."%' 
        //                         or alm__almacens.lote like '%".$valor."%' 
        //                         or alm__almacens.registro_sanitario like '%".$valor."%' )" ;
        //             }
        //             else
        //             {
        //                 $sqls.=" and (prod__productos.codigo like '%".$valor."%' 
        //                         or prod__productos.nombre like '%".$valor."%' 
        //                         or prod__dispensers.nombre like '%".$valor."%' 
        //                         or prod__forma_farmaceuticas.nombre like '%".$valor."%' 
        //                         or prod__categorias.nombre like '%".$valor."%' 
        //                         or alm__almacens.codigo like '%".$valor."%' 
        //                         or alm__almacens.lote like '%".$valor."%' 
        //                         or alm__almacens.registro_sanitario like '%".$valor."%' )" ;
        //             }
    
        //         }
        //         $productos= Alm_Almacen::join('prod__productos','prod__productos.id','alm__almacens.idproducto')
        //                                 ->join('prod__dispensers','prod__dispensers.id','prod__productos.iddispenser')
        //                                 ->join('prod__forma_farmaceuticas','prod__forma_farmaceuticas.id','prod__productos.idformafarm')
        //                                 ->join('prod__categorias','prod__categorias.id','prod__productos.idcategoria')
        //                                     ->select($raw,
        //                                             'alm__almacens.id as id',
        //                                             'alm__almacens.cantidad',
        //                                             'tipo_entrada',
        //                                             'lote',
        //                                             'fecha_vencimiento',
        //                                             'alm__almacens.codigo',
        //                                             'registro_sanitario',
        //                                             'ubicacion_estante',
        //                                             'alm__almacens.activo')
        //                                     ->orderby('ubicacion_estante','asc')
        //                                     ->where('idsucursal',$request->idsucursal)
        //                                     ->whereraw($sqls)
        //                                     ->paginate(40);
        //     }
        // }
        
        // else
        // {
        //     $productos= Alm_Almacen::join('prod__productos','prod__productos.id','alm__almacens.idproducto')
        //                             ->join('prod__dispensers','prod__dispensers.id','prod__productos.iddispenser')
        //                             ->join('prod__forma_farmaceuticas','prod__forma_farmaceuticas.id','prod__productos.idformafarm')
        //                             ->join('prod__categorias','prod__categorias.id','prod__productos.idcategoria')
        //                                 ->select($raw,
        //                                         'alm__almacens.id as id',
        //                                         'alm__almacens.cantidad',
        //                                         'tipo_entrada',
        //                                         'lote',
        //                                         'fecha_vencimiento',
        //                                         'alm__almacens.codigo',
        //                                         'registro_sanitario',
        //                                         'ubicacion_estante',
        //                                         'alm__almacens.activo')
        //                                 ->orderby('ubicacion_estante','asc')
        //                                 ->where('idsucursal',$request->idsucursal)
        //                                 ->paginate(40);
        // }
        // return [
        //             'pagination'=>[
        //                 'total'         =>    $productos->total(),
        //                 'current_page'  =>    $productos->currentPage(),
        //                 'per_page'      =>    $productos->perPage(),
        //                 'last_page'     =>    $productos->lastPage(),
        //                 'from'          =>    $productos->firstItem(),
        //                 'to'            =>    $productos->lastItem(),
        //             ] ,
        //             'productos'=>$productos,
        //        ];

        if(!empty($request->buscar))
        {
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor)
                {
                    if(empty($sqls)){
                        $sqls="(alm__almacens.codigo like '%".$valor."%' 
                                or alm__almacens.nombre_almacen like '%".$valor."%' 
                                or alm__almacens.telefono like '%".$valor."%' 
                                or alm__almacens.direccion like '%".$valor."%' 
                                or alm__almacens.departamento like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.="and (alm__almacens.codigo like '%".$valor."%'  
                                or alm__almacens.nombre_almacen like '%".$valor."%' 
                                or alm__almacens.telefono like '%".$valor."%' 
                                or alm__almacens.direccion like '%".$valor."%' 
                                or alm__almacens.departamento like '%".$valor."%')" ;
                    }
    
                }

                /**
                 * select alm__almacens.id, adm__sucursals.id as idsucursal, 
                 *        adm__sucursals.cod as codsuc, adm__sucursals.razon_social,
                 *        adm__sucursals.tipo, adm__sucursals.correlativo,
                 *        alm__almacens.codigo, alm__almacens.nombre_alamcen, 
                 *        alm__almacens.telefono, alm__almacens.direccion, 
                 *        alm__almacens.departamento, alm__almacens.ciudad, 
                 *        alm__almacens.activo
                 * from alm__almacens
                 * left join adm__sucursals on alm__almacens.idsucursal = adm__sucursals.id
                 * where alm__almacens.codigo like '%$valor%'
                 *    or alm__almacens.nombre_almacen  like '%$valor%'
                 *    or alm__almacens.telefono  like '%$valor%'
                 *    or alm__almacens.direccion  like '%$valor%'
                 *    or alm__almacens.departamento  like '%$valor%'
                 */
                $almacenes= DB::table('alm__almacens')
                        ->leftJoin('adm__sucursals','alm__almacens.idsucursal','=','adm__sucursals.id')
                        ->selectRaw('alm__almacens.id, adm__sucursals.id as idsucursal, 
                                     adm__sucursals.cod as codsuc, adm__sucursals.razon_social,
                                     adm__sucursals.tipo, adm__sucursals.correlativo,
                                     alm__almacens.codigo, alm__almacens.nombre_almacen, 
                                     alm__almacens.telefono, alm__almacens.direccion, 
                                     alm__almacens.departamento, alm__almacens.ciudad, 
                                     alm__almacens.activo')
                        ->whereraw($sqls)
                        ->paginate(15);
            }
            
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $almacenes->total(),
                            'current_page'  =>    $almacenes->currentPage(),
                            'per_page'      =>    $almacenes->perPage(),
                            'last_page'     =>    $almacenes->lastPage(),
                            'from'          =>    $almacenes->firstItem(),
                            'to'            =>    $almacenes->lastItem(),
                        ] ,
                    'almacenes'=>$almacenes,
            ];
        }else {
            /**
             * select alm__almacens.id, adm__sucursals.id as idsucursal, 
             *        adm__sucursals.cod as codsuc, 
             *        alm__almacens.codigo, adm__sucursals.razon_social,
             *        adm__sucursals.tipo, adm__sucursals.correlativo,
             *        alm__almacens.nombre_almacen, 
             *        alm__almacens.telefono, alm__almacens.direccion, 
             *        alm__almacens.departamento, alm__almacens.ciudad, 
             *        alm__almacens.activo
             * from alm__almacens
             * left join adm__sucursals on alm__almacens.idsucursal = adm__sucursals.id
             * where alm__almacens.activo = 1
             * 
             */
            $almacenes= DB::table('alm__almacens')
                        ->leftJoin('adm__sucursals','alm__almacens.idsucursal','=','adm__sucursals.id')
                        ->selectRaw('alm__almacens.id, adm__sucursals.id as idsucursal, adm__sucursals.idrubro, 
                                     adm__sucursals.cod as codsuc, 
                                     alm__almacens.codigo, adm__sucursals.razon_social,
                                     adm__sucursals.tipo, adm__sucursals.correlativo,
                                     alm__almacens.nombre_almacen, 
                                     alm__almacens.telefono, alm__almacens.direccion, 
                                     alm__almacens.departamento, alm__almacens.ciudad, 
                                     alm__almacens.activo')
                        //->where('alm__almacens.activo','=',1)
                        ->paginate(10);
            return [
                    'pagination'=>[
                        'total'         =>    $almacenes->total(),
                        'current_page'  =>    $almacenes->currentPage(),
                        'per_page'      =>    $almacenes->perPage(),
                        'last_page'     =>    $almacenes->lastPage(),
                        'from'          =>    $almacenes->firstItem(),
                        'to'            =>    $almacenes->lastItem(),
                    ] ,
                    'almacenes'=>$almacenes,
               ];
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $letracodigo='ALM';
        $maxcorrelativo = Alm_Almacen::select(DB::raw('max(correlativo) as maximo'))
                                      ->get()->toArray();
        $correlativo=$maxcorrelativo[0]['maximo'];
        if(is_null($correlativo))
            $correlativo=1;
        else
            $correlativo=$correlativo+1;

        if($correlativo<10)
            $codigo='00'.$correlativo;
        else
            if($correlativo<100)
                $codigo='0'.$correlativo;
                
        
        $codigo=$letracodigo.$codigo;

        $almacen = new Alm_Almacen();
        $almacen->idsucursal = $request->idsucursal;
        // $almacen->idproducto=$request->idproducto;
        // $almacen->idusuario=$request->idusuario;
        // $almacen->cantidad=$request->cantidad;
        // $almacen->tipo_entrada=$request->tipo_entrada;
        // $almacen->lote=$request->lote;
        // $almacen->fecha_vencimiento=$request->fecha_vencimiento;
        // $almacen->codigo=$request->codigo;
        // $almacen->registro_sanitario=$request->registro_sanitario;
        // $almacen->ubicacion_estante=$request->ubicacion_estante;
        $almacen->id_usuario_registra = auth()->user()->id;
        $almacen->codigo = $codigo;
        $almacen->nombre_almacen = $request->nombre_almacen;
        $almacen->telefono = $request->telefono;
        $almacen->direccion = $request->direccion;
        $almacen->departamento = $request->departamento;
        $almacen->ciudad = $request->ciudad;
        $almacen->estado = $request->estado;
        $almacen->correlativo = $correlativo;
        $almacen->save();
        //return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alm_Almacen  $alm_Almacen
     * @return \Illuminate\Http\Response
     */
    public function show(Alm_Almacen $alm_Almacen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alm_Almacen  $alm_Almacen
     * @return \Illuminate\Http\Response
     */
    public function edit(Alm_Almacen $alm_Almacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alm_Almacen  $alm_Almacen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alm_Almacen $alm_Almacen)
    {
        $updateAlamcen=Alm_Almacen::find($request->id);
        $updateAlamcen->idsucursal = $request->idsucursal==null?0:$request->idsucursal;
        $updateAlamcen->nombre_almacen = $request->nombre_almacen;
        $updateAlamcen->telefono = $request->telefono;
        $updateAlamcen->direccion = $request->direccion;
        $updateAlamcen->departamento = $request->departamento;
        $updateAlamcen->ciudad = $request->ciudad;
        $updateAlamcen->id_usuario_modifica=auth()->user()->id;
        $updateAlamcen->save();        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alm_Almacen  $alm_Almacen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alm_Almacen $alm_Almacen)
    {
        //
    }
    public function desactivar(Request $request)
    {
        // $producto = Alm_Almacen::findOrFail($request->id);
        // $producto->activo=0;
        // $producto->id_usuario_modifica=auth()->user()->id;
        // $producto->save();

        $updateAlamcen = Alm_Almacen::findOrFail($request->id);
        $updateAlamcen->activo = 0;
        $updateAlamcen->id_usuario_modifica=auth()->user()->id;
        $updateAlamcen->save();
    }

    public function activar(Request $request)
    {
        // $producto = Alm_Almacen::findOrFail($request->id);
        // $producto->activo=1;
        // $producto->id_usuario_modifica=auth()->user()->id;
        // $producto->save();

        $updateAlamcen = Alm_Almacen::findOrFail($request->id);
        $updateAlamcen->activo = 1;
        $updateAlamcen->id_usuario_modifica=auth()->user()->id;
        $updateAlamcen->save();
    }


     public function listaralmacenes()
     {
        //$almacenes = Alm_Almacen::
     }   

}
