<?php

namespace App\Http\Controllers;

use App\Models\Ser_Venta_Maestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerVentaMaestroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray=array();
        $fechainicio = date("Y-m-d", strtotime($request->fechainicio));
        $fechafin=date("Y-m-d",strtotime($request->fechafin));
        $raw=DB::raw(DB::raw('concat(papellido," ",sapellido," ",nombre) as nombres'));
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(papellido like '%".$valor."%' or sapellido like '%".$valor."%' or nombre like '%".$valor."%' )";
                    }
                    else
                    {
                        $sqls.=" and (papellido like '%".$valor."%' or sapellido like '%".$valor."%' or nombre like '%".$valor."%' )";
                    }
    
                }
                $ventamaestro= Ser_Venta_Maestro::join('par__clientes','par__clientes.id','ser__venta__maestros.idcliente')
                                            ->select($raw,
                                                    'ser__venta__maestros.id as id',
                                                    'total',
                                                    'efectivo',
                                                    'cambio',
                                                    'ser__venta__maestros.created_at',
                                                    'ser__venta__maestros.activo')
                                            ->orderby('ser__venta__maestros.created_at','desc')
                                            ->whereraw($sqls)
                                            ->where('ser__venta__maestros.activo',1)
                                            ->whereBetween(DB::raw('date(ser__venta__maestros.created_at)'), [$fechainicio, $fechafin])
                                            ->paginate(20);
                
            }
        }
        
        else
        {
            $ventamaestro= Ser_Venta_Maestro::join('par__clientes','par__clientes.id','ser__venta__maestros.idcliente')
                                        ->select($raw,
                                                'ser__venta__maestros.id as id',
                                                'total',
                                                'efectivo',
                                                'cambio',
                                                'ser__venta__maestros.created_at',
                                                'ser__venta__maestros.activo')
                                                ->orderby('ser__venta__maestros.created_at','desc')
                                                ->where('ser__venta__maestros.activo',1)
                                                ->whereBetween(DB::raw('date(ser__venta__maestros.created_at)'), [$fechainicio, $fechafin])
                                        ->paginate(20);
        }
        
        //$ventamaestro = VentaMaestro::all();
        $sumatotal=0;
        foreach ($ventamaestro as $key => $value) {
            $sumatotal=$sumatotal+$value->total;
        }
        return ['pagination'=>[
                                'total'         =>    $ventamaestro->total(),
                                'current_page'  =>    $ventamaestro->currentPage(),
                                'per_page'      =>    $ventamaestro->perPage(),
                                'last_page'     =>    $ventamaestro->lastPage(),
                                'from'          =>    $ventamaestro->firstItem(),
                                'to'            =>    $ventamaestro->lastItem(),

                                ] ,
                'ventamaestro'=>$ventamaestro,
                'sumatotal'=>$sumatotal
            ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(is_null(session('idsuc')))
            $idsucursal=0;
        else
            $idsucursal=session('idsuc');

        $ventamaestro = new Ser_Venta_Maestro();

        $ventamaestro->num_documento=1;
        $ventamaestro->tipodocumento=1;
        $ventamaestro->idcliente=$request->idcliente;
        $ventamaestro->total=$request->total;
        $ventamaestro->efectivo=$request->efectivo;
        $ventamaestro->cambio=$request->cambio;
        $ventamaestro->id_usuario_registra=auth()->user()->id;
        $ventamaestro->idsucursal=$idsucursal;
        $ventamaestro->save();
        $id=$ventamaestro->id;
        
        DB::table('ser__ventas')
            ->where('estado', 0)
            ->where('idsucursal',$idsucursal)
            ->where('id_usuario_registra',auth()->user()->id)
            ->update(['estado' => 1,
                        'idventamaestro'=>$id]);
        return 'correcto';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ser_Venta_Maestro  $ser_Venta_Maestro
     * @return \Illuminate\Http\Response
     */
    public function show(Ser_Venta_Maestro $ser_Venta_Maestro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ser_Venta_Maestro  $ser_Venta_Maestro
     * @return \Illuminate\Http\Response
     */
    public function edit(Ser_Venta_Maestro $ser_Venta_Maestro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ser_Venta_Maestro  $ser_Venta_Maestro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ser_Venta_Maestro $ser_Venta_Maestro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ser_Venta_Maestro  $ser_Venta_Maestro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ser_Venta_Maestro $ser_Venta_Maestro)
    {
        //
    }
    public function registrarVentaMaestro()
    {
        //$venta=Venta::
        DB::table('ventas')
                ->where('estado', 0)
                ->update(['estado' => 1]);
    }
    public function desactivar(Request $request)
    {
        $ventamaestro = Ser_Venta_Maestro::findOrFail($request->id);
        $ventamaestro->activo=0;
        $ventamaestro->id_usuario_modifica=auth()->user()->id;
        $ventamaestro->save();
    }

    public function activar(Request $request)
    {
        $ventamaestro = Ser_Venta_Maestro::findOrFail($request->id);
        $ventamaestro->activo=1;
        $ventamaestro->id_usuario_modifica=auth()->user()->id;
        $ventamaestro->save();
    }
}
