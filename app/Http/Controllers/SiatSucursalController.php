<?php

namespace App\Http\Controllers;

use App\Models\Siat_Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatSucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
                            ss.nombre_suc_siat like '%".$valor."%' 
                                or ss.codigo_siat like '%".$valor."%' 
                                or u.name like '%".$valor."%'                                                                                           
                              )" ;
                    }
                    else
                    {
                        $sqls.="and (
                           ss.nombre_suc_siat like '%".$valor."%' 
                            or ss.codigo_siat like '%".$valor."%' 
                            or u.name like '%".$valor."%'                           
                          )" ;
                    }    
                }
                //query
                $index = DB::table('siat__sucursals as ss')
                ->join('users as u', 'u.id', '=', 'ss.id_usuario_modifica')
                ->join('adm__departamentos as ad', 'ad.id', '=', 'ss.departamento')
                ->select('ss.id','ss.nombre_suc_siat','ss.codigo_siat','ss.id_sucursal','u.name','ad.nombre as departamento','ss.estado','ss.updated_at')
                ->whereRaw($sqls)
                ->orderBy('id', 'desc')
                ->paginate(15); 
            }
            return 
            [
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
        } else {
            $index = DB::table('siat__sucursals as ss')
            ->join('users as u', 'u.id', '=', 'ss.id_usuario_modifica')
            ->join('adm__departamentos as ad', 'ad.id', '=', 'ss.departamento')
            ->select('ss.id','ss.nombre_suc_siat','ss.codigo_siat','ss.id_sucursal','u.name','ad.nombre as departamento','ss.estado','ss.updated_at')
         
            ->orderBy('id', 'desc')
            ->paginate(15); 
            return 
            [
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
    }

    public function store(Request $request){
      try {

        DB::beginTransaction();
        $cadena = $request->id_sucursal;

        $valores = explode(",", $cadena);
    
        for ($i = 0; $i < count($valores); $i++) {
            $valor = $valores[$i];  // Asignar el valor a una variable
 
            $ids = DB::table('siat__sucursals')
                ->select('id')
                ->where('id_sucursal', 'LIKE', "%$valor%")
                ->get();
                if ($ids->count()>0) {
                    return "Sucursal ya usada";
                }
        }
        
      

        $sucursales = DB::table('siat__sucursals')
    ->select('id', 'nombre_suc_siat')
    ->where('codigo_siat', intval($request->codigo))
    ->where('estado', 1)
    ->get();
    // Verificar si hay resultados
if ($sucursales->count() > 0) {
    // Si hay resultados
    return "Ya existe el codigo siat";  
} 
        

        $crear = new Siat_Sucursal();
        $crear->nombre_suc_siat=$request->nombre;
        $crear->codigo_siat=intval($request->codigo);
        $crear->departamento=$request->departamento;
        $crear->id_sucursal=$request->id_sucursal;
        $crear->id_usuario_registra=auth()->user()->id;
        $crear->id_usuario_modifica=auth()->user()->id;       
        $crear->save();
        DB::commit();
      } catch (\Throwable $th) {
        return $th;
      }
    }

    public function departamento_siat(){
        $departamentos = DB::table('adm__departamentos as ad')
        ->select('ad.id', 'ad.nombre', 'ad.abrev')
        ->get();
        return $departamentos;
    } 

    public function sucursal_siat(){
        $sucursales = DB::table('adm__sucursals as ass')
    ->select('ass.id', 'ass.tipo', 'ass.cod', 'ass.razon_social', 'ass.nombre_comercial', DB::raw('0 AS seleccionado'))
    ->where('ass.activo', 1)
    ->get();
  
        return $sucursales;
    }

    public function modificar_sucursal_sec(Request $request){
        try {
            DB::beginTransaction();
            DB::table('adm__sucursals')
            ->where('id', $request->id)
            ->update(['seleccionado' => $request->data]);    
            DB::commit();
        } catch (\Throwable $th) {
            return $th;
        }
       
    }
}
