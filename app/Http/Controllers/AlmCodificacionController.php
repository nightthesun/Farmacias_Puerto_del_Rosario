<?php

namespace App\Http\Controllers;

use App\Models\Alm_Codificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AlmCodificacionController extends Controller
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
                        // $sqls="(codestante like '%".$valor."%' or razon_social like '%".$valor."%')" ;
                        $sqls="(codestante like '%".$valor."%' or nombre_almacen like '%".$valor."%')" ;
                    }
                    else
                    {
                        // $sqls.=" and (codestante like '%".$valor."%' or razon_social like '%".$valor."%')" ;
                        $sqls.=" and (codestante like '%".$valor."%' or nombre_almacen like '%".$valor."%')" ;
                    }
    
                }
                // $estantes= Alm_Codificacion::join('adm__sucursals','adm__sucursals.id','alm__codificacions.idsucursal')
                //                             ->select('alm__codificacions.id',
                //                                     'razon_social',
                //                                      'telefonos',
                //                                      'idsucursal',
                //                                      'letraestante',
                //                                      'codestante',
                //                                      'numposicion',
                //                                      'numaltura',
                //                                      'alm__codificacions.activo')
                //                             ->orderby('letraestante','asc');
                //                             if($request->idsucursal!=0)
                //                                 $estantes=$estantes->where('idsucursal',$request->idsucursal);
                //                             $estantes=$estantes->whereraw($sqls)
                //                             ->paginate(40);

                $estantes= Alm_Codificacion::join('alm__almacens','alm__almacens.id','alm__codificacions.idalmacen')
                                        ->selectRaw('alm__codificacions.id,
                                                alm__almacens.id as idalmacen,
                                                alm__almacens.nombre_almacen,
                                                alm__almacens.telefono,
                                                alm__almacens.idsucursal,
                                                alm__codificacions.letraestante,
                                                alm__codificacions.codestante,
                                                alm__codificacions.numposicion,
                                                alm__codificacions.numaltura,
                                                alm__codificacions.activo')
                                        ->orderby('letraestante','asc');
                                        if($request->idalmacen!=0)
                                            $estantes=$estantes->where('idalmacen',$request->idalmacen);
                                        $estantes=$estantes->whereraw($sqls)
                                        ->paginate(40);
            }
        }
        
        else
        {
            // $estantes= Alm_Codificacion::join('adm__sucursals','adm__sucursals.id','alm__codificacions.idsucursal')
            //                             ->select('alm__codificacions.id',
            //                                     'razon_social',
            //                                     'telefonos',
            //                                     'idsucursal',
            //                                     'letraestante',
            //                                     'codestante',
            //                                     'numposicion',
            //                                     'numaltura',
            //                                     'alm__codificacions.activo')
            //                             ->orderby('letraestante','asc')
            //                             ->where('idsucursal',$request->idsucursal)
            //                             ->paginate(40);
            $estantes= Alm_Codificacion::join('alm__almacens','alm__almacens.id','alm__codificacions.idalmacen')
                                        ->selectRaw('alm__codificacions.id,
                                                alm__almacens.id as idalmacen,
                                                alm__almacens.nombre_almacen,
                                                alm__almacens.telefono,
                                                alm__almacens.idsucursal,
                                                alm__codificacions.letraestante,
                                                alm__codificacions.codestante,
                                                alm__codificacions.numposicion,
                                                alm__codificacions.numaltura,
                                                alm__codificacions.activo')
                                        ->orderby('letraestante','asc')
                                        ->where('idalmacen',$request->idalmacen)
                                        ->paginate(40);
        }
        
        $maxletra = Alm_Codificacion::select(DB::raw('max(numletra) as maximo'))
                                ->where('idalmacen',$request->idalmacen)
                                ->get();
        
        $sigletra=$maxletra[0]->maximo;
        //dd($sigletra);
        if(is_null($sigletra))
            $sigletra=1;
        else
            $sigletra=$sigletra+1;

        
        $letra=$this->getByPosition($sigletra);
        
        
        return ['pagination'=>[
            'total'         =>    $estantes->total(),
            'current_page'  =>    $estantes->currentPage(),
            'per_page'      =>    $estantes->perPage(),
            'last_page'     =>    $estantes->lastPage(),
            'from'          =>    $estantes->firstItem(),
            'to'            =>    $estantes->lastItem(),

        ] ,
                'estantes'=>$estantes,
                'letra'=>$letra,
                'numletra'=>$sigletra

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
        $codificacion= new Alm_Codificacion();
        $codificacion->idalmacen=$request->idalmacen;
        $codificacion->codestante=$request->codestante;
        $codificacion->letraestante=$request->letraestante;
        $codificacion->numletra=$request->numletra;
        $codificacion->numposicion=$request->numposicion;
        $codificacion->numaltura=$request->numaltura;
        $codificacion->id_usuario_registra=auth()->user()->id;
        $codificacion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alm_Codificacion  $alm_Codificacion
     * @return \Illuminate\Http\Response
     */
    public function show(Alm_Codificacion $alm_Codificacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alm_Codificacion  $alm_Codificacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Alm_Codificacion $alm_Codificacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alm_Codificacion  $alm_Codificacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alm_Codificacion $alm_Codificacion)
    {
        $codificacion = Alm_Codificacion::findOrFail($request->id);
        $codificacion->numposicion=$request->numposicion;
        $codificacion->numaltura=$request->numaltura;
        $codificacion->id_usuario_modifica=auth()->user()->id;
        $codificacion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alm_Codificacion  $alm_Codificacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alm_Codificacion $alm_Codificacion)
    {
        //
    }

    function getByPosition($index){
        #Crea un array con las letras de la A a la Z
        $alphabet = range('A', 'Z');
        #Seteamos la posición restando 1 porque los índices comienzan en 0
        $pos=$index-1;
        #Retornamos la letra, o NULL, si $index desborda el array
        #Para evitar que true sea tratado como índice 1, controlamos con is_bool también
        return ( !empty($alphabet[$pos]) && !is_bool($index) ) ? $alphabet[$pos] : NULL ;
    }

    function imprimirCodigos(Request $request)
    {
        $estante = Alm_Codificacion::where('id',$request->idestante)
                                    ->get();

        $posicion=$estante[0]->numposicion;
        $altura=$estante[0]->numaltura;
        $codigo=$estante[0]->codestante;
        //dd($request->lista);
        if($request->lista==0)
        {
            return view('reporte_estante')->with(['posicion'=>$posicion,
            'altura'=>$altura,
            'codestante'=>$codigo
        ]);
        }   
        else
        {
            /* echo "entra";
            $data = [
                'title' => 'Lista de Codigos para Imprimir',
                'heading' => 'Lista de Codigos para Imprimir',
                'posicion'=>$posicion,
                'altura'=>$altura,
                'codestante'=>$codigo
                  ];
              
              $pdf = PDF::loadView('reporte_estante', $data);
        
              return $pdf->download('imprimir_codigos.pdf');
             */
            
            
            return ['posicion'=>$posicion,
            'altura'=>$altura,
            'codestante'=>$codigo];
        }       
    }

    public function desactivar(Request $request)
    {
        $estante = Alm_Codificacion::findOrFail($request->id);
        $estante->activo=0;
        $estante->id_usuario_modifica=auth()->user()->id;
        $estante->save();
    }

    public function activar(Request $request)
    {
        $estante = Alm_Codificacion::findOrFail($request->id);
        $estante->activo=1;
        $estante->id_usuario_modifica=auth()->user()->id;
        $estante->save();
    }

    public function selectEstante(Request $request)
    {
        $estante = Alm_Codificacion::where('idsucursal',$request->idsucursal)
                                    ->where('activo',1)
                                    ->select('id','codestante','numposicion','numaltura')
                                    ->get();
        return $estante;
                                    

    }

    public function pdf() 
    {
    	$data = [
            'title' => 'First PDF for Coding Driver',
            'heading' => 'Hello from Coding Driver',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'        
              ];
          
          $pdf = Pdf::loadView('sample', $data);
    
          return $pdf->stream('codingdriver.pdf');
    }
}
