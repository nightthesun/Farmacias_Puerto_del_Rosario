<?php

namespace App\Http\Controllers;

use App\Models\Adm_AsigMasSucursales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetController extends Controller
{
    //********para listar si tiene permiso editar y activar
    public function permisos_editar_activar(Request $request){
        $iduserrolesuc=session('iduserrolesuc');
        $user_1 = Auth()->user()->id;
        $user_2 = Auth()->user()->name;
        if($user_1==1&&$user_2=='admin'){
            $resultadoConsulta = [
                ['id' => 1, 'edit' => 1, 'activar' => 1]
            ];
            return $resultadoConsulta;           
        }else{
        // $idsucursal=session('idsuc');
       // $nomsucursal=session('nomsucursal');
       //dd(session()->all());
       $resultadoConsulta = DB::table('adm__user_role_sucursals as aur')
       ->leftJoin('adm__asig_permiso_e_a_s as aap', 'aap.id_user_role_sucu', '=', 'aur.iduser')
       ->where('aur.activo', '=', 1)
       ->where('aur.id', '=', $iduserrolesuc)
       ->select('aur.id as id', 'aap.edit as edit', 'aap.activar as activar')
       ->get();
       return $resultadoConsulta;
        }      
    }



    //******************************permisos para ver lista por defecto */
        public function listarSucursal()
        {
            $idsuc = session('idsuc');
            $user_1 = Auth()->user()->id;
            $asignaciones = Adm_AsigMasSucursales::where('id_user_role_sucu', $user_1)->get();
            $where1 = ''; // Definir $where1 con un valor predeterminado
            $where2 = ''; // Definir $where2 con un valor predeterminado
        
            if ($asignaciones->count() > 0) {
    
            $codigos = [];
            foreach ($asignaciones as $asignacion) {
            $codigos[] = "'" . $asignacion->cod . "'"; // Agregar comillas simples alrededor de cada valor
            }
            $where1 = 'tda__tiendas.codigo IN (' . implode(',', $codigos) . ')';
            $where2 = 'aa.codigo IN (' . implode(',', $codigos) . ')';
            // No es necesario hacer nada si hay asignaciones
            } else {
            // Modificar $where solo si no hay asignaciones
            $where1 = "(ass.id = $idsuc)";
            $where2 = "(ass.id = $idsuc)";
            }
                if ($user_1 == 1) {
                $tiendas = DB::table('tda__tiendas')
                ->join('adm__sucursals as ass', 'tda__tiendas.idsucursal', '=', 'ass.id')
                ->select(
                'tda__tiendas.id as id_tienda',
                DB::raw('NULL as id_almacen'),
                'tda__tiendas.codigo',
                'ass.razon_social',
                'ass.razon_social as sucursal',
                'ass.cod as codigoS',
                DB::raw('"Tienda" as tipoCodigo'),
                'tda__tiendas.id AS id_tienda_almacen',
                'ass.id AS id_sucursal'
        );

$almacenes = DB::table('alm__almacens as aa')
    ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
    ->select(
        DB::raw('NULL as id_tienda'),
        'aa.id as id_almacen',
        'aa.codigo',
        'aa.nombre_almacen as razon_social',
        'ass.razon_social as sucursal',
        'ass.cod as codigoS',
        DB::raw('"Almacen" as tipoCodigo'),
        'aa.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
    );

$result = $tiendas->unionAll($almacenes)->get();
}   
    else
        {
            
        $tiendas = DB::table('tda__tiendas')
        ->join('adm__sucursals as ass', 'tda__tiendas.idsucursal', '=', 'ass.id')
        ->select(
        'tda__tiendas.id as id_tienda',
        DB::raw('NULL as id_almacen'),
        'tda__tiendas.codigo',
        'ass.razon_social',
        'ass.razon_social as sucursal',
        'ass.cod as codigoS',
        DB::raw('"Tienda" as tipoCodigo'),
        'tda__tiendas.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
        )
        ->whereRaw($where1);

        $almacenes = DB::table('alm__almacens as aa')
        ->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
        ->select(
        DB::raw('NULL as id_tienda'),
        'aa.id as id_almacen',
        'aa.codigo',
        'aa.nombre_almacen as razon_social',
        'ass.razon_social as sucursal',
        'ass.cod as codigoS',
        DB::raw('"Almacen" as tipoCodigo'),
        'aa.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
        )
        ->whereRaw($where2);
        $result = $tiendas->unionAll($almacenes)->get();
}



return $result;


    }

    //*****************************solo para mostrar sucursales*******************/
    public function listarSucursalGet()
    {
       
        $idsuc = session('idsuc');
        $user_1 = Auth()->user()->id;
        if($user_1==1){
            $sucursalesActivas = DB::table('adm__sucursals')
            ->select('id', 'cod', 'razon_social')
            ->where('activo', 1)
            ->get();
            return $sucursalesActivas;  
        }else{
            $where = "(adm__sucursals.id = $idsuc)";
            $sucursalesActivas = DB::table('adm__sucursals')
            ->select('id', 'cod', 'razon_social')
            ->where('activo', 1)
            ->whereRaw($where)
            ->get();
            return $sucursalesActivas;
        }
    }

    //***************************PARA MOSTRARAR EL TIPO DE DOCUMENTO************************ */
    public function listarTipoDoc(Request $request)
    {
        $tiposDocumento = DB::table('dir__tipo_doc')->get();
        return $tiposDocumento;
    }
    
    //****************************PARA MOSTRAR EL EXPEDICION CON LA ciudad*********************** */
    public function listarEx()
    {
        $ex = DB::table('adm__departamentos')->get();
        return $ex;
    }

    //***********************para mostrara las entradas********************** */
    public function listar_entradasXe(Request $request){
        $resultado = DB::table('prod__tipo_entradas')
        ->select('id', 'nombre')
        ->where('activo', 1)
        ->get();

        return $resultado;
    }

    //*******************verificica si tiene movimiento******************** */

    public function tiene_movimiento(Request $request){

        $idTiendaAlmacen = $request->id_T_A;
        $idIngreso = $request->id_ingreso;
        $almXtda = $request->almXtda;
         // para tienda es 1 y para almacen es 2   
        if($almXtda==1){
            //es tienda
            $tipo = 'TDA';
        }else {
            //es almacen
            $tipo = 'ALM';
        }
        
        
    $query1 = DB::table('ges_pre__venta2s as gpv')
    ->join('pivot__modulo_tienda_almacens as piv', 'piv.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
    ->select('gpv.listo_venta')
    ->where('piv.id_tienda_almacen', $idTiendaAlmacen)
    ->where('piv.id_ingreso', $idIngreso)
    ->where('piv.tipo', $tipo);

// Second query
$query2 = DB::table('ges_pre__venta_listas as gpv')
    ->join('pivot__modulo_tienda_almacens as piv', 'piv.id', '=', 'gpv.id_table_ingreso_tienda_almacen')
    ->select('gpv.listo_venta')
    ->where('piv.id_tienda_almacen', $idTiendaAlmacen)
    ->where('piv.id_ingreso', $idIngreso)
    ->where('piv.tipo', $tipo)
    ->unionAll($query1) // Combine with first query
    ->get();

      return $query2;    

    } 

    //*************************LISTA NORMAL SIN PERMISO DE TIENDAS ALMACENES O SUCURSAL****************** */
    public function listarSucusal_TDA_ALM_sin_permiso(){
        $tiendas = DB::table('tda__tiendas')
        ->join('adm__sucursals as ass', 'tda__tiendas.idsucursal', '=', 'ass.id')
        ->select(
        'tda__tiendas.id as id_tienda',
        DB::raw('NULL as id_almacen'),
        'tda__tiendas.codigo',
        'ass.razon_social',
        'ass.razon_social as sucursal',
        'ass.cod as codigoS',
        DB::raw('"Tienda" as tipoCodigo'),
        'tda__tiendas.id AS id_tienda_almacen',
        'ass.id AS id_sucursal'
)->where('ass.activo','=',1);

$almacenes = DB::table('alm__almacens as aa')
->join('adm__sucursals as ass', 'ass.id', '=', 'aa.idsucursal')
->select(
DB::raw('NULL as id_tienda'),
'aa.id as id_almacen',
'aa.codigo',
'aa.nombre_almacen as razon_social',
'ass.razon_social as sucursal',
'ass.cod as codigoS',
DB::raw('"Almacen" as tipoCodigo'),
'aa.id AS id_tienda_almacen',
'ass.id AS id_sucursal'
)->where('ass.activo','=',1);

$result = $tiendas->unionAll($almacenes)->get();
return $result;    
    }

    //********************APERTURA /CIERRE************************* */
    public function listarAperturaCierre(Request $request){
        $ultimoRegistro = DB::table('caja__apertura_cierres')
        ->select('id','turno_caja', 'tipo_caja_c_a', 'total_caja', 'estado_caja', 'id_arqueo','id_apertura_cierre')
        ->where('id_sucursal', $request->id_sucursal)
  
        ->orderBy('id', 'desc')
        ->first();
        if($ultimoRegistro==null){
            $ultimoRegistro=0;  
        }     
     return $ultimoRegistro;    
    }

    public function listarMoneda_2(Request $request){
        
        $moneda = DB::table('adm__credecial_correos')
        ->where('id', 1)
        ->value('moneda');
    // Asigna directamente el valor de $moneda a $data_1
    $data_1 = $moneda;
        if ($data_1==0||$data_1==null||$data_1=="") { 
                      
            return response()->json([                        
                'moneda' => 0,
                'listaMoneda' => null, 
            ]);
        }else {
         
            $monedas_2 =  DB::table('caja__monedas')
            ->select('id', 'tipo_corte', 'valor', 'unidad', 'unidad_entera', DB::raw('0.00 AS valor_default'),DB::raw('0 AS input'),'id_nacionalidad_pais')
            ->where('id_nacionalidad_pais', $moneda)
            ->where('activo', 1)
            ->get();
           
            return response()->json([                        
                'moneda' => $moneda,
                'listaMoneda' => $monedas_2, 
            ]);
        }
    }

    ///--------------
    public function getBancos(){
        $bancos = DB::table('adm__bancos')
    ->select('id', 'nombre', 'activo')
    ->orderBy('id', 'desc')
    ->get();
    return $bancos;
    }

    ////////////////////////////USUARIOS///////////////////////////////
    public function getUser(){
        $usuario = DB::table('users as u')
            ->join('rrh__empleados as re', 're.id', '=', 'u.idempleado')
            ->select('u.id','u.name','u.responsable',DB::raw("CONCAT(COALESCE(re.nombre, ''), ' ', COALESCE(re.papellido, ''), ' ', COALESCE(re.sapellido, '')) AS nom_completo"),'re.ci','u.responsable')
            ->where('u.activo', 1)
            ->get();
        return $usuario;     

    }
}
