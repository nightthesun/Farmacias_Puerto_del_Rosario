<?php

namespace App\Http\Controllers;

use App\Models\Prod_Descuento;
use Illuminate\Http\Request;

class ProdDescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        {
            $buscararray=array();
            if(!empty($request->buscar)){
                $buscararray = explode(" ",$request->buscar);
                //dd($buscararray);
                $valor=sizeof($buscararray);
                if($valor > 0)
                {
                    $sqls='';
                    foreach($buscararray as $valor)
                    {
                        if(empty($sqls)){
                            $sqls="(nombre like '%".$valor."%')" ;
                        }
                        else
                        {
                            $sqls.=" and (nombre like '%".$valor."%')" ;
                        }
        
                    }
                    $descuentoProductos= Prod_Descuento::orderby('nombre','asc')
                                                ->whereraw($sqls)
                                                ->paginate(20);
                }
            }
            
            else
            {
                $descuentoProductos= Prod_Descuento::selectRaw('id,nombre,monto_descuento,idtipodescuento,regla,aplica_a,estado,activo')
                                                    ->orderby('nombre','asc')
                                                    ->paginate(20);
            }
            
            //$categoria = Prod_Descuento::all();
            return ['pagination'=>[
                'total'         =>    $descuentoProductos->total(),
                'current_page'  =>    $descuentoProductos->currentPage(),
                'per_page'      =>    $descuentoProductos->perPage(),
                'last_page'     =>    $descuentoProductos->lastPage(),
                'from'          =>    $descuentoProductos->firstItem(),
                'to'            =>    $descuentoProductos->lastItem(),
    
            ] ,
                    'descuentos'=>$descuentoProductos,
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
        $nuevoDescuentoProducto=new Prod_Descuento();
        $nuevoDescuentoProducto->nombre=$request->nombre;
        $nuevoDescuentoProducto->monto_descuento=$request->monto_descuento;
        $nuevoDescuentoProducto->idtipodescuento=$request->idtipodescuento;
        $nuevoDescuentoProducto->regla=$request->regla;
        $nuevoDescuentoProducto->aplica_a=$request->aplica_a;
        $nuevoDescuentoProducto->activo=$request->activo;
        $nuevoDescuentoProducto->estado=$request->estado;
        $nuevoDescuentoProducto->id_usuario_registra=auth()->user()->id;
        $nuevoDescuentoProducto->save();
        //return Prod_Descuento::create(request()->input());
        //return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prod_Descuento  $prod_Descuento
     * @return \Illuminate\Http\Response
     */
    public function show(Prod_Descuento $prod_Descuento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prod_Descuento  $prod_Descuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Prod_Descuento $prod_Descuento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prod_Descuento  $prod_Descuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prod_Descuento $prod_Descuento)
    {
        $oldProducto= Prod_Descuento::find($request->id);
        $oldProducto->nombre = $request->nombre;
        $oldProducto->monto_descuento = $request->monto_descuento ;
        $oldProducto->regla = $request->regla ;
        $oldProducto->aplica_a = $request->aplica_a;
        $oldProducto->id_usuario_registra = auth()->user()->id;
        $oldProducto->save();
        // return $oldProducto;
        // return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prod_Descuento  $prod_Descuento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prod_Descuento $prod_Descuento)
    {
        //
    }
}
