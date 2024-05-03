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
                        $sqls="(razon_social like '%".$valor."%' or nit like '%".$valor."%' or direccion like '%".$valor."%' or adm__rubros.nombre like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (razon_social like '%".$valor."%' or nit like '%".$valor."%' or direccion like '%".$valor."%' or adm__rubros.nombre like '%".$valor."%')" ;
                    }
    
                }
                /**
                 * select adm__sucursals.id,
                 * 	      adm__rubros.id as idrubro,
                 * 	      adm__rubros.nombre as nomrubro,
                 *        adm__sucursals.tipo,
                 *        adm__sucursals.cod,
                 *        adm__sucursals.correlativo,
                 *        adm__sucursals.razon_social,
                 *        adm__sucursals.nombre_comercial,
                 *        adm__sucursals.telefonos,
                 *        adm__sucursals.nit,
                 *        adm__sucursals.direccion,
                 *        adm__sucursals.ciudad, 
                 *        adm__sucursals.activo, 
                 *        alm__almacens.codigo  as codalamcen
                 * from adm__sucursals
                 * join adm__rubros on adm__sucursals.idrubro = adm__rubros.id
                 * join alm__almacens on adm__sucursals.id = alm__almacens.idsucursal
                 */


                $sucursales= Adm_Sucursal::join('adm__rubros','adm__rubros.id','adm__sucursals.idrubro')
                                         ->leftJoin('alm__almacens','adm__sucursals.id','alm__almacens.idsucursal')
                                            ->select('adm__sucursals.id',
                                                    'adm__rubros.id as idrubro',
                                                    'adm__rubros.nombre as nomrubro',
                                                    'adm__sucursals.tipo',
                                                    'adm__sucursals.cod',
                                                    'adm__sucursals.correlativo',
                                                    'adm__sucursals.razon_social',
                                                    'adm__sucursals.nombre_comercial',
                                                    'adm__sucursals.telefonos',
                                                    'adm__sucursals.nit',
                                                    'adm__sucursals.direccion',
                                                    'adm__sucursals.departamento',
                                                    'adm__sucursals.ciudad',
                                                    'adm__sucursals.activo',
                                                    DB::raw('alm__almacens.codigo  as codalamcen')
                                                    )
                                            //->orderby('razon_social','asc')
                                            ->orderby('correlativo','asc')
                                            ->whereraw($sqls)
                                            
                                            ->paginate(50);
            }
        }
        
        else
        {
            $sucursales= Adm_Sucursal::join('adm__rubros','adm__rubros.id','adm__sucursals.idrubro')
                                     ->leftJoin('alm__almacens','adm__sucursals.id','alm__almacens.idsucursal')
                                    ->select('adm__sucursals.id',
                                            'adm__rubros.id as idrubro',
                                            'adm__rubros.nombre as nomrubro',
                                            'adm__sucursals.tipo',
                                            'adm__sucursals.cod',
                                            'adm__sucursals.correlativo',
                                            'adm__sucursals.razon_social',
                                            'adm__sucursals.nombre_comercial',
                                            'adm__sucursals.telefonos',
                                            'adm__sucursals.nit',
                                            'adm__sucursals.direccion',
                                            'adm__sucursals.departamento',
                                            'adm__sucursals.ciudad',
                                            'adm__sucursals.activo',
                                            DB::raw('alm__almacens.codigo  as codalamcen')
                                            )
                                    //->orderby('razon_social','asc')
                                    ->orderby('correlativo','asc')
                                    ->paginate(50);
        }
        
        //$sucursales = Adm_Sucursal::all();
        
        return ['pagination'=>[
                'total'         =>    $sucursales->total(),
                'current_page'  =>    $sucursales->currentPage(),
                'per_page'      =>    $sucursales->perPage(),
                'last_page'     =>    $sucursales->lastPage(),
                'from'          =>    $sucursales->firstItem(),
                'to'            =>    $sucursales->lastItem(),

            ] ,
                'sucursales'=>$sucursales,
        ];
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
