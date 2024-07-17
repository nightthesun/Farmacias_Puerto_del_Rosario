<?php

namespace App\Http\Controllers;

use App\Models\Dir_Cliente;
use App\Models\dir_Persona;
use App\Models\dir_Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DirClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscararray=array();
        $limite_x=$request->limite;
        if ($limite_x==10) {
            $limite_x=9999999999999;
        }
        $bus = $request->query('buscarP_E');
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
               foreach($buscararray as $valor)
                {  
                                 
                    if(empty($sqls)){
                        $sqls="(
                            dc.num_documento  like '%".$valor."%' 
                            or dc.nom_a_facturar  like '%".$valor."%'
                            or dc.correo like '%".$valor."%' 
                        )" ;
                       
                       
                    }
                    else
                    {
                        $sqls.="and (
                           dc.num_documento  like '%".$valor."%' 
                           or dc.nom_a_facturar  like '%".$valor."%'
                           or dc.correo like '%".$valor."%'                      
                          )" ;                      
                    }
    
                }
                //------------------inicio query
      
            // Consulta para obtener los clientes personas
$clientesPersonas = DB::table('dir__clientes as dc')
->select(
    'dc.id as id',
    'dc.id_per_emp as id_persona_empresa',
    'dp.nombres as nombre','dp.apellidos as apellido',
    DB::raw("CONCAT(dp.nombres, ' ', dp.apellidos) AS nombre_completo"),
    DB::raw("CONCAT(COALESCE(dp.documento_identidad, ''), ' ', COALESCE(dp.complemento, '')) AS documento_identidad"),
    'dp.complemento as id_complemento',
    'dc.correo as correo',
    'dc.telefono as telefono',
    'dc.direccion as direccion',
    'dc.nom_a_facturar as nom_a_facturar',
    'dc.pais as pais',
    'dc.ciudad',
    'u.name as name',
    'u.id as id_user',
    DB::raw('GREATEST(dc.created_at, dc.updated_at) AS fecha_mas_reciente'),
    'dc.num_documento as num_documento',
    'dc.tipo_per_emp as tipo_per_emp',
    'dc.activo as activo',
    'dt.id as id_tipo_doc',

    'dt.nombre_doc as nom_documento',
    'dt.datos as datos_tipo_documento',
    DB::raw('MAX(vr.id_cliente) AS id_max')
)
->join('dir__tipo_doc as dt', 'dc.id_tipo_doc', '=', 'dt.id')
->join('users as u', 'dc.id_user', '=', 'u.id')
->join('dir__personas as dp', 'dp.id', '=', 'dc.id_per_emp')
->leftJoin('ven__recibos as vr', 'vr.id_cliente', '=', 'dc.id')
->whereRaw($sqls)

->where('dc.tipo_per_emp', '=', $bus)
->groupBy(
    'dc.id',
    'dc.id_per_emp',
    'dp.nombres',
    'dp.apellidos',
    'dp.documento_identidad',
    'dp.complemento',
    'dc.correo',
    'dc.telefono',
    'dc.direccion',
    'dc.nom_a_facturar',
    'dc.pais',
    'dc.ciudad',
    'u.name',
    'u.id',
    'dt.id',
    'dt.nombre_doc',
    'dt.datos',
    'dc.created_at',
    'dc.updated_at',
    'dc.num_documento',
    'dc.tipo_per_emp',
    'dc.activo'
)
->orderBy('dc.id', 'desc')
->limit($limite_x);
// Consulta para obtener los clientes empresas
$clientesEmpresas = DB::table('dir__clientes as dc')
->select(
    'dc.id as id',
    'dc.id_per_emp as id_persona_empresa',
    DB::raw('null as nombre'),
    DB::raw('null as apellido'), 
    'de.razon_social as nombre_completo',
    'de.nit as documento_identidad',
    DB::raw('null as id_complemento'),
    'dc.correo as correo',
    'dc.telefono as telefono',
    'dc.direccion as direccion',
    'dc.nom_a_facturar as nom_a_facturar',
    'dc.pais as pais',
    'dc.ciudad',
    'u.name as name',
    'u.id as id_user',
    DB::raw('GREATEST(dc.created_at, dc.updated_at) AS fecha_mas_reciente'),
    'dc.num_documento as num_documento',
    'dc.tipo_per_emp as tipo_per_emp',
    'dc.activo as activo',
    'dt.id as id_tipo_doc',
    
    'dt.nombre_doc as nom_documento',
    'dt.datos as datos_tipo_documento',
    DB::raw('MAX(vr.id_cliente) AS id_max')

)
->join('dir__tipo_doc as dt', 'dc.id_tipo_doc', '=', 'dt.id')
->join('users as u', 'dc.id_user', '=', 'u.id')
->join('dir__empresas as de','dc.id_per_emp', '=', 'de.id')
->leftJoin('ven__recibos as vr', 'vr.id_cliente', '=', 'dc.id')
->whereRaw($sqls)
->where('dc.tipo_per_emp', '=', $bus)
->groupBy(
    'dc.id',
    'dc.id_per_emp',
    'de.razon_social',
    'de.nit',
    'dc.correo',
    'dc.telefono',
    'dc.direccion',
    'dc.nom_a_facturar',
    'dc.pais',
    'dc.ciudad',
    'u.name',
    'u.id',
    'dc.created_at',
    'dc.updated_at',
    'dc.num_documento',
    'dc.tipo_per_emp',
    'dc.activo',
    'dt.id',
    'dt.nombre_doc',
    'dt.datos'
)
->orderBy('dc.id', 'desc')
->limit($limite_x);

// Unir los resultados de ambas consultas
$clientes = $clientesPersonas->unionAll($clientesEmpresas);
$clientes = $clientes->orderByDesc('id')->paginate(25);
         }    
         return  
         [
             'pagination'=>
                 [
                     'total'         =>    $clientes->total(),
                     'current_page'  =>    $clientes->currentPage(),
                     'per_page'      =>    $clientes->perPage(),
                     'last_page'     =>    $clientes->lastPage(),
                     'from'          =>    $clientes->firstItem(),
                     'to'            =>    $clientes->lastItem(),
                 ] ,
             'clientes'=>$clientes,
     ]; 
     }
     else{
        $clientesPersonas = DB::table('dir__clientes as dc')
        ->select(
            'dc.id as id',
            'dc.id_per_emp as id_persona_empresa',
            'dp.nombres as nombre','dp.apellidos as apellido',
            DB::raw("CONCAT(dp.nombres, ' ', dp.apellidos) AS nombre_completo"),
            DB::raw("CONCAT(COALESCE(dp.documento_identidad, ''), ' ', COALESCE(dp.complemento, '')) AS documento_identidad"),
            'dp.complemento as id_complemento',
            'dc.correo as correo',
            'dc.telefono as telefono',
            'dc.direccion as direccion',
            'dc.nom_a_facturar as nom_a_facturar',
            'dc.pais as pais',
            'dc.ciudad',
            'u.name as name',
            'u.id as id_user',
            DB::raw('GREATEST(dc.created_at, dc.updated_at) AS fecha_mas_reciente'),
            'dc.num_documento as num_documento',
            'dc.tipo_per_emp as tipo_per_emp',
            'dc.activo as activo',
            'dt.id as id_tipo_doc',
            
            'dt.nombre_doc as nom_documento',
            'dt.datos as datos_tipo_documento',
            DB::raw('MAX(vr.id_cliente) AS id_max')
        )
        ->join('dir__tipo_doc as dt', 'dc.id_tipo_doc', '=', 'dt.id')
        ->join('users as u', 'dc.id_user', '=', 'u.id')
        ->join('dir__personas as dp', 'dp.id', '=', 'dc.id_per_emp') 
        ->leftJoin('ven__recibos as vr', 'vr.id_cliente', '=', 'dc.id') 
        ->where('dc.tipo_per_emp', '=', $bus)
        ->groupBy(
            'dc.id',
            'dc.id_per_emp',
            'dp.nombres',
            'dp.apellidos',
            'dp.documento_identidad',
            'dp.complemento',
            'dc.correo',
            'dc.telefono',
            'dc.direccion',
            'dc.nom_a_facturar',
            'dc.pais',
            'dc.ciudad',
            'u.name',
            'u.id',
            'dt.id',
            'dt.nombre_doc',
            'dt.datos',
            'dc.created_at',
            'dc.updated_at',
            'dc.num_documento',
            'dc.tipo_per_emp',
            'dc.activo'
        )
        ->orderBy('dc.id', 'desc')
        ->limit($limite_x);
        // Consulta para obtener los clientes empresas
        $clientesEmpresas = DB::table('dir__clientes as dc')
        ->select(
            'dc.id as id',
            'dc.id_per_emp as id_persona_empresa',
            DB::raw('null as nombre'),
            DB::raw('null as apellido'), 
            'de.razon_social as nombre_completo',
            'de.nit as documento_identidad',
            DB::raw('null as id_complemento'),
            'dc.correo as correo',
            'dc.telefono as telefono',
            'dc.direccion as direccion',
            'dc.nom_a_facturar as nom_a_facturar',
            'dc.pais as pais',
            'dc.ciudad',
            'u.name as name',
            'u.id as id_user',
            DB::raw('GREATEST(dc.created_at, dc.updated_at) AS fecha_mas_reciente'),
            'dc.num_documento as num_documento',
            'dc.tipo_per_emp as tipo_per_emp',
            'dc.activo as activo',
            'dt.id as id_tipo_doc',
          
            'dt.nombre_doc as nom_documento',
            'dt.datos as datos_tipo_documento',
            DB::raw('MAX(vr.id_cliente) AS id_max')
        )
        ->join('dir__tipo_doc as dt', 'dc.id_tipo_doc', '=', 'dt.id')
        ->join('users as u', 'dc.id_user', '=', 'u.id')
        ->join('dir__empresas as de', 'de.id', '=', 'dc.id_per_emp')
        ->leftJoin('ven__recibos as vr', 'vr.id_cliente', '=', 'dc.id')
        ->where('dc.tipo_per_emp', '=', $bus)
       
    ->groupBy(
        'dc.id',
        'dc.id_per_emp',
        'de.razon_social',
        'de.nit',
        'dc.correo',
        'dc.telefono',
        'dc.direccion',
        'dc.nom_a_facturar',
        'dc.pais',
        'dc.ciudad',
        'u.name',
        'u.id',
        'dc.created_at',
        'dc.updated_at',
        'dc.num_documento',
        'dc.tipo_per_emp',
        'dc.activo',
        'dt.id',
        'dt.nombre_doc',
        'dt.datos'
    )
    ->orderBy('dc.id', 'desc')
    ->limit($limite_x);
        
        // Unir los resultados de ambas consultas
        $clientes = $clientesPersonas->unionAll($clientesEmpresas);
        $clientes = $clientes->orderByDesc('id')->paginate(25);
        return  
        [ 'pagination'=>
                [
                    'total'         =>    $clientes->total(),
                    'current_page'  =>    $clientes->currentPage(),
                    'per_page'      =>    $clientes->perPage(),
                    'last_page'     =>    $clientes->lastPage(),
                    'from'          =>    $clientes->firstItem(),
                    'to'            =>    $clientes->lastItem(),
                ] ,
          'clientes'=>$clientes,
     ];
     }    
 }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //tipo_per_emp=1 es empleado  si es 2 es empresa
        $primerGuardadoExitoso = false;
        
        try {

               // Iniciar una transacción
               DB::beginTransaction();
             
               if ($request->correo=="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com") {
                $correo_query = DB::table('adm__credecial_correos')
                ->select('correo')
                ->where('id','=',1)
                ->first();
                    if ($correo_query) {
                        $dato_correo = $correo_query->correo;    
                    }else{dd('error');}                          
               }else{
                $dato_correo = $request->correro;
               }
            // Buscar el usuario con el correo electrónico dado
            $clienteB = Dir_Cliente::where('num_documento', $request->num_documento)->first();  
            if ($clienteB) {
            // El numero de docuemnto ya está registrado
            
            return response()->json(['error' => 'El numero de documento ya existe.'], 400);   
            } else {
            // El numero de docuemnto no está registrado tipo_per_emp=1 es persona  y tipo_per_emp=2 es empresa 
            if ($request->tipo_per_emp==1) {
                $persona_empresa=new dir_Persona();               
                $persona_empresa->nombres=strtoupper($request->nombre);
                $persona_empresa->apellidos=strtoupper($request->apellido);
                $persona_empresa->documento_identidad=strtoupper($request->num_documento);
                $persona_empresa->complemento=strtoupper($request->ex);
                $persona_empresa->save();
                }
                else {
                    //empresa
                    if ($request->tipo_per_emp==2) {
                $persona_empresa=new dir_Empresa();
                $persona_empresa->razon_social=strtoupper($request->nom_a_facturar);      
                $persona_empresa->nit=strtoupper($request->num_documento);   
                $persona_empresa->save();
                    }
                    else{
                        dd("error");
                    }
                }
                $primerGuardadoExitoso = true;  
                // Si llegamos aquí sin errores, confirmamos la transacción
                DB::commit();
                $cliente=new Dir_Cliente();
                $cliente->correo=$dato_correo;
                $cliente->telefono=$request->telefono;
                $cliente->direccion=strtoupper($request->direccion);
                $cliente->id_tipo_doc=$request->id_tipo_doc;
                $cliente->nom_a_facturar=strtoupper($request->nom_a_facturar);
                $cliente->pais=strtoupper($request->pais);
                $cliente->ciudad=$request->ciudad;
                $cliente->id_user=auth()->user()->id;
                $cliente->id_usuario_registra=auth()->user()->id;
                $cliente->id_per_emp=$persona_empresa->id;
                $cliente->num_documento=$request->num_documento;
                $cliente->tipo_per_emp=$request->tipo_per_emp;
                $cliente->save();
                // Devolver el ID del nuevo cliente como respuesta
      
            }
            return response()->json([
                'id' => $cliente->id,
                'nom_a_facturar' => $cliente->nom_a_facturar,
                'correo' => $cliente->correo,
                'id_tipo_doc' => $cliente->id_tipo_doc,
                'num_documento' => $cliente->num_documento,
            ], 201);

        } catch (\Throwable $th) {
          
            // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
                
                if ($primerGuardadoExitoso) {
                    DB::rollback();
                    // Eliminar el producto guardado
                    $persona_empresa->delete();
                }
                return response()->json(['error' => $th->getMessage()],500);
            
        }
        
    }

    public function desactivar(Request $request)
    {      
        $update = Dir_Cliente::findOrFail($request->id);
        $update->activo = 0;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    public function activar(Request $request)
    {  $update = Dir_Cliente::findOrFail($request->id);
        $update->activo = 1;
        $update->id_user=auth()->user()->id;
        $update->id_usuario_modifica=auth()->user()->id;
        $update->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Cliente $dir_Cliente)
    {

      
         $primerGuardadoExitoso = false;
         try {
            if ($request->correo=="farmacia_pueto_del_rosarioxwass1234887458888@gmail.com") {
                $correo_query = DB::table('adm__credecial_correos')
                ->select('correo')
                ->where('id','=',1)
                ->first();
                    if ($correo_query) {
                        $dato_correo = $correo_query->correo;    
                    }else{dd('error');}                          
               }else{
                $dato_correo = $request->correo;
               }
                // Iniciar una transacción
                DB::beginTransaction();
             if ($request->tipo_per_emp==1) {
             $persona_empresa=dir_Persona::find($request->id_per_emp);
             $persona_empresa->nombres=strtoupper($request->nombre);
             $persona_empresa->apellidos=strtoupper($request->apellido);
             $persona_empresa->documento_identidad=$request->num_documento;
             $persona_empresa->complemento=strtoupper($request->ex);
             $persona_empresa->save();
             }
             else {
                 if ($request->tipo_per_emp==2) {
            $persona_empresa=dir_Empresa::find($request->id_per_emp);
             $persona_empresa->razon_social=strtoupper($request->nom_a_facturar);      
             $persona_empresa->nit=$request->num_documento;   
             $persona_empresa->save();
                 }
                 else{
                     dd("error");
                 }
             }
             $primerGuardadoExitoso = true;  
             // Si llegamos aquí sin errores, confirmamos la transacción
             DB::commit();
             $cliente=Dir_Cliente::find($request->id);
             $cliente->correo=$dato_correo;
             $cliente->telefono=$request->telefono;
             $cliente->direccion=strtoupper($request->direccion);
             $cliente->id_tipo_doc=$request->id_tipo_doc;
             $cliente->nom_a_facturar=strtoupper($request->nom_a_facturar);
             $cliente->pais=strtoupper($request->pais);
             $cliente->ciudad=strtoupper($request->ciudad);
             $cliente->id_user=auth()->user()->id;
             $cliente->id_usuario_modifica=auth()->user()->id;
             $cliente->id_per_emp=$persona_empresa->id;
             $cliente->num_documento=$request->num_documento;
             $cliente->tipo_per_emp=$request->tipo_per_emp;
             $cliente->save();
 
         } catch (\Throwable $th) {
                 // Si el primer guardado fue exitoso y ocurre un error, revertimos la transacción
                 if ($primerGuardadoExitoso) {
                     DB::rollback();
                     // Eliminar el producto guardado
                    // $persona_empresa->delete();
                 }
                 return response()->json(['error' => $th->getMessage()],500);
             
         }
    }

    public function listarTipoDoc(Request $request)
    {
        $tiposDocumento = DB::table('dir__tipo_doc')->get();
        return $tiposDocumento;
    }
    public function listarEx()
    {
        $ex = DB::table('adm__departamentos')->get();
        return $ex;
    }
         
   
}
