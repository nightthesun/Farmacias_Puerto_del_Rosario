<?php

namespace App\Http\Controllers;

use App\Models\Siat_evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $data= $request->inicial;

        switch ($data) {
            case 1:
                    $sucursal = $request->id_sucursal;
                    if($sucursal=='10000'){
                    $sqls="( ss.id <> 0)" ;
                    }else{
                    $sqls="( ss.id = '".(int)$sucursal."' )" ;
                    }
                                     
            break;
            case 2:
                    $num_factura = $request->num_factura;
                 $sqls="( f.numFactura = '".(int)$num_factura."' )" ;                    
            break;
            case 3:
                         $cuf = $request->cuf;
                 $sqls="( f.cuf = '".$cuf."' )" ;                    
            break;
            case 4:           
        $sector = $request->sector;
            $sqls="( f.codSector = '".(int)$sector."' )" ;                   
            break;
            case 5:           
         $estado = $request->estado;
                 $sqls="( f.codEstado = '".$estado."' )" ;                    
            break;
             case 6:           
         $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
              $sqls = "( f.fechaEmision BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' )";
                
            break;
            
            default:
                 $sqls="( ss.id <> 0)" ;
                break;
        }

        $index = DB::table('ven__factura_siat as f')
    ->join('ven__recibos as r', 'r.id', '=', 'f.id_venta')
    ->join('adm__sucursals as ss', 'ss.id', '=', 'r.id_sucursal')
    ->join('excel__emision as e', function ($join) {
        $join->on('e.codigo', '=', 'f.codSector')
             ->where('e.id_catalogo', 3);
    })
    ->whereRaw($sqls)
    ->select(
        'f.id',
        'f.id_venta',
        'f.id_cufd',
        'f.id_cuis',
        'f.cuf',
        DB::raw('f.sucursal_siat as punto_suc'),
        'f.punto_venta',
        'f.numFactura',
        'f.fechaEmision',
        'f.codRecepcion',
        'f.codDescripcion',
        'f.codEstado',
        'f.codSector',
        'f.tipo_contigencia',
        DB::raw('r.id as id_venta'),
        'r.nro_doc',
        DB::raw('r.razon_social as nom_cliente'),
        DB::raw('ss.id as id_sucursal'),
        DB::raw('ss.razon_social as nomre_sucursal'),
        DB::raw('e.descripcion as sector_descripcion'),
        DB::raw('e.codigo as cod_sector')
    )
  
    ->orderBy('f.id', 'desc')
    ->paginate(15);
         return   [
                    'pagination'=>
                        [
                            'total'         =>    $index->total(),
                            'current_page'  =>    $index->currentPage(),
                            'per_page'      =>    $index->perPage(),
                            'last_page'     =>    $index->lastPage(),
                            'from'          =>    $index->firstItem(),
                            'to'            =>    $index->lastItem(),
                        ] ,
                    'index'=>$index,
            ];
    }

    public function getEmisorAndSector(){

        $query_1 = DB::table('siat__emisors as e')
    ->join('siat__sucursals as s', 'e.id_siat_sucursal', '=', 's.id')
    ->join('adm__sucursals as ss', 'ss.id', '=', 's.id_sucursal')
    ->select(
        'e.id as id_emisor',
        'e.id_punto_venta',
        'e.nombre as nombre_emisor',
        'e.id_siat_sucursal',
        's.nombre_suc_siat',
        's.codigo_siat',
        'ss.razon_social',
        'ss.id as id_sucursal'
    )
    ->where('e.estado', 1)
    ->get();

    if (count($query_1)>0) {
        $error_1=1;
    }else{
        $error_1=0;
    }

    $query_2 = DB::table('excel__emision')
    ->select(
        'codigo',
        'id_catalogo',
        'descripcion'
    )
    ->where('id_catalogo', 3)
    ->get();
    if (count($query_2)>0) {
        $error_2=1;
    }else{
        $error_2=0;
    }

    return response()->json(['emisor_query_1' => $query_1, 'error_1' => $error_1,
                            'sector_query_2' => $query_2,'error_2' => $error_2                          
    ]);
   
    }

    public function getQueryModal_1(Request $request){
                
    $contigencia = DB::table('excel__emision')
            ->select('*')
            ->where('id_catalogo', 6)
            ->where('codigo', (int)$request->id_contigencia)
            ->first();

            $sucursal = DB::table('adm__sucursals as ass')
            ->join('siat__sucursals as ss', 'ass.id', '=', 'ss.id_sucursal')
            ->join('siat__emisors as e', 'ss.id_emisor', '=', 'e.id')
            ->select(
                'ass.razon_social',
                'ss.nombre_suc_siat'
            )
            ->where('ass.id', (int)$request->id_sucursal)
            ->where('ss.codigo_siat', (int)$request->suc)
            ->first();
        
    return response()->json(['contigencia' => $contigencia, 'sucursal' => $sucursal]);        
     
    }


    
}
