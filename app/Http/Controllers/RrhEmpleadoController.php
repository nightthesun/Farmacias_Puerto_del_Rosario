<?php

namespace App\Http\Controllers;

use App\Models\Rrh_Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RrhEmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $raw=DB::raw('concat(ifnull(papellido," ")," ",ifnull(sapellido," ")," ",rrh__empleados.nombre) as nomempleado');
        $raw2=DB::raw('concat(domicilio,"-",adm__ciudads.nombre) as direccion');
        $buscararray=array();
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
            //dd($buscararray);
            $valor=sizeof($buscararray);
            if($valor > 0){
                $sqls='';
                foreach($buscararray as $valor){
                    if(empty($sqls)){
                        $sqls="(rrh__empleados.nombre like '%".$valor."%' or papellido like '%".$valor."%' or  sapellido like '%".$valor."%' or ci like '%".$valor."%'
                        or rrh__formacions.nombre like '%".$valor."%'
                        or rrh__profesions.nombre like '%".$valor."%'
                        or rrh__cargos.nombre like '%".$valor."%' 
                        or nrcuenta like '%".$valor."%')" ;
                    }
                    else
                    {
                        $sqls.=" and (rrh__empleados.nombre like '%".$valor."%' or papellido like '%".$valor."%' or  sapellido like '%".$valor."%' or ci like '%".$valor."%'
                        or rrh__formacions.nombre like '%".$valor."%'
                        or rrh__profesions.nombre like '%".$valor."%'
                        or rrh__cargos.nombre like '%".$valor."%' 
                        or nrcuenta like '%".$valor."%')" ;
                    }
    
                }
                $empleados= Rrh_Empleado::join('rrh__formacions','rrh__formacions.id','rrh__empleados.idformacion')
                                        ->join('rrh__profesions','rrh__profesions.id','rrh__empleados.idprofesion')
                                        ->join('rrh__cargos','rrh__cargos.id','rrh__empleados.idcargo')
                                        ->leftjoin('adm__departamentos','adm__departamentos.id','rrh__empleados.iddepartamento')
                                        ->leftjoin('adm__nacionalidads','adm__nacionalidads.id', 'rrh__empleados.idnacionalidad')
                                        ->leftjoin('adm__ciudads','adm__ciudads.id', 'rrh__empleados.idciudad')
                                        ->leftjoin('adm__bancos','adm__bancos.id', 'rrh__empleados.idbanco')
                                        ->select('rrh__empleados.id',
                                                'rrh__empleados.nombre',
                                                'papellido',
                                                'sapellido',
                                                $raw,
                                                'sexo',
                                                'ci',
                                                'telefonos',
                                                'fechanacimiento',
                                                'estadocivil',
                                                'rrh__formacions.nombre as nomformacion',
                                                'rrh__profesions.nombre as nomprofecion',
                                                'rrh__cargos.nombre as nomcargo',
                                                'rrh__formacions.id as idformacion',
                                                'rrh__profesions.id as idprofesion',
                                                'rrh__cargos.id as idcargo',
                                                'foto',
                                                'domicilio',
                                                $raw2,
                                                'fechaingreso',
                                                'fecharetiro',
                                                'nrcuenta',
                                                'obs',
                                                'rrh__empleados.activo',
                                                'rrh__empleados.iddepartamento',
                                                'idnacionalidad',
                                                'idciudad',
                                                'idbanco',
                                                'adm__bancos.nombre',
                                                'complementoci',
                                                'celular',
                                                'nit')
                                        ->orderby('rrh__empleados.papellido','asc')
                                        ->orderby('rrh__empleados.sapellido','asc')
                                        ->orderby('rrh__empleados.nombre','asc')
                                        ->whereraw($sqls)->paginate(50);
            }
        }
        
        else
        {
            $empleados= Rrh_Empleado::join('rrh__formacions','rrh__formacions.id','rrh__empleados.idformacion')
                                    ->join('rrh__profesions','rrh__profesions.id','rrh__empleados.idprofesion')
                                    ->join('rrh__cargos','rrh__cargos.id','rrh__empleados.idcargo')
                                    ->leftjoin('adm__departamentos','adm__departamentos.id','rrh__empleados.iddepartamento')
                                    ->leftjoin('adm__nacionalidads','adm__nacionalidads.id', 'rrh__empleados.idnacionalidad')
                                    ->leftjoin('adm__ciudads','adm__ciudads.id', 'rrh__empleados.idciudad')
                                    ->leftjoin('adm__bancos','adm__bancos.id', 'rrh__empleados.idbanco')
                                    ->select('rrh__empleados.id',
                                            'rrh__empleados.nombre',
                                            'papellido',
                                            'sapellido',
                                            $raw,
                                            'sexo',
                                            'ci',
                                            'telefonos',
                                            'fechanacimiento',
                                            'estadocivil',
                                            'rrh__formacions.nombre as nomformacion',
                                            'rrh__profesions.nombre as nomprofecion',
                                            'rrh__cargos.nombre as nomcargo',
                                            'rrh__formacions.id as idformacion',
                                            'rrh__profesions.id as idprofesion',
                                            'rrh__cargos.id as idcargo',
                                            'foto',
                                            'domicilio',
                                            $raw2,
                                            'fechaingreso',
                                            'fecharetiro',
                                            'nrcuenta',
                                            'obs',
                                            'rrh__empleados.activo',
                                            'rrh__empleados.iddepartamento',
                                            'idnacionalidad',
                                            'idciudad',
                                            'idbanco',
                                            'adm__bancos.nombre as nombanco',
                                            'complementoci',
                                            'celular',
                                            'nit')
                                    ->orderby('rrh__empleados.papellido','asc')
                                    ->orderby('rrh__empleados.sapellido','asc')
                                    ->orderby('rrh__empleados.nombre','asc')
                                    ->paginate(50);
        }
        
        //$empleados = Rrh_Empleado::all();
        
        
        return ['pagination'=>[
            'total'         =>    $empleados->total(),
            'current_page'  =>    $empleados->currentPage(),
            'per_page'      =>    $empleados->perPage(),
            'last_page'     =>    $empleados->lastPage(),
            'from'          =>    $empleados->firstItem(),
            'to'            =>    $empleados->lastItem(),

            ] ,
                'empleados'=>$empleados
        ];
    }
    public function perfil()
    {
        $raw=DB::raw('concat(ifnull(papellido," ")," ",ifnull(sapellido," ")," ",rrh__empleados.nombre) as nomempleado');
        $raw2=DB::raw('concat(domicilio,"-",adm__ciudads.nombre) as direccion');
        
        
       
            $empleados= Rrh_Empleado::join('users','users.idempleado','rrh__empleados.id')
                                    ->join('rrh__formacions','rrh__formacions.id','rrh__empleados.idformacion')
                                    ->join('rrh__profesions','rrh__profesions.id','rrh__empleados.idprofesion')
                                    ->join('rrh__cargos','rrh__cargos.id','rrh__empleados.idcargo')
                                    ->leftjoin('adm__departamentos','adm__departamentos.id','rrh__empleados.iddepartamento')
                                    ->leftjoin('adm__nacionalidads','adm__nacionalidads.id', 'rrh__empleados.idnacionalidad')
                                    ->leftjoin('adm__ciudads','adm__ciudads.id', 'rrh__empleados.idciudad')
                                    ->leftjoin('adm__bancos','adm__bancos.id', 'rrh__empleados.idbanco')
                                    ->select('rrh__empleados.id',
                                            'rrh__empleados.nombre',
                                            'papellido',
                                            'sapellido',
                                            $raw,
                                            'sexo',
                                            'ci',
                                            'telefonos',
                                            'fechanacimiento',
                                            'estadocivil',
                                            'rrh__formacions.nombre as nomformacion',
                                            'rrh__profesions.nombre as nomprofecion',
                                            'rrh__cargos.nombre as nomcargo',
                                            'rrh__formacions.id as idformacion',
                                            'rrh__profesions.id as idprofesion',
                                            'rrh__cargos.id as idcargo',
                                            'foto',
                                            'domicilio',
                                            $raw2,
                                            'fechaingreso',
                                            'fecharetiro',
                                            'nrcuenta',
                                            'obs',
                                            'rrh__empleados.activo',
                                            'rrh__empleados.iddepartamento',
                                            'idnacionalidad',
                                            'idciudad',
                                            'idbanco',
                                            'adm__bancos.nombre as nombanco',
                                            'complementoci',
                                            'celular',
                                            'nit')
                                    ->orderby('rrh__empleados.papellido','asc')
                                    ->orderby('rrh__empleados.sapellido','asc')
                                    ->orderby('rrh__empleados.nombre','asc')
                                    ->where('users.id',auth()->user()->id)
                                    ->get();
       
        
        return $empleados;
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
        $validate=$request->validate([
            'ci'=>'required | unique:rrh__empleados'
        ]);
        
        $empleado = new Rrh_Empleado();

        if($request->hasFile('foto'))
        {
            $filename=$request->foto->getClientOriginalName();
            info($filename);
            $empleado->foto=$request->file('foto')->store('empleados');
        }
        
        if(strlen($request->papellido)!=0){
            if(strlen($request->sapellido)!=0)
                $codempleado=$request->ci."-".$request->nombre[0].$request->papellido[0].$request->sapellido[0];

            else
                $codempleado=$request->ci."-".$request->nombre[0].$request->papellido[0];
        }
        else
        {
            if(strlen($request->sapellido)!=0)
                $codempleado=$request->ci."-".$request->nombre[0].$request->sapellido[0];
        }

        $empleado->codempleado=$codempleado;
        $empleado->nombre=$request->nombre;
        $empleado->papellido=$request->papellido;
        $empleado->sapellido=$request->sapellido;
        
        $empleado->ci=$request->ci;
        $empleado->sexo=$request->sexo;
        $empleado->complementoci=$request->complementoci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->fechanacimiento=$request->fechanacimiento;
        
        $empleado->estadocivil=$request->estadocivil;
        $empleado->idnacionalidad=$request->idnacionalidad;
        
        $empleado->domicilio=$request->domicilio;
        $empleado->idciudad=$request->idciudad;
        $empleado->telefonos=$request->telefonos;
        $empleado->celular=$request->celular;
        
        $empleado->idformacion=$request->idformacion;
        $empleado->idprofesion=$request->idprofesion;
        $empleado->idcargo=$request->idcargo;
        $empleado->nit=$request->nit;
        $empleado->fechaingreso=$request->fechaingreso;
        $empleado->fecharetiro=$request->fecharetiro;
        
        $empleado->idbanco=$request->idbanco;
        $empleado->nrcuenta=$request->nrcuenta;
        
        $empleado->obs=$request->obs;
        $empleado->id_usuario_registra=auth()->user()->id;
        $empleado->save();

        return $request;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrh_Empleado  $rrh_Empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Rrh_Empleado $rrh_Empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrh_Empleado  $rrh_Empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Rrh_Empleado $rrh_Empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrh_Empleado  $rrh_Empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request);
        $empleado = Rrh_Empleado::findOrFail($request->id);

        
        if($request->hasFile('foto'))
        {
            $filename=$request->foto->getClientOriginalName();
            info($filename);
            $empleado->foto=$request->file('foto')->store('empleados');
        }

        if(strlen($request->papellido)!=0){
            if(strlen($request->sapellido)!=0)
                $codempleado=$request->ci."-".$request->nombre[0].$request->papellido[0].$request->sapellido[0];

            else
                $codempleado=$request->ci."-".$request->nombre[0].$request->papellido[0];
        }
        else
        {
            if(strlen($request->sapellido)!=0)
                $codempleado=$request->ci."-".$request->nombre[0].$request->sapellido[0];
        }
        $empleado->codempleado=$codempleado;
        $empleado->nombre=$request->nombre;
        $empleado->papellido=$request->papellido;
        $empleado->sapellido=$request->sapellido;
       
        $empleado->ci=$request->ci;
        $empleado->sexo=$request->sexo;
        $empleado->complementoci=$request->complementoci;
        $empleado->iddepartamento=$request->iddepartamento;
        $empleado->fechanacimiento=$request->fechanacimiento;
        
        $empleado->estadocivil=$request->estadocivil;
        $empleado->idnacionalidad=$request->idnacionalidad;
        
        $empleado->domicilio=$request->domicilio;
        $empleado->idciudad=$request->idciudad;
        $empleado->telefonos=$request->telefonos;
        $empleado->celular=$request->celular;
        
        $empleado->idformacion=$request->idformacion;
        $empleado->idprofesion=$request->idprofesion;
        $empleado->idcargo=$request->idcargo;
        $empleado->nit=$request->nit;
        $empleado->fechaingreso=$request->fechaingreso;

        $data="";
        if($request->fecharetiro=='null')
            $data=null;
        else
            $data=$request->fecharetiro;
        $empleado->fecharetiro=$data;
        
        $empleado->idbanco=$request->idbanco;
        $empleado->nrcuenta=$request->nrcuenta;
        
        $empleado->obs=$request->obs;
        $empleado->id_usuario_modifica=auth()->user()->id;
        $empleado->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrh_Empleado  $rrh_Empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rrh_Empleado $rrh_Empleado)
    {
        //
    }
    public function desactivar(Request $request)
    {
        $empleado = Rrh_Empleado::findOrFail($request->id);
        $empleado->activo=0;
        $empleado->id_usuario_modifica=auth()->user()->id;
        $empleado->save();
    }

    public function activar(Request $request)
    {
        $empleado = Rrh_Empleado::findOrFail($request->id);
        $empleado->activo=1;
        $empleado->id_usuario_modifica=auth()->user()->id;
        $empleado->save();
    }
    public function selectEmpleado(Request $request)
    {
        $raw=DB::raw('concat(ifnull(papellido," ")," ",ifnull(sapellido," ")," ",rrh__empleados.nombre) as nomempleado');
        $empleados=Rrh_Empleado::select('id',$raw)
                                ->where('activo',1)
                                ->orderby('rrh__empleados.papellido','asc')
                                ->orderby('rrh__empleados.sapellido','asc')
                                ->orderby('rrh__empleados.nombre','asc')
                                ->get();
        return $empleados;
    }
    public function selectNoUser(Request $request)
    {
        /**
         * Sql para la consulta
         * select	rrh__empleados.`id`,
         * concat(ifnull(`papellido`, " "), " ", ifnull(`sapellido`, " "), " ", rrh__empleados.`nombre`) as nomempleado,
         * `codempleado` as name
         * from
         * 	rrh__empleados
         * where `id` not in (select idempleado from users where activo = 1) 
         */
        
        $raw2=DB::raw('concat(ifnull(papellido," ")," ",ifnull(sapellido," ")," ",rrh__empleados.nombre) as nomempleado');               
        $raw3=DB::raw('codempleado as name');
        //dd($raw2);
        $user=DB::table('users')->select('idempleado')->where('activo',1)->get()->toArray();
        //dd($user);
        $users=array();

        foreach ($user as $value) {
            array_push($users,$value->idempleado);
        }
        //dd($users);
        $empleados=DB::table('rrh__empleados')->select('id',$raw2,$raw3)
        ->whereNotIn('id',$users )
        ->get();

        //dd($empleados);
        return $empleados;
    }

    public function getsaldo(){

        $user = auth()->user()->id;

if ($user === 1) {
    $idsuc = 1;
    $nomrol = "root";
    $id_user2 = 1;
} else {
    $idsuc = session('idsuc');
    $nomrol = session('nomrol');
    $id_user2 = session('id_user2');
}

//dd(session()->all());
$credencial = DB::table('adm__credecial_correos as acc')
->join('adm__nacionalidads as an', 'an.id', '=', 'acc.moneda')
->select('acc.tiempo_limite', 'acc.monto_limite', 'an.simbolo')
->first();

  

// Acceder a los valores
$tiempoLimite = $credencial->tiempo_limite;
$montoLimite = $credencial->monto_limite;
$simbolo = $credencial->simbolo;

$registro = DB::table('caja__entrada_salidas as ces')
    ->join('caja__arqueo as ca', 'ca.id', '=', 'ces.id_arqueo')
    ->select(
        'ces.id', 
        'ces.created_at',
        DB::raw('DATE_ADD(ces.created_at, INTERVAL 72 HOUR) AS fecha_modificada'),
        DB::raw("(SELECT SUM(c.valor)
                  FROM caja__entrada_salidas c
                  JOIN caja__arqueo ca2 ON ca2.id = c.id_arqueo
                  WHERE c.entrada_salida = 2
                    AND c.id_sucursal = ces.id_sucursal
                    AND c.transaccion = 0
                    AND ca2.id_usuario = $id_user2) AS suma_valor"),
        DB::raw("TIMESTAMPDIFF(DAY, NOW(), DATE_ADD(ces.created_at, INTERVAL $tiempoLimite HOUR)) AS dias_faltantes"),
        DB::raw("TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(ces.created_at, INTERVAL $tiempoLimite HOUR)) % 24 AS horas_faltantes"),
        DB::raw("TIMESTAMPDIFF(MINUTE, NOW(), DATE_ADD(ces.created_at, INTERVAL $tiempoLimite HOUR)) % 60 AS minutos_faltantes")
    )
    ->where('ces.entrada_salida', 2)
    ->where('ces.id_sucursal', $idsuc)
    ->where('ces.transaccion', 0)
    ->where('ca.id_usuario', $id_user2)
    ->orderBy('ces.created_at', 'ASC')
    ->limit(1)
    ->first();



return response()->json([
    'registro' => $registro,
    'nomrol' => $nomrol,
    'montoLimite' => $montoLimite,
    'simbolo' => $simbolo   
 ]);

    
    }
    
}
