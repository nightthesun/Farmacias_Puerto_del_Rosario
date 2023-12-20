<?php

namespace App\Http\Controllers;

use App\Models\Ser_Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $venta = new Ser_Venta();
        
        $venta->idprestacion=$request->idprestacion;
        $venta->iddescuento=$request->iddescuento;
        $venta->monto_a_cancelar=$request->monto_a_cancelar;
        $venta->id_usuario_registra=auth()->user()->id;
        //dd(session('idsuc'));
        if(is_null(session('idsuc')))
            $venta->idsucursal=0;
        else
            $venta->idsucursal=session('idsuc');
        $venta->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ser_Venta  $ser_Venta
     * @return \Illuminate\Http\Response
     */
    public function show(Ser_Venta $ser_Venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ser_Venta  $ser_Venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Ser_Venta $ser_Venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ser_Venta  $ser_Venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ser_Venta $ser_Venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ser_Venta  $ser_Venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ser_Venta $ser_Venta)
    {
        //
    }
    public function ventasListar()
    {
        if(is_null(session('idsuc')))
            $idsucursal=0;
        else
            $idsucursal=session('idsuc');
        $raw=DB::raw('concat(ser__areas.codigo,ser__prestacions.codigo) as cod');
        $raw2=DB::raw('concat(par__desc_servicios.nombre," ",monto,IF(siporcentaje=1, "%", "Bs.")) as descuento');
        $ser__ventas=Ser_Venta::select($raw,$raw2,
                        'ser__ventas.id as id',
                        'ser__prestacions.nombre as nompres',
                        'ser__prestacions.precio',
                        'par__desc_servicios.nombre as nomdesc',
                        'monto_a_cancelar',
                        'estado')
                        ->join('ser__prestacions','ser__prestacions.id','ser__ventas.idprestacion')
                        ->join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                        ->leftjoin('par__desc_servicios','par__desc_servicios.id','ser__ventas.iddescuento')
                        ->where('estado',0)
                        ->where('ser__ventas.id_usuario_registra',auth()->user()->id)
                        ->where('ser__ventas.idsucursal',$idsucursal)
                        ->orderby('ser__ventas.created_at','asc')
                        ->get();

        $sumatotal=0;        
        
        foreach ($ser__ventas as $value) {
            $sumatotal=$sumatotal+$value->monto_a_cancelar;
        }

        return ['ventas'=>$ser__ventas,
                'sumatotal'=>$sumatotal];
    }
    public function desactivar(Request $request)
    {
        $venta = Ser_Venta::findOrFail($request->id);
        $venta->estado=2;
        $venta->id_usuario_modifica=auth()->user()->id;
        $venta->save();
    }
    public function ventasDetalle(Request $request)
    {
        $id=$request->id;
        $raw=DB::raw('concat(ser__areas.codigo,ser__prestacions.codigo) as cod');
        $raw2=DB::raw('concat(par__desc_servicios.nombre," ",monto,IF(siporcentaje=1, "%", "Bs.")) as descuento');
        $ventadetalle=Ser_Venta::select($raw,$raw2,
                        'ser__ventas.id as id',
                        'ser__prestacions.nombre as nompres',
                        'ser__prestacions.precio',
                        'par__desc_servicios.nombre as nomdesc',
                        'monto_a_cancelar',
                        'estado')
                        ->join('ser__prestacions','ser__prestacions.id','ser__ventas.idprestacion')
                        ->join('ser__areas','ser__areas.id','ser__prestacions.idarea')
                        ->leftjoin('par__desc_servicios','par__desc_servicios.id','ser__ventas.iddescuento')
                        ->where('estado',1)
                        ->where('idventamaestro',$id)
                        ->orderby('ser__ventas.created_at','asc')
                        ->get();
        return $ventadetalle;

    }
}
