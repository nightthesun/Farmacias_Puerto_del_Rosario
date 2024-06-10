<?php

namespace App\Http\Controllers;

use App\Models\Adm_Sucursal;
use App\Models\Adm_SucursalLista;
use App\Models\Adm_SucursalListaAvanzada;
use App\Models\Tda_Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmSucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
       
    
        $buscararray=array();
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(ass.razon_social like '%".$valor."%' or ass.nit' like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (ass.razon_social like '%".$valor."%' or ass.nit' like '%".$valor."%')" ;
                    }
    
                }
                $sucursales0 = DB::table('adm__sucursals as ass')
                ->join('adm__rubros as ar', 'ass.idrubro', '=', 'ar.id')
                ->join('tda__tiendas as tt', 'tt.idsucursal', '=', 'ass.id')
                ->join ('adm__departamentos as ad','ad.id','=','ass.departamento')   
                ->where('tt.activo', 1)
                ->whereraw($sqls)
                ->select(
                    'ass.id',
                    'ar.id as idrubro',
                    'ar.nombre as nomrubro',
                    'ass.tipo',
                    'ass.cod',
                    'ass.correlativo',
                    'ass.razon_social',
                    'ass.nombre_comercial',
                    'ass.telefonos',
                    'ass.nit',
                    'ass.direccion',
                    'ass.departamento',
                    'ad.nombre as nom_departamento',
                    'ass.ciudad',
                    'ass.activo',
                    'tt.id as id_tienda',
                    'tt.codigo as codigo_tienda',
                    DB::raw('NULL as codalmacen'),
                    DB::raw('NULL as nombre_alm'),
                    
                )
                ->orderBy('ass.id', 'desc')
                ->get();
                $almacenes = DB::table('alm__almacens as aa')
                ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
                ->where('aa.activo', 1)
                ->select(
                    'aa.id',
                    'aa.codigo as codalamcen',
                    'aa.nombre_almacen',
                    'aa.idsucursal'
                )
                ->get();
                $sucursales = $sucursales0;
                
                
                $sucursales = $sucursales0->map(function($su) use ($almacenes) {
                    $cadenaCod = "";
                $cadenaNom = "";
                    $almacenesSuc = $almacenes->where('idsucursal', $su->id);
                    
                    if ($almacenesSuc->isNotEmpty()) {
                        $cods = $almacenesSuc->pluck('codalamcen')->implode(' ');
                        $nombres = $almacenesSuc->pluck('nombre_almacen')->implode(' ');
                        
                        $cadenaCod .= ' ' . $cods;
                        $cadenaNom .= ' ' . $nombres;
                        
                        $su->codalmacen = trim($cadenaCod);
                        $su->nombre_alm = trim($cadenaNom);
                    } else {
                        $su->codalmacen = 'Sin almacen';
                        $su->nombre_alm = 'Sin almacen';
                    }
                
                    return $su;
                });
                
              
    // Ahora vamos a utilizar el paginador de Laravel
    $sucursalesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $sucursales->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 10),
        $sucursales->count(),
        10,
        \Illuminate\Pagination\Paginator::resolveCurrentPage(),
        ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );
            }
            return ['pagination'=>[
                'total'         =>    $sucursalesPaginated->total(),
                'current_page'  =>    $sucursalesPaginated->currentPage(),
                'per_page'      =>    $sucursalesPaginated->perPage(),
                'last_page'     =>    $sucursalesPaginated->lastPage(),
                'from'          =>    $sucursalesPaginated->firstItem(),
                'to'            =>    $sucursalesPaginated->lastItem(),

            ] ,
                'sucursalesPaginated'=>$sucursalesPaginated,
        ];
        }
        
        else
        {$sucursales0 = DB::table('adm__sucursals as ass')
            ->join('adm__rubros as ar', 'ass.idrubro', '=', 'ar.id')
            ->join('tda__tiendas as tt', 'tt.idsucursal', '=', 'ass.id')
            ->join ('adm__departamentos as ad','ad.id','=','ass.departamento')
            ->where('tt.activo', 1)
            ->select(
                'ass.id',
                'ar.id as idrubro',
                'ar.nombre as nomrubro',
                'ass.tipo',
                'ass.cod',
                'ass.correlativo',
                'ass.razon_social',
                'ass.nombre_comercial',
                'ass.telefonos',
                'ass.nit',
                'ass.direccion',
                'ass.departamento',
                'ad.nombre as nom_departamento',
                'ass.ciudad',
                'ass.activo',
                'tt.id as id_tienda',
                'tt.codigo as codigo_tienda',
                DB::raw('NULL as codalmacen'),
                DB::raw('NULL as nombre_alm'),
            )
            ->orderBy('ass.id', 'desc')
            ->get();
        
        $almacenes = DB::table('alm__almacens as aa')
            ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
            ->where('aa.activo', 1)
            ->select(
                'aa.id',
                'aa.codigo as codalamcen',
                'aa.nombre_almacen',
                'aa.idsucursal'
            )
            ->get();
        
        $sucursales = $sucursales0->map(function($su) use ($almacenes) {
            $cadenaCod = "";
            $cadenaNom = "";
            $almacenesSuc = $almacenes->where('idsucursal', $su->id);
            
            if ($almacenesSuc->isNotEmpty()) {
                $cods = $almacenesSuc->pluck('codalamcen')->implode(' ');
                $nombres = $almacenesSuc->pluck('nombre_almacen')->implode(' ');
                
                $cadenaCod .= ' ' . $cods;
                $cadenaNom .= ' ' . $nombres;
                
                $su->codalmacen = trim($cadenaCod);
                $su->nombre_alm = trim($cadenaNom);
            } else {
                $su->codalmacen = 'Sin almacen';
                $su->nombre_alm = 'Sin almacen';
            }
        
            return $su;
        });
        
     // Ahora vamos a utilizar el paginador de Laravel
$sucursalesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
    $sucursales->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 10),
    $sucursales->count(),
    10,
    \Illuminate\Pagination\Paginator::resolveCurrentPage(),
    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
);
        
 
            return ['pagination'=>[
                'total'         =>    $sucursalesPaginated->total(),
                'current_page'  =>    $sucursalesPaginated->currentPage(),
                'per_page'      =>    $sucursalesPaginated->perPage(),
                'last_page'     =>    $sucursalesPaginated->lastPage(),
                'from'          =>    $sucursalesPaginated->firstItem(),
                'to'            =>    $sucursalesPaginated->lastItem(),

            ] ,
                'sucursalesPaginated'=>$sucursalesPaginated,
        ];
        }
        
        //$sucursales = Adm_Sucursal::all();
        
      
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
        $letracodigo='SU';
        $letrasCodigoTienda = 'TDA';

        $maxcorrelativo = Adm_Sucursal::select(DB::raw('max(correlativo) as maximo'))
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

                
        
        $codigoTienda = $letrasCodigoTienda.$codigo;        
        $codigo=$letracodigo.$codigo;
        
        
        $sucursal = new Adm_Sucursal();
        $sucursal->idrubro = $request->idrubro;
        $sucursal->tipo = $request->tipo;
        $sucursal->cod = $codigo;
        $sucursal->correlativo = $correlativo;
        $sucursal->razon_social = $request->razon_social;
        $sucursal->nombre_comercial = $request->nombre_comercial;
        $sucursal->telefonos = $request->telefonos;
        $sucursal->nit = $request->nit;
        $sucursal->direccion = $request->direccion;
        $sucursal->departamento = $request->departamento;
        $sucursal->ciudad = $request->ciudad;
        $sucursal->id_usuario_registra=auth()->user()->id;
        $sucursal->save();

        $tienda = new Tda_Tienda();
        $tienda->codigo = $codigoTienda;
        $tienda->idsucursal = $sucursal->id;
        $tienda->activo = 1;
        $tienda->id_usuario_registra = auth()->user()->id;
        $tienda->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adm_Sucursal  $adm_Sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(Adm_Sucursal $adm_Sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adm_Sucursal  $adm_Sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(Adm_Sucursal $adm_Sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Adm_Sucursal  $adm_Sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $sucursal = Adm_Sucursal::findOrFail($request->id);

        $sucursal->idrubro=$request->idrubro;
        $sucursal->tipo=$request->tipo;
        $sucursal->razon_social=$request->razon_social;
        $sucursal->nombre_comercial=$request->nombre_comercial;
        $sucursal->telefonos=$request->telefonos;
        $sucursal->nit=$request->nit;
        $sucursal->direccion=$request->direccion;
        $sucursal->departamento = $request->departamento;
        $sucursal->ciudad=$request->ciudad;
        $sucursal->id_usuario_modifica=auth()->user()->id;
        $sucursal->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adm_Sucursal  $adm_Sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Sucursal $adm_Sucursal)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $sucursal = Adm_Sucursal::findOrFail($request->id);
        $sucursal->activo=0;
        $sucursal->id_usuario_modifica=auth()->user()->id;
        $sucursal->save();
    }

    public function activar(Request $request)
    {
        $sucursal = Adm_Sucursal::findOrFail($request->id);
        $sucursal->activo=1;
        $sucursal->id_usuario_modifica=auth()->user()->id;
        $sucursal->save();
    }
    public function selectSucursal(Request $request)
    {
        // $sucursales=Adm_Sucursal::select('id','cod','tipo','razon_social as nombre')
        //                         ->where('activo',1)
        //                         ->orderBy('cod', 'asc')
        //                         ->get();

        $sucursales2 = DB::table('adm__sucursals')
    ->join('adm__rubros', 'adm__rubros.id', '=', 'adm__sucursals.idrubro')
    ->select('adm__sucursals.id', 'adm__sucursals.cod', 'adm__sucursals.tipo', 'adm__sucursals.razon_social as nombre', 'adm__sucursals.activo as activosucursal', 'adm__rubros.id as rubro_id', 'adm__rubros.nombre as nomrubro', 'adm__rubros.areamedica', 'adm__rubros.activo as activorubro')
    ->where('adm__sucursals.activo', 1)
    ->orderBy('adm__sucursals.cod', 'asc')
    ->get();
        // corregido por el ing.remberto datos uso del modelo incorrecto 
       // $sucursales=Adm_Sucursal::join('adm__rubros','adm__rubros.id','adm__sucursals.idrubro')
        //                        ->select(DB::raw('
        //                            adm__sucursals.id,
        //                            adm__sucursals.cod,
         //                           adm__sucursals.tipo,
          //                          adm__sucursals.razon_social as nombre,
          //                          adm__sucursals.activo as activosucursal,
           //                         adm__rubros.id,
           //                         adm__rubros.nombre as nomrubro,
           //                         adm__rubros.areamedica,
            //                        adm__rubros.activo as activorubro
            //                        '))
            //                    ->where('adm__sucursals.activo',1)
             //                   ->orderBy('adm__sucursals.cod', 'asc')
             //                   ->get();
          
        return $sucursales2;
    }

    public function listarListas(Request $request){
        $data = DB::table('prod__listas as pl')
    ->select('pl.id', 'pl.nombre_lista', 'pl.codigo_tda_alm', 'pl.id_tda_alm', 'ass.id as id_sucursal', 'ass.razon_social')
    ->join('tda__tiendas as tt', 'tt.codigo', '=', 'pl.codigo_tda_alm')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'tt.idsucursal')
    ->where('pl.activo', 1)
    ->where('pl.id_sucursal', $request->id)
    ->unionAll(DB::table('prod__listas as pl')
    ->select('pl.id', 'pl.nombre_lista', 'pl.codigo_tda_alm', 'pl.id_tda_alm', 'ass.id as id_sucursal', 'aa.nombre_almacen as razon_social')
    ->join('alm__almacens as aa', 'aa.codigo', '=', 'pl.codigo_tda_alm')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
    ->where('pl.activo', 1)
    ->where('pl.id_sucursal', $request->id))
    ->get();
    return  $data;

    }

    public function registrarlista(Request $request){
        try {
           // casos uno
        if ($request->id_rapido==1) {

            Adm_SucursalLista::where('id_sucursal', $request->id_sucursal)->delete();
         }else{  
             // Buscar asignaciones existentes 
        $asignarExistente = Adm_SucursalLista::where('id_sucursal', $request->id_sucursal)->get();

        // Si existen asignaciones, eliminarlas
        if ($asignarExistente->count() > 0) {
            Adm_SucursalLista::where('id_sucursal', $request->id_sucursal)->delete();
        }
            DB::table('adm__sucursal_listas')->insert([
                'id_sucursal' => $request->id_sucursal,
                'rapido' => $request->id_rapido,
                'id_lista' => $request->valor_rapido,
            ]);
         }
         // caso dos
         if ($request->id_avanzado==1) {          
             Adm_SucursalListaAvanzada::where('id_sucursal', $request->id_sucursal)->delete();
         } else {
              // Buscar asignaciones existentes 
        $asignarExistente = Adm_SucursalListaAvanzada::where('id_sucursal', $request->id_sucursal)->get();

        // Si existen asignaciones, eliminarlas
        if ($asignarExistente->count() > 0) {
            Adm_SucursalListaAvanzada::where('id_sucursal', $request->id_sucursal)->delete();
        }
        $bloque = $request->valor_avanzado;
            foreach ($bloque as $item) {
             $avanzado = $item['id'];
          
             $datos = [
                 'id_sucursal' => $request->id_sucursal,
                 'avanzado' => $request->id_avanzado,
                 'id_lista' => $avanzado,             
             ];

             DB::table('adm__sucursal_lista_avanzadas')->insert($datos);          
          }
                  
 
         }
        }catch (\Throwable $th) {
          
            return response()->json(['error' => $th->getMessage()],500);
        }
    }

    public function listarArray(Request $request) {
        $resultado1 = DB::table('adm__sucursal_listas as aa')
        ->where('aa.id_sucursal', $request->id)
        ->get();
    
    $resultado2 = DB::table('adm__sucursal_lista_avanzadas as bb')
        ->where('bb.id_sucursal', $request->id)
        ->get();
      
     
        return ['resultado1' => $resultado1,
            'resultado2' => $resultado2
        ];
    }
    
}
