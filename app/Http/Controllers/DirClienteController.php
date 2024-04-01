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
    public function index()
    {
        //
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
            if ($request->tipo_per_emp==1) {
            $persona_empresa=new dir_Persona();
            $persona_empresa->nombres=$request->nombre;
            $persona_empresa->apellidos=$request->apellido;
            $persona_empresa->documento_identidad=$request->num_documento;
            $persona_empresa->complemento=$request->ex;
            $persona_empresa->save();
            }
            else {
                if ($request->tipo_per_emp==2) {
            $persona_empresa=new dir_Empresa();
            $persona_empresa->razon_social=$request->nom_a_facturar;      
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
            $cliente=new Dir_Cliente();
            $cliente->correo=$request->correo;
            $cliente->telefono=$request->telefono;
            $cliente->direccion=$request->direccion;
            $cliente->id_tipo_doc=$request->id_tipo_doc;
            $cliente->nom_a_facturar=$request->nom_a_facturar;
            $cliente->pais=$request->pais;
            $cliente->ciudad=$request->ciudad;
            $cliente->id_user=auth()->user()->id;
            $cliente->id_usuario_registra=auth()->user()->id;
            $cliente->id_per_emp=$persona_empresa->id;
            $cliente->num_documento=$request->num_documento;
            $cliente->tipo_per_emp=$request->tipo_per_emp;
            $cliente->save();

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

    /**
     * Display the specified resource.
     */
    public function show(Dir_Cliente $dir_Cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dir_Cliente $dir_Cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir_Cliente $dir_Cliente)
    {
        //
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



        /**
         * 

*select dc.id as id,dp.id as id_persona,dp.nombres as nombres,dp.apellidos as apellidos,dp.documento_identidad as ci,ad.abrev as ex,dc.correo as correo ,dc.telefono as telefono ,dc.nom_a_facturar as nom_a_facturar,
*dc.pais as pais, dc.ciudad,u.name as name,u.id as id_user,GREATEST(dc.created_at,dc.updated_at) AS fecha_mas_reciente, dc.num_documento as num_documento,dc.tipo_per_emp as tipo_per_emp, dc.activo as activo,dt.id as id_tipo_doc,dt.nombre_doc,dt.nombre_doc as nom_documento,dt.datos as datos_tipo_documento
*from dir__clientes dc
*JOIN dir__tipo_doc dt on dc.id_tipo_doc=dt.id
*join users u on dc.id_user=u.id
*join dir__personas dp on dp.id =dc.id_per_emp
*left join adm__departamentos ad on ad.id=dp.complemento

*where dc.tipo_per_emp=1


*select dc.id as id,dp.id as id_persona,dp.nombres as nombres,dp.apellidos as apellidos,dp.documento_identidad as ci,ad.abrev as ex,dc.correo as correo ,dc.telefono as telefono ,dc.nom_a_facturar as nom_a_facturar,
*dc.pais as pais, dc.ciudad,u.name as name,u.id as id_user,GREATEST(dc.created_at,dc.updated_at) AS fecha_mas_reciente, dc.num_documento as num_documento,dc.tipo_per_emp as tipo_per_emp, dc.activo as activo,dt.id as id_tipo_doc,dt.nombre_doc,dt.nombre_doc as nom_documento,dt.datos as datos_tipo_documento 
*from dir__clientes dc
*JOIN dir__tipo_doc dt on dc.id_tipo_doc=dt.id
*join users u on dc.id_user=u.id
*join dir__empresas de on de.id =dc.id_per_emp
*where dc.tipo_per_emp=2
         * 
         * 
         * 
         * 
         */


    }
   
}
